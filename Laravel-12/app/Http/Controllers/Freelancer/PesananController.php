<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananController extends Controller
{
    private function authorizeFreelancer(Request $request): void
    {
        $user = $request->user();

        abort_if(! $user || $user->role !== 'freelancer', 403);
    }

    public function index(Request $request): View
{
    $this->authorizeFreelancer($request);

    $pesanans = Pesanan::with(['customer', 'jasa', 'pembayaran', 'progressPekerjaans'])
        ->where('id_freelancer', $request->user()->id)
        ->whereIn('status_pesanan', [
            'dibayar',
            'diproses',
            'menunggu_approve',
            'revisi',
            'selesai',
            'dispute',
        ])
        ->latest()
        ->get();

    return view('freelancer.pesanan.index', compact('pesanans'));
}

    public function show(Request $request, Pesanan $pesanan)
    {
        $this->authorizeFreelancer($request);

        abort_if($pesanan->id_freelancer !== $request->user()->id, 403);

        return redirect()->route('freelancer.progress.create', $pesanan->id);
    }
}
