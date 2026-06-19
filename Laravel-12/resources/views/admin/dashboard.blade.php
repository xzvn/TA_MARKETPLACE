@extends('layouts.admin')

@section('title', 'Dashboard Admin - JasaKampus')

@section('content')
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
            <button class="px-5 py-3 bg-white border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">
                ⇩ Unduh Laporan
            </button>

            <button class="px-5 py-3 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">
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
            <h3 class="text-xl font-bold mt-1">{{ $totalUsers }}</h3>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold">⚑</div>
                <span class="text-xs font-semibold text-purple-600">+5%</span>
            </div>
            <p class="text-xs text-slate-400 mt-4 uppercase">Freelancer Aktif</p>
            <h3 class="text-xl font-bold mt-1">{{ $freelancerApproved }}</h3>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center font-bold">▣</div>
                <span class="text-xs font-semibold text-orange-600">+8%</span>
            </div>
            <p class="text-xs text-slate-400 mt-4 uppercase">Layanan Terdaftar</p>
            <h3 class="text-xl font-bold mt-1">1,890</h3>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold">⚡</div>
                <span class="text-xs font-semibold text-blue-600">+15%</span>
            </div>
            <p class="text-xs text-slate-400 mt-4 uppercase">Proyek Berjalan</p>
            <h3 class="text-xl font-bold mt-1">452</h3>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold">▣</div>
                <span class="text-xs font-semibold text-slate-500">Total</span>
            </div>
            <p class="text-xs text-slate-400 mt-4 uppercase">Transaksi</p>
            <h3 class="text-xl font-bold mt-1">8,120</h3>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold">💵</div>
                <span class="text-xs font-semibold text-purple-600">+21%</span>
            </div>
            <p class="text-xs text-slate-400 mt-4 uppercase">Total Pendapatan</p>
            <h3 class="text-xl font-bold mt-1">Rp 142.5M</h3>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center font-bold">🛡</div>
                <span class="text-xs font-semibold text-red-600">Tinggi</span>
            </div>
            <p class="text-xs text-slate-400 mt-4 uppercase">Verifikasi Pending</p>
            <h3 class="text-xl font-bold mt-1">{{ $freelancerPending }}</h3>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex justify-between items-start">
                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold">⚒</div>
                <span class="text-xs font-semibold text-slate-600">Aktif</span>
            </div>
            <p class="text-xs text-slate-400 mt-4 uppercase">Dispute Aktif</p>
            <h3 class="text-xl font-bold mt-1">18</h3>
        </div>
    </div>

    <!-- CHART SECTION -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">

        <!-- BAR CHART -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-semibold text-slate-800">Tren Pendapatan Bulanan</h3>
                <span class="text-xs px-3 py-1 bg-slate-100 rounded-full">Tahun 2024</span>
            </div>

            <div class="h-64 flex items-end gap-2 px-4">
                <div class="flex-1 bg-blue-100 h-40 rounded-t"></div>
                <div class="flex-1 bg-blue-100 h-60 rounded-t"></div>
                <div class="flex-1 bg-blue-100 h-42 rounded-t"></div>
                <div class="flex-1 bg-blue-200 h-48 rounded-t"></div>
                <div class="flex-1 bg-blue-200 h-52 rounded-t"></div>
                <div class="flex-1 bg-blue-300 h-60 rounded-t"></div>
            </div>

            <div class="grid grid-cols-6 text-xs text-slate-500 mt-3 px-4">
                <span>Jan</span>
                <span>Feb</span>
                <span>Mar</span>
                <span>Apr</span>
                <span>Mei</span>
                <span>Jun</span>
            </div>
        </div>

        <!-- USER GROWTH -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-semibold text-slate-800">Pertumbuhan Pengguna</h3>

                <div class="flex items-center gap-4 text-xs">
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                        Mahasiswa
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-2 h-2 bg-purple-600 rounded-full"></span>
                        Klien
                    </span>
                </div>
            </div>

            <div class="h-64 flex items-end justify-between px-8 text-xs text-slate-400">
                <span>Mgg 1</span>
                <span>Mgg 2</span>
                <span>Mgg 3</span>
                <span>Mgg 4</span>
            </div>
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
                <tr>
                    <td class="px-6 py-4">
                        <span class="text-blue-600">●</span>
                        Pembayaran Diterima - Order #8821
                    </td>
                    <td class="px-6 py-4">John Koe</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">BERHASIL</span>
                    </td>
                    <td class="px-6 py-4 text-slate-500">2 Menit Lalu</td>
                    <td class="px-6 py-4">
                        <a href="#" class="text-blue-600 font-semibold">Detail</a>
                    </td>
                </tr>

                <tr>
                    <td class="px-6 py-4">
                        <span class="text-purple-600">●</span>
                        Registrasi Freelancer Baru
                    </td>
                    <td class="px-6 py-4">Ahmad Maulana</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">MENUNGGU</span>
                    </td>
                    <td class="px-6 py-4 text-slate-500">15 Menit Lalu</td>
                    <td class="px-6 py-4">
                        <a href="#" class="text-blue-600 font-semibold">Review</a>
                    </td>
                </tr>

                <tr>
                    <td class="px-6 py-4">
                        <span class="text-red-600">●</span>
                        Dispute Dibuka - Order #8815
                    </td>
                    <td class="px-6 py-4">Sarah T.</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">URGENT</span>
                    </td>
                    <td class="px-6 py-4 text-slate-500">42 Menit Lalu</td>
                    <td class="px-6 py-4">
                        <a href="#" class="text-blue-600 font-semibold">Investigasi</a>
                    </td>
                </tr>

                <tr>
                    <td class="px-6 py-4">
                        <span class="text-green-600">●</span>
                        Layanan Baru Dipublikasikan
                    </td>
                    <td class="px-6 py-4">Randi P.</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">AKTIF</span>
                    </td>
                    <td class="px-6 py-4 text-slate-500">1 Jam Lalu</td>
                    <td class="px-6 py-4">
                        <a href="#" class="text-blue-600 font-semibold">Detail</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="text-center py-4 border-t border-slate-100">
            <a href="#" class="text-sm text-blue-600 font-semibold">
                Tampilkan 10 Log Lainnya
            </a>
        </div>
    </div>
@endsection