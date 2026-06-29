<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Freelancer Panel - JasaKampus')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f6f8fb] text-slate-800 min-h-screen">
    @php
    $user = auth()->user();

    $profileUrl = \Illuminate\Support\Facades\Route::has('freelancer.profile.index')
    ? route('freelancer.profile.index')
    : '#';

    $navItems = [
    [
    'label' => 'Dashboard',
    'icon' => '▦',
    'routes' => ['dashboard'],
    'patterns' => ['dashboard'],
    ],
    [
    'label' => 'Jasa Saya',
    'icon' => '▣',
    'routes' => ['freelancer.jasa.index'],
    'patterns' => ['freelancer.jasa.*'],
    ],
    [
    'label' => 'Chat',
    'icon' => '💬',
    'routes' => ['freelancer.chat.index'],
    'patterns' => ['freelancer.chat.*'],
    ],
    [
    'label' => 'Proyek',
    'icon' => '📁',
    'routes' => ['freelancer.pesanan.index', 'freelancer.project.index'],
    'patterns' => ['freelancer.pesanan.*', 'freelancer.project.*', 'freelancer.progress.*', 'freelancer.hasil.*'],
    ],
    [
    'label' => 'Earnings',
    'icon' => '💵',
    'routes' => ['freelancer.earnings.index', 'freelancer.earning.index'],
    'patterns' => ['freelancer.earnings.*', 'freelancer.earning.*'],
    ],
    [
    'label' => 'Portofolio',
    'icon' => '🖼',
    'routes' => ['freelancer.portfolio.index'],
    'patterns' => ['freelancer.portfolio.*'],
    ],
    [
    'label' => 'Profil',
    'icon' => '👤',
    'routes' => ['freelancer.profile.index'],
    'patterns' => ['freelancer.profile.*'],
    ],
    ];

    $resolveRoute = function ($routes) {
    foreach ($routes as $route) {
    if (\Illuminate\Support\Facades\Route::has($route)) {
    return route($route);
    }
    }

    return '#';
    };

    $isActive = function ($patterns) {
    foreach ($patterns as $pattern) {
    if (request()->routeIs($pattern)) {
    return true;
    }
    }

    return false;
    };
    @endphp

    <div class="flex min-h-screen">
        {{-- SIDEBAR --}}
        <aside class="w-64 bg-[#f2f5f8] border-r border-slate-200 flex flex-col">
            <div class="h-16 flex items-center px-6 border-b border-slate-200">
                <h1 class="text-xl font-bold text-blue-700">
                    JasaKampus
                </h1>
            </div>

            <nav class="flex-1 px-4 py-5 space-y-2">
                @foreach ($navItems as $item)
                @php
                $href = $resolveRoute($item['routes']);
                $active = $isActive($item['patterns']);

                $menuClass = $active
                ? 'bg-blue-600 text-white shadow-sm'
                : 'text-slate-700 hover:bg-white hover:text-blue-700';
                @endphp

                <a href="{{ $href }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold transition {{ $menuClass }}">
                    <span>{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                </a>
                @endforeach
            </nav>

            <div class="px-4 py-5 border-t border-slate-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-semibold text-red-600 hover:bg-red-50 transition">
                        <span>↩</span>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN --}}
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
                            {{ $user->nama }}
                        </p>
                        <p class="text-xs text-slate-500">
                            Freelancer
                        </p>
                    </div>

                    <x-notifikasi-bell />

                    <a href="{{ $profileUrl }}"
                        class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold overflow-hidden hover:ring-4 hover:ring-blue-100 transition"
                        title="Profil Freelancer">
                        @if ($user->foto_profil)
                        <<img src="{{ str_starts_with($user->foto_profil, 'http') ? $user->foto_profil : asset('storage/' . $user->foto_profil) }}"
                            alt="Foto Profil"
                            class="w-full h-full object-cover">
                        @else
                        {{ strtoupper(substr($user->nama ?? $user->email, 0, 1)) }}
                        @endif
                    </a>
                </div>
            </header>

            <section class="p-8">
                @yield('content')
            </section>
        </main>
    </div>
</body>

</html>