<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\negocio\NegocioController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Google Authentication Routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// routes/web.php - Agrega esta ruta temporal
Route::get('/auth-debug', function () {
    return view('auth.login-debug');
})->name('auth.debug');

// Authentication Routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});

// Negocio Routes
Route::get('/negocio/registro', [NegocioController::class, 'create'])->name('negocio.create');
Route::post('/negocio/registro', [NegocioController::class, 'store'])->name('negocio.store');
Route::get('/negocio/datos', [NegocioController::class, 'datosNegocio'])->name('negocio.datos');
Route::post('/negocio/datos', [NegocioController::class, 'guardarNombre'])->name('negocio.nombre.store');
Route::get('/negocio/categorias', [NegocioController::class, 'categorias'])->name('negocio.categorias');
Route::post('/negocio/categorias', [NegocioController::class, 'guardarCategorias'])->name('negocio.categorias.store');
Route::get('/negocio/equipo', [NegocioController::class, 'equipo'])->name('negocio.equipo');
Route::post('/negocio/equipo', [NegocioController::class, 'guardarEquipo'])->name('negocio.equipo.store');
Route::get('/negocio/ubicacion', [NegocioController::class, 'ubicacion'])->name('negocio.ubicacion');
Route::post('/negocio/ubicacion', [NegocioController::class, 'guardarUbicacion'])->name('negocio.ubicacion.store');
Route::get('/negocio/verificar-direccion', [NegocioController::class, 'verificarDireccion'])->name('negocio.verificacion');
Route::post('/negocio/verificar-direccion', [NegocioController::class, 'guardarVerificacion'])->name('negocio.verificacion.store');

// Empresa Routes
Route::get('/empresa/dashboard', function () {
    return view('empresa.dashboard');
})->name('empresa.dashboard');



require __DIR__.'/auth.php';
