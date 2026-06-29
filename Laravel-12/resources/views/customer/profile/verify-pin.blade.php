@extends('layouts.customer')

@section('title', 'Verifikasi PIN - JasaKampus')

@section('content')
<section class="px-6 py-6">
    <div class="max-w-xl mx-auto bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <div class="mb-6 text-center">
            <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto text-3xl font-bold">
                ✉
            </div>

            <h1 class="text-2xl font-bold text-slate-900 mt-4">
                Verifikasi Perubahan Profil
            </h1>

            <p class="text-sm text-slate-500 mt-2">
                Kami telah mengirim PIN 6 digit ke email akun kamu. Masukkan PIN tersebut untuk menyimpan perubahan profil.
            </p>
        </div>

        @if (session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-50 text-green-700 text-sm font-semibold">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-50 text-red-700 text-sm">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('customer.profile.verify') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    PIN Verifikasi
                </label>

                <input type="text"
                    name="pin"
                    value="{{ old('pin') }}"
                    maxlength="6"
                    inputmode="numeric"
                    placeholder="Masukkan 6 digit PIN"
                    class="w-full border border-slate-300 rounded-xl px-4 py-3 text-center text-2xl tracking-[0.4em] font-bold focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="flex justify-between gap-3">
                <a href="{{ route('customer.profile.index') }}"
                    class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 font-bold hover:bg-slate-50">
                    Batal
                </a>

                <button type="submit"
                    class="px-5 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700">
                    Verifikasi & Simpan
                </button>
            </div>
        </form>

        <p class="text-xs text-slate-400 mt-5 text-center">
            PIN berlaku selama 10 menit.
        </p>
    </div>
</section>
@endsection