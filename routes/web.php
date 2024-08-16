<?php

// routes/web.php

use App\Http\Controllers\api\ApiController;
use App\Http\Controllers\api\ApiControllerAdmin;
use Illuminate\Support\Facades\Route;

// Halaman login
Route::get('/login', function () {
    return view('login');
});

// Proses login admin
Route::post('/loginAdmin', [ApiController::class, 'adminLogin']);

// Halaman home (Hanya bisa diakses jika sudah login)
Route::get('/home', function () {
    // Pastikan user sudah login
    if (!session('user')) {
        return redirect('/login');
    }

    return view('home', ['user' => session('user')]);
})->middleware('auth:sanctum');

Route::post('/logout', function () {
    auth()->user()->tokens()->delete();
    return redirect('/login');
})->middleware('auth');

Route::get('/admin/validasi-users', [ApiControllerAdmin::class, 'getAllValidasiUsers']);
Route::post('/admin/registerValidasi', [ApiController::class, 'validasi']);
Route::post('/admin/deleteValidasi',[ApiControllerAdmin::class,"deleteValidasi"]);
Route::get('/admin/showuser',[ApiController::class,"showuser"]);
Route::post('/admin/deleteUser',[ApiController::class,"deleteUser"]);
