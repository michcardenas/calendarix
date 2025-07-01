<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controladores generales
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Controladores de administración
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

// Controladores del negocio/empresa
use App\Http\Controllers\negocio\NegocioController;
use App\Http\Controllers\Empresa\DashboardEmpresaController;
use App\Http\Controllers\Empresa\FotoController;
use App\Http\Controllers\Empresa\ServicioEmpresaController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Login tradicional
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Ruta de debug (temporal)
Route::get('/auth-debug', function () {
    return view('auth.login-debug');
})->name('auth.debug');


/*
|--------------------------------------------------------------------------
| RUTAS AUTENTICADAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PERFIL DEL USUARIO
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD GENERAL (Administrador o usuario autenticado)
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | NEGOCIOS DEL USUARIO
    |--------------------------------------------------------------------------
    */
    Route::get('/mis-empresas', [NegocioController::class, 'index'])->name('negocio.index');
    Route::get('/empresa/{id}', [NegocioController::class, 'show'])->name('negocio.show');

});


/*
|--------------------------------------------------------------------------
| RUTAS ADMINISTRATIVAS (panel de admin)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});


/*
|--------------------------------------------------------------------------
| FLUJO DE REGISTRO DE NEGOCIOS
|--------------------------------------------------------------------------
*/

Route::prefix('negocio')->group(function () {
    Route::get('/registro', [NegocioController::class, 'create'])->name('negocio.create');
    Route::post('/registro', [NegocioController::class, 'store'])->name('negocio.store');

    Route::get('/datos', [NegocioController::class, 'datosNegocio'])->name('negocio.datos');
    Route::post('/datos', [NegocioController::class, 'guardarNombre'])->name('negocio.nombre.store');

    Route::get('/categorias', [NegocioController::class, 'categorias'])->name('negocio.categorias');
    Route::post('/categorias', [NegocioController::class, 'guardarCategorias'])->name('negocio.categorias.store');

    Route::get('/equipo', [NegocioController::class, 'equipo'])->name('negocio.equipo');
    Route::post('/equipo', [NegocioController::class, 'guardarEquipo'])->name('negocio.equipo.store');

    Route::get('/ubicacion', [NegocioController::class, 'ubicacion'])->name('negocio.ubicacion');
    Route::post('/ubicacion', [NegocioController::class, 'guardarUbicacion'])->name('negocio.ubicacion.store');

    Route::get('/verificar-direccion', [NegocioController::class, 'verificarDireccion'])->name('negocio.verificacion');
    Route::post('/verificar-direccion', [NegocioController::class, 'guardarVerificacion'])->name('negocio.verificacion.store');

    Route::delete('/negocios/{negocio}', [NegocioController::class, 'destroy'])->name('negocio.destroy');

    Route::get('/empresa/editor/{id}', [App\Http\Controllers\Empresa\EditorEmpresaController::class, 'index'])->name('empresa.editor');
    Route::post('/empresa/{id}/servicios/guardar', [ServicioEmpresaController::class, 'guardar'])->name('empresa.servicios.guardar');

});


/*
|--------------------------------------------------------------------------
| EMPRESAS - DASHBOARD INDIVIDUAL
|--------------------------------------------------------------------------
*/

Route::get('/empresa/dashboard/{id}', [DashboardEmpresaController::class, 'index'])->name('empresa.dashboard');

Route::post('/empresa/{id}/configuracion', [App\Http\Controllers\Empresa\ConfiguracionEmpresaController::class, 'guardarConfiguracion'])
    ->name('empresa.configuracion.guardar');

Route::get('/empresa/{id}/configuracion', [App\Http\Controllers\Empresa\ConfiguracionEmpresaController::class, 'obtenerConfiguracion'])
    ->name('empresa.configuracion.obtener');

Route::get('/empresa/{id}/vista-previa', [App\Http\Controllers\Empresa\ConfiguracionEmpresaController::class, 'vistaPrevia'])
    ->name('empresa.vista-previa');

Route::get('/bloques/{tipo}', [App\Http\Controllers\Empresa\BloquesController::class, 'mostrarBloque'])
    ->name('empresa.bloques.mostrar');

Route::get('/empresa/{id}/servicios', [App\Http\Controllers\Empresa\ServicioEmpresaController::class, 'mostrar'])
    ->name('empresa.servicios.mostrar');

Route::post('/empresa/{id}/servicios', [App\Http\Controllers\Empresa\ServicioEmpresaController::class, 'guardar'])
    ->name('empresa.servicios.guardar');
/*
|--------------------------------------------------------------------------
| INCLUYE RUTAS DE AUTENTICACIÓN DE LARAVEL BREEZE
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
