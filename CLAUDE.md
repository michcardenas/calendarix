# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Calendarix is a Laravel 12 appointment booking and business management platform. It enables businesses (negocios) to:
- Manage their public profile with services, products, schedules, and team members
- Accept appointment bookings from clients with worker-specific scheduling
- Sell products through a shopping cart and checkout system
- Configure working hours, blocked dates, and availability

## Development Commands

### Setup & Installation
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Running the Application
```bash
# Start all services (server, queue, logs, vite) in one command
composer dev

# Or run individually:
php artisan serve              # Development server
php artisan queue:listen       # Queue worker
php artisan pail              # View logs
npm run dev                   # Vite dev server
```

### Building Assets
```bash
npm run build                 # Production build
```

### Testing
```bash
composer test                 # Run PHPUnit tests (clears config first)
php artisan test             # Alternative test command
php artisan test --filter TestName  # Run specific test
```

### Code Quality
```bash
./vendor/bin/pint            # Laravel Pint (code style fixer)
```

## Architecture & Key Concepts

### Model Architecture: Empresa vs Negocio

**Critical distinction**: The codebase uses both `Empresa` and `Negocio` models that represent the **same business entity** but serve different purposes:

- **`Negocio` Model** (`app/Models/Negocio.php`): The primary business entity with full attributes (name, contact info, profile settings, etc.). Used for public-facing views and business profile management.

- **`Empresa` Model** (`app/Models/Empresa/Empresa.php`): A lightweight proxy that references the `negocios` table. Used in internal management contexts (dashboard, configuration, agenda). Both models query the same database table.

**Important**: When writing queries or relationships, verify which model is being used in that context. They are interchangeable in terms of data but have different relationship definitions.

### Key Models

- `Cita` — Appointments (fecha, hora_inicio, hora_fin, trabajador_id, servicio_id)
- `Trabajador` — Workers/staff members
- `HorarioLaboral` — Business working hours by day of week (1–7)
- `DiaBloqueado` — Blocked dates
- `Producto` / `ImagenProducto` — Products and their images
- `Checkout` / `CheckoutDetalle` — Orders and line items
- `Cliente` — Clients associated with a business
- `ServicioEmpresa` (`app/Models/Empresa/ServicioEmpresa.php`) — Services offered

### Appointment Booking System

The appointment system is worker-centric with anti-overlap validation:

1. **Scheduling logic** (see `resources/views/empresa/partials/modal-agendar.blade.php`):
   - Global variables `window.NEGOCIO_HORARIOS`, `window.RESERVAS`, `window.TRABAJADOR_HORARIOS` manage schedules
   - Time slots are 15-minute intervals
   - Validation prevents overlapping appointments for the same worker
   - Frontend generates available slots by filtering out occupied time ranges

2. **Calendar integration** (`resources/views/negocio/perfil.blade.php`):
   - FullCalendar displays appointments, blocked dates, and past dates with visual differentiation
   - AJAX endpoints: `/negocios/{id}/agenda/citas-dia` (single day), `/negocios/{id}/agenda/citas-mes` (month view)
   - Event colors by status: pendiente (indigo), confirmada (green), cancelada (red)

### Controller Organization

Controllers are organized by user role/context:

- **`Admin/`**: Admin dashboard and user management
- **`Empresa/`**: Business owner dashboard — `AgendaController`, `CatalogoController`, `ConfiguracionEmpresaController`, `DashboardEmpresaController`, `ProductoController`, `ServicioEmpresaController`, `BloquesController`, `EditorEmpresaController`, `NegocioConfiguracionController`
- **`negocio/NegocioController`**: Multi-step business registration wizard
- **`Auth/`**: Laravel Breeze authentication + Google OAuth (`GoogleController`)
- **Root**: `CheckoutController`, `CarritoController`, `NegocioController` (public profile), `ProfileController`

### Authentication & Authorization

- Uses Laravel Breeze for base authentication
- Google OAuth via Laravel Socialite (`/auth/google`)
- Role-based permissions via Spatie Laravel-Permission
- Most `/empresa/*` routes require authentication (`auth` middleware)
- Admin routes use `admin` middleware prefix

### View Structure

- **`layouts/app.blade.php`**: Public layout with FullCalendar, Tailwind
- **`layouts/empresa.blade.php`**: Business dashboard layout with sidebar navigation
- **`empresa/partials/`**: Reusable modals — `modal-agendar.blade.php` (appointments), `carrito-modal.blade.php` (cart), `modal-nuevo-servicio.blade.php`, `modal-editar-servicio.blade.php`
- **`empresa/bloques/`**: Profile block partials — `contacto`, `galeria`, `horario`, `servicios`, `ubicacion`
- **`empresa/trabajadores/`**: Workers management views with create/edit modals
- **`empresa/clientes/`**: Clients management views with create/edit modals
- **`negocio/perfil.blade.php`**: Public business profile page with calendar, services, products, cart
- **`checkout/`**: Order flow views (index, confirmar, success, failure, pedidos)

### Shopping Cart & Checkout

- Cart stored in localStorage (`carritoNegocio` key)
- Supports both products (`Producto`) and services (`ServicioEmpresa`)
- `CarritoController` handles cart item operations; `CheckoutController` handles order creation
- Orders stored in `Checkout` (header) + `CheckoutDetalle` (line items) models
- Email confirmation sent via `emails/pedido-confirmado.blade.php`
- Order status tracking: pendiente, confirmado, enviado, completado, cancelado

### Email Notifications

- Uses Laravel Mail with queue support
- Order confirmations: `pedido-confirmado.blade.php`
- General notifications: `NotificacionGeneral` mailable class
- Queue driver: database (configure in `.env`)

## Database Conventions

- Main business table: `negocios` (accessed via both `Empresa` and `Negocio` models)
- Day of week: 1=Monday, 7=Sunday (PHP's ISO-8601, adjusted from JavaScript's 0-6)
- Time fields: stored as TIME (HH:MM:SS), frontend uses HH:MM (first 5 chars)
- Appointments: `citas` table with foreign keys to `trabajadores`, `servicios_empresa`

## JavaScript Architecture

### Global Variables for Scheduling

The appointment modal relies on global window variables (set in `perfil.blade.php`):

```javascript
window.NEGOCIO_HORARIOS    // {1: [{inicio:'08:00', fin:'17:00'}], ...} - Business hours by day
window.TRABAJADOR_HORARIOS // Worker-specific hours (future feature)
window.TRABAJADOR_BLOQUEOS // Worker-specific blocked dates (future feature)
window.RESERVAS            // {'YYYY-MM-DD': {trabajador_id: [{inicio, fin}]}} - Booked slots
```

### Time Helper Functions

```javascript
window.t2m(hhmm)                              // Convert 'HH:MM' to minutes
window.m2t(minutes)                           // Convert minutes to 'HH:MM'
window.overlapsAny(start, end, intervals)     // Check time overlap
window.generateFreeQuarterSlots(...)          // Generate available 15-min slots
```

## Common Workflows

### Adding a New Service to a Business

1. Navigate to `/empresa/{id}/catalogo/servicios`
2. Modal form submits to `CatalogoController@guardarServicio`
3. Creates `ServicioEmpresa` record with `negocio_id`
4. Service appears in public profile and appointment modal

### Creating an Appointment

1. User clicks date in FullCalendar on public profile
2. `modal-agendar.blade.php` opens with pre-filtered time slots
3. Frontend calls `/negocios/{id}/agenda/citas-dia` to load existing appointments
4. Validates no overlap for selected worker
5. AJAX POST to `AgendaController@store` creates `Cita`
6. Calendar auto-refreshes with new event

### Business Registration Flow

Multi-step wizard in `/negocio/` routes:
1. `/negocio/registro` - Basic info (name, email, phone)
2. `/negocio/datos` - Business name
3. `/negocio/categorias` - Business categories
4. `/negocio/equipo` - Team size
5. `/negocio/ubicacion` - Address (can be virtual)
6. `/negocio/verificar-direccion` - Confirm address
7. Redirects to dashboard

## Dependencies

- **Backend**: Laravel 12, Spatie Laravel-Permission (roles), Laravel Socialite (Google OAuth)
- **Frontend**: Alpine.js, Tailwind CSS 3, FullCalendar
- **Build**: Vite 6
- **Database**: SQLite (dev), configurable for MySQL/PostgreSQL

## Important Notes

- Windows development environment: project at `c:\Users\HP\Documents\Calendarix\calendarix`
- Uses database driver for sessions, queues, and cache (`SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`, `CACHE_STORE=database`)
- Appointments use worker-level scheduling, not just business-level
- Cart system supports mixed items (products + services)

## Route Patterns

Key route patterns to be aware of:

- **Public business profile**: `/negocios/{id}-{slug}` - SEO-friendly business pages
- **Business dashboard**: `/empresa/{id}/dashboard` - Owner management interface
- **Catalog management**: `/empresa/{id}/catalogo/servicios` and `/empresa/{id}/catalogo/productos`
- **Workers management**: `/empresa/{empresa}/trabajadores` (CRUD)
- **Clients management**: `/empresa/{empresa}/clientes` (CRUD)
- **Agenda/Calendar**: `/empresa/{id}/agenda` (management) vs `/negocios/{id}/agenda/*` (public booking)
- **Appointments config**: `/empresa/{id}/configuracion/citas` (status changes, view, delete)
- **API endpoints**: Most AJAX calls follow `/negocios/{id}/agenda/citas-dia` pattern for public, `/empresa/{id}/*` for authenticated
