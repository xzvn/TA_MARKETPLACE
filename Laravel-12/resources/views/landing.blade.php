<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JasaKampus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900">

    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="text-2xl font-bold text-blue-600">
                JasaKampus
            </div>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="#" class="text-gray-700 hover:text-blue-600">Pasar Jasa</a>
                <a href="#" class="text-gray-700 hover:text-blue-600">Kategori</a>
                <a href="#" class="text-gray-700 hover:text-blue-600">Tentang Kami</a>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                    class="px-4 py-2 text-sm font-semibold text-blue-600 border border-blue-600 rounded-lg hover:bg-blue-50">
                    Login Customer
                </a>

                <a href="{{ route('register') }}"
                    class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    Daftar Customer
                </a>

                <a href="{{ route('freelancer.register') }}"
                    class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg hover:bg-gray-800">
                    Daftar Freelancer
                </a>
            </div>
        </div>
    </nav>

    <section class="max-w-7xl mx-auto px-6 py-20 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
            <p class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold mb-6">
                Platform Jasa Mahasiswa Terverifikasi
            </p>

            <h1 class="text-5xl font-bold leading-tight text-gray-900 mb-6">
                Hubungkan Kebutuhan Anda dengan Talenta Mahasiswa Terbaik
            </h1>

            <p class="text-lg text-gray-600 mb-8">
                Temukan mahasiswa berbakat untuk membantu desain grafis, pemrograman, penulisan, riset, editing video, dan berbagai kebutuhan proyek lainnya.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('login') }}"
                    class="px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold text-center hover:bg-blue-700">
                    Login Customer
                </a>

                <a href="{{ route('register') }}"
                    class="px-6 py-3 bg-white border border-blue-600 text-blue-600 rounded-xl font-semibold text-center hover:bg-blue-50">
                    Daftar Customer
                </a>

                <a href="{{ route('freelancer.register') }}"
                    class="px-6 py-3 bg-gray-900 text-white rounded-xl font-semibold text-center hover:bg-gray-800">
                    Daftar Jadi Freelancer
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-xl p-8">
            <div class="h-80 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl flex items-center justify-center">
                <p class="text-gray-500 font-medium">Ilustrasi Marketplace Jasa Mahasiswa</p>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-12">
        <h2 class="text-3xl font-bold text-center mb-10">
            Jelajahi Kategori Populer
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="font-bold text-lg">Desain Grafis</h3>
                <p class="text-gray-500 text-sm mt-2">Logo, poster, branding, dan visual kreatif.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="font-bold text-lg">Pemrograman</h3>
                <p class="text-gray-500 text-sm mt-2">Website, aplikasi, dan script otomatisasi.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="font-bold text-lg">Akademik</h3>
                <p class="text-gray-500 text-sm mt-2">Riset, proofreading, dan olah data.</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow">
                <h3 class="font-bold text-lg">Video Editing</h3>
                <p class="text-gray-500 text-sm mt-2">Reels, TikTok, YouTube, dan animasi.</p>
            </div>
        </div>
    </section>

</body>

</html>