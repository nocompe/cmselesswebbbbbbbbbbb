<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VideoCategoryController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MultimediaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Page;
/*
|--------------------------------------------------------------------------
| Rutas Públicas (Frontend)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tutoriales', [PageController::class, 'tutoriales'])->name('tutoriales');
Route::get('/trabaja', [PageController::class, 'trabaja'])->name('trabaja');
Route::post('/trabaja', [PageController::class, 'storeApplication'])->name('trabaja.store');
Route::get('/capacitaciones', [PageController::class, 'capacitaciones'])->name('capacitaciones');

Route::get('/nosotros', [HomeController::class, 'about'])->name('about');
Route::get('/contacto', [HomeController::class, 'contact'])->name('contact');
Route::post('/contacto', [HomeController::class, 'sendContact'])->name('contact.send');

// Centro Multimedia
Route::prefix('multimedia')->name('multimedia.')->group(function () {
    Route::get('/', [MultimediaController::class, 'index'])->name('index');
    Route::get('/categoria/{category:slug}', [MultimediaController::class, 'category'])->name('category');
    Route::get('/{video:slug}', [MultimediaController::class, 'show'])->name('show');
    Route::get('/api/load-more', [MultimediaController::class, 'loadMore'])->name('load-more');
});

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rutas del Panel de Administración (CMS)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Perfil de usuario
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');

    // Gestión de Usuarios
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Gestión de Roles
    Route::resource('roles', RoleController::class)->except(['show']);

    // Gestión de Páginas (CMS)
    Route::resource('pages', PageController::class);
    Route::post('/pages/{page}/sections', [PageController::class, 'storeSection'])->name('pages.sections.store');
    Route::put('/pages/{page}/sections/{section}', [PageController::class, 'updateSection'])->name('pages.sections.update');
    Route::delete('/pages/{page}/sections/{section}', [PageController::class, 'destroySection'])->name('pages.sections.destroy');
    Route::post('/pages/{page}/sections/reorder', [PageController::class, 'reorderSections'])->name('pages.sections.reorder');

    // Gestión de Secciones (edición rápida)
    Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
    Route::get('/sections/{section}/edit', [SectionController::class, 'edit'])->name('sections.edit');
    Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
    Route::post('/sections/{section}/quick-update', [SectionController::class, 'quickUpdate'])->name('sections.quick-update');
    Route::post('/sections/{section}/upload-image', [SectionController::class, 'uploadImage'])->name('sections.upload-image');

    // Gestión de Videos
    Route::resource('videos', VideoController::class);
    Route::post('/videos/{video}/toggle-status', [VideoController::class, 'toggleStatus'])->name('videos.toggle-status');
    Route::post('/videos/reorder', [VideoController::class, 'reorder'])->name('videos.reorder');

    // Categorías de Videos
    Route::resource('video-categories', VideoCategoryController::class);

    // Gestión de Media
    Route::resource('media', MediaController::class)->except(['create', 'edit']);
    Route::post('/media/bulk-delete', [MediaController::class, 'bulkDelete'])->name('media.bulk-delete');
    Route::get('/media-picker', [MediaController::class, 'picker'])->name('media.picker');

    // Sliders
    Route::resource('sliders', SliderController::class);

    // FAQs
    Route::resource('faqs', FaqController::class);
    Route::post('/faqs/reorder', [FaqController::class, 'reorder'])->name('faqs.reorder');

    // Configuración
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::put('/settings/{group}', [SettingController::class, 'updateGroup'])->name('settings.update-group');

    // Logs de Actividad
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
    Route::get('/logs/export', [ActivityLogController::class, 'export'])->name('logs.export');
    Route::get('/logs/{log}', [ActivityLogController::class, 'show'])->name('logs.show');
    Route::get('/users/{user}/activity', [ActivityLogController::class, 'userActivity'])->name('logs.user');
    Route::delete('/logs/clear', [ActivityLogController::class, 'clear'])->name('logs.clear');

    // Gestión de postulaciones (Trabaja con nosotros)
    Route::get('/applications', [AdminController::class, 'applications'])->name('applications');
    Route::patch('/applications/{id}/status', [AdminController::class, 'updateStatus'])->name('applications.update-status');
    Route::get('/applications/{id}/cv', [AdminController::class, 'downloadCV'])->name('applications.download-cv');
    Route::delete('/applications/{id}', [AdminController::class, 'deleteApplication'])->name('applications.delete');

});

