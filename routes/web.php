<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\ValidationController;
use App\Http\Controllers\Admin\PublicationController as AdminPublicationController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

// --Route Halaman User--
Route::get('/', function () {
    return view('halaman-user.beranda-user');
});

Route::get('/team-user', [TeamController::class, 'index'])->name('teams.frontend.index');

Route::get('/about-user', [AboutController::class, 'userIndex'])->name('about.user');

Route::get('/mitra-user', [AboutController::class, 'partnersIndex'])->name('partners.user');

Route::get('/program-user', [ProgramController::class, 'index'])->name('programs.frontend.index');

Route::get('/project-user', [ProjectController::class, 'index'])->name('projects.frontend.index');

Route::get('/news-user', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/publication-user', [PublicationController::class, 'index'])->name('publications.index');
Route::get('/publications/{publication}', [PublicationController::class, 'show'])->name('publications.show');
Route::get('/publications/{publication}/download', [PublicationController::class, 'download'])->name('publications.download');

Route::get('/biografi-user', function () {
    return view('halaman-user.biografi-user');
});


// --Route Halaman Dosen--
Route::get('/team-dosen', [TeamController::class, 'dosenIndex'])
    ->middleware(\App\Http\Middleware\EnsureUserApproved::class);

Route::get('/about-dosen', [AboutController::class, 'dosenIndex'])
    ->middleware(\App\Http\Middleware\EnsureUserApproved::class)
    ->name('about.dosen');

Route::get('/mitra-dosen', [AboutController::class, 'partnersDosenIndex'])
    ->middleware(\App\Http\Middleware\EnsureUserApproved::class)
    ->name('partners.dosen');

Route::get('/program-dosen', [ProgramController::class, 'dosenIndex'])
    ->middleware(\App\Http\Middleware\EnsureUserApproved::class);

Route::get('/project-dosen', [ProjectController::class, 'dosenIndex'])
    ->middleware(\App\Http\Middleware\EnsureUserApproved::class);

Route::get('/news-dosen', [NewsController::class, 'dosenIndex'])
    ->middleware(\App\Http\Middleware\EnsureUserApproved::class)
    ->name('news.dosen');

Route::get('/profil-dosen', function () {
    return view('halaman-dosen.profil-dosen');
})->middleware(\App\Http\Middleware\EnsureUserApproved::class);

Route::get('/biografi-dosen', function () {
    return view('halaman-dosen.biografi-dosen');
})->middleware(\App\Http\Middleware\EnsureUserApproved::class);


// --Route Halaman Admin (legacy static pages)--
Route::get('/research-team-admin', function () {
    if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'content_creator')) abort(403);
    return view('halaman-admin.research-team-admin');
});

Route::get('/news-admin', function () {
    if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'content_creator')) abort(403);
    return redirect()->route('admin.news.index');
});

Route::get('/program-admin', function () {
    if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'content_creator')) abort(403);
    return redirect()->route('admin.programs.index');
});


// --Authorized--
Route::get('/registrasi', [AuthController::class, 'showRegister'])->name('registrasi');
Route::post('/registrasi', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.masuk');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman role
Route::get('/beranda-admin', function () {
    if (!Auth::check() || Auth::user()->role !== 'admin') abort(403);
    return view('halaman-admin.beranda-admin');
});

Route::get('/beranda-dosen', function () {
    if (!Auth::check() || Auth::user()->role !== 'dosen') abort(403);
    return view('halaman-dosen.beranda-dosen');
})->middleware(\App\Http\Middleware\EnsureUserApproved::class);

Route::get('/dosen/status', function () {
    if (!Auth::check()) return redirect('/login');
    return view('halaman-dosen.status-pending');
})->name('dosen.status');

Route::get('/beranda-creator', function () {
    if (!Auth::check() || Auth::user()->role !== 'content_creator') abort(403);
    return view('halaman-creator.beranda-creator');
});


// --Admin CMS Routes (semua dalam group auth)--
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Validasi Registrasi
    Route::get('/validasi-registrasi', [ValidationController::class, 'index'])->name('validasi.index');
    Route::post('/validasi-registrasi/{id}/approve', [ValidationController::class, 'approve'])->name('validasi.approve');
    Route::post('/validasi-registrasi/{id}/reject', [ValidationController::class, 'reject'])->name('validasi.reject');

    // Publications
    Route::resource('/publications', AdminPublicationController::class)->except(['show']);

    // Projects
    Route::get('/projects', [AdminProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [AdminProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [AdminProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [AdminProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [AdminProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');

    // News
    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

    // Programs
    Route::get('/programs', [AdminProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/create', [AdminProgramController::class, 'create'])->name('programs.create');
    Route::post('/programs', [AdminProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs/{program}/edit', [AdminProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{program}', [AdminProgramController::class, 'update'])->name('programs.update');
    Route::delete('/programs/{program}', [AdminProgramController::class, 'destroy'])->name('programs.destroy');

    // Teams
    Route::get('/teams', [AdminTeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [AdminTeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [AdminTeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}/edit', [AdminTeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{team}', [AdminTeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [AdminTeamController::class, 'destroy'])->name('teams.destroy');

    // Partners / Mitra
    Route::get('/partners', [AdminPartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/create', [AdminPartnerController::class, 'create'])->name('partners.create');
    Route::post('/partners', [AdminPartnerController::class, 'store'])->name('partners.store');
    Route::get('/partners/{partner}/edit', [AdminPartnerController::class, 'edit'])->name('partners.edit');
    Route::put('/partners/{partner}', [AdminPartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partners/{partner}', [AdminPartnerController::class, 'destroy'])->name('partners.destroy');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
});


// // Profile routes (harus login)
// Route::middleware('auth')->group(function () {
//     Route::get('/profil-dosen', [ProfileController::class, 'showProfile'])->name('profile.show');
//     Route::put('/profil-dosen/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
// });
