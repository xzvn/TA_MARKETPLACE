<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Dispute;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\NotifikasiService;

class DisputeController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        $user = $request->user();

        abort_if(! $user || $user->role !== 'admin', 403);
    }

    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        $disputes = Dispute::with([
            'pesanan.jasa',
            'pesanan.pembayaran',
            'customer',
            'freelancer',
        ])
            ->latest()
            ->get();

        $selectedDispute = null;
        $chatRiwayat = collect();

        if ($disputes->count() > 0) {
            $selectedId = $request->query('dispute', $disputes->first()->id);

            $selectedDispute = Dispute::with([
                'pesanan.jasa',
                'pesanan.pembayaran',
                'customer',
                'freelancer',
            ])->findOrFail($selectedId);

            $pesanan = $selectedDispute->pesanan;

            if ($pesanan) {
                $chatRiwayat = Chat::with('pengirim')
                    ->where('id_jasa', $pesanan->id_jasa)
                    ->where('id_customer', $pesanan->id_customer)
                    ->where('id_freelancer', $pesanan->id_freelancer)
                    ->orderBy('created_at')
                    ->get();
            }
        }

        return view('admin.dispute.index', compact(
            'disputes',
            'selectedDispute',
            'chatRiwayat'
        ));
    }

    public function refund(Request $request, Dispute $dispute): RedirectResponse
    {
        $this->authorizeAdmin($request);

        abort_if(
            ! in_array($dispute->status_dispute, ['pending', 'diproses']),
            403,
            'Dispute ini sudah diproses.'
        );

        $request->validate([
            'keputusan_admin' => ['required', 'string', 'min:5'],
        ]);

        $dispute->load('pesanan.pembayaran');

        if ($dispute->pesanan->pembayaran) {
            $dispute->pesanan->pembayaran->update([
                'status_escrow' => 'dikembalikan',
                'tanggal_release' => now(),
            ]);
        }

        $dispute->pesanan->update([
            'status_pesanan' => 'dibatalkan',
        ]);

        $dispute->update([
            'status_dispute' => 'refund',
            'keputusan_admin' => $request->keputusan_admin,
            'tanggal_diproses' => now(),
        ]);

        NotifikasiService::kirim(
            [$dispute->id_customer, $dispute->id_freelancer],
            'Dispute Diputuskan Refund',
            'Admin memutuskan refund untuk pesanan #' . $dispute->id_pesanan . '. Dana dikembalikan kepada customer.',
            'dispute',
            null
        );

        return redirect()
            ->route('admin.disputes.index', ['dispute' => $dispute->id])
            ->with('success', 'Dana berhasil dikembalikan kepada customer.');
    }

    public function releaseToFreelancer(Request $request, Dispute $dispute): RedirectResponse
    {
        $this->authorizeAdmin($request);

        abort_if(
            ! in_array($dispute->status_dispute, ['pending', 'diproses']),
            403,
            'Dispute ini sudah diproses.'
        );

        $request->validate([
            'keputusan_admin' => ['required', 'string', 'min:5'],
        ]);

        $dispute->load('pesanan.pembayaran');

        if ($dispute->pesanan->pembayaran) {
            $dispute->pesanan->pembayaran->update([
                'status_escrow' => 'dicairkan',
                'tanggal_release' => now(),
            ]);
        }

        $dispute->pesanan->update([
            'status_pesanan' => 'selesai',
        ]);

        $dispute->update([
            'status_dispute' => 'lanjutkan_pesanan',
            'keputusan_admin' => $request->keputusan_admin,
            'tanggal_diproses' => now(),
        ]);

        NotifikasiService::kirim(
            [$dispute->id_customer, $dispute->id_freelancer],
            'Dispute Selesai',
            'Admin memutuskan dana pesanan #' . $dispute->id_pesanan . ' dicairkan kepada freelancer.',
            'dispute',
            null
        );

        return redirect()
            ->route('admin.disputes.index', ['dispute' => $dispute->id])
            ->with('success', 'Dana berhasil dilepaskan kepada freelancer.');
    }
}
