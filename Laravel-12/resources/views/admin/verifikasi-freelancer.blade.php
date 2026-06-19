@extends('layouts.admin')

@section('title', 'Verifikasi Freelancer - JasaKampus')

@section('content')
    <div class="flex items-start justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold text-slate-900">
                Verifikasi Freelancer
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Kelola pengajuan freelancer yang baru mendaftar ke platform JasaKampus.
            </p>
        </div>

        <a href="{{ route('dashboard') }}"
           class="px-5 py-3 bg-white border border-slate-200 rounded-lg text-sm font-semibold hover:bg-slate-50">
            Kembali Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 px-5 py-4 bg-green-100 text-green-700 rounded-xl border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs text-slate-400 uppercase font-semibold">Total Pengajuan</p>
            <h3 class="text-3xl font-bold text-slate-900 mt-2">
                {{ $verifikasis->count() }}
            </h3>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs text-slate-400 uppercase font-semibold">Menunggu Verifikasi</p>
            <h3 class="text-3xl font-bold text-yellow-600 mt-2">
                {{ $verifikasis->where('status_verifikasi', 'pending')->count() }}
            </h3>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs text-slate-400 uppercase font-semibold">Disetujui</p>
            <h3 class="text-3xl font-bold text-green-600 mt-2">
                {{ $verifikasis->where('status_verifikasi', 'approved')->count() }}
            </h3>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 flex items-center justify-between border-b border-slate-200">
            <div>
                <h3 class="font-semibold text-slate-800">
                    Daftar Pengajuan Freelancer
                </h3>
                <p class="text-sm text-slate-500">
                    Cek email kampus, KTM, dan portofolio sebelum menyetujui akun freelancer.
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Freelancer</th>
                        <th class="px-6 py-4 text-left font-semibold">Email Kampus</th>
                        <th class="px-6 py-4 text-left font-semibold">Universitas</th>
                        <th class="px-6 py-4 text-left font-semibold">File</th>
                        <th class="px-6 py-4 text-left font-semibold">Status</th>
                        <th class="px-6 py-4 text-left font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($verifikasis as $item)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($item->freelancer->nama ?? 'F', 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="font-semibold text-slate-800">
                                            {{ $item->freelancer->nama ?? '-' }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $item->program_studi ?? 'Program studi belum diisi' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-slate-700">
                                {{ $item->email_kampus }}
                            </td>

                            <td class="px-6 py-4 text-slate-700">
                                {{ $item->universitas }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <a href="{{ asset('storage/' . $item->file_ktm) }}"
                                       target="_blank"
                                       class="block text-blue-600 font-semibold hover:underline">
                                        Lihat KTM
                                    </a>

                                    @php
                                        $portofolio = $item->freelancer?->portofolios->first();
                                    @endphp

                                    @if ($portofolio)
                                        <a href="{{ asset('storage/' . $portofolio->file_portofolio) }}"
                                           target="_blank"
                                           class="block text-blue-600 font-semibold hover:underline">
                                            Lihat Portofolio
                                        </a>
                                    @else
                                        <span class="text-slate-400">
                                            Portofolio tidak ada
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                @if ($item->status_verifikasi === 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">
                                        MENUNGGU
                                    </span>
                                @elseif ($item->status_verifikasi === 'approved')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                        DISETUJUI
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                                        DITOLAK
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if ($item->status_verifikasi === 'pending')
                                    <div class="space-y-3">
                                        <form method="POST" action="{{ route('admin.verifikasi.approve', $item->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit"
                                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.verifikasi.reject', $item->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <textarea name="catatan_admin"
                                                      rows="2"
                                                      class="w-full rounded-lg border-slate-300 text-sm"
                                                      placeholder="Alasan penolakan"
                                                      required></textarea>

                                            <button type="submit"
                                                    class="mt-2 w-full px-4 py-2 bg-red-600 text-white rounded-lg font-semibold hover:bg-red-700">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="text-sm text-slate-500">
                                        Sudah diproses

                                        @if ($item->catatan_admin)
                                            <p class="mt-2 text-xs text-red-600">
                                                {{ $item->catatan_admin }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                Belum ada pengajuan freelancer.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection