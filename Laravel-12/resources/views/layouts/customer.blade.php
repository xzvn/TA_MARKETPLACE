<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Customer Dashboard - JasaKampus')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f4f7fb] text-slate-800 min-h-screen">

    <div class="min-h-screen flex flex-col">

        <header class="h-14 bg-white border-b border-slate-200 flex items-center px-5 sticky top-0 z-50">
            <div class="flex items-center gap-8 w-full">
                <a href="{{ route('dashboard') }}" class="text-lg font-bold text-blue-700">
                    JasaKampus
                </a>

                <nav class="hidden md:flex items-center gap-5 text-xs font-medium">
                    <a href="{{ route('customer.marketplace') }}"
                        class="{{ request()->routeIs('customer.marketplace') ? 'text-blue-700 border-b-2 border-blue-700 pb-1' : 'text-slate-600 hover:text-blue-700' }}">
                        Pasar Jasa
                    </a>

                    <a href="#"
                        class="text-slate-600 hover:text-blue-700">
                        Kategori
                    </a>

                    <a href="#"
                        class="text-slate-600 hover:text-blue-700">
                        Tentang Kami
                    </a>
                </nav>

                <form method="GET"
                    action="{{ route('customer.marketplace') }}"
                    class="hidden md:block flex-1 max-w-xl ml-auto">
                    <input type="text"
                        name="search"
                        placeholder="Cari layanan..."
                        class="w-full h-9 rounded-md border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                </form>
            </div>
        </header>

        <div class="flex flex-1">
            <aside class="w-52 bg-[#eef3f8] border-r border-slate-200 hidden md:block">
                <nav class="p-4 space-y-2 text-sm">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-md font-semibold {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-white' }}">
                        <span>▦</span>
                        <span>Beranda</span>
                    </a>

                    <a href="{{ route('customer.order.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-md font-medium {{ request()->routeIs('customer.order.*') ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-white' }}">
                        <span>🛒</span>
                        <span>Pesanan</span>
                    </a>

                    <a href="{{ route('customer.chat.index') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-md font-medium {{ request()->routeIs('customer.chat.*') ? 'bg-blue-600 text-white' : 'text-slate-700 hover:bg-white' }}">
                        <span>💬</span>
                        <span>Pesan</span>
                    </a>

                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-md font-medium text-slate-700 hover:bg-white">
                        <span>💳</span>
                        <span>Pembayaran</span>
                    </a>

                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-md font-medium text-slate-700 hover:bg-white">
                        <span>⭐</span>
                        <span>Ulasan</span>
                    </a>

                    <a href="#"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-md font-medium text-slate-700 hover:bg-white">
                        <span>⚙</span>
                        <span>Progress</span>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-md font-medium text-slate-700 hover:bg-white">
                        <span>👤</span>
                        <span>Profil</span>
                    </a>
                </nav>
            </aside>

            <main class="flex-1">
                <div class="min-h-[calc(100vh-56px)]">
                    @if (session('success'))
                    <div class="mx-6 mt-6 px-5 py-4 bg-green-100 text-green-700 rounded-xl border border-green-200">
                        {{ session('success') }}
                    </div>
                    @endif

                    @yield('content')
                </div>

                <footer class="bg-white border-t border-slate-200 px-8 py-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-sm">
                        <div>
                            <h3 class="font-bold text-blue-700 text-lg">JasaKampus</h3>
                            <p class="text-slate-500 mt-3 leading-relaxed">
                                Memberdayakan mahasiswa untuk membuka peluang jasa,
                                membangun portofolio, dan bertransaksi dengan aman.
                            </p>
                        </div>

                        <div>
                            <h4 class="font-bold text-slate-800 mb-3">PASAR</h4>
                            <p class="text-slate-500 mb-2">Grafis & Desain</p>
                            <p class="text-slate-500 mb-2">Pemrograman Digital</p>
                            <p class="text-slate-500 mb-2">Penulisan & Terjemahan</p>
                            <p class="text-slate-500">Video & Animasi</p>
                        </div>

                        <div>
                            <h4 class="font-bold text-slate-800 mb-3">TENTANG</h4>
                            <p class="text-slate-500 mb-2">Karier</p>
                            <p class="text-slate-500 mb-2">Pers & Berita</p>
                            <p class="text-slate-500 mb-2">Kemitraan</p>
                            <p class="text-slate-500">Kebijakan Privasi</p>
                        </div>

                        <div>
                            <h4 class="font-bold text-slate-800 mb-3">KOMUNITAS</h4>
                            <p class="text-slate-500 mb-2">Acara</p>
                            <p class="text-slate-500 mb-2">Blog</p>
                            <p class="text-slate-500 mb-2">Forum</p>
                            <p class="text-slate-500">Afiliasi</p>
                        </div>
                    </div>

                    <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <p>© 2024 JasaKampus Inc. Hak cipta dilindungi undang-undang.</p>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="text-red-600 font-semibold hover:underline">
                                Logout
                            </button>
                        </form>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>