<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Portofolio;
use App\Models\User;
use App\Models\VerifikasiFreelancer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Services\CloudinaryService;

class FreelancerRegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register-freelancer');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string'],
            'email_kampus' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email',
                'unique:verifikasi_freelancers,email_kampus',

                function ($attribute, $value, $fail) {
                    $domain = strtolower(substr(strrchr($value, "@"), 1));

                    $blockedDomains = [
                        'gmail.com',
                        'yahoo.com',
                        'ymail.com',
                        'rocketmail.com',
                        'outlook.com',
                        'hotmail.com',
                        'live.com',
                        'icloud.com',
                        'me.com',
                        'proton.me',
                        'protonmail.com',
                        'aol.com',
                        'mail.com',
                        'zoho.com',
                        'gmx.com',
                    ];

                    if (in_array($domain, $blockedDomains)) {
                        $fail('Gunakan email kampus atau email institusi pendidikan, bukan email pribadi.');
                    }
                },
            ],
            'universitas' => ['required', 'string', 'max:255'],
            'program_studi' => ['nullable', 'string', 'max:255'],
            'file_ktm' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'file_portofolio' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf,doc,docx,ppt,pptx',
                'max:1024',
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::transaction(function () use ($request) {
            $emailFolder = str_replace(['@', '.'], '_', strtolower($request->email_kampus));

            $ktmPath = CloudinaryService::uploadFile(
                $request->file('file_ktm'),
                'jasakampus/freelancer/' . $emailFolder . '/ktm'
            );

            $portofolioPath = CloudinaryService::uploadFile(
                $request->file('file_portofolio'),
                'jasakampus/freelancer/' . $emailFolder . '/portfolio'
            );

            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email_kampus,
                'role' => 'freelancer',
                'alamat' => $request->alamat,
                'status_akun' => 'active',
                'password' => Hash::make($request->password),
            ]);

            VerifikasiFreelancer::create([
                'id_freelancer' => $user->id,
                'email_kampus' => $request->email_kampus,
                'universitas' => $request->universitas,
                'program_studi' => $request->program_studi,
                'file_ktm' => $ktmPath,
                'status_verifikasi' => 'pending',
                'tanggal_pengajuan' => now(),
            ]);

            Portofolio::create([
                'id_freelancer' => $user->id,
                'judul_portofolio' => 'Portofolio Awal',
                'deskripsi' => 'Portofolio yang diunggah saat pendaftaran freelancer.',
                'file_portofolio' => $portofolioPath,
            ]);

            Auth::login($user);
        });

        return redirect()
            ->route('dashboard')
            ->with('success', 'Pendaftaran freelancer berhasil. Akun Anda menunggu verifikasi admin.');
    }
}
