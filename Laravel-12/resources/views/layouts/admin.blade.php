<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard - JasaKampus')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f6f8fb] text-slate-800 min-h-screen">
    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-[#f2f5f8] border-r border-slate-200 flex flex-col">
            <div class="h-16 flex items-center px-6 border-b border-slate-200">
                <h1 class="text-xl font-bold text-blue-700">JasaKampus</h1>
            </div>

            <nav class="flex-1 px-4 py-5 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition
   {{ request()->routeIs('dashboard') 
        ? 'bg-blue-600 text-white' 
        : 'text-slate-700 hover:bg-white' }}">
                    <span>▦</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.verifikasi.freelancer') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition
   {{ request()->routeIs('admin.verifikasi.*') 
        ? 'bg-blue-600 text-white' 
        : 'text-slate-700 hover:bg-white' }}">
                    <span>⌘</span>
                    <span>Verifikasi</span>
                </a>

                <a href="{{ route('admin.withdrawals.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.withdrawals.*') ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-white' }}">
                    <span>🏦</span>
                    <span>Pencairan Dana</span>
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-white">
                    <span>♙</span>
                    <span>Users</span>
                </a>

                <a href="{{ route('admin.transactions.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.transactions.*') ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-white' }}">
                    <span>💳</span>
                    <span>Pembayaran</span>
                </a>

                <a href="{{ route('admin.disputes.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('admin.disputes.*') ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-white' }}">
                    <span>⚖️</span>
                    <span>Aduan</span>
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-white">
                    <span>♙</span>
                    <span>Profile</span>

                    <a href="{{ route('admin.jasa.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition
   {{ request()->routeIs('admin.jasa.*') ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                        <span>🛠️</span>
                        <span>Kelola Jasa</span>
                    </a>

                </a>
            </nav>

            <div class="px-4 py-5 border-t border-slate-200 space-y-2">
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-white">
                    <span>⚙</span>
                    <span>Settings</span>
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-white">
                    <span>?</span>
                    <span>Help</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50">
                        <span>↩</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="flex-1">

            <!-- TOPBAR -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
                <div></div>

                <div class="flex items-center gap-4">
                    <div class="relative">
                        <input type="text"
                            placeholder="Cari data..."
                            class="w-72 rounded-lg border-slate-300 text-sm pl-10 pr-4 py-2 focus:ring-blue-500 focus:border-blue-500">
                        <span class="absolute left-3 top-2 text-slate-400">⌕</span>
                    </div>

                    <button class="w-9 h-9 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50">

                    </button>

                    @php
                    $jumlahNotifikasi = \App\Models\Notifikasi::where('id_user', auth()->id())
                    ->where('dibaca', false)
                    ->count();
                    @endphp

                    <a href="{{ route('notifikasi.index') }}"
                        class="relative inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-200 hover:bg-slate-50">
                        🔔

                        @if ($jumlahNotifikasi > 0)
                        <span class="absolute -top-1 -right-1 min-w-5 h-5 px-1 rounded-full bg-red-600 text-white text-[11px] font-bold flex items-center justify-center">
                            {{ $jumlahNotifikasi }}
                        </span>
                        @endif
                    </a>

                    <div class="w-9 h-9 rounded-full bg-slate-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>
                </div>
            </header>

            <!-- CONTENT -->
            <section class="p-8">
                @yield('content')
            </section>

        </main>
    </div>
</body>

</html>