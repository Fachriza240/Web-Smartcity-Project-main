<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
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
use App\Http\Controllers\SearchController;

Route::get('/', function () {
    return view('halaman-user.beranda-user');
});

Route::get('/team-user', [TeamController::class, 'index'])->name('teams.frontend.index');

Route::get('/about-user', [AboutController::class, 'userIndex'])->name('about.user');

Route::get('/mitra-user', [AboutController::class, 'partnersIndex'])->name('partners.user');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');

Route::get('/program-user', [ProgramController::class, 'index'])->name('programs.frontend.index');

Route::get('/project-user', [ProjectController::class, 'index'])->name('projects.frontend.index');
Route::get('/project/{project}/document', [ProjectController::class, 'document'])->name('project.document');

Route::get('/search', [SearchController::class, 'index'])->name('search.index');

Route::get('/news-user', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/publication-user', [PublicationController::class, 'index'])->name('publications.index');
Route::get('/publications/{publication}', [PublicationController::class, 'show'])->name('publications.show');
Route::get('/publications/{publication}/download', [PublicationController::class, 'download'])->name('publications.download');

Route::get('/biografi-user', function () {
    return view('halaman-user.biografi-user');
});


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

Route::get('/profil-dosen', [App\Http\Controllers\ProfileController::class, 'show'])
    ->middleware(\App\Http\Middleware\EnsureUserApproved::class)
    ->name('profil.dosen');

Route::put('/profil-dosen', [App\Http\Controllers\ProfileController::class, 'update'])
    ->middleware(\App\Http\Middleware\EnsureUserApproved::class)
    ->name('profil.dosen.update');

Route::middleware(\App\Http\Middleware\EnsureUserApproved::class)->group(function () {

    Route::get('/dosen/publikasi',         [App\Http\Controllers\DosenKontenController::class, 'publikasiIndex'])->name('dosen.publikasi.index');
    Route::get('/dosen/publikasi/create',  [App\Http\Controllers\DosenKontenController::class, 'publikasiCreate'])->name('dosen.publikasi.create');
    Route::post('/dosen/publikasi',        [App\Http\Controllers\DosenKontenController::class, 'publikasiStore'])->name('dosen.publikasi.store');
    Route::get('/dosen/publikasi/{p}/edit',[App\Http\Controllers\DosenKontenController::class, 'publikasiEdit'])->name('dosen.publikasi.edit');
    Route::get('/dosen/publikasi/{p}/file',[App\Http\Controllers\DosenKontenController::class, 'publikasiFile'])->name('dosen.publikasi.file');
    Route::put('/dosen/publikasi/{p}',     [App\Http\Controllers\DosenKontenController::class, 'publikasiUpdate'])->name('dosen.publikasi.update');
    Route::delete('/dosen/publikasi/{p}',  [App\Http\Controllers\DosenKontenController::class, 'publikasiDestroy'])->name('dosen.publikasi.destroy');

    Route::get('/dosen/hki',               [App\Http\Controllers\DosenKontenController::class, 'hkiIndex'])->name('dosen.hki.index');
    Route::get('/dosen/hki/create',        [App\Http\Controllers\DosenKontenController::class, 'hkiCreate'])->name('dosen.hki.create');
    Route::post('/dosen/hki',              [App\Http\Controllers\DosenKontenController::class, 'hkiStore'])->name('dosen.hki.store');
    Route::get('/dosen/hki/{h}/edit',      [App\Http\Controllers\DosenKontenController::class, 'hkiEdit'])->name('dosen.hki.edit');
    Route::put('/dosen/hki/{h}',           [App\Http\Controllers\DosenKontenController::class, 'hkiUpdate'])->name('dosen.hki.update');
    Route::delete('/dosen/hki/{h}',        [App\Http\Controllers\DosenKontenController::class, 'hkiDestroy'])->name('dosen.hki.destroy');

    Route::get('/dosen/notifications/{id}/read', [App\Http\Controllers\DosenKontenController::class, 'markNotificationAsRead'])->name('dosen.notifications.read');
});

Route::get('/biografi-dosen', function () {
    return view('halaman-dosen.biografi-dosen');
})->middleware(\App\Http\Middleware\EnsureUserApproved::class);


Route::middleware('role:admin,content_creator')->group(function () {
    Route::get('/research-team-admin', function () {
        return view('halaman-admin.research-team-admin');
    });

    Route::get('/news-admin', function () {
        return redirect()->route('admin.news.index');
    });

    Route::get('/program-admin', function () {
        return redirect()->route('admin.programs.index');
    });
});


Route::get('/registrasi', [AuthController::class, 'showRegister'])->name('registrasi');
Route::post('/registrasi', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.masuk');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/beranda-admin', function () {
    return view('halaman-admin.beranda-admin');
})->middleware('role:admin');

Route::get('/beranda-dosen', function () {
    return view('halaman-dosen.beranda-dosen');
})->middleware('role:dosen');

Route::get('/dosen/status', function () {
    return view('halaman-dosen.status-pending');
})->name('dosen.status')->middleware('auth');

Route::get('/beranda-creator', function () {
    return view('halaman-creator.beranda-creator');
})->middleware('role:content_creator');


Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('role:admin')->group(function () {
        Route::get('/validasi-registrasi', [ValidationController::class, 'index'])->name('validasi.index');
        Route::post('/validasi-registrasi/{id}/approve', [ValidationController::class, 'approve'])->name('validasi.approve');
        Route::post('/validasi-registrasi/{id}/reject', [ValidationController::class, 'reject'])->name('validasi.reject');

        Route::get('/hki', [\App\Http\Controllers\Admin\HkiController::class, 'index'])->name('hki.index');
        Route::get('/hki/create', [\App\Http\Controllers\Admin\HkiController::class, 'create'])->name('hki.create');
        Route::post('/hki', [\App\Http\Controllers\Admin\HkiController::class, 'store'])->name('hki.store');
        Route::get('/hki/{hki}/edit', [\App\Http\Controllers\Admin\HkiController::class, 'edit'])->name('hki.edit');
        Route::put('/hki/{hki}', [\App\Http\Controllers\Admin\HkiController::class, 'update'])->name('hki.update');
        Route::delete('/hki/{hki}', [\App\Http\Controllers\Admin\HkiController::class, 'destroy'])->name('hki.destroy');
    });

    Route::middleware('role:admin,content_creator')->group(function () {

    Route::resource('/publications', AdminPublicationController::class)->except(['show']);
    Route::get('/publications/{publication}/file', [AdminPublicationController::class, 'file'])->name('publications.file');

    Route::get('/projects', [AdminProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [AdminProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [AdminProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [AdminProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [AdminProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');

    Route::get('/news', [AdminNewsController::class, 'index'])->name('news.index');
    Route::get('/news/create', [AdminNewsController::class, 'create'])->name('news.create');
    Route::post('/news', [AdminNewsController::class, 'store'])->name('news.store');
    Route::get('/news/{news}/edit', [AdminNewsController::class, 'edit'])->name('news.edit');
    Route::put('/news/{news}', [AdminNewsController::class, 'update'])->name('news.update');
    Route::delete('/news/{news}', [AdminNewsController::class, 'destroy'])->name('news.destroy');

    Route::get('/programs', [AdminProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/create', [AdminProgramController::class, 'create'])->name('programs.create');
    Route::post('/programs', [AdminProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs/{program}/edit', [AdminProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{program}', [AdminProgramController::class, 'update'])->name('programs.update');
    Route::delete('/programs/{program}', [AdminProgramController::class, 'destroy'])->name('programs.destroy');

    Route::get('/teams', [AdminTeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [AdminTeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [AdminTeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}/edit', [AdminTeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{team}', [AdminTeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{team}', [AdminTeamController::class, 'destroy'])->name('teams.destroy');

    Route::get('/partners', [AdminPartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/create', [AdminPartnerController::class, 'create'])->name('partners.create');
    Route::post('/partners', [AdminPartnerController::class, 'store'])->name('partners.store');
    Route::get('/partners/{partner}/edit', [AdminPartnerController::class, 'edit'])->name('partners.edit');
    Route::put('/partners/{partner}', [AdminPartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partners/{partner}', [AdminPartnerController::class, 'destroy'])->name('partners.destroy');

    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');

    }); 
});


