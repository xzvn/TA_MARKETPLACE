@extends('layouts.customer')

@section('title', 'Profil Saya - JasaKampus')

@section('content')
<section class="px-6 py-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">
                Profil Saya
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Kelola informasi akun customer kamu.
            </p>
        </div>

        @if (session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-50 text-green-700 text-sm font-semibold">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 text-red-700 text-sm">
            <p class="font-bold mb-2">Ada data yang belum sesuai:</p>
            <ul class="list-disc ml-5 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('customer.profile.update') }}"
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
                        Format: JPG, PNG, WEBP. Maksimal 2MB.
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
</section>
@endsection