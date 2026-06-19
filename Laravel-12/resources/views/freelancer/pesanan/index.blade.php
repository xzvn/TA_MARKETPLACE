@extends('layouts.freelancer')

@section('title', 'Pesanan Masuk - JasaKampus')
@section('page-title', 'Pesanan Masuk')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-900">
        Pesanan Masuk
    </h1>
    <p class="text-sm text-slate-500 mt-1">
        Daftar order customer yang sudah melakukan pembayaran.
    </p>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-200">
        <h3 class="font-semibold text-slate-800">
            Daftar Pesanan
        </h3>
        <p class="text-sm text-slate-500">
            Pesanan baru muncul setelah customer menyelesaikan pembayaran.
        </p>
    </div>

    <div class="mb-4 p-3 bg-yellow-100 text-yellow-800 rounded-lg">
        Total proyek ditemukan: {{ $pesanans->count() }}

        <br>

        ID Pesanan:
        @foreach ($pesanans as $p)
        #{{ $p->id }}
        @endforeach
    </div>
    
    @if ($pesanans->count() > 0)
    <div class="divide-y divide-slate-100">
        @foreach ($pesanans as $pesanan)
        <a href="{{ route('freelancer.progress.create', $pesanan->id) }}"
            class="block px-6 py-5 hover:bg-slate-50 transition">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <h4 class="font-bold text-slate-900">
                            Order #{{ $pesanan->id }}
                        </h4>

                        <span class="px-3 py-1 rounded-full text-xs font-bold
                                        @if ($pesanan->status_pesanan === 'dibayar') bg-blue-100 text-blue-700
                                        @elseif ($pesanan->status_pesanan === 'diproses') bg-yellow-100 text-yellow-700
                                        @elseif ($pesanan->status_pesanan === 'selesai') bg-green-100 text-green-700
                                        @else bg-slate-100 text-slate-700
                                        @endif">
                            {{ str_replace('_', ' ', strtoupper($pesanan->status_pesanan)) }}
                        </span>
                    </div>

                    <p class="text-sm text-slate-500 mt-1">
                        Customer: {{ $pesanan->customer->nama ?? '-' }}
                    </p>

                    <p class="text-sm text-slate-500">
                        Jasa: {{ $pesanan->jasa->nama_jasa ?? '-' }}
                    </p>

                    <p class="text-sm text-slate-600 mt-2 line-clamp-1">
                        {{ $pesanan->deskripsi_kebutuhan }}
                    </p>
                </div>

                <div class="text-right shrink-0">
                    <p class="font-bold text-blue-600 text-lg">
                        Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        {{ $pesanan->created_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    @else
    <div class="p-10 text-center">
        <div class="w-16 h-16 mx-auto rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-2xl mb-4">
            📦
        </div>

        <h3 class="text-lg font-bold text-slate-900">
            Belum ada pesanan masuk
        </h3>

        <p class="text-sm text-slate-500 mt-2">
            Pesanan akan muncul setelah customer membuat order dan menyelesaikan pembayaran.
        </p>
    </div>
    @endif
</div>
@endsection