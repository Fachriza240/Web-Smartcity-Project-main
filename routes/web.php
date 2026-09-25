<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\HkiController as AdminHkiController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\PublicationController as AdminPublicationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Admin\ValidationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiografiController;
use App\Http\Controllers\DosenKontenController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'halaman-user.beranda-user')->name('home');
Route::get('/about-user', [AboutController::class, 'userIndex'])->name('about.user');
Route::get('/mitra-user', [AboutController::class, 'partnersIndex'])->name('partners.user');
Route::get('/program-user', [ProgramController::class, 'index'])->name('programs.frontend.index');
Route::get('/project-user', [ProjectController::class, 'index'])->name('projects.frontend.index');
Route::get('/news-user', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/publication-user', [PublicationController::class, 'index'])->name('publications.index');
Route::get('/publications/{publication}', [PublicationController::class, 'show'])->name('publications.show');
Route::get('/publications/{publication}/download', [PublicationController::class, 'download'])->name('publications.download');
Route::get('/team-user', [TeamController::class, 'index'])->name('teams.frontend.index');
Route::get('/biografi-user/{user?}', [BiografiController::class, 'user'])->whereNumber('user')->name('biografi.user');
Route::view('/kontak', 'halaman-user.contact-user')->name('contact');
Route::redirect('/contact', '/kontak', 301);
Route::get('/cari', [SearchController::class, 'index'])->name('search');

Route::middleware('guest')->group(function () {
    Route::get('/registrasi', [AuthController::class, 'showRegister'])->name('registrasi');
    Route::post('/registrasi', [AuthController::class, 'register']);
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.masuk');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dosen/status', fn () => view('halaman-dosen.status-pending'))
    ->middleware('auth')
    ->name('dosen.status');

Route::middleware('approved')->group(function () {
    Route::get('/beranda-dosen', fn () => view('halaman-dosen.beranda-dosen'))->middleware('role:dosen')->name('beranda.dosen');
    Route::get('/about-dosen', [AboutController::class, 'dosenIndex'])->name('about.dosen');
    Route::get('/mitra-dosen', [AboutController::class, 'partnersDosenIndex'])->name('partners.dosen');
    Route::get('/program-dosen', [ProgramController::class, 'dosenIndex'])->name('programs.dosen');
    Route::get('/project-dosen', [ProjectController::class, 'dosenIndex'])->name('projects.dosen');
    Route::get('/news-dosen', [NewsController::class, 'dosenIndex'])->name('news.dosen');
    Route::get('/team-dosen', [TeamController::class, 'dosenIndex'])->name('teams.dosen');
    Route::get('/biografi-dosen/{user?}', [BiografiController::class, 'dosen'])->whereNumber('user')->name('biografi.dosen');

    Route::get('/profil-dosen', [ProfileController::class, 'show'])->name('profil.dosen');
    Route::put('/profil-dosen', [ProfileController::class, 'update'])->name('profil.dosen.update');

    Route::controller(DosenKontenController::class)->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/publikasi', 'publikasiIndex')->name('publikasi.index');
        Route::get('/publikasi/create', 'publikasiCreate')->name('publikasi.create');
        Route::post('/publikasi', 'publikasiStore')->name('publikasi.store');
        Route::get('/publikasi/{p}/edit', 'publikasiEdit')->name('publikasi.edit');
        Route::put('/publikasi/{p}', 'publikasiUpdate')->name('publikasi.update');
        Route::delete('/publikasi/{p}', 'publikasiDestroy')->name('publikasi.destroy');

        Route::get('/hki', 'hkiIndex')->name('hki.index');
        Route::get('/hki/create', 'hkiCreate')->name('hki.create');
        Route::post('/hki', 'hkiStore')->name('hki.store');
        Route::get('/hki/{h}/edit', 'hkiEdit')->name('hki.edit');
        Route::put('/hki/{h}', 'hkiUpdate')->name('hki.update');
        Route::delete('/hki/{h}', 'hkiDestroy')->name('hki.destroy');

        Route::get('/notifications/{id}/read', 'markNotificationAsRead')->name('notifications.read');
    });
});

Route::get('/beranda-admin', fn () => view('halaman-admin.beranda-admin'))
    ->middleware('role:admin')
    ->name('beranda.admin');

Route::get('/beranda-creator', fn () => view('halaman-creator.beranda-creator'))
    ->middleware(['approved', 'role:content_creator'])
    ->name('beranda.creator');

Route::middleware('role:admin,content_creator')->group(function () {
    Route::redirect('/research-team-admin', '/admin/teams');
    Route::redirect('/news-admin', '/admin/news');
    Route::redirect('/program-admin', '/admin/programs');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/validasi-registrasi', [ValidationController::class, 'index'])->name('validasi.index');
    Route::post('/validasi-registrasi/{id}/approve', [ValidationController::class, 'approve'])->name('validasi.approve');
    Route::post('/validasi-registrasi/{id}/reject', [ValidationController::class, 'reject'])->name('validasi.reject');

    Route::resource('/publications', AdminPublicationController::class)->except(['show']);

    foreach ([
        'projects' => [AdminProjectController::class, 'project'],
        'news' => [AdminNewsController::class, 'news'],
        'programs' => [AdminProgramController::class, 'program'],
        'teams' => [AdminTeamController::class, 'team'],
        'partners' => [AdminPartnerController::class, 'partner'],
        'hki' => [AdminHkiController::class, 'hki'],
    ] as $uri => [$controller, $param]) {
        Route::get("/{$uri}", [$controller, 'index'])->name("{$uri}.index");
        Route::get("/{$uri}/create", [$controller, 'create'])->name("{$uri}.create");
        Route::post("/{$uri}", [$controller, 'store'])->name("{$uri}.store");
        Route::get("/{$uri}/{{$param}}/edit", [$controller, 'edit'])->name("{$uri}.edit");
        Route::put("/{$uri}/{{$param}}", [$controller, 'update'])->name("{$uri}.update");
        Route::delete("/{$uri}/{{$param}}", [$controller, 'destroy'])->name("{$uri}.destroy");
    }

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});
