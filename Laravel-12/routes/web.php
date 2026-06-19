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



Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user->role === 'admin') {
        $totalUsers = User::count();

        $freelancerPending = VerifikasiFreelancer::where('status_verifikasi', 'pending')->count();

        $freelancerApproved = VerifikasiFreelancer::where('status_verifikasi', 'approved')->count();

        $freelancerRejected = VerifikasiFreelancer::where('status_verifikasi', 'rejected')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'freelancerPending',
            'freelancerApproved',
            'freelancerRejected'
        ));
    }

    if ($user->role === 'freelancer') {
        return view('freelancer.dashboard');
    }

    $jasa = Jasa::with('freelancer')
        ->where('status_jasa', 'active')
        ->whereHas('freelancer.verifikasiFreelancer', function ($q) {
            $q->where('status_verifikasi', 'approved');
        })
        ->latest()
        ->limit(6)
        ->get();

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
});

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
});

require __DIR__ . '/auth.php';
