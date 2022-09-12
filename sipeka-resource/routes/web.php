<?php

use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DataMaster\MenuController;
use App\Http\Controllers\DataMaster\SettingController;
use App\Http\Controllers\DataMaster\UnorController;
use App\Http\Controllers\DataMaster\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PresenceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('public.welcome');
})->name('home');

// absensi
Route::get('/presence/{id}', [PresenceController::class, 'input'])->name('presence.index');
Route::post('/presence/{id}', [PresenceController::class, 'store'])->name('presence.store');
Route::get('/presence/{id}/list', [PresenceController::class, 'list'])->name('presence.list');
Route::get('/presence/{id}/data', [PresenceController::class, 'showDatatable'])->name('presence.data');
Route::post('/password/{id}/{type}', [PresenceController::class, 'checkPassword'])->name('password.check');
Route::get('/event/public/list', [EventController::class, 'showDatatablePublic'])->name('event.list_public');

Route::middleware(['auth', 'user.menu'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.index');

    Route::prefix('master')->group(function () {
        // Route Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/data', [UserController::class, 'showDatatable'])->name('users.datatable');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}/edit', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.delete');

        // Route Menus
        Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
        Route::get('/menus/data', [MenuController::class, 'showDatatable'])->name('menus.datatable');
        Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
        Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
        Route::get('/menus/{id}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menus/{id}/edit', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.delete');

        // Route Setting
        Route::get('/setting', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/setting/sync', [SettingController::class, 'sync'])->name('settings.sync');
        Route::put('/setting', [SettingController::class, 'store'])->name('settings.store');

        Route::get('/roles', function () {
            return view('dashboard');
        })->name('roles.index');

        Route::get('/roles/create', function () {
            return view('dashboard');
        })->name('roles.create');

        Route::put('/roles/{id}', function () {
            return view('dashboard');
        })->name('roles.update');

        Route::delete('/roles/{id}', function () {
            return view('dashboard');
        })->name('roles.delete');


        Route::get('/permissions', function () {
            return view('dashboard');
        })->name('permissions.index');

        Route::get('/permissions/create', function () {
            return view('dashboard');
        })->name('permissions.create');

        Route::put('/permissions/{id}', function () {
            return view('dashboard');
        })->name('permissions.update');

        Route::delete('/permissions/{id}', function () {
            return view('dashboard');
        })->name('permissions.delete');


        // Unit Organisasi
        Route::get('/unor', [UnorController::class, 'index'])->name('unor.index');
        Route::get('/unor/create', [UnorController::class, 'create'])->name('unor.create');
        Route::post('/unor', [UnorController::class, 'store'])->name('unor.store');
        Route::get('/unor/data', [UnorController::class, 'showDatatable'])->name('unor.datatable');
        Route::get('/unor/{id}/edit', [UnorController::class, 'edit'])->name('unor.edit');
        Route::put('/unor/{id}/edit', [UnorController::class, 'update'])->name('unor.update');
        Route::delete('/unor/{id}', [UnorController::class, 'destroy'])->name('unor.delete');
    });


    // Manajemen Kegiatan
    Route::get('/events', [EventController::class, 'index'])->name('event.index');
    Route::post('/events', [EventController::class, 'store'])->name('event.store');
    Route::get('/events/data', [EventController::class, 'showDatatable'])->name('event.datatable');
    Route::get('/events/create', [EventController::class, 'create'])->name('event.create');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/events/{id}/edit', [EventController::class, 'update'])->name('event.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('event.delete');

    Route::get('/presence/{id}/print', [PresenceController::class, 'print'])->name('presence.print');

    Route::prefix('certificate')->group(function () {
        Route::get('/{id}/print', [CertificateController::class, 'print_certificate'])->name('print_certificate.index');
        Route::get('/{id}', [CertificateController::class, 'show_certificate'])->name('certificate.show');
    });
});

require __DIR__ . '/auth.php';
