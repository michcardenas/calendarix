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
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SuscripcionAdminController;
use App\Http\Controllers\Admin\PageEditorController;

// Controladores del negocio/empresa
use App\Http\Controllers\negocio\NegocioController;
use App\Http\Controllers\Empresa\DashboardEmpresaController;
use App\Http\Controllers\Empresa\GaleriaController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\Empresa\ServicioEmpresaController;
use App\Http\Controllers\Empresa\EmpresaController;
use App\Http\Controllers\Empresa\NegocioConfiguracionController;
use App\Http\Controllers\Empresa\CatalogoController;
use App\Http\Controllers\Empresa\AgendaController;
use App\Http\Controllers\SuscripcionController;

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $plans = \App\Models\Plan::where('is_active', true)->orderBy('sort_order')->get();
    $negociosDestacados = \App\Models\Negocio::withCount('resenas')
        ->withAvg('resenas', 'rating')
        ->latest()
        ->take(8)
        ->get();

    $hero       = \App\Models\SiteContent::get('home_hero');
    $businesses = \App\Models\SiteContent::get('home_businesses');
    $pricing    = \App\Models\SiteContent::get('home_pricing');
    $features   = \App\Models\SiteContent::get('home_features');
    $cta        = \App\Models\SiteContent::get('home_cta');

    return view('welcome', compact('plans', 'negociosDestacados', 'hero', 'businesses', 'pricing', 'features', 'cta'));
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

    Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard-cliente', [DashboardController::class, 'cliente'])
        ->name('client.dashboard-client');

    // Endpoint de debug para verificar citas (solo autenticados)
    Route::get('/dashboard-cliente/debug', [DashboardController::class, 'debugCitas'])
        ->name('client.dashboard-debug');

    // Perfil del cliente (actualizar datos desde dashboard)
    Route::patch('/dashboard-cliente/profile', [DashboardController::class, 'updateProfile'])
        ->name('client.profile.update');

    // Elegir plan (obligatorio si no tiene suscripción activa)
    Route::get('/elegir-plan', [DashboardController::class, 'elegirPlan'])
        ->name('client.elegir-plan');
    Route::post('/elegir-plan', [DashboardController::class, 'seleccionarPlan'])
        ->name('client.seleccionar-plan');

    // Suscripción con Bamboo Payment
    Route::prefix('suscripcion')->name('suscripcion.')->group(function () {
        Route::post('/iniciar', [SuscripcionController::class, 'iniciarSuscripcion'])->name('iniciar');
        Route::get('/tokenizar', [SuscripcionController::class, 'mostrarFormularioTokenizacion'])->name('tokenizar');
        Route::post('/procesar-token', [SuscripcionController::class, 'procesarToken'])->name('procesar-token');
        Route::post('/cancelar', [SuscripcionController::class, 'cancelar'])->name('cancelar');
    });
});

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
    Route::resource('plans', PlanController::class);
    Route::patch('plans/{plan}/toggle', [PlanController::class, 'toggleActive'])->name('plans.toggle');

    // Suscripciones
    Route::resource('suscripciones', SuscripcionAdminController::class)->only(['index', 'show'])->parameters(['suscripciones' => 'suscripcion']);
    Route::patch('suscripciones/{suscripcion}/status', [SuscripcionAdminController::class, 'updateStatus'])->name('suscripciones.update-status');

    // Editor de Paginas
    Route::get('page-editor', [PageEditorController::class, 'index'])->name('page-editor.index');
    Route::get('page-editor/home', [PageEditorController::class, 'editHome'])->name('page-editor.home');
    Route::put('page-editor/home', [PageEditorController::class, 'updateHome'])->name('page-editor.home.update');
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

    Route::prefix('{id}/configuracion')->name('configuracion.')->group(function () {
        Route::get('/negocio', [EmpresaController::class, 'configNegocio'])->name('negocio');

        // INDEXCITAS en EmpresaController (usa {id})
        Route::get('/citas', [EmpresaController::class, 'indexCitas'])->name('citas');
        // 👇 nuevas
        Route::get('/citas/{cita}', [EmpresaController::class, 'showCita'])->name('citas.show');
        Route::patch('/citas/{cita}/estado', [EmpresaController::class, 'cambiarEstadoCita'])->name('citas.estado');
        Route::patch('/citas/{cita}/reprogramar', [EmpresaController::class, 'reprogramarCita'])->name('citas.reprogramar');
        Route::delete('/citas/{cita}', [EmpresaController::class, 'destroyCita'])->name('citas.destroy');


        // (opcional) pantalla de ajustes de citas si la quieres aparte
        // Route::get('/citas/ajustes', [EmpresaController::class, 'configCitas'])->name('citas.ajustes');

        Route::get('/ventas', [EmpresaController::class, 'configVentas'])->name('ventas');
        Route::get('/facturacion', [EmpresaController::class, 'configFacturacion'])->name('facturacion');
        Route::get('/equipo', [EmpresaController::class, 'configEquipo'])->name('equipo');
        Route::get('/formularios', [EmpresaController::class, 'configFormularios'])->name('formularios');
        Route::get('/pagos', [EmpresaController::class, 'configPagos'])->name('pagos');
    });

    Route::prefix('{id}/catalogo')->name('catalogo.')->group(function () {
        Route::get('/servicios', [CatalogoController::class, 'menuServicios'])->name('servicios');
        Route::get('/servicios/crear', [CatalogoController::class, 'formCrearServicio'])->name('servicios.crear');
        Route::post('/servicios', [CatalogoController::class, 'guardarServicio'])->name('servicios.guardar');
        Route::get('/servicios/{servicio}/editar', [CatalogoController::class, 'editarServicio'])->name('servicios.editar');
        Route::put('/servicios/{servicio}', [CatalogoController::class, 'actualizarServicio'])->name('servicios.actualizar');
        Route::post('/servicios/{servicio}/duplicar', [CatalogoController::class, 'duplicarServicio'])->name('servicios.duplicar');
        Route::delete('/servicios/{servicio}', [CatalogoController::class, 'eliminarServicio'])->name('servicios.eliminar');

        Route::post('/categorias', [CatalogoController::class, 'guardarCategoria'])->name('categorias.guardar');
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

Route::get('/empresa/{empresa}/clientes', [EmpresaController::class, 'clientes'])
    ->name('empresa.clientes.index');

Route::prefix('empresa/{empresa}/clientes')->group(function () {
    Route::post('/crear', [EmpresaController::class, 'storeCliente'])->name('empresa.clientes.store');
    Route::put('/{cliente}/editar', [EmpresaController::class, 'updateCliente'])->name('empresa.clientes.update');
    Route::delete('/{cliente}/eliminar', [EmpresaController::class, 'destroyCliente'])->name('empresa.clientes.destroy');
});
// Mostrar todos los trabajadores
Route::get('/empresa/{empresa}/trabajadores', [EmpresaController::class, 'trabajadores'])
    ->name('empresa.trabajadores.index');

// CRUD de trabajadores con prefijos claros
Route::prefix('empresa/{empresa}/trabajadores')->group(function () {
    Route::post('/crear', [EmpresaController::class, 'storeTrabajador'])->name('empresa.trabajadores.store');
    Route::put('/{trabajador}/editar', [EmpresaController::class, 'updateTrabajador'])->name('empresa.trabajadores.update');
    Route::delete('/{trabajador}/eliminar', [EmpresaController::class, 'destroyTrabajador'])->name('empresa.trabajadores.destroy');
});

// Galería de fotos
Route::get('/empresa/{empresa}/galeria', [GaleriaController::class, 'index'])->name('empresa.galeria.index');
Route::prefix('empresa/{empresa}/galeria')->group(function () {
    Route::post('/subir', [GaleriaController::class, 'store'])->name('empresa.galeria.store');
    Route::delete('/{foto}/eliminar', [GaleriaController::class, 'destroy'])->name('empresa.galeria.destroy');
});

// Reseñas - empresa panel
Route::get('/empresa/{empresa}/resenas', [EmpresaController::class, 'resenas'])->name('empresa.resenas.index');

// Reseñas - acciones (autenticadas)
Route::middleware('auth')->group(function () {
    Route::post('/resenas', [ResenaController::class, 'store'])->name('resenas.store');
    Route::post('/resenas/{resena}/responder', [ResenaController::class, 'responder'])->name('resenas.responder');
});

// Reseñas - calificacion publica (signed URL, no requiere auth)
Route::get('/calificar/{cita}', [ResenaController::class, 'calificar'])
    ->name('resena.calificar')
    ->middleware('signed');
Route::post('/calificar/{cita}', [ResenaController::class, 'calificarStore'])
    ->name('resena.calificar.store');

//agenda
Route::get('/empresa/{id}/agenda', [AgendaController::class, 'index'])->name('empresa.agenda');
// routes/web.php
Route::get('/empresa/{id}/agenda/configurar', [AgendaController::class, 'configurar'])->name('empresa.agenda.configurar');

Route::post('/empresa/{id}/agenda/bloqueados', [AgendaController::class, 'guardarBloqueados'])->name('agenda.guardar_bloqueados');

// Explorar negocios afiliados
Route::get('/negocios', [\App\Http\Controllers\NegocioController::class, 'explorar'])
    ->name('negocios.explorar');

// URL amigable con slug (sin ID)
Route::get('/negocios/{slug}', [\App\Http\Controllers\NegocioController::class, 'show'])
    ->name('negocios.show')
    ->where('slug', '[a-z0-9\-]+');

// Redirect 301 de la URL vieja (con ID) a la nueva
Route::get('/negocios/{id}-{oldSlug}', function ($id, $oldSlug) {
    $negocio = \App\Models\Negocio::findOrFail($id);
    return redirect()->route('negocios.show', ['slug' => $negocio->slug], 301);
})->where('id', '[0-9]+');

Route::post('/negocios/{negocio}/agenda', [AgendaController::class, 'store'])
     ->name('agenda.store');

Route::get('/negocios/{negocio}/agenda/citas-dia', [AgendaController::class, 'citasDia'])
    ->name('agenda.citas-dia');

Route::get('/negocios/{negocio}/agenda/citas-mes', [AgendaController::class, 'citasMes'])
    ->name('agenda.citas-mes');

require __DIR__ . '/auth.php';
