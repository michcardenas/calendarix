# PLAN.md — Análisis Completo de Calendarix

## 1. Resumen General

**Calendarix** es una plataforma SaaS de gestión de negocios y reservas de citas construida con **Laravel 12**. Permite a negocios (peluquerías, consultorios, etc.) crear un perfil público, gestionar servicios, productos, trabajadores, clientes, y recibir reservas de citas online con un sistema anti-solapamiento por trabajador.

**Público objetivo:**
- **Dueños de negocios** — gestionan su empresa, catálogo, agenda y equipo
- **Clientes finales** — reservan citas, compran productos/servicios
- **Administradores** — gestionan usuarios, planes de suscripción y configuración global

**Stack tecnológico:** Laravel 12, Blade, Alpine.js, Tailwind CSS, Bootstrap 5, FullCalendar 6, Chart.js, Vite 6, SQLite (dev), Spatie Laravel-Permission, Laravel Socialite (Google OAuth).

---

## 2. Panel Admin

**Layout:** `resources/views/layouts/admin.blade.php`
**Sidebar:** `resources/views/admin/partials/sidebar.blade.php`
**CSS:** `public/css/admin-dashboard.css`, `public/css/admin-plans.css`

| Módulo | Vista(s) | Controlador | Descripción |
|--------|----------|-------------|-------------|
| **Dashboard** | `admin/dashboard-admin.blade.php` | `Admin\DashboardController@index` | Panel principal con estadísticas (citas hoy, negocios activos, métricas de ingresos). Usa Chart.js. |
| **Gestión de Usuarios** | `admin/users/index.blade.php`, `create.blade.php`, `edit.blade.php` | `Admin\UserController` (CRUD completo) | Lista, crea, edita y elimina usuarios. Asigna roles con Spatie. Búsqueda y paginación. |
| **Gestión de Planes** | `admin/plans/index.blade.php`, `create.blade.php`, `edit.blade.php` | `Admin\PlanController` (CRUD + toggle) | Gestión de planes de suscripción con features (CRM IA, ecommerce, multi-sucursal, recordatorios WhatsApp/email). Toggle activo/inactivo. |

**Secciones del sidebar con vistas pendientes:**
- Gestión de Contenido (sin implementar)
- Configuración del Sistema (sin implementar)
- Reportes (sin implementar)

---

## 3. Panel Empresa (Dueño de Negocio)

**Layout:** `resources/views/layouts/empresa.blade.php`
**Sidebar:** `resources/views/empresa/partials/sidebar.blade.php`

| Módulo | Vista(s) | Controlador | Descripción |
|--------|----------|-------------|-------------|
| **Dashboard** | `empresa/dashboard.blade.php` | `Empresa\EmpresaController@dashboard` | Estadísticas del negocio: total citas, servicios activos, miembros del equipo, próximas citas. |
| **Agenda** | `empresa/agenda.blade.php` | `Empresa\AgendaController@index` | Calendario con FullCalendar. Visualización de citas, horarios laborales y días bloqueados. |
| **Configurar Agenda** | `agenda/configurar.blade.php` | `Empresa\AgendaController@configurar` | Configurar horarios laborales por día (lunes–domingo) y días bloqueados. |
| **Catálogo — Servicios** | `empresa/catalogo/menu-servicios.blade.php` | `Empresa\CatalogoController` (CRUD completo) | Lista servicios por categoría. Crear, editar, duplicar, eliminar servicios. Gestión de categorías. |
| **Catálogo — Productos** | `empresa/catalogo/panel_productos.blade.php`, `crear_producto.blade.php`, `editar_producto.blade.php` | `Empresa\ProductoController` (CRUD completo) | Gestión de productos con imágenes múltiples, precios, stock, códigos de barras. |
| **Trabajadores** | `empresa/trabajadores/index.blade.php` + modales | `Empresa\EmpresaController` (storeTrabajador, updateTrabajador, destroyTrabajador) | CRUD de trabajadores con modales para crear/editar. |
| **Clientes** | `empresa/clientes/index.blade.php` + modales | `Empresa\EmpresaController` (storeCliente, updateCliente, destroyCliente) | CRUD de clientes con modales para crear/editar. |
| **Config — Negocio** | `empresa/configuracion/negocio.blade.php` | `Empresa\NegocioConfiguracionController@guardar` | Editar datos del negocio: contacto, dirección, logo, portada, redes sociales. |
| **Config — Citas** | `empresa/configuracion/citas/index.blade.php` + modales | `Empresa\EmpresaController@indexCitas` | Lista de citas con filtros (búsqueda, fecha, estado, trabajador). Ver detalle, cambiar estado, eliminar. |
| **Config — Centros** | `empresa/configuracion/centros.blade.php` | `Empresa\NegocioConfiguracionController@centros` | Gestión de sucursales/centros del negocio. |
| **Config — Procedencia** | `empresa/configuracion/procedencia.blade.php` | `Empresa\NegocioConfiguracionController@procedencia` | Configurar enlaces de redes sociales y fuentes de referencia. |
| **Editor de Perfil** | `empresa/editor-dashboard.blade.php` | `Empresa\EditorEmpresaController@index` | Editor visual de bloques del perfil público (drag & drop con Sortable.js). |
| **Bloques de Perfil** | `empresa/bloques/` (servicios, galería, horario, contacto, ubicación) | `Empresa\BloquesController@mostrarBloque` | Bloques individuales del perfil público. |
| **Pedidos** | `checkout/pedidos.blade.php` | `CheckoutController@pedidos` | Lista de pedidos del negocio con cambio de estado de pago. |

### Panel Cliente (Usuario Final)

**Vista:** `resources/views/client/dashboard-client.blade.php`
**Controlador:** `Admin\DashboardController@cliente`

| Módulo | Descripción |
|--------|-------------|
| **Dashboard Cliente** | Muestra negocios del usuario, estadísticas de citas mensuales/semanales, próximas 8 citas, negocios recomendados, favoritos. |
| **Perfil Público de Negocio** | `negocio/perfil.blade.php` — FullCalendar, lista servicios/productos, reservar citas, carrito de compras. |
| **Reservar Cita** | Modal `empresa/partials/modal-agendar.blade.php` — Selección de servicio, trabajador, fecha y slot de 15 min con validación anti-solapamiento. |
| **Carrito de Compras** | Modal `empresa/partials/carrito-modal.blade.php` — Carrito en localStorage, productos y servicios mixtos. |
| **Checkout** | `checkout/index.blade.php`, `confirmar.blade.php`, `success.blade.php`, `failure.blade.php` — Flujo completo de compra con confirmación por email. |
| **Perfil de Usuario** | `profile/edit.blade.php` — Editar datos personales, cambiar contraseña, eliminar cuenta. |

---

## 4. Rutas Registradas

### `admin/` — Panel de Administración
```
GET    /admin/users                       Admin\UserController@index          admin.users.index
GET    /admin/users/create                Admin\UserController@create         admin.users.create
POST   /admin/users                       Admin\UserController@store          admin.users.store
GET    /admin/users/{user}                Admin\UserController@show           admin.users.show
GET    /admin/users/{user}/edit           Admin\UserController@edit           admin.users.edit
PUT    /admin/users/{user}                Admin\UserController@update         admin.users.update
DELETE /admin/users/{user}                Admin\UserController@destroy        admin.users.destroy
GET    /admin/plans                       Admin\PlanController@index          admin.plans.index
GET    /admin/plans/create                Admin\PlanController@create         admin.plans.create
POST   /admin/plans                       Admin\PlanController@store          admin.plans.store
GET    /admin/plans/{plan}                Admin\PlanController@show           admin.plans.show
GET    /admin/plans/{plan}/edit           Admin\PlanController@edit           admin.plans.edit
PUT    /admin/plans/{plan}                Admin\PlanController@update         admin.plans.update
DELETE /admin/plans/{plan}                Admin\PlanController@destroy        admin.plans.destroy
PATCH  /admin/plans/{plan}/toggle         Admin\PlanController@toggleActive   admin.plans.toggle
```

### `negocio/` — Wizard de Registro de Negocio
```
GET    /negocio/registro                  negocio\NegocioController@create          negocio.create
POST   /negocio/registro                  negocio\NegocioController@store           negocio.store
GET    /negocio/datos                     negocio\NegocioController@datosNegocio    negocio.datos
POST   /negocio/datos                     negocio\NegocioController@guardarNombre   negocio.nombre.store
GET    /negocio/categorias                negocio\NegocioController@categorias      negocio.categorias
POST   /negocio/categorias                negocio\NegocioController@guardarCategorias
GET    /negocio/equipo                    negocio\NegocioController@equipo          negocio.equipo
POST   /negocio/equipo                    negocio\NegocioController@guardarEquipo
GET    /negocio/ubicacion                 negocio\NegocioController@ubicacion       negocio.ubicacion
POST   /negocio/ubicacion                 negocio\NegocioController@guardarUbicacion
GET    /negocio/verificar-direccion       negocio\NegocioController@verificarDireccion
POST   /negocio/verificar-direccion       negocio\NegocioController@guardarVerificacion
DELETE /negocio/negocios/{negocio}        negocio\NegocioController@destroy
GET    /negocio/empresa/editor/{id}       Empresa\EditorEmpresaController@index
```

### `empresa/` — Dashboard y Gestión del Negocio
```
GET    /empresa/{id}/dashboard                              Empresa\EmpresaController@dashboard
GET    /empresa/{id}/configuracion                          Empresa\EmpresaController@configuracion
GET    /empresa/{id}/agenda                                 Empresa\AgendaController@index
GET    /empresa/{id}/agenda/configurar                      Empresa\AgendaController@configurar
POST   /empresa/{id}/agenda/bloqueados                      Empresa\AgendaController@guardarBloqueados
GET    /empresa/{id}/catalogo/servicios                     Empresa\CatalogoController@menuServicios
GET    /empresa/{id}/catalogo/servicios/crear               Empresa\CatalogoController@formCrearServicio
POST   /empresa/{id}/catalogo/servicios                     Empresa\CatalogoController@guardarServicio
GET    /empresa/{id}/catalogo/servicios/{servicio}/editar   Empresa\CatalogoController@editarServicio
PUT    /empresa/{id}/catalogo/servicios/{servicio}          Empresa\CatalogoController@actualizarServicio
POST   /empresa/{id}/catalogo/servicios/{servicio}/duplicar Empresa\CatalogoController@duplicarServicio
DELETE /empresa/{id}/catalogo/servicios/{servicio}          Empresa\CatalogoController@eliminarServicio
POST   /empresa/{id}/catalogo/categorias                    Empresa\CatalogoController@guardarCategoria
GET    /empresa/{id}/catalogo/productos                     Empresa\ProductoController@panel
GET    /empresa/{id}/catalogo/producto/crear                Empresa\ProductoController@create
POST   /empresa/catalogo/producto                           Empresa\ProductoController@store
GET    /empresa/catalogo/producto/{producto}/editar          Empresa\ProductoController@edit
PUT    /empresa/catalogo/producto/{producto}                 Empresa\ProductoController@update
DELETE /empresa/catalogo/producto/{producto}                 Empresa\ProductoController@destroy
DELETE /empresa/productos/imagen/{id}                        Empresa\ProductoController@eliminarImagen
GET    /empresa/{empresa}/clientes                          Empresa\EmpresaController@clientes
POST   /empresa/{empresa}/clientes/crear                    Empresa\EmpresaController@storeCliente
PUT    /empresa/{empresa}/clientes/{cliente}/editar          Empresa\EmpresaController@updateCliente
DELETE /empresa/{empresa}/clientes/{cliente}/eliminar        Empresa\EmpresaController@destroyCliente
GET    /empresa/{empresa}/trabajadores                      Empresa\EmpresaController@trabajadores
POST   /empresa/{empresa}/trabajadores/crear                Empresa\EmpresaController@storeTrabajador
PUT    /empresa/{empresa}/trabajadores/{trabajador}/editar   Empresa\EmpresaController@updateTrabajador
DELETE /empresa/{empresa}/trabajadores/{trabajador}/eliminar  Empresa\EmpresaController@destroyTrabajador
GET    /empresa/{id}/configuracion/negocio                  Empresa\EmpresaController@negocio
GET    /empresa/{id}/configuracion/citas                    Empresa\EmpresaController@indexCitas
GET    /empresa/{id}/configuracion/citas/{cita}             Empresa\EmpresaController@showCita
PATCH  /empresa/{id}/configuracion/citas/{cita}/estado      Empresa\EmpresaController@cambiarEstadoCita
DELETE /empresa/{id}/configuracion/citas/{cita}             Empresa\EmpresaController@destroyCita
GET    /empresa/{id}/configuracion/ventas                   Empresa\EmpresaController@configVentas
GET    /empresa/{id}/configuracion/facturacion              Empresa\EmpresaController@configFacturacion
GET    /empresa/{id}/configuracion/equipo                   Empresa\EmpresaController@configEquipo
GET    /empresa/{id}/configuracion/formularios              Empresa\EmpresaController@configFormularios
GET    /empresa/{id}/configuracion/pagos                    Empresa\EmpresaController@configPagos
POST   /empresa/configuracion/negocio/guardar               Empresa\NegocioConfiguracionController@guardar
GET    /empresa/configuracion/centros                       Empresa\NegocioConfiguracionController@centros
PUT    /empresa/configuracion/centros/{id}                  Empresa\NegocioConfiguracionController@actualizarCentro
GET    /empresa/configuracion/procedencia                   Empresa\NegocioConfiguracionController@procedencia
POST   /empresa/configuracion/procedencia                   Empresa\NegocioConfiguracionController@actualizarProcedencia
GET    /empresa/{id}/configuracion                          Empresa\ConfiguracionEmpresaController@obtenerConfiguracion
POST   /empresa/{id}/configuracion                          Empresa\ConfiguracionEmpresaController@guardarConfiguracion
GET    /empresa/{id}/vista-previa                           Empresa\ConfiguracionEmpresaController@vistaPrevia
GET    /empresa/{id}/servicios                              Empresa\ServicioEmpresaController@mostrar
GET    /bloques/{tipo}                                      Empresa\BloquesController@mostrarBloque
```

### Rutas Públicas — Perfil de Negocio y Citas
```
GET    /negocios/{id}-{slug}              NegocioController@show              negocios.show
POST   /negocios/{negocio}/agenda         Empresa\AgendaController@store      agenda.store
GET    /negocios/{negocio}/agenda/citas-dia   Empresa\AgendaController@citasDia
GET    /negocios/{negocio}/agenda/citas-mes   Empresa\AgendaController@citasMes
```

### Checkout y Carrito
```
GET    /empresa/{id}/checkout             CheckoutController@index            checkout.index
POST   /empresa/{id}/checkout/add         CheckoutController@add              checkout.add
POST   /empresa/{id}/checkout/finalizar   CheckoutController@finalizar
POST   /checkout/{empresa}/finalizar      CheckoutController@finalizar
POST   /checkout/{empresa}/confirmar      CheckoutController@confirmar
GET    /checkout/confirmar/{id}           CheckoutController@confirmar
POST   /checkout/confirmar/{id}           CheckoutController@guardarDatos
PUT    /checkout/{checkout}/estado        CheckoutController@cambiarEstado
POST   /checkout/{id}/remove             CheckoutController@remove
GET    /empresa/{id}/catalogo/pedidos     CheckoutController@pedidos
GET    /success                           CheckoutController@success
GET    /failure                           CheckoutController@failure
POST   /carrito/agregar                   CarritoController@agregar
```

### Autenticación (auth.php + Google OAuth)
```
GET    /login                             Auth\AuthenticatedSessionController@create
POST   /login                             Auth\AuthenticatedSessionController@store
POST   /logout                            Auth\AuthenticatedSessionController@destroy
GET    /register                          Auth\RegisteredUserController@create
POST   /register                          Auth\RegisteredUserController@store
GET    /forgot-password                   Auth\PasswordResetLinkController@create
POST   /forgot-password                   Auth\PasswordResetLinkController@store
GET    /reset-password/{token}            Auth\NewPasswordController@create
POST   /reset-password                    Auth\NewPasswordController@store
GET    /verify-email                      Auth\EmailVerificationPromptController
GET    /verify-email/{id}/{hash}          Auth\VerifyEmailController
POST   /email/verification-notification   Auth\EmailVerificationNotificationController@store
GET    /confirm-password                  Auth\ConfirmablePasswordController@show
POST   /confirm-password                  Auth\ConfirmablePasswordController@store
PUT    /password                          Auth\PasswordController@update
GET    /auth/google                       Auth\GoogleController@redirectToGoogle
GET    /auth/google/callback              Auth\GoogleController@handleGoogleCallback
```

### Dashboard y Perfil de Usuario
```
GET    /dashboard                         Admin\DashboardController@dashboard
GET    /dashboard-cliente                 Admin\DashboardController@cliente
GET    /dashboard-cliente/debug           Admin\DashboardController@debugCitas
GET    /profile                           ProfileController@edit
PATCH  /profile                           ProfileController@update
DELETE /profile                           ProfileController@destroy
GET    /mis-empresas                      negocio\NegocioController@index
GET    /empresa/{id}                      negocio\NegocioController@show
```

---

## 5. Modelos Principales

### Diagrama de Relaciones

```
User (1) ──────→ (M) Negocio
  └──────────→ (M) Checkout
  └──────────→ (M) Cita

Negocio (1) ──→ (M) ServicioEmpresa
  ├──────────→ (M) Producto
  ├──────────→ (M) HorarioLaboral
  ├──────────→ (M) DiaBloqueado
  ├──────────→ (M) Cita
  ├──────────→ (M) Cliente
  └──────────→ (M) Trabajador

Empresa (proxy) ──→ misma tabla que Negocio
  ├──────────→ (M) Cliente
  ├──────────→ (M) Trabajador
  └──────────→ (M) Producto

Trabajador (1) ──→ (M) Cita

ServicioEmpresa (1) ──→ (M) Cita
  └──────────→ (M) CheckoutDetalle

Producto (1) ──→ (M) ImagenProducto
  └──────────→ (M) CheckoutDetalle

Checkout (1) ──→ (M) CheckoutDetalle
```

### Detalle por Modelo

| Modelo | Tabla | Atributos Clave | Relaciones |
|--------|-------|-----------------|------------|
| `App\Models\User` | `users` | name, email, password, dni, celular, pais, ciudad, foto | hasMany: Negocio. Usa Spatie HasRoles. |
| `App\Models\Negocio` | `negocios` | neg_nombre, neg_email, neg_telefono, neg_nombre_comercial, neg_categorias (JSON), neg_direccion, configuracion_bloques (JSON) | belongsTo: User. hasMany: ServicioEmpresa, Producto, HorarioLaboral, DiaBloqueado, Cita, Cliente. |
| `App\Models\Empresa\Empresa` | `negocios` | Proxy ligero, guarded=[] | hasMany: Cliente, Trabajador, Producto. **Misma tabla que Negocio.** |
| `App\Models\Empresa\ServicioEmpresa` | `servicios_empresa` | nombre, descripcion, precio, categoria, duracion | belongsTo: Negocio. |
| `App\Models\Cita` | `citas` | fecha, hora_inicio, hora_fin, nombre_cliente, estado, notas, precio_cerrado | belongsTo: Negocio, User, ServicioEmpresa, Trabajador. |
| `App\Models\Trabajador` | `trabajadores` | nombre, email, telefono | belongsTo: Empresa. hasMany: Cita. |
| `App\Models\Cliente` | `clientes` | nombre, email, telefono | belongsTo: Negocio. |
| `App\Models\Producto` | `productos` | nombre, codigo_barras, marca, precio_compra, precio_venta, precio_promocional, stock, estado_publicado | belongsTo: Empresa. hasMany: ImagenProducto. |
| `App\Models\ImagenProducto` | `imagenes_productos` | producto_id, ruta | belongsTo: Producto. |
| `App\Models\Checkout` | `checkouts` | nombre, email, telefono, direccion, estado_pago, metodo_pago | belongsTo: Empresa, User. hasMany: CheckoutDetalle. Accessor: `total`. |
| `App\Models\CheckoutDetalle` | `checkout_detalles` | cantidad, precio_unitario, precio_total | belongsTo: Checkout, Producto, ServicioEmpresa. |
| `App\Models\HorarioLaboral` | `horarios_laborales` | dia_semana (1–7), hora_inicio, hora_fin, activo | Relación implícita con Negocio. |
| `App\Models\DiaBloqueado` | `dias_bloqueados` | fecha_bloqueada | belongsTo: Negocio. |
| `App\Models\Plan` | `plans` | name, slug, price, interval, max_professionals, crm_ia_enabled, ecommerce_enabled, multi_branch_enabled, is_active | Sin relaciones definidas aún. |

---

## 6. Funcionalidades Existentes (Funcionando)

### Autenticación y Autorización
- [x] Registro e inicio de sesión con email/contraseña (Laravel Breeze)
- [x] Login con Google OAuth (Socialite)
- [x] Verificación de email
- [x] Recuperación de contraseña con email de confirmación
- [x] Roles: Admin, Cliente (Spatie Laravel-Permission)
- [x] Redirección post-login por rol (`RoleRedirectService`)

### Registro de Negocio (Wizard Multi-Paso)
- [x] Paso 1: Datos personales + imagen
- [x] Paso 2: Nombre comercial y sitio web
- [x] Paso 3: Selección de categorías
- [x] Paso 4: Tamaño del equipo
- [x] Paso 5: Ubicación/dirección (soporte virtual)
- [x] Paso 6: Verificación de dirección
- [x] Eliminación de negocio

### Panel Empresa — Catálogo
- [x] CRUD completo de servicios con categorías
- [x] Duplicar servicios
- [x] CRUD completo de productos con imágenes múltiples
- [x] Precios con parsing multi-formato (punto, coma, moneda)

### Panel Empresa — Equipo y Clientes
- [x] CRUD de trabajadores (modal)
- [x] CRUD de clientes (modal)

### Panel Empresa — Agenda y Citas
- [x] Visualización de calendario con FullCalendar
- [x] Configuración de horarios laborales por día (lunes–domingo)
- [x] Gestión de días bloqueados
- [x] Creación de citas con validación anti-solapamiento por trabajador
- [x] Slots de 15 minutos
- [x] AJAX: citas por día y por mes
- [x] Gestión de citas: ver detalle, cambiar estado (pendiente/confirmada/cancelada), eliminar
- [x] Emails de confirmación al crear cita (cliente + dueño)

### Panel Empresa — Configuración
- [x] Editar datos del negocio (contacto, dirección, logo, portada)
- [x] Configurar redes sociales (Facebook, Instagram)
- [x] Gestión de centros/sucursales
- [x] Configuración de bloques del perfil público (JSON)
- [x] Editor visual de bloques con drag & drop

### Carrito y Checkout
- [x] Carrito en localStorage (productos + servicios mixtos)
- [x] Agregar/eliminar items del carrito
- [x] Flujo de checkout: revisión → datos de contacto → confirmación
- [x] Creación de pedido (Checkout + CheckoutDetalle)
- [x] Email de confirmación de pedido (cliente + dueño)
- [x] Lista de pedidos del negocio
- [x] Cambiar estado de pago de pedidos

### Perfil Público de Negocio
- [x] Página pública con URL SEO-friendly (`/negocios/{id}-{slug}`)
- [x] Visualización de servicios, productos, calendario
- [x] Reserva de citas desde perfil público
- [x] Carrito de compras integrado

### Panel Admin
- [x] Dashboard con estadísticas y gráficos (Chart.js)
- [x] CRUD completo de usuarios con asignación de roles
- [x] CRUD completo de planes de suscripción
- [x] Toggle activo/inactivo de planes

### Notificaciones por Email
- [x] Email de bienvenida al registrarse
- [x] Email de verificación de cuenta
- [x] Email de cambio de contraseña
- [x] Email de confirmación de pedido
- [x] Email de confirmación de cita
- [x] Plantilla genérica reutilizable (`NotificacionGeneral`)

### Dashboard Cliente
- [x] Estadísticas de citas mensuales y semanales
- [x] Próximas citas (8)
- [x] Negocios recomendados
- [x] Contador de favoritos

---

## 7. Funcionalidades Faltantes / Incompletas

### Crítico — Seguridad y Middleware
- [ ] **Rutas de empresa/ sin middleware `auth`**: La mayoría de rutas bajo `/empresa/` NO tienen middleware de autenticación. Cualquier usuario (o visitante) podría acceder al dashboard de cualquier negocio.
- [ ] **Sin middleware de autorización por propietario**: No hay verificación de que el usuario autenticado sea el dueño del negocio al que accede. Un usuario podría editar negocios de otros.
- [ ] **Rutas admin/ solo usan `auth`, no `role:Admin`**: Las rutas `/admin/*` solo requieren autenticación pero no verifican el rol de administrador.

### Funcionalidades Declaradas en UI Sin Implementar

| Funcionalidad | Evidencia | Estado |
|---------------|-----------|--------|
| **Configuración de Ventas** | Ruta `empresa/{id}/configuracion/ventas` → `EmpresaController@configVentas` | Método existe pero **vista no encontrada** |
| **Configuración de Facturación** | Ruta `empresa/{id}/configuracion/facturacion` → `EmpresaController@configFacturacion` | Método existe pero **vista no encontrada** |
| **Configuración de Equipo** | Ruta `empresa/{id}/configuracion/equipo` → `EmpresaController@configEquipo` | Método existe pero **vista no encontrada** |
| **Configuración de Formularios** | Ruta `empresa/{id}/configuracion/formularios` → `EmpresaController@configFormularios` | Método existe pero **vista no encontrada** |
| **Configuración de Pagos** | Ruta `empresa/{id}/configuracion/pagos` → `EmpresaController@configPagos` | Método existe pero **vista no encontrada** |
| **Bloque Galería** | `empresa/bloques/galeria.blade.php` existe, `BloquesController@bloqueGaleria` | Implementación **placeholder** — no funcional |
| **Horarios por Trabajador** | Variables JS: `window.TRABAJADOR_HORARIOS`, `window.TRABAJADOR_BLOQUEOS` | Declaradas como "future feature" — **sin implementar** |
| **Sidebar Admin — Gestión de Contenido** | Sección visible en sidebar | **Sin rutas ni controladores** |
| **Sidebar Admin — Configuración del Sistema** | Sección visible en sidebar | **Sin rutas ni controladores** |
| **Sidebar Admin — Reportes** | Sección visible en sidebar | **Sin rutas ni controladores** |

### Modelo Plan sin Integración
- [ ] El modelo `Plan` y su CRUD existen, pero **no hay relación con `Negocio` ni `User`**
- [ ] No existe tabla `subscriptions` ni lógica de suscripción
- [ ] No hay restricción de funcionalidades según plan (ecommerce, CRM, multi-sucursal)
- [ ] No hay pasarela de pago para planes

### Rutas Duplicadas / Conflictivas
- [ ] `/empresa/{id}/configuracion` definida **3 veces** con controladores distintos (`EmpresaController`, `ConfiguracionEmpresaController`)
- [ ] `/empresa/{id}/dashboard` definida **2 veces** (`DashboardEmpresaController@index` y `EmpresaController@dashboard`)
- [ ] Varias rutas de checkout duplicadas con patrones similares (`/checkout/{empresa}/finalizar` vs `/empresa/{id}/checkout/finalizar`)

### Funcionalidades No Implementadas
- [ ] **Sistema de favoritos**: El dashboard cliente muestra "favoritos" pero no hay modelo, migración ni lógica
- [ ] **Negocios recomendados**: Se cargan pero sin algoritmo real de recomendación
- [ ] **Pasarela de pago real**: No hay integración con Stripe, MercadoPago, etc. El checkout solo registra el pedido
- [ ] **Notificaciones push / en tiempo real**: No hay WebSockets ni broadcasting
- [ ] **API REST**: No existe `routes/api.php` con endpoints
- [ ] **Búsqueda global de negocios**: La homepage tiene barra de búsqueda pero sin backend
- [ ] **Sistema de reseñas/valoraciones**: No implementado
- [ ] **Recordatorios de cita** (WhatsApp/email): Feature declarada en planes pero sin implementación
- [ ] **CRM con IA**: Feature declarada en planes pero sin implementación
- [ ] **Multi-sucursal**: Feature declarada en planes pero sin implementación real
- [ ] **Tests**: No se encontraron tests funcionales más allá de los de Breeze por defecto

### Problemas de Código Detectados
- [ ] **Rutas de upload hardcodeadas**: `NegocioConfiguracionController` y `ProductoController` usan rutas absolutas del servidor de producción
- [ ] **Modelo dual Negocio/Empresa**: Genera confusión — algunos controladores usan `Empresa`, otros `Negocio`, para la misma tabla
- [ ] **Método `guardarServicio()` en modelo** `ServicioEmpresa`: Lógica de negocio que debería estar en el controlador
- [ ] **DashboardEmpresaController** muy ligero: Solo retorna vista, sin métricas
- [ ] **Sin validación CSRF explícita** en varias rutas AJAX (depende del middleware global)
- [ ] **Sin Livewire**: La aplicación usa Blade tradicional + AJAX, no se encontraron componentes Livewire

---

## Resumen de Archivos Clave

| Tipo | Cantidad |
|------|----------|
| Controladores | 30 |
| Modelos | 14 |
| Migraciones | 44 |
| Vistas Blade | ~89 |
| Layouts | 6 |
| Componentes Livewire | 0 |
| Rutas (~) | 130+ |
| Emails/Mailables | 2 plantillas + 2 clases |
