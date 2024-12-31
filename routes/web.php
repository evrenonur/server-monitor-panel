<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Admin\ServerUsageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServerSshController;
use App\Http\Controllers\Admin\ServerUpdateController;

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
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Server routes
    Route::resource('servers', ServerController::class);

    // User routes
    Route::resource('users', UserController::class);

    // Server SSH route
    Route::get('servers/{server}/ssh', [ServerSshController::class, 'show'])->name('servers.ssh');
    // Server usage routes
    Route::get('servers/{server}/usage', [ServerUsageController::class, 'show'])->name('servers.usage');
    Route::get('servers/{server}/usage/data', [ServerUsageController::class, 'data'])->name('servers.usage.data');

    // Docker route
    Route::get('/servers/{server}/docker', [ServerController::class, 'docker'])->name('servers.docker');
});

Route::get('/admin/servers/{server}/test', [App\Http\Controllers\Admin\ServerController::class, 'test'])->name('admin.servers.test');
