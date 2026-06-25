@extends('layouts.customer')

@section('title', 'Dashboard Customer - JasaKampus')

@section('content')
<section class="px-6 py-6">
    <div class="flex items-start justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Layanan Desain
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Ditemukan {{ $jasa->count() }} freelancer mahasiswa yang tersedia untuk membantumu.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-sm text-slate-600">
                Urutkan berdasarkan:
            </span>

            <select class="rounded-md border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                <option>Paling Relevan</option>
                <option>Harga Terendah</option>
                <option>Harga Tertinggi</option>
                <option>Terbaru</option>
            </select>
        </div>
    </div>

    @if ($jasa->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach ($jasa as $item)
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden hover:shadow-md transition">
            <div class="relative h-44 bg-slate-100">
                @if ($item->thumbnail)
                <img src="{{ asset('storage/' . $item->thumbnail) }}"
                    alt="{{ $item->nama_jasa }}"
                    class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-slate-200">
                    <span class="text-5xl">🖼️</span>
                </div>
                @endif

                <button class="absolute top-3 right-3 w-8 h-8 bg-white/90 rounded-full flex items-center justify-center text-slate-600 shadow-sm">
                    ♡
                </button>
            </div>

            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-slate-800 text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr($item->freelancer->nama ?? 'F', 0, 1)) }}
                        </div>

                        <span class="text-xs font-semibold text-slate-700">
                            {{ $item->freelancer->nama ?? 'Freelancer' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-1 text-blue-600 font-bold">
                        <span>★</span>

                        @if ($item->reviews_count > 0)
                        <span>{{ number_format($item->rating_rata_rata, 1) }}</span>
                        <span class="text-xs text-slate-400 font-medium">
                            ({{ $item->reviews_count }})
                        </span>
                        @else
                        <span class="text-xs text-slate-400 font-medium">
                            Belum ada rating
                        </span>
                        @endif
                    </div>
                </div>

                <h3 class="font-bold text-slate-900 text-base leading-snug line-clamp-2 min-h-[44px]">
                    {{ $item->nama_jasa }}
                </h3>

                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-[10px] font-bold uppercase">
                        {{ $item->kategori }}
                    </span>

                    <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded text-[10px] font-bold uppercase">
                        Terverifikasi
                    </span>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100 flex items-end justify-between">
                    <div>
                        <p class="text-xs text-slate-500">
                            Mulai dari
                        </p>

                        <p class="text-xs text-slate-500 mt-1">
                            {{ $item->estimasi_pengerjaan }}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-lg font-bold text-blue-600">
                            Rp {{ number_format($item->harga, 0, ',', '.') }}
                        </p>

                        <a href="{{ route('customer.jasa.show', $item->id) }}"
                            class="inline-block mt-2 px-3 py-1.5 bg-blue-600 text-white rounded-md text-xs font-semibold hover:bg-blue-700">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="flex items-center justify-center gap-2 mt-8">
        <button class="w-8 h-8 rounded-md border border-slate-300 bg-white text-slate-500">
            ‹
        </button>

        <button class="w-8 h-8 rounded-md bg-blue-600 text-white text-sm font-bold">
            1
        </button>

        <button class="w-8 h-8 rounded-md border border-slate-300 bg-white text-slate-600 text-sm">
            2
        </button>

        <button class="w-8 h-8 rounded-md border border-slate-300 bg-white text-slate-600 text-sm">
            3
        </button>

        <span class="px-2 text-slate-400">...</span>

        <button class="w-8 h-8 rounded-md border border-slate-300 bg-white text-slate-600 text-sm">
            12
        </button>

        <button class="w-8 h-8 rounded-md border border-slate-300 bg-white text-slate-500">
            ›
        </button>
    </div>
    @else
    <div class="bg-white border border-slate-200 rounded-xl p-12 text-center">
        <div class="w-16 h-16 mx-auto rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-4">
            🔍
        </div>

        <h3 class="text-lg font-bold text-slate-900">
            Belum ada layanan tersedia
        </h3>

        <p class="text-sm text-slate-500 mt-2">
            Layanan akan muncul setelah freelancer membuat jasa dan terverifikasi.
        </p>
    </div>
    @endif
</section>
@endsection