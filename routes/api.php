<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SystemInfoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('/validate-token', function (Request $request) {
    $token = str_replace('Bearer ', '', $request->header('Authorization'));
    return response()->json([
        'valid' => \App\Models\User::where('api_token', $token)->exists()
    ]);
})->middleware('api');
Route::post('/system-info', [SystemInfoController::class, 'store']);
