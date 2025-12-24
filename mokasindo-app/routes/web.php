<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;       // Tambahan dari GitHub
use Illuminate\Auth\Events\Registered;     // Tambahan dari GitHub
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;
use App\Models\User;
use App\Services\TelegramService;          // Tambahan dari GitHub
use App\Http\Controllers\CompanyRegistrationController;

// Controllers
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\MyAdController;
use App\Http\Controllers\MyBidController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\Admin\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // Get dynamic stats from database
    $stats = [
        'sold' => \App\Models\Auction::where('status', 'sold')->count(),
        'members' => \App\Models\User::where('role', 'user')->count(),
        'auctions' => \App\Models\Auction::count(),
    ];

    return view('landing', compact('stats'));
});

// Locale switcher
Route::get('/locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('locale.switch');

// Group Etalase
Route::prefix('etalase')->name('etalase.')->group(function () {
    Route::get('/filters', [VehicleController::class, 'filters'])->name('filters');
    Route::get('/', [VehicleController::class, 'index'])->name('index');
    Route::get('/{id}', [VehicleController::class, 'show'])->name('show');
});

// Group Member Area (Auth Required)
Route::middleware('auth')->group(function () {
    // Dashboard User
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 1. Wishlists
    Route::get('/wishlists', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlists', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlists/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    // 2. Edit Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // 3. Ganti Password
    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // 4. Iklan Saya
    Route::get('/my-ads', [MyAdController::class, 'index'])->name('my.ads');

    // 5. Hasil Bid / Lelang
    Route::get('/my-bids', [MyBidController::class, 'index'])->name('my.bids');

    // 6. Vehicle Management (CRUD Kendaraan)
    Route::get('/vehicles/create', [VehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [VehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{id}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update');
    Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy'])->name('vehicles.destroy');
});

// Auction Routes (Sistem Lelang)
Route::prefix('auctions')->name('auctions.')->group(function () {
    // Public routes
    Route::get('/', [AuctionController::class, 'index'])->name('index');
    Route::get('/{id}', [AuctionController::class, 'show'])->name('show');
    Route::get('/{id}/data', [AuctionController::class, 'getData'])->name('data');

    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::get('/create/{vehicleId}', [AuctionController::class, 'create'])->name('create');
        Route::post('/store', [AuctionController::class, 'store'])->name('store');
        Route::post('/{id}/bid', [AuctionController::class, 'placeBid'])->name('bid');
        Route::post('/{id}/cancel', [AuctionController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/end', [AuctionController::class, 'end'])->name('end');

        // API endpoints for real-time updates
        Route::get('/{id}/bids-data', [AuctionController::class, 'getBidsData'])->name('bids-data');
        Route::get('/{id}/status-data', [AuctionController::class, 'getStatusData'])->name('status-data');
    });
});

// Notifications Routes
Route::prefix('notifications')->name('notifications.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::post('/{id}/read', [App\Http\Controllers\NotificationController::class, 'read'])->name('read');
    Route::post('/read-all', [App\Http\Controllers\NotificationController::class, 'readAll'])->name('read-all');
    Route::delete('/{id}', [App\Http\Controllers\NotificationController::class, 'delete'])->name('delete');
    Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unread-count');
});

// Midtrans client return URLs (after payment page redirect)
Route::get('/midtrans/finish', [DepositController::class, 'midtransReturn'])->name('midtrans.finish');
Route::get('/midtrans/unfinish', [DepositController::class, 'midtransReturn'])->name('midtrans.unfinish');
Route::get('/midtrans/error', [DepositController::class, 'midtransReturn'])->name('midtrans.error');

// Deposit Routes (Deposit 5% sebelum bid)
Route::prefix('deposits')->name('deposits.')->middleware('auth')->group(function () {
    Route::get('/', [DepositController::class, 'index'])->name('index');
    Route::get('/create', [DepositController::class, 'create'])->name('create');
    Route::post('/store', [DepositController::class, 'store'])->name('store');
    Route::get('/{auctionId}', [DepositController::class, 'show'])->name('show');
    Route::post('/{auctionId}/pay', [DepositController::class, 'pay'])->name('pay');
    Route::get('/payment/{depositId}', [DepositController::class, 'payment'])->name('payment');
    Route::post('/confirm/{depositId}', [DepositController::class, 'confirm'])->name('confirm');
    Route::post('/withdraw', [DepositController::class, 'withdraw'])->name('withdraw');
    Route::post('/webhook', [DepositController::class, 'webhook'])->name('webhook')->withoutMiddleware('auth');
});

// Payment Routes (Full payment untuk pemenang lelang)
Route::prefix('payments')->name('payments.')->middleware('auth')->group(function () {
    Route::get('/{auctionId}', [PaymentController::class, 'show'])->name('show');
    Route::post('/{auctionId}/pay', [PaymentController::class, 'pay'])->name('pay');
    Route::get('/invoice/{paymentId}', [PaymentController::class, 'invoice'])->name('invoice');
    Route::post('/confirm/{paymentId}', [PaymentController::class, 'confirm'])->name('confirm');
    Route::post('/webhook', [PaymentController::class, 'webhook'])->name('webhook')->withoutMiddleware('auth');

    // Admin only
    Route::post('/verify/{paymentId}', [PaymentController::class, 'verify'])->name('verify');
    Route::post('/reject/{paymentId}', [PaymentController::class, 'reject'])->name('reject');
});

// Group Route Company
Route::controller(CompanyController::class)->group(function () {
    Route::get('/about', 'about')->name('company.about');
    Route::get('/faq', 'faq')->name('company.faq');
    Route::get('/contact', 'contact')->name('company.contact');
    Route::post('/contact', 'storeContact')->name('company.contact.store');

    Route::get('/careers', 'career')->name('company.career');
    Route::get('/careers/{id}', 'careerDetail')->name('company.career.show');
    Route::post('/careers/{id}/apply', 'storeCareerApplication')->name('company.career.store');

    Route::get('/terms', function () {
        $page = Page::findBySlug('terms');
        return view('pages.company.generic', compact('page'));
    })->name('company.terms');

    Route::get('/privacy', function () {
        $page = Page::findBySlug('privacy-policy');
        return view('pages.company.generic', compact('page'));
    })->name('company.privacy');

    Route::get('/how-it-works', function () {
        $page = Page::findBySlug('how-it-works');
        return view('pages.company.generic', compact('page'));
    })->name('company.how_it_works');

    Route::get('/cookie-policy', function () {
        $page = Page::findBySlug('cookie-policy');
        return view('pages.company.generic', compact('page'));
    })->name('company.cookie_policy');
});

// --- TES EVENT LISTENER REGISTERED ---
// (Bagian ini dikembalikan dari kode GitHub agar fitur tes notifikasi jalan)
Route::get('/tes-register', function () {
    // Kita pakai WAKTU (time) biar emailnya unik terus setiap detik
    $unik = time();

    // Membuat User Baru Pura-pura
    $userBaru = User::create([
        'name' => "Member $unik",
        'email' => "member$unik@test.com",
        'password' => Hash::make('password123'),
        'telegram_chat_id' => '6179231520', // GANTI ID INI DENGAN ID TELEGRAM SIAPAPUN YANG MAU TES ambil di @userinfobot
        'role' => 'member'
    ]);

    // Memicu Event (Seolah-olah user baru saja daftar)
    // Ini akan memicu Listener untuk kirim Laporan ke Admin
    event(new Registered($userBaru));

    return "✅ User <b>{$userBaru->name}</b> berhasil didaftarkan! <br><br>" .
        "1. Cek HP Admin (Laporan Pendaftaran Masuk).<br>" .
        "2. Cek HP User (Ucapan Selamat Datang Masuk).";
});

// Instagram feed (example)
Route::get('/instagram-feed', [InstagramController::class, 'getMedia']);

// Public Pages List (All Published Pages)
Route::get('/pages', function () {
    $pages = Page::where('is_published', true)->orderBy('updated_at', 'desc')->paginate(12);
    return view('pages.index', compact('pages'));
})->name('pages.index');

// Public Page View (Dynamic CMS Pages)
Route::get('/page/{slug}', function ($slug) {
    $page = Page::where('slug', $slug)->where('is_published', true)->firstOrFail();
    return view('pages.show', compact('page'));
})->name('page.show');

// ====================================================
// 4. HELPER TESTING (Force Login)
// ====================================================
// Jalankan URL ini sekali di browser agar kamu login otomatis sebagai ID 1 (http://mokasindo.test/force-login)
Route::get('/force-login', function () {
    $user = User::find(1);

    if (!$user) {
        // Buat user dummy jika belum ada
        $user = User::create([
            'id' => 1,
            'name' => 'Tester User',
            'email' => 'tester@example.com',
            'password' => bcrypt('password'),
        ]);
    }

    Auth::login($user);

    return "<h1>Berhasil Login!</h1> 
            <p>Login sebagai: <b>" . $user->name . "</b></p>
            <p>Menu Cepat: 
                <a href='/profile'>[Ke Profil]</a> | 
                <a href='/wishlists'>[Ke Wishlists]</a> | 
                <a href='/etalase/vehicles'>[Ke Etalase]</a>
            </p>";
});

// Registrasi perusahaan/user publik
Route::get('/register', [CompanyRegistrationController::class, 'create'])->name('register.form');
Route::post('/register', [CompanyRegistrationController::class, 'store'])->name('company.register');

// Login: tampilkan form login
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/');
    }
    return view('pages.company.login');
})->name('login');

// Proses login
Route::post('/login', function (Request $request) {
    $data = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = ['email' => $data['email'], 'password' => $data['password']];
    $remember = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();

        // Redirect based on role
        $user = Auth::user();
        if ($user->role === 'admin') {
            return redirect()->intended('/admin');
        }

        return redirect()->intended('/dashboard');
    }

    return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
})->name('login.process');

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Admin Routes - CMS Management
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Team Management
    Route::resource('teams', App\Http\Controllers\Admin\TeamController::class);

    // Users Management (Admin)
    Route::resource('users', App\Http\Controllers\Admin\UsersController::class);

    // Vacancy Management
    Route::resource('vacancies', App\Http\Controllers\Admin\VacancyController::class);
    Route::get('vacancies/{vacancy}/applications', [App\Http\Controllers\Admin\VacancyController::class, 'applications'])
        ->name('vacancies.applications');
    Route::get('applications/{application}/download-cv', [App\Http\Controllers\Admin\VacancyController::class, 'downloadCV'])
        ->name('applications.download-cv');
    Route::patch('applications/{application}/status', [App\Http\Controllers\Admin\VacancyController::class, 'updateApplicationStatus'])
        ->name('applications.update-status');

    // FAQ Management
    Route::resource('faqs', App\Http\Controllers\Admin\FaqController::class);

    // Inquiry/Contact Management
    Route::get('inquiries', [App\Http\Controllers\Admin\InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('inquiries/{inquiry}', [App\Http\Controllers\Admin\InquiryController::class, 'show'])->name('inquiries.show');
    Route::post('inquiries/{inquiry}/reply', [App\Http\Controllers\Admin\InquiryController::class, 'reply'])->name('inquiries.reply');
    Route::post('inquiries/{inquiry}/spam', [App\Http\Controllers\Admin\InquiryController::class, 'markAsSpam'])->name('inquiries.spam');
    Route::delete('inquiries/{inquiry}', [App\Http\Controllers\Admin\InquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Page Management (CMS)
    Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
    Route::post('pages/{page}/toggle-publish', [App\Http\Controllers\Admin\PageController::class, 'togglePublish'])->name('pages.toggle-publish');
    Route::get('pages/{page}/revisions', [App\Http\Controllers\Admin\PageController::class, 'revisions'])->name('pages.revisions');
    Route::post('pages/{page}/revisions/{revision}/revert', [App\Http\Controllers\Admin\PageController::class, 'revertRevision'])->name('pages.revisions.revert');

    // Vehicle / Listings Management (Admin)
    Route::resource('vehicles', App\Http\Controllers\Admin\VehiclesController::class);
    Route::post('vehicles/bulk-action', [App\Http\Controllers\Admin\VehiclesController::class, 'bulkAction'])->name('vehicles.bulk');
    Route::post('vehicles/{vehicle}/approve', [App\Http\Controllers\Admin\VehiclesController::class, 'approve'])->name('vehicles.approve');
    Route::post('vehicles/{vehicle}/reject', [App\Http\Controllers\Admin\VehiclesController::class, 'reject'])->name('vehicles.reject');
    Route::post('vehicles/{vehicle}/toggle-feature', [App\Http\Controllers\Admin\VehiclesController::class, 'toggleFeature'])->name('vehicles.toggle-feature');
    Route::post('vehicles/{vehicle}/status', [App\Http\Controllers\Admin\VehiclesController::class, 'changeStatus'])->name('vehicles.change-status');

    // Auctions Management
    Route::resource('auctions', App\Http\Controllers\Admin\AuctionsController::class);
    Route::post('auctions/sync-status', [App\Http\Controllers\Admin\AuctionsController::class, 'syncStatus'])->name('auctions.sync-status');
    Route::post('auctions/add-vehicles', [App\Http\Controllers\Admin\AuctionsController::class, 'addVehicles'])->name('auctions.add-vehicles');
    Route::post('auctions/{auction}/force-end', [App\Http\Controllers\Admin\AuctionsController::class, 'forceEnd'])->name('auctions.force-end');
    Route::post('auctions/{auction}/reopen', [App\Http\Controllers\Admin\AuctionsController::class, 'reopen'])->name('auctions.reopen');
    Route::post('auctions/{auction}/adjust-timer', [App\Http\Controllers\Admin\AuctionsController::class, 'adjustTimer'])->name('auctions.adjust-timer');
    Route::get('auctions/{auction}/bids', [App\Http\Controllers\Admin\AuctionsController::class, 'bids'])->name('auctions.bids');



    // Payments & Transactions Management
    Route::get('payments', [App\Http\Controllers\Admin\PaymentsController::class, 'index'])->name('payments.index');
    Route::get('payments/{payment}', [App\Http\Controllers\Admin\PaymentsController::class, 'show'])->name('payments.show');
    Route::post('payments/{payment}/verify', [App\Http\Controllers\Admin\PaymentsController::class, 'verify'])->name('payments.verify');
    Route::post('payments/{payment}/reject', [App\Http\Controllers\Admin\PaymentsController::class, 'reject'])->name('payments.reject');
    Route::post('payments/{payment}/refund', [App\Http\Controllers\Admin\PaymentsController::class, 'refund'])->name('payments.refund');

    // Platform Settings
    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Deposits Management
    Route::get('deposits', [App\Http\Controllers\Admin\DepositsController::class, 'index'])->name('deposits.index');
    Route::post('deposits/{deposit}/approve', [App\Http\Controllers\Admin\DepositsController::class, 'approve'])->name('deposits.approve');
    Route::post('deposits/{deposit}/reject', [App\Http\Controllers\Admin\DepositsController::class, 'reject'])->name('deposits.reject');
    Route::get('deposits/{deposit}/proof', [App\Http\Controllers\Admin\DepositsController::class, 'getProof'])->name('deposits.proof');

    // Reports & Analytics
    Route::get('reports', [App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('reports.index');
    Route::get('payments/{payment}/invoice', [App\Http\Controllers\Admin\PaymentsController::class, 'invoice'])->name('payments.invoice');
    Route::get('payments/webhook-logs', [App\Http\Controllers\Admin\PaymentsController::class, 'webhookLogs'])->name('payments.webhook-logs');

    // Reconciliation Notes (Subscription Payments)
    Route::get('payments/reconciliation', [App\Http\Controllers\Admin\PaymentsController::class, 'reconciliation'])->name('payments.reconciliation');
    Route::post('payments/{payment}/reconciliation-note', [App\Http\Controllers\Admin\PaymentsController::class, 'updateReconciliationNote'])->name('payments.reconciliation.note');

    // Subscription Plans (CRUD)
    Route::resource('subscription-plans', App\Http\Controllers\Admin\SubscriptionPlansController::class);

    // User Subscriptions Management
    Route::get('user-subscriptions', [App\Http\Controllers\Admin\UserSubscriptionsController::class, 'index'])->name('user-subscriptions.index');
    Route::get('user-subscriptions/{subscription}', [App\Http\Controllers\Admin\UserSubscriptionsController::class, 'show'])->name('user-subscriptions.show');
    Route::post('user-subscriptions/{subscription}/approve', [App\Http\Controllers\Admin\UserSubscriptionsController::class, 'approve'])->name('user-subscriptions.approve');
    Route::post('user-subscriptions/{subscription}/cancel', [App\Http\Controllers\Admin\UserSubscriptionsController::class, 'cancel'])->name('user-subscriptions.cancel');
    Route::post('user-subscriptions/{subscription}/force-cancel', [App\Http\Controllers\Admin\UserSubscriptionsController::class, 'forceCancel'])->name('user-subscriptions.force-cancel');
});