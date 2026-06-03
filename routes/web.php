<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

// --Route Halaman User--
Route::get('/', function () {
    return view('halaman-user.beranda-user');
});

Route::get('/team-user', function () {
    return view('halaman-user.team-user');
});

Route::get('/about-user', function () {
    return view('halaman-user.about-user');
});

Route::get('/program-user', function () {
    return view('halaman-user.program-user');
});

Route::get('/project-user', function () {
    return view('halaman-user.project-user');
});

Route::get('/news-user', function () {
    return view('halaman-user.news-user');
});

Route::get('/biografi-user', function () {
    return view('halaman-user.biografi-user');
});


// --Route Halaman Dosen--
Route::get('/team-dosen', function () {
    return view('halaman-dosen.team-dosen');
});

Route::get('/about-dosen', function () {
    return view('halaman-dosen.about-dosen');
});

Route::get('/program-dosen', function () {
    return view('halaman-dosen.program-dosen');
});

Route::get('/project-dosen', function () {
    return view('halaman-dosen.project-dosen');
});

Route::get('/news-dosen', function () {
    return view('halaman-dosen.news-dosen');
});

Route::get('/profil-dosen', function () {
    return view('halaman-dosen.profil-dosen');
});

Route::get('/biografi-dosen', function () {
    return view('halaman-dosen.biografi-dosen');
});


// --Route Halaman Admin--
Route::get('/research-team-admin', function () {
    return view('halaman-admin.research-team-admin');
});

Route::get('/project-admin', function () {
    return view('halaman-admin.project-admin');
});

Route::get('/program-admin', function () {
    return view('halaman-admin.program-admin');
});

Route::get('/news-admin', function () {
    return view('halaman-admin.news-admin');
});


// --Authorized--
// Registrasi
Route::get('/registrasi',[AuthController::class,'showRegister']);
Route::post('/registrasi',[AuthController::class,'register']);

// Login & Logout
Route::get('/login',[AuthController::class,'showLogin'])->name('login');
Route::post('/login',[AuthController::class,'login'])->name('login.masuk');
Route::post('/logout',[AuthController::class,'logout'])->name('logout');

// Halaman role
Route::get('/beranda-admin', function(){
    if(!Auth::check() || Auth::user()->role!=='admin') abort(403);
    return view('halaman-admin.beranda-admin');
});

Route::get('/beranda-dosen', function(){
    if(!Auth::check() || Auth::user()->role!=='dosen') abort(403);
    return view('halaman-dosen.beranda-dosen');
});


// // Profile routes (harus login)
// Route::middleware('auth')->group(function () {
//     Route::get('/profil-dosen', [ProfileController::class, 'showProfile'])->name('profile.show');
//     Route::put('/profil-dosen/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
// });