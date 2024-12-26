<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Admin\ServerUsageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

// Auth routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Server routes
    Route::resource('servers', ServerController::class)->names([
        'index' => 'admin.servers.index',
        'create' => 'admin.servers.create',
        'store' => 'admin.servers.store',
        'show' => 'admin.servers.show',
        'edit' => 'admin.servers.edit',
        'update' => 'admin.servers.update',
        'destroy' => 'admin.servers.destroy',
    ]);
});

Route::get('admin/servers/{server}/usage', [ServerUsageController::class, 'show'])->name('admin.servers.usage');
Route::get('admin/servers/{server}/usage/data', [ServerUsageController::class, 'data'])->name('admin.servers.usage.data');
