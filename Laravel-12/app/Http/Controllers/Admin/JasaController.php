<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jasa;
use App\Services\NotifikasiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JasaController extends Controller
{
    private function authorizeAdmin(Request $request): void
    {
        $user = $request->user();

        abort_if(! $user || $user->role !== 'admin', 403);
    }

    public function index(Request $request): View
    {
        $this->authorizeAdmin($request);

        $jasas = Jasa::with('freelancer')
            ->latest()
            ->get();

        return view('admin.jasa.index', compact('jasas'));
    }

    public function approve(Request $request, Jasa $jasa): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $jasa->update([
            'status_jasa' => 'active',
        ]);

        NotifikasiService::kirim(
            $jasa->id_freelancer,
            'Jasa Disetujui',
            'Jasa "' . $jasa->nama_jasa . '" telah disetujui oleh admin dan sekarang tampil di marketplace.',
            'system',
            route('freelancer.jasa.index', [], false)
        );

        return back()->with('success', 'Jasa berhasil disetujui.');
    }

    public function reject(Request $request, Jasa $jasa): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $jasa->update([
            'status_jasa' => 'rejected',
        ]);

        NotifikasiService::kirim(
            $jasa->id_freelancer,
            'Jasa Ditolak',
            'Jasa "' . $jasa->nama_jasa . '" ditolak oleh admin. Silakan periksa kembali data jasa kamu.',
            'system',
            route('freelancer.jasa.index', [], false)
        );

        return back()->with('success', 'Jasa berhasil ditolak.');
    }
}
