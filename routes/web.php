<?php

use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogCategoryManagementController;
use App\Http\Controllers\Admin\BlogManagementController;
use App\Http\Controllers\Admin\BookingManagementController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\CmsManagementController;
use App\Http\Controllers\Admin\InquiryManagementController;
use App\Http\Controllers\Admin\FaqManagementController;
use App\Http\Controllers\Admin\LocationManagementController;
use App\Http\Controllers\Admin\ServiceManagementController;
use App\Http\Controllers\Admin\ServiceProviderManagementController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ClientTestController;
use App\Http\Controllers\SocialLoginController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::prefix('admin')->name('admin.')->group(function() {
    Route::redirect('/', 'admin/login');
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', [AuthController::class, 'index'] )->name('login');
        Route::post('/login', [AuthController::class, 'login'] )->name('login-process');
        Route::get("/forgot-password", [AuthController::class, 'forgotPasswordIndex'])->name('forgot-password.index');
        Route::get("/otp-validate", [AuthController::class, 'forgotPasswordStore'])->name('forgot-password.store');
        Route::match(['get', 'post'], '/forgot-password', [AuthController::class, 'forgotPasswordIndex'])->name('forgot-password.index');
        Route::match(['get', 'post'], "/otp-validate", [AuthController::class, 'forgotPasswordStore'])->name('forgot-password.otp-validation');
        Route::match(['get', 'post'], "/reset-password", [AuthController::class, 'resetPassword'])->name('forgot-password.reset-password');

    });

    Route::middleware(['is-super-admin'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'] )->name('logout');

        Route::get('/dashboard', [AdminManagementController::class, 'dashboard'])->name('dashboard');
        Route::match(['get', 'post'],'/profile', [AdminManagementController::class, 'adminProfile'])->name('admin-profile');
        Route::post('admin-change-password', [AdminManagementController::class, 'adminChangePassword'])->name('change-password');

        Route::match(['get', 'post'],'/users/list', [UserManagementController::class, 'index'])->name('users.list');
        Route::get('users/{id}/show', [UserManagementController::class, 'show'])->name('users.show');
        Route::get('users/create',[UserManagementController::class, 'create'])->name('users.create');
        Route::post('users/create',[UserManagementController::class, 'store'])->name('users.store');
        Route::get('users/{id}/edit',[UserManagementController::class, 'edit'])->name('users.edit');
        Route::post('users/{id}/update',[UserManagementController::class, 'update'])->name('users.update');
        Route::post('users/{id}/delete',[UserManagementController::class, 'delete'])->name('users.delete');
        Route::post('users/{id}/change-status',[UserManagementController::class, 'changeStatus'])->name('users.change-status');

        Route::match(['get', 'post'],'/service-providers/list', [ServiceProviderManagementController::class, 'index'])->name('service-providers.list');
        Route::get('service-providers/{id}/show', [ServiceProviderManagementController::class, 'show'])->name('service-providers.show');
        Route::get('service-providers/create',[ServiceProviderManagementController::class, 'create'])->name('service-providers.create');
        Route::post('service-providers/create',[ServiceProviderManagementController::class, 'store'])->name('service-providers.store');
        Route::get('service-providers/{id}/edit',[ServiceProviderManagementController::class, 'edit'])->name('service-providers.edit');
        Route::post('service-providers/{id}/update',[ServiceProviderManagementController::class, 'update'])->name('service-providers.update');
        Route::post('service-providers/{id}/delete',[ServiceProviderManagementController::class, 'delete'])->name('service-providers.delete');
        Route::post('service-providers/{id}/change-status',[ServiceProviderManagementController::class, 'changeStatus'])->name('service-providers.change-status');

        Route::match(['get', 'post'],'/cms/list', [CmsManagementController::class, 'index'])->name('cms.list');
        Route::get('/cms/create',[CmsManagementController::class, 'create'])->name('cms.create');
        Route::post('/cms/store',[CmsManagementController::class, 'store'])->name('cms.store');
        Route::get('/cms/{id}/edit', [CmsManagementController::class, 'edit'])->name('cms.edit');
        Route::post('/cms/{id}/update', [CmsManagementController::class, 'update'])->name('cms.update');
        Route::post('/cms/{id}/delete',[CmsManagementController::class, 'remove'])->name('cms.remove');

        Route::get('/faq/list', [FaqManagementController::class, 'index'])->name('faq.list');
        Route::post('/faq/getFaqs', [FaqManagementController::class, 'getFaqs'])->name('faq.getFaqs');
        Route::get('/faq/create', [FaqManagementController::class, 'create'])->name('faq.create');
        Route::post('/faq/store', [FaqManagementController::class, 'store'])->name('faq.store');
        Route::get('/faq/{id}/edit', [FaqManagementController::class, 'edit'])->name('faq.edit');
        Route::patch('/faq/{id}/update', [FaqManagementController::class, 'update'])->name('faq.update');
        Route::delete('/faq/{id}/delete', [FaqManagementController::class, 'remove'])->name('faq.delete');

        Route::get('/blog/list', [BlogManagementController::class, 'index'])->name('blog.list');
        Route::get('/blog-category/get-categories', [BlogManagementController::class, 'getBlogCategories'])->name('getBlogCategories.list');
        Route::post('/blog/list', [BlogManagementController::class, 'getBlogs'])->name('blog.getBlogs');
        Route::get('/blog/create', [BlogManagementController::class, 'create'])->name('blog.create');
        Route::post('/blog/store', [BlogManagementController::class, 'store'])->name('blog.store');
        Route::get('/blog/{id}/edit', [BlogManagementController::class, 'edit'])->name('blog.edit');
        Route::post('/blog/{id}/update', [BlogManagementController::class, 'update'])->name('blog.update');
        Route::delete('/blog/{id}/delete', [BlogManagementController::class, 'remove'])->name('blog.delete');
        Route::post('blog/{id}/change-status',[BlogManagementController::class, 'changeStatus'])->name('blog.change-status');

        Route::match(['get', 'post'],'/blog-category/list', [BlogCategoryManagementController::class, 'index'])->name('blog-category.list');
        Route::get('/blog-category/create', [BlogCategoryManagementController::class, 'create'])->name('blog-category.create');
        Route::post('/blog-category/store', [BlogCategoryManagementController::class, 'store'])->name('blog-category.store');
        Route::get('/blog-category/{id}/edit', [BlogCategoryManagementController::class, 'edit'])->name('blog-category.edit');
        Route::post('/blog-category/{id}/update', [BlogCategoryManagementController::class, 'update'])->name('blog-category.update');
        Route::delete('/blog-category/{id}/delete', [BlogCategoryManagementController::class, 'remove'])->name('blog-category.delete');
        Route::post('blog-category/{id}/change-status',[BlogCategoryManagementController::class, 'changeStatus'])->name('blog-category.change-status');


        Route::match(['get', 'post'],'/service/list', [ServiceManagementController::class, 'index'])->name('service.list');
        Route::get('/service/create', [ServiceManagementController::class, 'create'])->name('service.create');
        Route::get('/service/categoryList', [ServiceManagementController::class, 'getCategoryList'])->name('service.categoryList');
        Route::post('/service/store', [ServiceManagementController::class, 'store'])->name('service.store');
        Route::get('/service/{id}/edit', [ServiceManagementController::class, 'edit'])->name('service.edit');
        Route::post('/service/{id}/update', [ServiceManagementController::class, 'update'])->name('service.update');
        Route::post('/service/{id}/delete', [ServiceManagementController::class, 'remove'])->name('service.delete');

        Route::match(['get', 'post'],'/category/list', [CategoryManagementController::class, 'index'])->name('category.list');
        Route::get('/category/create', [CategoryManagementController::class, 'create'])->name('category.create');
        Route::post('/category/store', [CategoryManagementController::class, 'store'])->name('category.store');
        Route::get('/category/{id}/edit', [CategoryManagementController::class, 'edit'])->name('category.edit');
        Route::post('/category/{id}/update', [CategoryManagementController::class, 'update'])->name('category.update');
        Route::post('/category/{id}/delete', [CategoryManagementController::class, 'remove'])->name('category.delete');

        Route::match(['get','post'], '/location/list', [LocationManagementController::class, 'index'])->name('location.list');
        Route::get('/location/create', [LocationManagementController::class, 'create'])->name('location.create');
        Route::post('/location/store', [LocationManagementController::class, 'store'])->name('location.store');
        Route::get('/location/{id}/edit', [LocationManagementController::class, 'edit'])->name('location.edit');
        Route::post('/location/{id}/delete', [LocationManagementController::class, 'remove'])->name('location.delete');
        Route::match(['get', 'post'], '/booking/list', [BookingManagementController::class, 'index'])->name('booking.list');
        
        Route::match(['get', 'post'], '/inquiry/list', [InquiryManagementController::class, 'index'])->name('inquiry.list');
        Route::get('inquiry/{id}/show', [InquiryManagementController::class, 'show'])->name('inquiry.show');
        Route::post('/inquiry/{id}/reply', [InquiryManagementController::class, 'reply'])->name('inquiry.reply');

        Route::get('/site-setting', [SiteSettingController::class, 'index'])->name('site-setting.index');
        Route::post('/site-setting/update', [SiteSettingController::class, 'update'])->name('site-setting.update');
    });
});


Route::name('frontend.')->group(function() {
    Route::middleware(['guest'])->group(function () {
        Route::get('/', fn () => Inertia::render('Frontend/Home'))->name('home');
        Route::match(['get', 'post'], '/contact-us', \App\Http\Controllers\Frontend\ContactUsController::class)->name('contact');
        Route::resource('blog', \App\Http\Controllers\Frontend\BlogController::class)->only(['index', 'show']);
        Route::get('/login', [\App\Http\Controllers\Frontend\AuthController::class, 'index'])->name('login');
        Route::get('/register', fn () => Inertia::render('Frontend/Register'));
        Route::get('/forgot-password', fn () => Inertia::render('Frontend/ForgotPassword'));
        Route::get('/reset-password', fn () => Inertia::render('Frontend/ResetPassword'))->name('password.reset');
        Route::get('/verify-email', fn () => Inertia::render('Frontend/VerifyMail'))->name('mail.verify');
        Route::post('/login', [\App\Http\Controllers\Frontend\AuthController::class, 'login'])->name('login');
        Route::post('/register', [\App\Http\Controllers\Frontend\AuthController::class, 'registration'])->name('registration');
        Route::post('/forgot-password', [\App\Http\Controllers\Frontend\AuthController::class, 'forgotPassword']);
        Route::post('/verify-email', [\App\Http\Controllers\Frontend\AuthController::class, 'verifyMail']);
        Route::post('/reset-password', [\App\Http\Controllers\Frontend\AuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');
        Route::post('/resend-otp', [\App\Http\Controllers\Frontend\AuthController::class, 'resendOtpCode'])->name('otp.resend');
    });
    
    Route::middleware(['auth'])->group(function () {
        Route::middleware(['is-customer'])->group(function () {
            Route::get('/client-dashboard', fn () => Inertia::render('Frontend/ClientDashboard'))->name('client.dashboard');
            Route::get('/client-profile', [\App\Http\Controllers\Frontend\Customer\ProfileController::class, 'index'])->name('client.profile');
            Route::post('/client-profile', [\App\Http\Controllers\Frontend\Customer\ProfileController::class, 'store'])->name('client.profile.store');
        });

        Route::middleware(['is-provider'])->group(function () {
            Route::get('/provider-dashboard', fn () => Inertia::render('Frontend/ProviderDashboard'))->name('provider.dashboard');
            Route::get('/provider-profile', [\App\Http\Controllers\Frontend\Provider\ProfileController::class, 'index'])->name('provider.profile');
            Route::post('/provider-profile', [\App\Http\Controllers\Frontend\Provider\ProfileController::class, 'index'])->name('provider.profile.store');
        });

        Route::post('/change-password', [\App\Http\Controllers\Frontend\AuthController::class, 'changePassword']);
        Route::post('/logout', [\App\Http\Controllers\Frontend\AuthController::class, 'logout'])->name('logout');
    });
    Route::get('/site-settings', \App\Http\Controllers\Frontend\SettingsController::class)->name('site.settings');
});
