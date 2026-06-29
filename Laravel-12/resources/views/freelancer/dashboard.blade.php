@extends('layouts.freelancer')

@section('title', 'Dashboard Freelancer - JasaKampus')
@section('page-title', 'Dashboard Freelancer')

@section('content')
@php
$user = auth()->user();
$verifikasi = $user->verifikasiFreelancer;
$status = $verifikasi?->status_verifikasi;

$totalJasa = $totalJasa ?? 0;
$jasaAktif = $jasaAktif ?? 0;
$jasaPending = $jasaPending ?? 0;
$pesananBerjalan = $pesananBerjalan ?? 0;
$pesananSelesai = $pesananSelesai ?? 0;
$saldoDitahan = $saldoDitahan ?? 0;
$totalPendapatan = $totalPendapatan ?? 0;
$ratingRataRata = $ratingRataRata ?? 0;
$totalReview = $totalReview ?? 0;
$pesananTerbaru = $pesananTerbaru ?? collect();
$jasaTerbaru = $jasaTerbaru ?? collect();
@endphp

<div class="flex items-start justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">
            Ringkasan Freelancer
        </h1>
        <p class="text-sm text-slate-500 mt-1">
            Selamat datang kembali, {{ $user->nama }}. Kelola jasa, proyek, dan penghasilan Anda di sini.
        </p>
    </div>

    <div class="flex gap-3">
        @if ($status === 'approved')
        <a href="{{ route('freelancer.jasa.create') }}"
            class="px-5 py-3 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">
            + Tambah Jasa
        </a>
        @else
        <button disabled
            class="px-5 py-3 bg-slate-300 text-slate-600 rounded-lg text-sm font-semibold cursor-not-allowed">
            + Tambah Jasa
        </button>
        @endif
    </div>
</div>

@if ($status === 'pending')
<div class="mb-6 p-5 bg-yellow-100 border border-yellow-200 text-yellow-800 rounded-xl">
    <p class="font-bold">Akun sedang menunggu verifikasi admin.</p>
    <p class="text-sm mt-1">
        Anda belum bisa membuat jasa sampai admin menyetujui data KTM dan portofolio Anda.
    </p>
</div>
@elseif ($status === 'approved')
<div class="mb-6 p-5 bg-green-100 border border-green-200 text-green-800 rounded-xl">
    <p class="font-bold">Akun freelancer Anda sudah diverifikasi.</p>
    <p class="text-sm mt-1">
        Anda sudah bisa membuat jasa dan menerima pesanan dari customer.
    </p>
</div>
@elseif ($status === 'rejected')
<div class="mb-6 p-5 bg-red-100 border border-red-200 text-red-800 rounded-xl">
    <p class="font-bold">Verifikasi akun Anda ditolak.</p>
    <p class="text-sm mt-1">
        Catatan admin: {{ $verifikasi?->catatan_admin ?? 'Tidak ada catatan.' }}
    </p>
</div>
@endif

{{-- STATISTIK --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                👤
            </div>
            <span class="text-xs font-semibold text-blue-600">Akun</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Status Verifikasi</p>
        <h3 class="text-xl font-bold mt-1 capitalize">
            {{ $status ?? 'Belum Ada' }}
        </h3>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold">
                ▣
            </div>
            <span class="text-xs font-semibold text-purple-600">Jasa</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Total Jasa</p>
        <h3 class="text-xl font-bold mt-1">
            {{ $totalJasa }}
        </h3>
        <p class="text-xs text-slate-400 mt-1">
            Aktif: {{ $jasaAktif }} • Pending: {{ $jasaPending }}
        </p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center font-bold">
                ⚡
            </div>
            <span class="text-xs font-semibold text-green-600">Aktif</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Project Berjalan</p>
        <h3 class="text-xl font-bold mt-1">
            {{ $pesananBerjalan }}
        </h3>
        <p class="text-xs text-slate-400 mt-1">
            Selesai: {{ $pesananSelesai }}
        </p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center font-bold">
                💵
            </div>
            <span class="text-xs font-semibold text-yellow-600">Saldo</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Pendapatan Cair</p>
        <h3 class="text-xl font-bold mt-1">
            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
        </h3>
        <p class="text-xs text-slate-400 mt-1">
            Ditahan: Rp {{ number_format($saldoDitahan, 0, ',', '.') }}
        </p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center font-bold">
                ★
            </div>
            <span class="text-xs font-semibold text-yellow-600">Rating</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Rating Freelancer</p>
        <h3 class="text-xl font-bold mt-1">
            {{ $totalReview > 0 ? number_format((float) $ratingRataRata, 1) : '0.0' }}
        </h3>
        <p class="text-xs text-slate-400 mt-1">
            {{ $totalReview }} review
        </p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                🖼
            </div>
            <span class="text-xs font-semibold text-indigo-600">Total</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Portofolio</p>
        <h3 class="text-xl font-bold mt-1">
            {{ $user->portofolios()->count() }}
        </h3>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- PROJECT TERBARU --}}
    <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-semibold text-slate-800">
                    Project Terbaru
                </h3>
                <p class="text-sm text-slate-500">
                    Daftar proyek yang sedang atau pernah Anda kerjakan.
                </p>
            </div>
        </div>

        @if ($pesananTerbaru->count() > 0)
        <div class="space-y-4">
            @foreach ($pesananTerbaru as $pesanan)
            @php
            $warnaStatus = match ($pesanan->status_pesanan) {
            'selesai' => 'bg-green-100 text-green-700',
            'dibayar', 'diproses', 'menunggu_approve' => 'bg-blue-100 text-blue-700',
            'revisi' => 'bg-yellow-100 text-yellow-700',
            'dispute', 'dibatalkan' => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-700',
            };
            @endphp

            <div class="p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h4 class="font-bold text-slate-900">
                            {{ $pesanan->jasa->nama_jasa ?? 'Jasa tidak ditemukan' }}
                        </h4>

                        <p class="text-sm text-slate-500 mt-1">
                            Customer:
                            <span class="font-semibold text-slate-700">
                                {{ $pesanan->customer->nama ?? '-' }}
                            </span>
                        </p>

                        <p class="text-xs text-slate-400 mt-1">
                            Order #{{ $pesanan->id }} • {{ $pesanan->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>

                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $warnaStatus }}">
                        {{ ucwords(str_replace('_', ' ', $pesanan->status_pesanan)) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-5 bg-slate-50 rounded-xl text-slate-500 text-sm">
            Belum ada project aktif.
        </div>
        @endif
    </div>

    {{-- MENU CEPAT --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-5">
            Menu Cepat
        </h3>

        <div class="space-y-3">
            @if ($status === 'approved')
            <a href="{{ route('freelancer.jasa.create') }}"
                class="block px-5 py-4 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700">
                Buat Jasa Baru
            </a>
            @else
            <div class="block px-5 py-4 bg-slate-100 text-slate-400 rounded-xl font-semibold">
                Buat Jasa Baru
            </div>
            @endif

            <a href="#"
                class="block px-5 py-4 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200">
                Lihat Portofolio
            </a>

            <a href="#"
                class="block px-5 py-4 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200">
                Lihat Project
            </a>

            <a href="#"
                class="block px-5 py-4 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200">
                Penghasilan Saya
            </a>
        </div>
    </div>
</div>

{{-- JASA TERBARU --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mt-6">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="font-semibold text-slate-800">
                Jasa Terbaru
            </h3>
            <p class="text-sm text-slate-500">
                Daftar jasa terbaru yang Anda buat.
            </p>
        </div>
    </div>

    @if ($jasaTerbaru->count() > 0)
    <div class="space-y-4">
        @foreach ($jasaTerbaru as $item)
        @php
        $warnaJasa = match ($item->status_jasa) {
        'active' => 'bg-green-100 text-green-700',
        'pending' => 'bg-yellow-100 text-yellow-700',
        'rejected' => 'bg-red-100 text-red-700',
        default => 'bg-slate-100 text-slate-700',
        };
        @endphp

        <div class="p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h4 class="font-bold text-slate-900">
                        {{ $item->nama_jasa }}
                    </h4>

                    <p class="text-sm text-slate-500 mt-1">
                        {{ $item->kategori }} • Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        Dibuat {{ $item->created_at->format('d M Y, H:i') }}
                    </p>
                </div>

                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $warnaJasa }}">
                    {{ ucfirst($item->status_jasa) }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="p-5 bg-slate-50 rounded-xl text-slate-500 text-sm">
        Anda belum membuat jasa.
    </div>
    @endif
</div>
@endsection