<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SeoController;
use Illuminate\Support\Facades\Route;

// Careers lives on an external site (configurable in Website Settings).
Route::get('/careers', fn () => redirect()->away(setting('careers_url', 'https://jobs.kibokogroup.com')))->name('careers');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots']);
Route::post('/contact-us', [ContactController::class, 'store'])->middleware('throttle:contact')->name('contact.store');
Route::get('/what-we-do/{slug}', [PageController::class, 'service'])->name('services.show');
Route::get('/insights/{slug}', [PageController::class, 'insight'])->name('insights.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'show'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:login');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');
        Route::get('profile', [Admin\ProfileController::class, 'edit'])->name('profile');
        Route::put('profile', [Admin\ProfileController::class, 'update']);

        Route::get('settings/{group}', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings/{group}', [Admin\SettingController::class, 'update'])->name('settings.update');

        Route::get('media/picker', [Admin\MediaController::class, 'picker'])->name('media.picker');
        Route::resource('media', Admin\MediaController::class)->except(['create', 'show']);

        Route::get('enquiries', [Admin\EnquiryController::class, 'index'])->name('enquiries.index');
        Route::get('enquiries/{enquiry}', [Admin\EnquiryController::class, 'show'])->name('enquiries.show');
        Route::post('enquiries/{enquiry}/reply', [Admin\EnquiryController::class, 'reply'])->name('enquiries.reply');
        Route::patch('enquiries/{enquiry}/toggle', [Admin\EnquiryController::class, 'toggle'])->name('enquiries.toggle');
        Route::delete('enquiries/{enquiry}', [Admin\EnquiryController::class, 'destroy'])->name('enquiries.destroy');

        Route::controller(Admin\ResourceController::class)->prefix('r/{module}')->name('r.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{id}/edit', 'edit')->whereNumber('id')->name('edit');
            Route::put('{id}', 'update')->whereNumber('id')->name('update');
            Route::delete('{id}', 'destroy')->whereNumber('id')->name('destroy');
        });
    });
});

Route::get('/{slug?}', [PageController::class, 'show'])->where('slug', '[a-z0-9\-]+')->name('page');

