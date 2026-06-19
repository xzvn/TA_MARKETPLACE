<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Freelancer Panel - JasaKampus')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f6f8fb] text-slate-800 min-h-screen">
    <div class="flex min-h-screen">

        <aside class="w-64 bg-[#f2f5f8] border-r border-slate-200 flex flex-col">
            <div class="h-16 flex items-center px-6 border-b border-slate-200">
                <h1 class="text-xl font-bold text-blue-700">JasaKampus</h1>
            </div>

            <nav class="flex-1 px-4 py-5 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold bg-blue-600 text-white">
                    <span>▦</span>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('freelancer.jasa.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('freelancer.jasa.*') ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-white' }}">
                    <span>▣</span>
                    <span>Jasa Saya</span>
                </a>

                <a href="{{ route('freelancer.chat.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-white">
                    <span>💬</span>
                    <span>Chat</span>
                </a>
                <a href="{{ route('freelancer.pesanan.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('freelancer.pesanan.*') || request()->routeIs('freelancer.progress.*') || request()->routeIs('freelancer.hasil.*') ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-white' }}">
                    <span>📁</span>
                    <span>Proyek</span>
                </a>

                <a href="{{ route('freelancer.earnings.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium {{ request()->routeIs('freelancer.earnings.*') ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-white' }}">
                    <span>💵</span>
                    <span>Earnings</span>
                </a>
                
                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-slate-700 hover:bg-white">
                    <span>👤</span>
                    <span>Profile</span>
                </a>
            </nav>

            <div class="px-4 py-5 border-t border-slate-200">
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

        <main class="flex-1">
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-8">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">
                        @yield('page-title', 'Dashboard Freelancer')
                    </h2>
                </div>

                <div class="flex items-center gap-4">
                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-semibold text-slate-800">
                            {{ Auth::user()->nama }}
                        </p>
                        <p class="text-xs text-slate-500">
                            Freelancer
                        </p>
                    </div>

                    <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                    </div>
                </div>
            </header>

            <section class="p-8">
                @yield('content')
            </section>
        </main>
    </div>
</body>

</html>