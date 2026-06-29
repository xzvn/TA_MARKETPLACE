<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Freelancer - JasaKampus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">

    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="bg-white rounded-2xl shadow p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Daftar Sebagai Freelancer
            </h1>

            <p class="text-gray-600 mb-8">
                Lengkapi data diri, email kampus, KTM, dan portofolio Anda untuk proses verifikasi.
            </p>

            <form method="POST" action="{{ route('freelancer.register.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="mt-1 w-full rounded-lg border-gray-300" required>
                    @error('nama')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Alamat Domisili</label>
                    <textarea name="alamat" class="mt-1 w-full rounded-lg border-gray-300" rows="3" required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kampus / Universitas</label>
                    <input type="text" name="universitas" value="{{ old('universitas') }}" class="mt-1 w-full rounded-lg border-gray-300" required>
                    @error('universitas')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Program Studi</label>
                    <input type="text" name="program_studi" value="{{ old('program_studi') }}" class="mt-1 w-full rounded-lg border-gray-300">
                    @error('program_studi')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Kampus</label>
                    <input
                        type="email"
                        name="email_kampus"
                        value="{{ old('email_kampus') }}"
                        class="mt-1 w-full rounded-lg border-gray-300"
                        required>

                    ```
                    <p class="text-sm text-gray-500 mt-1">
                        Gunakan email kampus atau email institusi pendidikan. Email pribadi seperti Gmail/Yahoo tidak diperbolehkan.
                    </p>

                    @error('email_kampus')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    ```

                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Upload KTM</label>
                    <input
                        type="file"
                        name="file_ktm"
                        class="mt-1 w-full rounded-lg border-gray-300"
                        required>

                    ```
                    @error('file_ktm')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    ```

                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Upload Portofolio</label>
                    <input
                        type="file"
                        name="file_portofolio"
                        class="mt-1 w-full rounded-lg border-gray-300"
                        accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.ppt,.pptx"
                        required>

                    ```
                    @error('file_portofolio')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    ```

                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" class="mt-1 w-full rounded-lg border-gray-300" required>
                    @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="mt-1 w-full rounded-lg border-gray-300" required>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700">
                    Kirim Lamaran Freelancer
                </button>
            </form>
        </div>
    </div>

</body>

</html>