<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function index(Request $request): View
    {
        $notifikasis = Notifikasi::where('id_user', $request->user()->id)
            ->latest()
            ->get();

        return view('notifikasi.index', compact('notifikasis'));
    }

    public function read(Request $request, Notifikasi $notifikasi): RedirectResponse
    {
        abort_if($notifikasi->id_user !== $request->user()->id, 403);

        $notifikasi->update([
            'dibaca' => true,
            'dibaca_pada' => now(),
        ]);

        if ($notifikasi->url) {
            return redirect($notifikasi->url);
        }

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function readAll(Request $request): RedirectResponse
    {
        Notifikasi::where('id_user', $request->user()->id)
            ->where('dibaca', false)
            ->update([
                'dibaca' => true,
                'dibaca_pada' => now(),
            ]);

        return back()->with('success', 'Semua notifikasi sudah dibaca.');
    }
}
