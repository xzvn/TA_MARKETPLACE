@extends('layouts.freelancer')

@section('title', 'Profil Freelancer - JasaKampus')
@section('page-title', 'Profil Freelancer')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-900">
        Profil Freelancer
    </h1>
    <p class="text-sm text-slate-500 mt-1">
        Kelola informasi akun freelancer, status verifikasi, dan data portofolio Anda.
    </p>
</div>

@if (session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 text-green-700 text-sm font-semibold">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 text-sm">
    <p class="font-bold mb-2">Ada data yang belum sesuai:</p>
    <ul class="list-disc ml-5 space-y-1">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- FORM PROFIL --}}
    <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h2 class="text-xl font-bold text-slate-900 mb-5">
            Informasi Akun
        </h2>

        <form action="{{ route('freelancer.profile.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-5">
                <div class="w-24 h-24 rounded-full bg-blue-600 text-white flex items-center justify-center overflow-hidden text-3xl font-bold">
                    @if ($user->foto_profil)
                    <img src="{{ str_starts_with($user->foto_profil, 'http') ? $user->foto_profil : asset('storage/' . $user->foto_profil) }}"
                        alt="Foto Profil"
                        class="w-full h-full object-cover">
                    @else
                    {{ strtoupper(substr($user->nama ?? $user->email, 0, 1)) }}
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Foto Profil
                    </label>
                    <input type="file"
                        name="foto_profil"
                        accept="image/*"
                        class="block w-full text-sm text-slate-600 border border-slate-300 rounded-xl p-2">
                    <p class="text-xs text-slate-400 mt-2">
                        Format JPG, PNG, atau WEBP. Maksimal 2MB.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nama
                    </label>
                    <input type="text"
                        name="nama"
                        value="{{ old('nama', $user->nama) }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Email
                    </label>
                    <input type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        No HP
                    </label>
                    <input type="text"
                        name="no_hp"
                        value="{{ old('no_hp', $user->no_hp) }}"
                        class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Role
                    </label>
                    <input type="text"
                        value="{{ ucfirst($user->role) }}"
                        disabled
                        class="w-full border border-slate-200 bg-slate-100 text-slate-500 rounded-xl px-4 py-3">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Alamat
                </label>
                <textarea name="alamat"
                    rows="4"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('alamat', $user->alamat) }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('dashboard') }}"
                    class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-50">
                    Kembali
                </a>

                <button type="submit"
                    class="px-5 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- STATUS VERIFIKASI --}}
    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">
                Status Verifikasi
            </h2>

            @php
            $status = $verifikasi?->status_verifikasi;
            $warnaStatus = match ($status) {
            'approved' => 'bg-green-100 text-green-700',
            'pending' => 'bg-yellow-100 text-yellow-700',
            'rejected' => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-700',
            };
            @endphp

            <span class="inline-block px-4 py-2 rounded-full text-sm font-bold {{ $warnaStatus }}">
                {{ $status ? ucfirst($status) : 'Belum Ada' }}
            </span>

            <div class="mt-5 text-sm text-slate-600 space-y-2">
                <p>
                    <span class="font-semibold">Nama:</span>
                    {{ $user->nama }}
                </p>
                <p>
                    <span class="font-semibold">Email:</span>
                    {{ $user->email }}
                </p>
                <p>
                    <span class="font-semibold">Bergabung:</span>
                    {{ $user->created_at->format('d M Y') }}
                </p>
            </div>

            @if ($status === 'rejected')
            <div class="mt-5 p-4 rounded-xl bg-red-50 text-red-700 text-sm">
                <p class="font-bold">Catatan Admin:</p>
                <p class="mt-1">
                    {{ $verifikasi?->catatan_admin ?? 'Tidak ada catatan.' }}
                </p>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <h2 class="text-xl font-bold text-slate-900 mb-4">
                Portofolio
            </h2>

            <p class="text-3xl font-bold text-blue-600">
                {{ $portofolios->count() }}
            </p>
            <p class="text-sm text-slate-500 mt-1">
                Total portofolio yang terhubung dengan akun ini.
            </p>

            <a href="{{ route('freelancer.portfolio.index') }}"
                class="inline-block mt-5 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">
                Lihat Portofolio
            </a>
        </div>
    </div>
</div>
@endsection