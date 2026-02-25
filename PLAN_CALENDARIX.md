# PLAN DE DESARROLLO — CALENDARIX
## Rediseño Visual + Expansión de Categorías + Nuevas Funcionalidades

**Versión:** 2.0  
**Fecha:** Febrero 2026  
**Stack:** Laravel 12 + Blade + Alpine.js + Tailwind CSS  
**Objetivo:** Transformar Calendarix de plataforma de belleza a marketplace universal de reservas de servicios, con identidad visual renovada según el brandbook oficial.

---

## ⚠️ REGLAS DE DISEÑO — LEER ANTES DE TOCAR CUALQUIER VISTA

### Referencia visual obligatoria: vagaro.com
El cliente tomó **vagaro.com** como sitio de referencia. Toda decisión de layout, componentes y UX debe estar alineada con ese estilo antes de aplicar la identidad de marca Calendarix.

### Qué significa esto en la práctica:

**Seguir de Vagaro:**
- Layout limpio y espacioso, fondo blanco predominante con secciones en gris muy claro (`#f8f8f8`)
- Navbar simple: logo izquierda, links centrales o derecha, CTA destacado
- Hero con imagen de fondo real (foto de ambiente) + overlay oscuro + texto blanco + search bar centrada
- Cards de negocios con foto grande (aspect ratio 3:2), nombre, categoría, rating y dirección — sin exceso de información
- Búsqueda con filtros en sidebar (desktop) o en top-bar horizontal
- Tipografía limpia, jerarquía clara, mucho whitespace
- Botones redondeados pero no pill extremo — `rounded-lg` a `rounded-xl`
- Transiciones y hovers sutiles, sin animaciones llamativas
- Footer oscuro (`#1a1a2e` o similar) con links organizados en columnas

**Aplicar de Calendarix (brandbook):**
- Color primario `#5a31d7` en CTAs principales, links activos, highlights
- Color secundario `#32ccbc` en badges, iconos de categoría, estados positivos
- `#ffa8d7` solo en badges de oferta/descuento
- Gradiente `#5a31d7 → #32ccbc` solo en el hero (overlay o banda decorativa) y banners CTA — NO en toda la página
- IBM Plex Sans como fuente en todo el sitio
- Logo Calendarix con sus reglas del brandbook (no deformar, no sombras)

**NO hacer:**
- No rediseñar desde cero si la vista actual ya sigue la línea de Vagaro
- No aplicar el gradiente morado-turquesa en toda la UI (solo en hero y banners específicos)
- No cambios bruscos en la estructura de páginas que ya funcionan bien visualmente
- No agregar animaciones complejas ni efectos que Vagaro no usa
- No usar cards con demasiados datos

---

## FILOSOFÍA DE DISEÑO — MUY IMPORTANTE LEER ANTES DE TOCAR CUALQUIER VISTA

**La estructura actual de calendarix.uy ya es la correcta y es la referencia.**
El sitio de inspiración estructural es **Vagaro (vagaro.com)** — mismo patrón:
navbar limpio → hero con search bar central → pills de categorías → grid de negocios → sección features → CTA para negocios → footer.

**Lo que NO se hace:**
- No se cambia la estructura de las páginas
- No se reubican secciones
- No se cambia el layout general
- No se agrega ni quita bloques que ya funcionan

**Lo que SÍ se hace:**
- Aplicar la paleta de colores del brandbook sobre la estética actual
- Cambiar la tipografía a IBM Plex Sans
- Ajustar colores de botones, badges, pills y acentos a los del manual de marca
- Actualizar textos que dicen "belleza" por lenguaje genérico (ej: "cualquier servicio")
- Agregar las nuevas categorías y subcategorías sin romper las existentes
- Mantener el mismo feeling visual limpio y profesional que tiene ahora

**Analogía para Claude Code:** Es como repintar una casa bien construida — misma estructura, nueva paleta. No derribar paredes.

---

## 1. IDENTIDAD VISUAL — BRANDBOOK (APLICAR EN TODAS LAS VISTAS)

### Paleta de colores
```css
/* Primarios */
--color-primary:     #5a31d7;  /* Morado profundo — principal */
--color-secondary:   #32ccbc;  /* Verde turquesa */
--color-accent:      #ffa8d7;  /* Lila rosado */

/* Complementarios */
--color-light-turq:  #90f7ec;
--color-light-lila:  #df8be8;
--color-gray:        #7c7c7c;

/* Gradiente */
--gradient-main: linear-gradient(135deg, #5a31d7, #32ccbc);
```

### Tipografía
- **Fuente principal:** IBM Plex Sans (Google Fonts)
- **Pesos:** 400 (Regular), 600 (SemiBold), 700 (Bold)
- Importar en `app.blade.php`: `@import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;600;700&display=swap')`

### Logo
- Usar versión horizontal en navbar (fondo blanco)
- Usar versión vertical o isotipo solo en móvil / favicon / splash
- NUNCA aplicar sombras, efectos, ni cambiar colores del logo

### Tailwind config — agregar al `tailwind.config.js`
```javascript
theme: {
  extend: {
    colors: {
      primary:   '#5a31d7',
      secondary: '#32ccbc',
      accent:    '#ffa8d7',
      lilac:     '#df8be8',
      'turq-light': '#90f7ec',
    },
    fontFamily: {
      sans: ['IBM Plex Sans', 'sans-serif'],
    },
  }
}
```

---

## 2. TAXONOMÍA COMPLETA DE CATEGORÍAS Y SUBCATEGORÍAS

### Categorías principales (con ícono sugerido de Heroicons/Lucide)

| Categoría   | Ícono sugerido      | Color de acento |
|-------------|---------------------|-----------------|
| Belleza     | `sparkles`          | `#df8be8`       |
| Bienestar   | `heart`             | `#32ccbc`       |
| Cuidados    | `shield-check`      | `#5a31d7`       |
| Fitness     | `bolt`              | `#ffa8d7`       |
| Deportes    | `trophy`            | `#90f7ec`       |
| Educación   | `academic-cap`      | `#5a31d7`       |
| Hogar       | `home`              | `#32ccbc`       |
| Mascotas    | `paw` (custom)      | `#df8be8`       |

### Subcategorías completas

#### 💜 Belleza
- Peluquería
- Barbería
- Uñas
- Depilación
- Maquillaje
- Cama Solar
- Tatuaje
- Peluquería Canina
- Micropigmentación
- Extensiones de cabello
- Lifting de pestañas

#### 🩵 Bienestar
- Spa
- Masajes
- Clínicas Estéticas
- Meditación
- Aromaterapia
- Terapia de flotación
- Reflexología

#### 🛡️ Cuidados
- Acupuntura
- Quiropráctico
- Nutricionista
- Coaching
- Fisioterapia
- Psicología
- Odontología
- Kinesiología
- Fonoaudiología
- Podología
- Osteopatía

#### ⚡ Fitness
- Yoga
- Gimnasio
- Entrenador Personal
- Pilates
- Ciclismo Indoor
- Baile
- CrossFit
- Natación
- Artes Marciales
- Zumba

#### 🏆 Deportes
- Cancha de Pádel
- Cancha de Fútbol 5
- Cancha de Tenis
- Cancha de Pickleball
- Cancha de Squash
- Cancha de Básquet
- Cancha de Vóley
- Pista de Atletismo
- Campo de Golf
- Piscina

#### 🎓 Educación *(nueva)*
- Clases de Idiomas
- Clases de Música
- Clases de Dibujo
- Tutorías
- Fotografía
- Cocina

#### 🏠 Hogar *(nueva)*
- Limpieza
- Plomería
- Electricista
- Carpintería
- Mudanzas
- Jardinería

#### 🐾 Mascotas *(nueva)*
- Veterinaria
- Guardería canina
- Paseo de perros
- Peluquería canina
- Adiestramiento

---

## 3. ESTRUCTURA DE VISTAS — MAPA COMPLETO

```
resources/views/
│
├── layouts/
│   ├── app.blade.php           # Layout público principal
│   ├── dashboard.blade.php     # Layout panel negocio
│   ├── admin.blade.php         # Layout panel admin
│   └── auth.blade.php          # Layout login/registro
│
├── components/
│   ├── navbar.blade.php
│   ├── footer.blade.php
│   ├── search-bar.blade.php
│   ├── business-card.blade.php
│   ├── service-card.blade.php
│   ├── category-pill.blade.php
│   ├── review-card.blade.php
│   ├── rating-stars.blade.php
│   ├── badge.blade.php
│   ├── alert.blade.php
│   └── modal.blade.php
│
├── public/                     # Vistas sin autenticación
│   ├── home.blade.php
│   ├── search.blade.php
│   ├── categories.blade.php
│   ├── category-detail.blade.php
│   ├── business-profile.blade.php
│   ├── booking-flow/
│   │   ├── step1-service.blade.php
│   │   ├── step2-datetime.blade.php
│   │   └── step3-confirm.blade.php
│   └── offers.blade.php
│
├── auth/
│   ├── login.blade.php
│   ├── register-client.blade.php
│   └── register-business.blade.php
│
├── client/                     # Panel del cliente
│   ├── dashboard.blade.php
│   ├── bookings/
│   │   ├── index.blade.php
│   │   ├── show.blade.php
│   │   └── reschedule.blade.php
│   ├── profile.blade.php
│   ├── favorites.blade.php
│   └── reviews/
│       └── create.blade.php
│
├── business/                   # Panel del negocio
│   ├── dashboard.blade.php
│   ├── services/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── edit.blade.php
│   ├── staff/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── schedules.blade.php
│   ├── bookings/
│   │   ├── index.blade.php
│   │   ├── calendar.blade.php
│   │   └── show.blade.php
│   ├── profile/
│   │   ├── edit.blade.php
│   │   └── gallery.blade.php
│   ├── reviews/
│   │   └── index.blade.php
│   └── reports/
│       └── index.blade.php
│
└── admin/                      # Panel administrador
    ├── dashboard.blade.php
    ├── businesses/
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── users/
    │   └── index.blade.php
    ├── categories/
    │   └── index.blade.php
    ├── bookings/
    │   └── index.blade.php
    └── reports/
        └── index.blade.php
```

---

## 4. REFERENCIA DE DISEÑO — VAGARO.COM

El cliente tomó **vagaro.com** como referencia de estructura y UX. Seguir su layout y flujo de navegación, aplicando encima la identidad visual del brandbook de Calendarix.

### Principio clave
> **Estructura = Vagaro. Identidad visual = Brandbook Calendarix.**
> No inventar layouts nuevos. Replicar la arquitectura de Vagaro con los colores, fuente y logo de Calendarix.

### Qué tomar de Vagaro

**Navbar:**
- Fondo blanco, sin color de fondo
- Logo a la izquierda
- Links de navegación en el centro (texto simple, sin mega-menu en mobile)
- Botones de acción a la derecha: login + CTA principal
- Sticky al hacer scroll con sombra sutil

**Hero:**
- Fondo limpio (blanco o muy claro), NO gradiente oscuro
- Título grande centrado, tipografía pesada
- Search bar prominente debajo del título: input de texto + ubicación + botón buscar
- El botón de búsqueda en color primario (`#5a31d7`)
- Imagen o collage de fotos a la derecha o como fondo suave (no bloquea el texto)
- NO usar gradiente oscuro que tape el texto — el héroe de Vagaro es limpio y claro

**Categorías:**
- Fila horizontal de pills/chips con scroll en móvil
- Cada pill: ícono pequeño + nombre de categoría
- Fondo neutro (blanco o gris muy claro), pills con borde sutil
- La pill activa/hover en color primario

**Cards de negocios:**
- Foto rectangular (ratio 4:3) con bordes redondeados
- Rating con estrellitas debajo del nombre
- Precio desde / rango de precios
- Dirección o barrio
- Sin sombras excesivas — sombra solo en hover

**Página de búsqueda / listado:**
- Sidebar de filtros a la izquierda (desktop) — sticky
- Grid de cards a la derecha (2-3 columnas)
- Mapa opcional a la derecha (toggle mostrar/ocultar)
- Header con "X resultados" + ordenar por

**Perfil de negocio:**
- Foto de portada full-width arriba
- Info del negocio en panel izquierdo (sticky en desktop)
- Servicios, horarios y reseñas en columna derecha
- Botón "Reservar" siempre visible (sticky en móvil)

**Flujo de reserva:**
- Modal o página dedicada — pasos en stepper horizontal
- Muy limpio, mucho espacio en blanco
- Resumen del pedido visible en todo momento

### Lo que NO hacer (diferencias con Vagaro)
- Vagaro usa colores muy neutros (grises, blancos) — Calendarix SÍ usa su paleta de marca (`#5a31d7`, `#32ccbc`) en acentos, botones y elementos destacados
- Vagaro tiene un footer muy denso — Calendarix puede tener uno más moderno y limpio
- Vagaro no tiene gradientes — Calendarix puede usar el gradiente de marca en el hero y CTAs pero de forma sutil

---

## 5. REDISEÑO DE VISTAS — ESPECIFICACIONES DETALLADAS

---

### 4.1 HOME (`public/home.blade.php`)

> ⚠️ La estructura actual YA ES LA CORRECTA — seguir el mismo orden de secciones.
> Solo aplicar cambios de color, tipografía y textos genéricos. No mover ni eliminar secciones.

**Cambios a aplicar sobre la estructura existente:**

1. **NAVBAR** *(ya existe — solo ajustar colores)*
   - Logo horizontal izquierda — sin cambios
   - Botón "Iniciar Sesión": outline con borde `#5a31d7`, texto `#5a31d7`
   - Botón "Registrar mi Negocio": fondo `#5a31d7`, texto blanco
   - Fondo blanco con `border-b border-gray-100` — igual que ahora

2. **HERO** *(ya existe — ajustar colores y texto)*
   - Fondo: gradiente `#5a31d7 → #32ccbc` (si actualmente es otro color, reemplazar)
   - Título: cambiar "Reserva tu próxima cita de belleza" → "Reserva cualquier servicio cerca de ti"
   - Subtítulo: quitar "salones, spas y profesionales de belleza" → "profesionales y negocios cerca de vos"
   - Botón "Buscar": color `#32ccbc` con texto blanco
   - Search bar: mantener misma estructura (texto + fecha + botón)

3. **PILLS DE CATEGORÍAS** *(ya existen — ampliar con nuevas categorías)*
   - Mantener el mismo componente pill actual
   - Color activo/hover: `#5a31d7` fondo, texto blanco
   - Color inactivo: `#f3f0ff` fondo, texto `#5a31d7`
   - Agregar las nuevas categorías principales al listado

4. **"POPULAR EN TU ZONA"** *(ya existe — solo ajustar colores de cards)*
   - Grid igual al actual
   - Badge "Nuevo": color `#32ccbc`
   - Badge descuento: color `#ffa8d7` texto oscuro
   - Hover en card: borde sutil `#5a31d7`

5. **"¿POR QUÉ CALENDARIX?"** *(ya existe — ajustar íconos y colores)*
   - Íconos: color `#32ccbc`
   - Títulos de features: `#5a31d7`

6. **CTA PARA NEGOCIOS** *(ya existe — ajustar colores)*
   - Fondo: gradiente `#5a31d7 → #32ccbc` o fondo `#5a31d7` sólido
   - Botón: fondo blanco, texto `#5a31d7`
   - Texto: cambiar "negocio de belleza" → "negocio"

7. **FOOTER** *(ya existe — sin cambios estructurales)*
   - Solo ajustar colores de links hover a `#32ccbc`

---

### 4.2 BÚSQUEDA (`public/search.blade.php`)

- **Sidebar filtros** (desktop) / **Bottom sheet** (móvil):
  - Categoría (select con las 8 categorías)
  - Subcategoría (select dinámico según categoría)
  - Fecha (date picker)
  - Precio (rango slider)
  - Calificación mínima (stars)
  - Distancia (si geolocalización activa)
  
- **Área de resultados:**
  - Header con "X resultados para [término]"
  - Sort: Relevancia | Precio | Rating | Distancia
  - Grid de `business-card` con paginación

- **Estado vacío:** ilustración + sugerencias de búsqueda

---

### 4.3 CATEGORÍAS (`public/categories.blade.php`)

- Grid de las 8 categorías principales con imagen de fondo, ícono y nombre
- Al click va a `/categoria/{slug}`

### 4.4 DETALLE DE CATEGORÍA (`public/category-detail.blade.php`)

- Header con nombre de categoría + pills de subcategorías
- Filtro de subcategoría activo resaltado en `#5a31d7`
- Grid de negocios de esa categoría

---

### 4.5 PERFIL DE NEGOCIO (`public/business-profile.blade.php`)

**Secciones:**
1. **Header** — foto de portada + logo/foto perfil flotante + nombre + categoría + rating
2. **Info básica** — dirección, teléfono, horarios, redes
3. **Galería** — grid de fotos del negocio
4. **Servicios** — lista con nombre, duración, precio, botón "Reservar"
5. **Equipo** — cards del staff con nombre y especialidad
6. **Reseñas** — promedio visual (barra por estrellas) + lista de reviews
7. **CTA flotante** en móvil: botón "Reservar" sticky bottom

---

### 4.6 FLUJO DE RESERVA (3 pasos)

#### Paso 1 — Elegir Servicio (`booking-flow/step1-service.blade.php`)
- Lista de servicios del negocio con: nombre, duración, precio
- Selector de profesional (si aplica) — opcional "Sin preferencia"
- Stepper visual en top: [1. Servicio] → [2. Fecha/Hora] → [3. Confirmar]

#### Paso 2 — Elegir Fecha y Hora (`booking-flow/step2-datetime.blade.php`)
- Calendario visual (mes) con días disponibles marcados en `#5a31d7`
- Slots de hora en grid (cada slot disponible / ocupado / seleccionado)
- Resumen lateral del servicio elegido

#### Paso 3 — Confirmar (`booking-flow/step3-confirm.blade.php`)
- Resumen completo: negocio, servicio, profesional, fecha, hora, precio
- Formulario si no está logueado (nombre, email, teléfono)
- Sección de pago (si aplica)
- Botón "Confirmar Reserva" en `#5a31d7`
- Política de cancelación visible

---

### 4.7 PANEL DEL CLIENTE

#### Dashboard (`client/dashboard.blade.php`)
- Saludo personalizado
- Próximas citas (cards con countdown)
- Últimas citas (historial reciente)
- Negocios favoritos
- Acceso rápido a: Mis Reservas | Mis Reseñas | Favoritos | Perfil

#### Mis Reservas (`client/bookings/index.blade.php`)
- Tabs: Próximas | Pasadas | Canceladas
- Cada reserva: negocio, servicio, fecha, estado (badge color), acciones (cancelar / reprogramar / dejar reseña)

#### Perfil (`client/profile.blade.php`)
- Foto de perfil
- Datos personales (nombre, email, teléfono, dirección)
- Cambio de contraseña
- Preferencias de notificación (email / WhatsApp)

---

### 4.8 PANEL DEL NEGOCIO

#### Dashboard (`business/dashboard.blade.php`)
- KPIs en cards: Reservas hoy | Reservas semana | Ingresos mes | Rating promedio
- Calendario de hoy (vista día con citas)
- Últimas reservas (tabla)
- Acciones rápidas: + Nueva cita manual | Ver calendario completo

#### Gestión de Servicios (`business/services/index.blade.php`)
- Tabla con: nombre, duración, precio, estado (activo/inactivo), acciones
- Botón "+ Agregar servicio"
- Form de creación/edición: nombre, descripción, duración, precio, categoría/subcategoría, profesionales asignados, imagen

#### Gestión de Staff (`business/staff/index.blade.php`)
- Cards del equipo con foto, nombre, especialidades
- Botón "+ Agregar profesional"
- Horarios por profesional: grilla semanal de disponibilidad

#### Calendario de Citas (`business/bookings/calendar.blade.php`)
- Vista mensual / semanal / diaria
- Color por estado: confirmada (verde) / pendiente (amarillo) / cancelada (rojo)
- Click en cita: modal con detalle y acciones

#### Perfil del Negocio (`business/profile/edit.blade.php`)
- Datos: nombre, descripción, categoría, subcategorías (multi-select), dirección, teléfono, email, web, redes
- Horarios de atención por día
- Galería de fotos (upload drag & drop)
- Configuración de reservas: tiempo mínimo anticipación, política cancelación, máx. reservas simultáneas

#### Reseñas (`business/reviews/index.blade.php`)
- Promedio general + distribución por estrellas
- Lista de reviews con opción de responder
- Reviews pendientes de respuesta destacadas

#### Reportes (`business/reports/index.blade.php`)
- Filtro por período
- Gráfico de reservas por día/semana
- Ingresos por servicio
- Clientes nuevos vs. recurrentes
- Exportar a CSV

---

### 4.9 PANEL ADMIN

#### Dashboard (`admin/dashboard.blade.php`)
- Métricas globales: Total negocios | Total usuarios | Reservas hoy | Ingresos plataforma
- Gráficos: reservas por categoría, crecimiento de negocios

#### Gestión de Negocios (`admin/businesses/index.blade.php`)
- Tabla: nombre, categoría, ciudad, estado (activo/pendiente/suspendido), fecha registro
- Filtros por estado y categoría
- Acciones: ver detalle, aprobar, suspender

#### Gestión de Usuarios (`admin/users/index.blade.php`)
- Tabla con filtros por rol (cliente / negocio / admin)
- Acciones: ver, suspender, cambiar rol

#### Gestión de Categorías (`admin/categories/index.blade.php`)
- CRUD de categorías principales y subcategorías
- Orden, ícono, color, estado activo/inactivo

---

## 5. COMPONENTES REUTILIZABLES — ESPECIFICACIONES

### `business-card`
```html
<!-- Uso: <x-business-card :business="$business" /> -->
- Foto de portada (aspect 4:3 con object-cover)
- Badge condicional: "Nuevo" (turquesa) o "-20%" (accent pink)
- Nombre del negocio (font-semibold)
- Subcategoría en pill pequeño
- Rating con estrellitas + cantidad de reviews
- Dirección con ícono de pin
- Hover: scale-105 + sombra primary
```

### `category-pill`
```html
<!-- Uso: <x-category-pill :category="$cat" :active="$active" /> -->
- Fondo: active → #5a31d7 text-white | inactive → #f3f0ff text-primary
- Ícono SVG + texto
- Transición suave de colores
```

### `rating-stars`
```html
<!-- Uso: <x-rating-stars :rating="4.7" :count="127" /> -->
- 5 estrellas: filled en #32ccbc, empty en gray-200
- Número decimal + cantidad en paréntesis
```

---

## 6. FUNCIONALIDADES NUEVAS A DESARROLLAR

> ⚠️ REGLA GENERAL: Antes de crear algo nuevo, verificar si ya existe en el código.
> Si existe, ajustar. Si no existe, crear siguiendo la estética actual del sitio.

### Prioridad ALTA (semanas 1-2) — Ajustes visuales y categorías
- [ ] Agregar colores del brandbook a `tailwind.config.js`
- [ ] Importar IBM Plex Sans en `app.blade.php`
- [ ] Reemplazar colores actuales de botones, pills y badges por los del manual de marca
- [ ] Migración `categories`: agregar `parent_id`, `icon`, `color`
- [ ] Seeder completo con todas las categorías y subcategorías nuevas
- [ ] Actualizar textos de "belleza" a lenguaje genérico en home y navbar
- [ ] Ampliar pills de categorías en home con las nuevas categorías principales

### Prioridad ALTA (semanas 3-4)
- [ ] Flujo de reserva en 3 pasos (rediseño visual completo)
- [ ] Panel de negocio — gestión de servicios con subcategoría
- [ ] Panel de negocio — calendario de citas mejorado
- [ ] Panel de cliente — dashboard y mis reservas rediseñados

### Prioridad MEDIA (semanas 5-6)
- [ ] Sistema de reseñas y calificaciones (cliente puede dejar reseña post-cita)
- [ ] Negocios pueden responder reseñas
- [ ] Galería de fotos para negocios
- [ ] Perfil de staff/profesionales

### Prioridad MEDIA (semana 7)
- [ ] Notificaciones por email (confirmación, recordatorio 24h antes, cancelación)
- [ ] Sistema de favoritos (cliente puede guardar negocios)
- [ ] Ofertas del día — negocios pueden crear descuentos temporales

### Prioridad BAJA (futuro)
- [ ] Integración WhatsApp para recordatorios
- [ ] Pagos en línea (MercadoPago para Uruguay/Argentina, Wompi para Colombia)
- [ ] Geolocalización y búsqueda por radio de distancia
- [ ] App móvil PWA
- [ ] Panel de reportes avanzados para negocios
- [ ] Sistema de planes/suscripción para negocios (Free / Pro / Enterprise)

---

## 7. MODELOS Y BASE DE DATOS — CAMBIOS REQUERIDOS

### Tablas a modificar

**`categories`** — agregar campo `parent_id` (nullable, FK self-referencial)
```sql
ALTER TABLE categories ADD COLUMN parent_id BIGINT UNSIGNED NULL;
ALTER TABLE categories ADD COLUMN icon VARCHAR(100) NULL;
ALTER TABLE categories ADD COLUMN color VARCHAR(20) NULL;
ALTER TABLE categories ADD FOREIGN KEY (parent_id) REFERENCES categories(id);
```

**`businesses`** — asegurar campos:
- `subcategory_id` (FK a categories)
- `cover_photo`
- `logo`
- `cancellation_policy`
- `min_advance_booking` (minutos mínimos de anticipación)

**Nueva tabla `business_photos`:**
```sql
id, business_id, url, order, created_at
```

**Nueva tabla `staff`:**
```sql
id, business_id, name, photo, bio, specialties (JSON), created_at
```

**Nueva tabla `staff_schedules`:**
```sql
id, staff_id, day_of_week (0-6), start_time, end_time, is_available
```

**Nueva tabla `favorites`:**
```sql
id, user_id, business_id, created_at
```

**Nueva tabla `offers`:**
```sql
id, business_id, service_id, discount_percent, valid_from, valid_until, active
```

---

## 8. INSTRUCCIONES PARA CLAUDE CODE

Cuando trabajes en este proyecto, seguí estas convenciones:

1. **Tailwind:** Usar las clases custom `text-primary`, `bg-primary`, `border-secondary` definidas en `tailwind.config.js`
2. **Componentes Blade:** Todo elemento reutilizable va en `resources/views/components/`
3. **Sin comentarios en el código** (preferencia del desarrollador)
4. **Alpine.js** para interactividad ligera (modales, tabs, dropdowns) sin necesidad de Vue/React
5. **Imágenes:** Usar `storage/app/public` con symlink, no `public/images` directo
6. **Validaciones:** Siempre con Form Requests en `app/Http/Requests/`
7. **Nomenclatura de rutas:** kebab-case. Ej: `business.services.index`, `client.bookings.store`
8. **Responsive first:** Diseñar mobile-first con Tailwind breakpoints `md:` y `lg:`

---

## 9. NOTAS DE DISEÑO VISUAL IMPORTANTES

- El gradiente `#5a31d7 → #32ccbc` es el elemento visual más distintivo de la marca — usarlo en heroes, banners CTA y elementos de acento
- Los botones primarios van en `#5a31d7` con hover más oscuro
- Los botones secundarios / de acción positiva van en `#32ccbc`
- Los badges de oferta / precio van en `#ffa8d7`
- Evitar usar verde o rojo puro para estados — adaptar a la paleta de marca (turquesa para éxito, lila para advertencia)
- Bordes redondeados generosos: `rounded-xl` para cards, `rounded-full` para pills y avatares
- Sombras suaves, nunca duras: `shadow-sm` por defecto, `shadow-md` en hover
- Espaciado generoso: `p-6` mínimo en cards, `gap-6` en grids

---

*Documento generado como contexto para Claude Code — Calendarix v2.0*