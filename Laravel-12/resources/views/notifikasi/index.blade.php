@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : (auth()->user()->role === 'freelancer' ? 'layouts.freelancer' : 'layouts.customer'))

@section('title', 'Notifikasi - JasaKampus')
@section('page-title', 'Notifikasi')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900">
                Notifikasi
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Lihat semua pemberitahuan terkait aktivitas akun dan pesanan.
            </p>
        </div>

        <form method="POST" action="{{ route('notifikasi.readAll') }}">
            @csrf

            <button type="submit"
                class="px-5 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700">
                Tandai Semua Dibaca
            </button>
        </form>
    </div>

    @if (session('success'))
    <div class="px-5 py-4 bg-green-100 border border-green-200 text-green-700 rounded-xl">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        @if ($notifikasis->count() > 0)
        <div class="divide-y divide-slate-100">
            @foreach ($notifikasis as $notifikasi)
            <div class="p-5 {{ $notifikasi->dibaca ? 'bg-white' : 'bg-blue-50' }}">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h3 class="font-bold text-slate-900">
                                {{ $notifikasi->judul }}
                            </h3>

                            @if (! $notifikasi->dibaca)
                            <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-xs font-bold">
                                Baru
                            </span>
                            @endif

                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-bold">
                                {{ strtoupper($notifikasi->tipe) }}
                            </span>
                        </div>

                        <p class="text-sm text-slate-600 mt-2">
                            {{ $notifikasi->pesan }}
                        </p>

                        <p class="text-xs text-slate-400 mt-2">
                            {{ $notifikasi->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <form method="POST"
                        action="{{ route('notifikasi.read', $notifikasi->id) }}">
                        @csrf

                        <button type="submit"
                            class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-50">
                            Buka
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-12 text-center">
            <div class="text-4xl mb-3">🔔</div>

            <h3 class="font-bold text-slate-900">
                Belum ada notifikasi
            </h3>

            <p class="text-sm text-slate-500 mt-2">
                Notifikasi aktivitas akan muncul di sini.
            </p>
        </div>
        @endif
    </div>
</div>

<x-auto-refresh :seconds="20" />

@endsection