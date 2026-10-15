<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ViewerInformationController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\InformationController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttachmentController;
use Illuminate\Support\Facades\Route;

// Redirect welcome page to login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('home') : redirect()->route('login');
});

// Authenticated Viewer & Admin Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Viewer Dashboard / Home
    Route::get('/dashboard', function () {
        if (auth()->user()->hasAnyRole(['Super Admin', 'Admin Kesekretariatan'])) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('home');
    })->name('dashboard');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/info/{slug}', [ViewerInformationController::class, 'show'])->name('info.show');
    Route::get('/info/{slug}/version', [ViewerInformationController::class, 'fetchVersion'])->name('info.version');
    
    // Bookmarks
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/toggle/{information_id}', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');

    // Secure PDF streaming
    Route::get('/attachments/stream/{id}', [AttachmentController::class, 'stream'])->name('attachments.stream');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Panel (Accessible by Super Admin and Admin Kesekretariatan)
    Route::middleware(['role:Super Admin|Admin Kesekretariatan'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Categories CRUD
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Informations CRUD
        Route::resource('informations', InformationController::class)->except(['show']);
        
        // Information Versions CRUD
        Route::get('informations/{information}/versions', [InformationController::class, 'versions'])->name('informations.versions');
        Route::get('informations/{information}/versions/create', [InformationController::class, 'createVersion'])->name('informations.versions.create');
        Route::post('informations/{information}/versions', [InformationController::class, 'storeVersion'])->name('informations.versions.store');
        Route::get('informations/{information}/versions/{version}/edit', [InformationController::class, 'editVersion'])->name('informations.versions.edit');
        Route::put('informations/{information}/versions/{version}', [InformationController::class, 'updateVersion'])->name('informations.versions.update');
        Route::delete('informations/{information}/versions/{version}', [InformationController::class, 'destroyVersion'])->name('informations.versions.destroy');
    });

    // Super Admin Only Panel (Users, Banners, Settings, Logs, DB Backup/Restore)
    Route::middleware(['role:Super Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('users/{user}/access', [UserController::class, 'manageAccess'])->name('users.access');
        Route::post('users/{user}/access', [UserController::class, 'updateAccess'])->name('users.access.update');

        Route::resource('groups', GroupController::class)->except(['show']);
        Route::get('groups/{group}/access', [GroupController::class, 'manageAccess'])->name('groups.access');
        Route::post('groups/{group}/access', [GroupController::class, 'updateAccess'])->name('groups.access.update');

        Route::resource('banners', BannerController::class)->except(['show']);
        
        // System Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

        // Audit Trail Logs
        Route::get('/logs/activity', [LogController::class, 'activityLogs'])->name('logs.activity');
        Route::get('/logs/login', [LogController::class, 'loginLogs'])->name('logs.login');
        
        // Database Backup/Restore
        Route::get('/logs/database', [LogController::class, 'dbBackupRestore'])->name('logs.database');
        Route::post('/logs/database/backup', [LogController::class, 'backup'])->name('logs.backup');
        Route::post('/logs/database/restore', [LogController::class, 'restore'])->name('logs.restore');
    });
});

require __DIR__.'/auth.php';
