<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionMonitoringController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        $user = $request->user();

        abort_if(! $user || $user->role !== 'admin', 403);
    }

    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        $pesanans = Pesanan::with(['customer', 'freelancer', 'jasa', 'pembayaran'])
            ->whereHas('pembayaran')
            ->latest()
            ->get();

        $totalEscrowVolume = Pembayaran::where('status_escrow', 'ditahan')
            ->sum('gross_amount');

        $pendapatanHariIni = Pembayaran::whereDate('tanggal_bayar', today())
            ->sum('gross_amount');

        $transaksiMenunggu = Pembayaran::where('status_escrow', 'ditahan')
            ->count();

        $totalTransaksi = Pembayaran::count();

        $tingkatKeamanan = 99.9;

        $weeklyLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

        $weeklyVolumes = collect($weeklyLabels)->map(function ($label, $index) {
            $date = now()->startOfWeek()->addDays($index);

            return [
                'label' => $label,
                'total' => Pembayaran::whereDate('created_at', $date)->sum('gross_amount'),
            ];
        });

        $maxWeeklyVolume = max($weeklyVolumes->max('total'), 1);

        return view('admin.transaction.index', compact(
            'pesanans',
            'totalEscrowVolume',
            'pendapatanHariIni',
            'transaksiMenunggu',
            'totalTransaksi',
            'tingkatKeamanan',
            'weeklyVolumes',
            'maxWeeklyVolume'
        ));
    }
}
