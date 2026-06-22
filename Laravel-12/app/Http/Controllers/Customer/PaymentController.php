<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\NotifikasiService;

class PaymentController extends Controller
{
    private function authorizeCustomer(Request $request, Pesanan $pesanan): void
    {
        $user = $request->user();

        abort_if(! $user || $user->role !== 'customer', 403);
        abort_if($pesanan->id_customer !== $user->id, 403);
    }

    public function show(Request $request, Pesanan $pesanan): View
    {
        $this->authorizeCustomer($request, $pesanan);

        $pesanan->load('jasa.freelancer', 'pembayaran');

        return view('customer.payment.show', compact('pesanan'));
    }

    public function pay(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $this->authorizeCustomer($request, $pesanan);

        abort_if(
            $pesanan->status_pesanan !== 'menunggu_pembayaran',
            403,
            'Pesanan ini sudah dibayar atau tidak dapat dibayar.'
        );

        Pembayaran::updateOrCreate(
            [
                'id_pesanan' => $pesanan->id,
            ],
            [
                'order_id' => 'JSK-' . $pesanan->id . '-' . time(),
                'transaction_id' => 'SIM-' . time(),
                'payment_type' => 'simulasi',
                'gross_amount' => $pesanan->total_harga,
                'transaction_status' => 'settlement',
                'fraud_status' => 'accept',
                'status_escrow' => 'ditahan',
                'tanggal_bayar' => now(),
            ]
        );

        $pesanan->update([
            'status_pesanan' => 'dibayar',
        ]);

        NotifikasiService::kirim(
            $pesanan->id_freelancer,
            'Pesanan Baru Sudah Dibayar',
            'Customer telah melakukan pembayaran untuk pesanan #' . $pesanan->id . '. Silakan mulai proses pekerjaan.',
            'order',
            route('freelancer.pesanan.show', $pesanan->id, false)
        );

        NotifikasiService::kirim(
            $pesanan->id_customer,
            'Pembayaran Berhasil',
            'Pembayaran untuk pesanan #' . $pesanan->id . ' berhasil. Dana ditahan sementara oleh sistem escrow.',
            'pembayaran',
            route('customer.order.show', $pesanan->id, false)
        );

        return redirect()
            ->route('customer.payment.show', $pesanan->id)
            ->with('success', 'Pembayaran berhasil. Dana ditahan sementara oleh sistem escrow.');
    }
}
