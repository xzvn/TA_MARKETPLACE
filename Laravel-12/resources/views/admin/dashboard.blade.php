@extends('layouts.admin')

@section('title', 'Dashboard Admin - JasaKampus')

@section('content')

@php
$aktivitasTerbaru = $aktivitasTerbaru ?? collect();
$adaLogLainnya = $adaLogLainnya ?? false;
$nextLogLimit = $nextLogLimit ?? 15;
@endphp

@php
$aktivitasTerbaru = $aktivitasTerbaru ?? collect();
$adaLogLainnya = $adaLogLainnya ?? false;
$nextLogLimit = $nextLogLimit ?? 15;
$tahunDashboard = $tahunDashboard ?? now()->year;
$trenPendapatanChart = $trenPendapatanChart ?? collect();
$pertumbuhanPenggunaChart = $pertumbuhanPenggunaChart ?? collect();
@endphp

@if (session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-100 text-green-700 font-semibold">
    {{ session('success') }}
</div>
@endif  
<!-- HEADER DASHBOARD -->
<div class="flex items-start justify-between mb-8">
    <div>
        <h2 class="text-3xl font-bold text-slate-900">
            Ringkasan Dashboard
        </h2>
        <p class="text-sm text-slate-500 mt-1">
            Selamat datang kembali, {{ Auth::user()->nama }}. Pantau performa platform JasaKampus hari ini.
        </p>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.reports.download') }}"
            class="px-5 py-3 rounded-xl bg-white border border-slate-200 text-slate-900 font-semibold hover:bg-slate-50">
            ⇩ Unduh Laporan
        </a>

        <button type="button"
            onclick="document.getElementById('requestModal').classList.remove('hidden')"
            class="px-5 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
            + Post a Request
        </button>
    </div>
</div>

<!-- STAT CARD -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold">👥</div>
            <span class="text-xs font-semibold text-blue-600">+12%</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Total Pengguna</p>
        <h3 class="text-xl font-bold mt-1">{{ $totalPengguna }}</h3>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold">⚑</div>
            <span class="text-xs font-semibold text-purple-600">+5%</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Freelancer Aktif</p>
        <h3 class="text-xl font-bold mt-1">{{ $freelancerAktif }}</h3>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-bold">▣</div>
            <span class="text-xs font-semibold text-orange-600">+8%</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Layanan Terdaftar</p>
        <h3 class="text-xl font-bold mt-1">{{ $layananTerdaftar }}</h3>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold">⚡</div>
            <span class="text-xs font-semibold text-blue-600">+15%</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Proyek Berjalan</p>
        <h3 class="text-xl font-bold mt-1">{{ $proyekBerjalan }}</h3>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold">▣</div>
            <span class="text-xs font-semibold text-slate-500">Total</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Transaksi</p>
        <h3 class="text-xl font-bold mt-1">{{ $totalTransaksi }}</h3>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold">💵</div>
            <span class="text-xs font-semibold text-purple-600">+21%</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Total Pendapatan</p>
        <h3 class="text-xl font-bold mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold">🛡</div>
            <span class="text-xs font-semibold text-red-600">Tinggi</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Verifikasi Pending</p>
        <h3 class="text-xl font-bold mt-1">{{ $verifikasiPending }}</h3>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex justify-between items-start">
            <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold">⚒</div>
            <span class="text-xs font-semibold text-slate-600">Aktif</span>
        </div>
        <p class="text-xs text-slate-400 mt-4 uppercase">Dispute Aktif</p>
        <h3 class="text-xl font-bold mt-1">{{ $disputeAktif }}</h3>
    </div>
</div>

<!-- CHART SECTION -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">

    <!-- BAR CHART -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Tren Pendapatan Bulanan</h3>
                <p class="text-sm text-slate-500">Pendapatan dari escrow yang sudah dicairkan.</p>
            </div>

            <span class="px-4 py-2 rounded-full bg-slate-100 text-slate-600 text-sm">
                Tahun {{ $tahunDashboard }}
            </span>
        </div>

        <div class="h-64 flex items-end gap-4">
            @foreach ($trenPendapatanChart as $item)
            <div class="flex-1 flex flex-col items-center justify-end h-full">
                <div class="w-full flex items-end justify-center h-52">
                    <div class="w-10 sm:w-14 rounded-t-xl bg-blue-300 hover:bg-blue-500 transition"
                        style="height: {{ max($item['persen'], $item['total'] > 0 ? 8 : 2) }}%;"
                        title="Rp {{ number_format($item['total'], 0, ',', '.') }}">
                    </div>
                </div>

                <p class="mt-3 text-sm text-slate-500">
                    {{ $item['bulan'] }}
                </p>

                <p class="text-xs text-slate-400">
                    Rp {{ number_format($item['total'], 0, ',', '.') }}
                </p>
            </div>
            @endforeach
        </div>
    </div>


</div>
<!-- USER GROWTH -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h3 class="text-lg font-bold text-slate-900">Pertumbuhan Pengguna</h3>
            <p class="text-sm text-slate-500">Jumlah user baru berdasarkan bulan.</p>
        </div>

        <div class="flex items-center gap-4 text-sm">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <span class="text-slate-600">Customer</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                <span class="text-slate-600">Freelancer</span>
            </div>
        </div>
    </div>

    <div class="h-64 flex items-end gap-4">
        @foreach ($pertumbuhanPenggunaChart as $item)
        <div class="flex-1 flex flex-col items-center justify-end h-full">
            <div class="w-full h-52 flex items-end justify-center gap-1">
                <div class="w-4 sm:w-5 rounded-t-lg bg-blue-400"
                    style="height: {{ max($item['customer_persen'], $item['customer'] > 0 ? 8 : 2) }}%;"
                    title="Customer: {{ $item['customer'] }}">
                </div>

                <div class="w-4 sm:w-5 rounded-t-lg bg-purple-400"
                    style="height: {{ max($item['freelancer_persen'], $item['freelancer'] > 0 ? 8 : 2) }}%;"
                    title="Freelancer: {{ $item['freelancer'] }}">
                </div>
            </div>

            <p class="mt-3 text-sm text-slate-500">
                {{ $item['bulan'] }}
            </p>
        </div>
        @endforeach
    </div>
</div>
<!-- LOG TABLE -->
<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-5 flex justify-between items-center border-b border-slate-200">
        <div>
            <h3 class="font-semibold text-slate-800">Log Aktivitas Terbaru</h3>
            <p class="text-sm text-slate-500">Data real-time dari seluruh interaksi platform.</p>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-slate-100 rounded-lg text-xs font-semibold hover:bg-slate-200">
                Filter Status
            </button>

            <button class="px-4 py-2 bg-slate-100 rounded-lg text-xs font-semibold hover:bg-slate-200">
                Lihat Semua
            </button>
        </div>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500">
            <tr>
                <th class="px-6 py-4 text-left font-semibold">Event</th>
                <th class="px-6 py-4 text-left font-semibold">User / Aktor</th>
                <th class="px-6 py-4 text-left font-semibold">Status</th>
                <th class="px-6 py-4 text-left font-semibold">Waktu</th>
                <th class="px-6 py-4 text-left font-semibold">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-100">
            @forelse ($aktivitasTerbaru as $log)
            <tr>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full
                        @if ($log['warna'] === 'green') bg-green-500
                        @elseif ($log['warna'] === 'yellow') bg-yellow-500
                        @elseif ($log['warna'] === 'red') bg-red-500
                        @else bg-blue-500
                        @endif">
                        </span>

                        <span class="font-medium text-slate-800">
                            {{ $log['event'] }}
                        </span>
                    </div>
                </td>

                <td class="px-6 py-4 text-slate-700">
                    {{ $log['aktor'] }}
                </td>

                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                    @if ($log['warna'] === 'green') bg-green-100 text-green-700
                    @elseif ($log['warna'] === 'yellow') bg-yellow-100 text-yellow-700
                    @elseif ($log['warna'] === 'red') bg-red-100 text-red-700
                    @else bg-blue-100 text-blue-700
                    @endif">
                        {{ $log['status'] }}
                    </span>
                </td>

                <td class="px-6 py-4 text-slate-500">
                    {{ $log['waktu']->diffForHumans() }}
                </td>

                <td class="px-6 py-4">
                    @if ($log['url'])
                    <a href="{{ $log['url'] }}" class="text-blue-600 font-semibold hover:underline">
                        Detail
                    </a>
                    @else
                    <span class="text-slate-400">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                    Belum ada aktivitas terbaru.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="text-center py-4 border-t border-slate-100">
        @if ($adaLogLainnya ?? false)
        <div class="px-6 py-5 text-center">
            <a href="{{ route('dashboard', ['log_limit' => $nextLogLimit]) }}"
                class="text-blue-600 font-semibold hover:underline">
                Tampilkan 10 Log Lainnya
            </a>
        </div>
        @else
        <div class="px-6 py-5 text-center text-slate-400">
            Semua log sudah ditampilkan
        </div>
        @endif
    </div>
</div>
<div id="requestModal"
    class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center px-4">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-slate-200">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-900">Post a Request</h3>
                <p class="text-sm text-slate-500 mt-1">
                    Kirim request atau pengumuman ke user.
                </p>
            </div>

            <button type="button"
                onclick="document.getElementById('requestModal').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-700 text-2xl">
                ×
            </button>
        </div>

        <form method="POST" action="{{ route('admin.request.store') }}" class="p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Target User
                </label>

                <select name="target"
                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="all">Semua User</option>
                    <option value="customer">Customer</option>
                    <option value="freelancer">Freelancer</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Judul
                </label>

                <input type="text"
                    name="judul"
                    required
                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Contoh: Informasi Maintenance Sistem">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Pesan
                </label>

                <textarea name="pesan"
                    rows="4"
                    required
                    class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                    placeholder="Tulis pesan request atau pengumuman..."></textarea>
            </div>

            <label class="flex items-center gap-3">
                <input type="checkbox"
                    name="kirim_email"
                    value="1"
                    class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">

                <span class="text-sm text-slate-600">
                    Kirim juga ke email user
                </span>
            </label>

            <div class="flex justify-end gap-3 pt-4">
                <button type="button"
                    onclick="document.getElementById('requestModal').classList.add('hidden')"
                    class="px-5 py-3 rounded-xl bg-slate-100 text-slate-700 font-semibold hover:bg-slate-200">
                    Batal
                </button>

                <button type="submit"
                    class="px-5 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    Kirim Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection