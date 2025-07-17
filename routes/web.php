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
use App\Http\Controllers\Empresa\EmpresaController;
use App\Http\Controllers\empresa\NegocioConfiguracionController;
use App\Http\Controllers\Empresa\CatalogoController;
use App\Http\Controllers\Empresa\ProductoController;
use App\Http\Controllers\Empresa\AgendaController;


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


// Rutas para el dashboard de empresa
Route::prefix('empresa')->name('empresa.')->group(function () {
    Route::get('/{id}/dashboard', [EmpresaController::class, 'dashboard'])->name('dashboard');
    Route::get('/{id}/configuracion', [EmpresaController::class, 'configuracion'])->name('configuracion');
    Route::get('/{id}/agenda', [EmpresaController::class, 'agenda'])->name('agenda');
    Route::get('/{id}/clientes', [EmpresaController::class, 'clientes'])->name('clientes');
    
    // Rutas para las subsecciones de configuración (para el futuro)
    Route::prefix('{id}/configuracion')->name('configuracion.')->group(function () {
        Route::get('/negocio', [EmpresaController::class, 'configNegocio'])->name('negocio');
        Route::get('/citas', [EmpresaController::class, 'configCitas'])->name('citas');
        Route::get('/ventas', [EmpresaController::class, 'configVentas'])->name('ventas');
        Route::get('/facturacion', [EmpresaController::class, 'configFacturacion'])->name('facturacion');
        Route::get('/equipo', [EmpresaController::class, 'configEquipo'])->name('equipo');
        Route::get('/formularios', [EmpresaController::class, 'configFormularios'])->name('formularios');
        Route::get('/pagos', [EmpresaController::class, 'configPagos'])->name('pagos');
    });
});

// Rutas para la configuración de empresa
Route::get('/empresa/{id}/configuracion/negocio', [EmpresaController::class, 'negocio'])
    ->name('empresa.configuracion.negocio');

Route::post('/empresa/configuracion/negocio/guardar', [NegocioConfiguracionController::class, 'guardar'])
    ->middleware(['auth'])
    ->name('negocio.guardar');

// resources/views/empresa/configuracion/
Route::get('/empresa/configuracion/centros', [NegocioConfiguracionController::class, 'centros'])->name('empresa.configuracion.centros');
Route::get('/empresa/configuracion/procedencia', [NegocioConfiguracionController::class, 'procedencia'])->name('empresa.configuracion.procedencia');
Route::put('/empresa/configuracion/centros/{id}', [NegocioConfiguracionController::class, 'actualizarCentro'])
    ->name('empresa.configuracion.centros.update');
Route::get('/empresa/configuracion/procedencia', [NegocioConfiguracionController::class, 'procedencia'])->name('empresa.configuracion.procedencia');
Route::put('/empresa/configuracion/procedencia/{id}', [NegocioConfiguracionController::class, 'actualizarProcedencia'])->name('empresa.configuracion.procedencia.update');
Route::post('/empresa/configuracion/procedencia', [NegocioConfiguracionController::class, 'actualizarProcedencia'])
    ->name('empresa.configuracion.procedencia.update');
Route::get('/empresa/negocio/catalogo/servicios', [CatalogoController::class, 'menuServicios'])->name('catalogo.servicios');
Route::post('/empresa/servicio/guardar', [CatalogoController::class, 'guardarServicio'])->name('catalogo.servicio.guardar');
Route::post('/empresa/negocio/catalogo/servicios', [CatalogoController::class, 'guardarServicio'])->name('servicios.guardar');
Route::post('/catalogo/servicios/guardar', [CatalogoController::class, 'guardarServicio'])->name('servicios.guardar');
Route::get('/catalogo/servicios/{id}/editar', [CatalogoController::class, 'editarServicio'])->name('servicios.editar');
Route::put('/catalogo/servicios/{id}', [CatalogoController::class, 'actualizarServicio'])->name('servicios.actualizar');
Route::post('/catalogo/servicios/{id}/duplicar', [CatalogoController::class, 'duplicarServicio'])->name('servicios.duplicar');
Route::delete('/catalogo/servicios/{id}', [CatalogoController::class, 'eliminarServicio'])->name('servicios.eliminar');
Route::get('/empresa/servicio/crear', [CatalogoController::class, 'formCrearServicio'])->name('servicios.crear');
Route::post('/empresa/catalogo/categorias/guardar', [CatalogoController::class, 'guardarCategoria'])
    ->name('catalogo.categorias.guardar');
Route::post('/empresa/servicio/guardar', [CatalogoController::class, 'guardarServicio'])->name('servicios.guardar');

//productos
Route::get('/empresa/catalogo/producto/crear', [ProductoController::class, 'create'])->name('producto.crear');
Route::post('/empresa/catalogo/producto', [ProductoController::class, 'store'])->name('producto.store');
Route::post('/empresa/catalogo/producto/guardar', [ProductoController::class, 'guardar'])->name('producto.guardar');
Route::get('/empresa/catalogo/productos', [ProductoController::class, 'panel'])->name('producto.panel');
// Mostrar el formulario de edición
Route::get('/empresa/catalogo/producto/{producto}/editar', [ProductoController::class, 'edit'])->name('producto.editar');
// Guardar cambios del producto
Route::put('/empresa/catalogo/producto/{producto}', [ProductoController::class, 'update'])->name('producto.actualizar');
// Eliminar un producto
Route::delete('/empresa/catalogo/producto/{producto}', [ProductoController::class, 'destroy'])->name('producto.eliminar');
Route::delete('/empresa/productos/imagen/{id}', [ProductoController::class, 'eliminarImagen'])->name('producto.imagen.eliminar');
Route::put('/empresa/catalogo/producto/{producto}/actualizar', [ProductoController::class, 'update'])->name('producto.actualizar');

//agenda
Route::get('/empresa/{id}/agenda', [AgendaController::class, 'index'])->name('empresa.agenda');
// routes/web.php
Route::get('/empresa/{id}/agenda/configurar', [AgendaController::class, 'configurar'])->name('empresa.agenda.configurar');

Route::post('/empresa/{id}/agenda/bloqueados', [AgendaController::class, 'guardarBloqueados'])->name('agenda.guardar_bloqueados');



require __DIR__.'/auth.php';
