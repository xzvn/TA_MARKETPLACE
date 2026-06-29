<?php

use App\Http\Controllers\Auth\FreelancerRegisterController;
use App\Models\User;
use App\Models\VerifikasiFreelancer;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\FreelancerVerificationController;
use App\Http\Controllers\Freelancer\JasaController;
use App\Http\Controllers\Freelancer\ChatController as FreelancerChatController;
use App\Http\Controllers\Customer\MarketplaceController;
use App\Http\Controllers\Customer\ChatController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Freelancer\PesananController as FreelancerPesananController;
use App\Http\Controllers\Freelancer\ProgressPekerjaanController;
use App\Models\Jasa;
use App\Http\Controllers\Customer\OrderReviewController;
use App\Http\Controllers\Freelancer\HasilPekerjaanController;
use App\Http\Controllers\Freelancer\EarningController;
use App\Http\Controllers\Freelancer\WithdrawalController as FreelancerWithdrawalController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\Admin\TransactionMonitoringController;
use App\Http\Controllers\Customer\DisputeController as CustomerDisputeController;
use App\Http\Controllers\Admin\DisputeController as AdminDisputeController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Admin\JasaController as AdminJasaController;
use App\Models\Notifikasi;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Models\Dispute;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Services\NotifikasiService;
use App\Http\Controllers\Customer\ProgressController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Models\Review;
use App\Http\Controllers\Freelancer\ProfileController as FreelancerProfileController;




Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        $totalPengguna = User::count();
        $freelancerAktif = User::where('role', 'freelancer')->count();

        $layananTerdaftar = Jasa::count();
        $layananAktif = Jasa::where('status_jasa', 'active')->count();
        $layananPending = Jasa::where('status_jasa', 'pending')->count();
        $layananDitolak = Jasa::where('status_jasa', 'rejected')->count();

        $proyekBerjalan = Pesanan::whereIn('status_pesanan', [
            'dibayar',
            'diproses',
            'revisi',
            'menunggu_approve',
        ])->count();

        $totalPesanan = Pesanan::count();
        $totalTransaksi = Pembayaran::count();

        $totalPendapatan = Pembayaran::where('status_escrow', 'dicairkan')
            ->sum('gross_amount');

        $escrowDitahan = Pembayaran::where('status_escrow', 'ditahan')
            ->sum('gross_amount');

        $verifikasiPending = VerifikasiFreelancer::where('status_verifikasi', 'pending')
            ->count();

        $disputeAktif = Dispute::whereIn('status_dispute', ['pending', 'diproses'])
            ->count();

        $withdrawalPending = Withdrawal::where('status_withdrawal', 'pending')
            ->count();

        $logLimit = (int) request()->query('log_limit', 5);

        if ($logLimit < 5) {
            $logLimit = 5;
        }

        if ($logLimit > 50) {
            $logLimit = 50;
        }

        $totalAktivitas = Notifikasi::count();

        $aktivitasTerbaru = Notifikasi::with('user')
            ->latest()
            ->take($logLimit)
            ->get()
            ->map(function ($notifikasi) {
                return [
                    'event' => $notifikasi->judul,
                    'aktor' => $notifikasi->user->nama ?? '-',
                    'status' => strtoupper($notifikasi->tipe),
                    'warna' => match ($notifikasi->tipe) {
                        'dispute' => 'red',
                        'withdrawal' => 'yellow',
                        'pembayaran' => 'green',
                        'order' => 'blue',
                        'progress' => 'blue',
                        'revisi' => 'yellow',
                        'hasil' => 'green',
                        default => 'blue',
                    },
                    'waktu' => $notifikasi->created_at,
                    'url' => $notifikasi->url,
                ];
            });

        $adaLogLainnya = $totalAktivitas > $aktivitasTerbaru->count();
        $nextLogLimit = $logLimit + 10;

        $tahunDashboard = now()->year;

        $bulanLabels = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];

        /*
|--------------------------------------------------------------------------
| Grafik Tren Pendapatan Bulanan
|--------------------------------------------------------------------------
*/

        $trenPendapatan = [];

        foreach ($bulanLabels as $bulan => $label) {
            $trenPendapatan[] = [
                'bulan' => $label,
                'total' => Pembayaran::where('status_escrow', 'dicairkan')
                    ->whereYear('created_at', $tahunDashboard)
                    ->whereMonth('created_at', $bulan)
                    ->sum('gross_amount'),
            ];
        }

        $pendapatanMaks = collect($trenPendapatan)->max('total') ?: 1;

        $trenPendapatanChart = collect($trenPendapatan)
            ->map(function ($item) use ($pendapatanMaks) {
                return [
                    'bulan' => $item['bulan'],
                    'total' => $item['total'],
                    'persen' => round(($item['total'] / $pendapatanMaks) * 100),
                ];
            })
            ->values();

        /*
|--------------------------------------------------------------------------
| Grafik Pertumbuhan Pengguna Bulanan
|--------------------------------------------------------------------------
*/

        $pertumbuhanPengguna = [];

        foreach ($bulanLabels as $bulan => $label) {
            $customer = User::where('role', 'customer')
                ->whereYear('created_at', $tahunDashboard)
                ->whereMonth('created_at', $bulan)
                ->count();

            $freelancer = User::where('role', 'freelancer')
                ->whereYear('created_at', $tahunDashboard)
                ->whereMonth('created_at', $bulan)
                ->count();

            $pertumbuhanPengguna[] = [
                'bulan' => $label,
                'customer' => $customer,
                'freelancer' => $freelancer,
            ];
        }

        $penggunaMaks = collect($pertumbuhanPengguna)
            ->flatMap(fn($item) => [$item['customer'], $item['freelancer']])
            ->max() ?: 1;

        $pertumbuhanPenggunaChart = collect($pertumbuhanPengguna)
            ->map(function ($item) use ($penggunaMaks) {
                return [
                    'bulan' => $item['bulan'],
                    'customer' => $item['customer'],
                    'freelancer' => $item['freelancer'],
                    'customer_persen' => round(($item['customer'] / $penggunaMaks) * 100),
                    'freelancer_persen' => round(($item['freelancer'] / $penggunaMaks) * 100),
                ];
            })
            ->values();

        return view('admin.dashboard', compact(
            'totalPengguna',
            'freelancerAktif',
            'layananTerdaftar',
            'layananAktif',
            'layananPending',
            'layananDitolak',
            'proyekBerjalan',
            'totalPesanan',
            'totalTransaksi',
            'totalPendapatan',
            'escrowDitahan',
            'verifikasiPending',
            'disputeAktif',
            'withdrawalPending',
            'aktivitasTerbaru',
            'logLimit',
            'totalAktivitas',
            'adaLogLainnya',
            'nextLogLimit',
            'tahunDashboard',
            'trenPendapatanChart',
            'pertumbuhanPenggunaChart'
        ));
    }

    if ($user->role === 'freelancer') {
        $totalJasa = Jasa::where('id_freelancer', $user->id)->count();

        $jasaAktif = Jasa::where('id_freelancer', $user->id)
            ->where('status_jasa', 'active')
            ->count();

        $jasaPending = Jasa::where('id_freelancer', $user->id)
            ->where('status_jasa', 'pending')
            ->count();

        $jasaDitolak = Jasa::where('id_freelancer', $user->id)
            ->where('status_jasa', 'rejected')
            ->count();

        $pesananBerjalan = Pesanan::where('id_freelancer', $user->id)
            ->whereIn('status_pesanan', [
                'dibayar',
                'diproses',
                'revisi',
                'menunggu_approve',
            ])
            ->count();

        $pesananSelesai = Pesanan::where('id_freelancer', $user->id)
            ->where('status_pesanan', 'selesai')
            ->count();

        $pesananDispute = Pesanan::where('id_freelancer', $user->id)
            ->where('status_pesanan', 'dispute')
            ->count();

        $saldoDitahan = Pembayaran::whereHas('pesanan', function ($q) use ($user) {
            $q->where('id_freelancer', $user->id);
        })
            ->where('status_escrow', 'ditahan')
            ->sum('gross_amount');

        $totalPendapatan = Pembayaran::whereHas('pesanan', function ($q) use ($user) {
            $q->where('id_freelancer', $user->id);
        })
            ->where('status_escrow', 'dicairkan')
            ->sum('gross_amount');

        $ratingRataRata = Review::where('id_freelancer', $user->id)
            ->avg('rating');

        $totalReview = Review::where('id_freelancer', $user->id)
            ->count();

        $pesananTerbaru = Pesanan::with(['customer', 'jasa', 'pembayaran'])
            ->where('id_freelancer', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $jasaTerbaru = Jasa::where('id_freelancer', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $aktivitasTerbaru = Notifikasi::where('id_user', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('freelancer.dashboard', compact(
            'totalJasa',
            'jasaAktif',
            'jasaPending',
            'jasaDitolak',
            'pesananBerjalan',
            'pesananSelesai',
            'pesananDispute',
            'saldoDitahan',
            'totalPendapatan',
            'ratingRataRata',
            'totalReview',
            'pesananTerbaru',
            'jasaTerbaru',
            'aktivitasTerbaru'
        ));
    }

    $jasa = Jasa::with('freelancer')
        ->withAvg('reviews as rating_rata_rata', 'rating')
        ->withCount('reviews')
        ->where('status_jasa', 'active')
        ->whereHas('freelancer.verifikasiFreelancer', function ($q) {
            $q->where('status_verifikasi', 'approved');
        })
        ->latest()
        ->paginate(6)
        ->withQueryString();



    return view('customer.dashboard', compact('jasa'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])
        ->name('notifikasi.index');

    Route::post('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'read'])
        ->name('notifikasi.read');

    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'readAll'])
        ->name('notifikasi.readAll');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/verifikasi-freelancer', [FreelancerVerificationController::class, 'index'])
        ->name('verifikasi.freelancer');

    Route::patch('/verifikasi-freelancer/{verifikasi}/approve', [FreelancerVerificationController::class, 'approve'])
        ->name('verifikasi.approve');

    Route::patch('/verifikasi-freelancer/{verifikasi}/reject', [FreelancerVerificationController::class, 'reject'])
        ->name('verifikasi.reject');

    Route::get('/pesanan', [FreelancerPesananController::class, 'index'])
        ->name('pesanan.index');

    Route::get('/pesanan/{pesanan}', [FreelancerPesananController::class, 'show'])
        ->name('pesanan.show');

    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])
        ->name('withdrawals.index');

    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])
        ->name('withdrawals.approve');

    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])
        ->name('withdrawals.reject');

    Route::get('/transactions', [TransactionMonitoringController::class, 'index'])
        ->name('transactions.index');

    Route::get('/disputes', [AdminDisputeController::class, 'index'])
        ->name('disputes.index');

    Route::post('/disputes/{dispute}/refund', [AdminDisputeController::class, 'refund'])
        ->name('disputes.refund');

    Route::post('/disputes/{dispute}/release', [AdminDisputeController::class, 'releaseToFreelancer'])
        ->name('disputes.release');

    Route::get('/jasa', [AdminJasaController::class, 'index'])->name('jasa.index');

    Route::post('/jasa/{jasa}/approve', [AdminJasaController::class, 'approve'])->name('jasa.approve');

    Route::post('/jasa/{jasa}/reject', [AdminJasaController::class, 'reject'])->name('jasa.reject');

    // Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/laporan/download', function () {
        abort_if(auth()->user()->role !== 'admin', 403);

        $filename = 'laporan-jasakampus-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            // agar aman dibuka di Excel
            echo chr(239) . chr(187) . chr(191);

            fputcsv($handle, ['LAPORAN DASHBOARD ADMIN JASAKAMPUS']);
            fputcsv($handle, ['Tanggal Unduh', now()->format('d-m-Y H:i:s')]);
            fputcsv($handle, []);

            fputcsv($handle, ['RINGKASAN']);
            fputcsv($handle, ['Total Pengguna', User::count()]);
            fputcsv($handle, ['Total Customer', User::where('role', 'customer')->count()]);
            fputcsv($handle, ['Total Freelancer', User::where('role', 'freelancer')->count()]);
            fputcsv($handle, ['Total Admin', User::where('role', 'admin')->count()]);
            fputcsv($handle, ['Total Jasa', Jasa::count()]);
            fputcsv($handle, ['Jasa Aktif', Jasa::where('status_jasa', 'active')->count()]);
            fputcsv($handle, ['Jasa Pending', Jasa::where('status_jasa', 'pending')->count()]);
            fputcsv($handle, ['Jasa Ditolak', Jasa::where('status_jasa', 'rejected')->count()]);
            fputcsv($handle, ['Total Pesanan', Pesanan::count()]);
            fputcsv($handle, ['Total Transaksi', Pembayaran::count()]);
            fputcsv($handle, ['Escrow Ditahan', Pembayaran::where('status_escrow', 'ditahan')->sum('gross_amount')]);
            fputcsv($handle, ['Escrow Dicairkan', Pembayaran::where('status_escrow', 'dicairkan')->sum('gross_amount')]);
            fputcsv($handle, ['Withdrawal Pending', Withdrawal::where('status_withdrawal', 'pending')->count()]);
            fputcsv($handle, ['Dispute Aktif', Dispute::whereIn('status_dispute', ['pending', 'diproses'])->count()]);
            fputcsv($handle, ['Verifikasi Freelancer Pending', VerifikasiFreelancer::where('status_verifikasi', 'pending')->count()]);
            fputcsv($handle, []);

            fputcsv($handle, ['DATA PESANAN TERBARU']);
            fputcsv($handle, ['ID Pesanan', 'Customer', 'Freelancer', 'Jasa', 'Status', 'Total Harga', 'Tanggal']);

            Pesanan::with(['customer', 'freelancer', 'jasa'])
                ->latest()
                ->take(50)
                ->get()
                ->each(function ($pesanan) use ($handle) {
                    fputcsv($handle, [
                        $pesanan->id,
                        $pesanan->customer->nama ?? '-',
                        $pesanan->freelancer->nama ?? '-',
                        $pesanan->jasa->nama_jasa ?? '-',
                        $pesanan->status_pesanan,
                        $pesanan->total_harga,
                        optional($pesanan->created_at)->format('d-m-Y H:i'),
                    ]);
                });

            fputcsv($handle, []);
            fputcsv($handle, ['DATA TRANSAKSI TERBARU']);
            fputcsv($handle, ['ID', 'Order ID', 'Status Transaksi', 'Status Escrow', 'Nominal', 'Tanggal']);

            Pembayaran::latest()
                ->take(50)
                ->get()
                ->each(function ($pembayaran) use ($handle) {
                    fputcsv($handle, [
                        $pembayaran->id,
                        $pembayaran->order_id,
                        $pembayaran->transaction_status,
                        $pembayaran->status_escrow,
                        $pembayaran->gross_amount,
                        optional($pembayaran->created_at)->format('d-m-Y H:i'),
                    ]);
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    })->name('reports.download');


    Route::post('/request/store', function (Request $request) {
        abort_if(auth()->user()->role !== 'admin', 403);

        $data = $request->validate([
            'target' => ['required', 'in:all,customer,freelancer'],
            'judul' => ['required', 'string', 'max:150'],
            'pesan' => ['required', 'string', 'min:5'],
            'kirim_email' => ['nullable', 'boolean'],
        ]);

        $users = User::query()
            ->when($data['target'] !== 'all', function ($query) use ($data) {
                $query->where('role', $data['target']);
            })
            ->whereIn('role', ['customer', 'freelancer'])
            ->get();

        foreach ($users as $user) {
            NotifikasiService::kirim(
                $user->id,
                $data['judul'],
                $data['pesan'],
                'system',
                route('dashboard', [], false),
                $request->boolean('kirim_email')
            );
        }

        return back()->with('success', 'Request berhasil dikirim ke ' . $users->count() . ' user.');
    })->name('request.store');
});

Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/marketplace', [MarketplaceController::class, 'index'])
        ->name('marketplace');

    Route::get('/jasa/{jasa}', [MarketplaceController::class, 'show'])
        ->name('jasa.show');

    Route::get('/chat', [ChatController::class, 'index'])
        ->name('chat.index');

    Route::get('/jasa/{jasa}/chat', [ChatController::class, 'show'])
        ->name('chat.show');

    Route::post('/jasa/{jasa}/chat', [ChatController::class, 'store'])
        ->name('chat.store');

    Route::get('/jasa/{jasa}/chat/messages', [ChatController::class, 'messages'])
        ->name('chat.messages');

    Route::get('/jasa/{jasa}/order', [OrderController::class, 'create'])
        ->name('order.create');

    Route::post('/jasa/{jasa}/order', [OrderController::class, 'store'])
        ->name('order.store');

    Route::get('/orders', [OrderController::class, 'index'])
        ->name('order.index');

    Route::get('/orders/{pesanan}', [OrderController::class, 'show'])
        ->name('order.show');

    Route::post('/order/{pesanan}/pay', [PaymentController::class, 'pay'])
        ->name('payment.pay');

    Route::get('/order/{pesanan}/payment', [PaymentController::class, 'show'])
        ->name('payment.show');

    Route::post('/orders/{pesanan}/approve', [OrderReviewController::class, 'approve'])
        ->name('order.approve');

    Route::post('/orders/{pesanan}/revision', [OrderReviewController::class, 'revision'])
        ->name('order.revision');

    Route::post('/orders/{pesanan}/review', [ReviewController::class, 'store'])
        ->name('order.review.store');

    Route::get('/orders/{pesanan}/review', [ReviewController::class, 'create'])
        ->name('order.review.create');

    Route::post('/orders/{pesanan}/dispute', [CustomerDisputeController::class, 'store'])
        ->name('order.dispute.store');

    Route::post('/orders/{pesanan}/dispute', [CustomerDisputeController::class, 'store'])
        ->name('order.dispute.store');

    Route::get('/payments', function () {
        return redirect()->route('customer.order.index');
    })->name('payment.index');

    Route::get('/reviews', [ReviewController::class, 'index'])
        ->name('review.index');

    Route::get('/progress', [ProgressController::class, 'index'])
        ->name('progress.index');

    Route::post('/order/{pesanan}/payment/finish', [PaymentController::class, 'finish'])
        ->name('payment.finish');

    Route::get('/payments', [PaymentController::class, 'index'])
        ->name('payment.index');

    Route::get('/profile', [CustomerProfileController::class, 'index'])
        ->name('profile.index');

    Route::put('/profile', [CustomerProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/profile/verify-pin', [CustomerProfileController::class, 'verifyForm'])
        ->name('profile.verify.form');

    Route::post('/profile/verify-pin', [CustomerProfileController::class, 'verify'])
        ->name('profile.verify');

    Route::post('/order/{pesanan}/payment/simulate-success', [PaymentController::class, 'simulateSuccess'])
        ->name('payment.simulate-success');
});


Route::post('/midtrans/notification', [PaymentController::class, 'notification'])
    ->name('midtrans.notification');

Route::get('/freelancer/register', [FreelancerRegisterController::class, 'create'])
    ->name('freelancer.register');

Route::post('/freelancer/register', [FreelancerRegisterController::class, 'store'])
    ->name('freelancer.register.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth'])->prefix('freelancer')->name('freelancer.')->group(function () {
    Route::get('/jasa', [JasaController::class, 'index'])
        ->name('jasa.index');

    Route::get('/jasa/create', [JasaController::class, 'create'])
        ->name('jasa.create');

    Route::post('/jasa', [JasaController::class, 'store'])
        ->name('jasa.store');

    Route::get('/chat', [FreelancerChatController::class, 'index'])
        ->name('chat.index');

    Route::get('/chat/{jasa}/{customer}', [FreelancerChatController::class, 'show'])
        ->name('chat.show');

    Route::post('/chat/{jasa}/{customer}', [FreelancerChatController::class, 'store'])
        ->name('chat.store');

    Route::get('/pesanan', [FreelancerPesananController::class, 'index'])
        ->name('pesanan.index');

    Route::get('/pesanan/{pesanan}', [FreelancerPesananController::class, 'show'])
        ->name('pesanan.show');

    Route::get('/pesanan/{pesanan}/progress/create', [ProgressPekerjaanController::class, 'create'])
        ->name('progress.create');

    Route::post('/pesanan/{pesanan}/progress', [ProgressPekerjaanController::class, 'store'])
        ->name('progress.store');

    Route::get('/pesanan/{pesanan}/hasil/create', [HasilPekerjaanController::class, 'create'])
        ->name('hasil.create');

    Route::post('/pesanan/{pesanan}/hasil', [HasilPekerjaanController::class, 'store'])
        ->name('hasil.store');


    Route::get('/earnings', [EarningController::class, 'index'])
        ->name('earnings.index');

    Route::get('/withdrawals', [FreelancerWithdrawalController::class, 'index'])
        ->name('withdrawals.index');

    Route::post('/withdrawals', [FreelancerWithdrawalController::class, 'store'])
        ->name('withdrawals.store');

    Route::get('/profile', [FreelancerProfileController::class, 'index'])
        ->name('profile.index');

    Route::put('/profile', [FreelancerProfileController::class, 'update'])
        ->name('profile.update');

    Route::get('/profile/verify-pin', [FreelancerProfileController::class, 'verifyForm'])
        ->name('profile.verify.form');

    Route::post('/profile/verify-pin', [FreelancerProfileController::class, 'verify'])
        ->name('profile.verify');

    Route::get('/portfolio', function () {
        $user = auth()->user();

        abort_if(! $user || $user->role !== 'freelancer', 403);

        $portofolios = $user->portofolios()
            ->latest()
            ->get();

        return view('freelancer.portfolio.index', compact('portofolios'));
    })->name('portfolio.index');
});

require __DIR__ . '/auth.php';
