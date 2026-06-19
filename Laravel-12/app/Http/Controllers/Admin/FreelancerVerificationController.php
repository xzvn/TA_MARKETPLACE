<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VerifikasiFreelancer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FreelancerVerificationController extends Controller
{
    private function authorizeAdmin(): void
    {
        $user = Auth::user();

        abort_if(! $user || $user->role !== 'admin', 403);
    }

    public function index(): View
    {
        $this->authorizeAdmin();

        $verifikasis = VerifikasiFreelancer::with('freelancer.portofolios')
            ->latest()
            ->get();

        return view('admin.verifikasi-freelancer', compact('verifikasis'));
    }

    public function approve(VerifikasiFreelancer $verifikasi): RedirectResponse
    {
        $this->authorizeAdmin();

        $verifikasi->update([
            'status_verifikasi' => 'approved',
            'tanggal_verifikasi' => now(),
            'catatan_admin' => null,
        ]);

        return back()->with('success', 'Freelancer berhasil disetujui.');
    }

    public function reject(Request $request, VerifikasiFreelancer $verifikasi): RedirectResponse
    {
        $this->authorizeAdmin();

        $request->validate([
            'catatan_admin' => ['required', 'string', 'max:1000'],
        ]);

        $verifikasi->update([
            'status_verifikasi' => 'rejected',
            'tanggal_verifikasi' => now(),
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Freelancer berhasil ditolak.');
    }
}