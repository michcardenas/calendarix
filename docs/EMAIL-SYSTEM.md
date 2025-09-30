# Sistema de Emails Global - Calendarix

Sistema de notificaciones por email flexible y reutilizable para todo tipo de eventos en la plataforma.

## 📧 Componentes

### 1. Mailable Genérico
**Ubicación**: `app/Mail/NotificacionGeneral.php`

Mailable que acepta parámetros dinámicos para construir cualquier tipo de notificación.

### 2. Vista de Email
**Ubicación**: `resources/views/emails/notificacion-general.blade.php`

Plantilla HTML responsive con diseño moderno que se adapta automáticamente al contenido.

## 🎯 Casos de Uso Implementados

### 🎉 Registro de Usuario

**Ubicación**: `RegisteredUserController@store()`

**Cuándo se envía**: Cuando un nuevo usuario se registra en la plataforma

**Destinatarios**:
- **Usuario nuevo**: Email de bienvenida

**Detalles enviados**: Nombre, email, fecha de registro

### ✅ Notificación de Nueva Cita

**Ubicación**: `AgendaController@enviarEmailsCita()`

**Cuándo se envía**: Cuando un cliente agenda una cita en un negocio

**Destinatarios**:
- **Cliente**: Confirmación de su cita
- **Dueño del negocio**: Notificación de nueva cita

**Ejemplo**:
```php
// En AgendaController::store()
$this->enviarEmailsCita($cita, $servicio, $trabajador, $negocioId);
```

### 🛒 Notificación de Nuevo Pedido

**Ubicación**: `CheckoutController@enviarEmailsPedido()`

**Cuándo se envía**: Cuando un cliente confirma su pedido

**Destinatarios**:
- **Cliente**: Confirmación de pedido recibido
- **Dueño del negocio**: Notificación de nuevo pedido a procesar

**Ejemplo**:
```php
// En CheckoutController::guardarDatos()
$this->enviarEmailsPedido($pedido);
```

### 🔐 Restablecimiento de Contraseña

**Ubicación**: `NewPasswordController@store()`

**Cuándo se envía**: Cuando un usuario restablece su contraseña exitosamente

**Destinatarios**:
- **Usuario**: Confirmación de cambio de contraseña con detalles de seguridad

**Detalles enviados**: Nombre, email, fecha/hora del cambio, dirección IP

### ✅ Verificación de Email

**Ubicación**: `VerifyEmailController@__invoke()`

**Cuándo se envía**: Cuando un usuario verifica su dirección de email

**Destinatarios**:
- **Usuario**: Confirmación de verificación exitosa

**Detalles enviados**: Nombre, email, fecha de verificación

## 🚀 Cómo Usar el Sistema

### Ejemplo Básico

```php
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionGeneral;

Mail::to('cliente@example.com')->send(new NotificacionGeneral(
    asunto: 'Título del Email',
    titulo: 'Encabezado Principal',
    mensaje: 'Mensaje descriptivo del email.',
    detalles: [
        'Campo 1' => 'Valor 1',
        'Campo 2' => 'Valor 2',
    ],
    accionTexto: 'Texto del Botón',
    accionUrl: 'https://example.com/accion',
    tipoIcono: 'success'
));
```

### Parámetros Disponibles

| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `asunto` | string | ✅ | Asunto del email |
| `titulo` | string | ✅ | Título principal visible en el email |
| `mensaje` | string | ✅ | Mensaje descriptivo principal |
| `detalles` | array | ❌ | Array asociativo con detalles adicionales |
| `accionTexto` | string | ❌ | Texto del botón de acción |
| `accionUrl` | string | ❌ | URL a la que redirige el botón |
| `tipoIcono` | string | ❌ | Tipo de ícono: `success`, `info`, `warning`, `error` |
| `colorIcono` | string | ❌ | Color personalizado en formato hex (ej: `#6366f1`) |

### Tipos de Íconos y Colores

| Tipo | Ícono | Color por Defecto | Uso Recomendado |
|------|-------|-------------------|-----------------|
| `success` | ✅ | Verde (#10b981) | Confirmaciones, operaciones exitosas |
| `info` | 📬 | Índigo (#6366f1) | Notificaciones informativas |
| `warning` | ⚠️ | Naranja (#f59e0b) | Advertencias, acciones pendientes |
| `error` | ❌ | Rojo (#ef4444) | Errores, cancelaciones |

## 📝 Ejemplos Completos

### Email de Bienvenida (Registro)

```php
Mail::to($user->email)->send(new NotificacionGeneral(
    asunto: '¡Bienvenido a Calendarix!',
    titulo: '¡Gracias por registrarte!',
    mensaje: 'Tu cuenta ha sido creada exitosamente. Ahora puedes explorar negocios, agendar citas y realizar pedidos.',
    detalles: [
        'Nombre' => $user->name,
        'Email' => $user->email,
        'Fecha de registro' => now()->format('d/m/Y H:i'),
    ],
    accionTexto: 'Ir al Dashboard',
    accionUrl: url('/dashboard'),
    tipoIcono: 'success'
));
```

### Email de Contraseña Restablecida

```php
Mail::to($user->email)->send(new NotificacionGeneral(
    asunto: '✅ Contraseña Restablecida',
    titulo: 'Tu contraseña ha sido cambiada',
    mensaje: 'Tu contraseña ha sido actualizada exitosamente. Si no fuiste tú quien realizó este cambio, contacta con soporte inmediatamente.',
    detalles: [
        'Nombre' => $user->name,
        'Email' => $user->email,
        'Fecha del cambio' => now()->format('d/m/Y H:i'),
        'Dirección IP' => $request->ip(),
    ],
    accionTexto: 'Iniciar Sesión',
    accionUrl: route('login'),
    tipoIcono: 'success'
));
```

### Notificación de Confirmación de Cita

```php
Mail::to($cliente->email)->send(new NotificacionGeneral(
    asunto: '✅ Cita Confirmada - Spa Relax',
    titulo: '¡Tu cita ha sido agendada!',
    mensaje: 'Hemos confirmado tu cita exitosamente. Te esperamos en la fecha y hora indicada.',
    detalles: [
        'Negocio' => 'Spa Relax',
        'Servicio' => 'Masaje Relajante',
        'Trabajador' => 'María González',
        'Fecha' => '15 de enero de 2025',
        'Hora' => '10:00 - 11:30',
        'Estado' => 'Pendiente de confirmación',
    ],
    accionTexto: 'Ver mis citas',
    accionUrl: url('/dashboard-cliente'),
    tipoIcono: 'success'
));
```

### Notificación al Negocio de Nuevo Pedido

```php
Mail::to($dueno->email)->send(new NotificacionGeneral(
    asunto: '🛒 Nuevo Pedido - #' . $pedido->id,
    titulo: '¡Tienes un nuevo pedido!',
    mensaje: 'Un cliente ha realizado un pedido en tu negocio. Revisa los detalles y confirma la disponibilidad.',
    detalles: [
        'Pedido #' => $pedido->id,
        'Cliente' => 'Juan Pérez',
        'Teléfono' => '+57 300 123 4567',
        'Email' => 'juan@example.com',
        'Productos' => "Producto A × 2 = $50.000\nProducto B × 1 = $25.000",
        'Total' => '$75.000',
        'Dirección de entrega' => 'Calle 123 #45-67, Bogotá',
    ],
    accionTexto: 'Ver pedido',
    accionUrl: route('empresa.dashboard', $negocio->id),
    tipoIcono: 'info'
));
```

### Notificación de Cambio de Estado

```php
Mail::to($cliente->email)->send(new NotificacionGeneral(
    asunto: '📦 Tu pedido ha sido despachado',
    titulo: '¡Tu pedido está en camino!',
    mensaje: 'Hemos despachado tu pedido. Pronto lo recibirás en tu domicilio.',
    detalles: [
        'Pedido #' => '12345',
        'Fecha de despacho' => '10 de enero de 2025',
        'Transportadora' => 'Envíos Express',
        'Número de seguimiento' => 'ABC123XYZ',
        'Tiempo estimado' => '2-3 días hábiles',
    ],
    accionTexto: 'Rastrear pedido',
    accionUrl: 'https://tracking.example.com/ABC123XYZ',
    tipoIcono: 'info'
));
```

### Recordatorio de Cita

```php
Mail::to($cliente->email)->send(new NotificacionGeneral(
    asunto: '⏰ Recordatorio: Tienes una cita mañana',
    titulo: 'Recordatorio de tu cita',
    mensaje: 'Te recordamos que tienes una cita programada para mañana. ¡No olvides asistir!',
    detalles: [
        'Negocio' => 'Clínica Dental Sonrisas',
        'Servicio' => 'Limpieza Dental',
        'Fecha' => 'Mañana, 16 de enero',
        'Hora' => '14:00',
        'Dirección' => 'Av. Principal #123',
    ],
    accionTexto: 'Ver detalles',
    accionUrl: url('/dashboard-cliente'),
    tipoIcono: 'warning'
));
```

## ⚙️ Configuración

### Variables de Entorno Requeridas

En tu archivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username
MAIL_PASSWORD=tu_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@calendarix.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Para Desarrollo (Mailtrap)

Mailtrap es ideal para testing:
1. Crea cuenta en https://mailtrap.io
2. Copia las credenciales SMTP
3. Pégalas en tu `.env`

### Para Producción

Opciones recomendadas:
- **SendGrid**: Hasta 100 emails/día gratis
- **Amazon SES**: $0.10 por cada 1000 emails
- **Mailgun**: Hasta 5000 emails/mes gratis
- **Gmail SMTP**: Hasta 500 emails/día (no recomendado para producción)

## 🎨 Personalización

### Cambiar Colores Globales

Edita `resources/views/emails/notificacion-general.blade.php`:

```php
// Cambiar color del header
background: linear-gradient(135deg, {{ $colorIcono }}, {{ $colorIcono }}dd);
```

### Agregar Logo del Negocio

```blade
<div class="email-icon">
    @if(isset($logoUrl))
        <img src="{{ $logoUrl }}" alt="Logo" style="width: 60px; height: 60px; border-radius: 50%;">
    @else
        @switch($tipoIcono)
            ...
        @endswitch
    @endif
</div>
```

## 🔒 Seguridad

- ❌ **Nunca** incluyas información sensible (contraseñas, tokens) en emails
- ✅ Usa tokens firmados para acciones críticas (confirmación, cancelación)
- ✅ Valida siempre los destinatarios antes de enviar
- ✅ Implementa rate limiting para prevenir spam
- ✅ Logs de todos los envíos (ya implementado)

## 📊 Monitoreo

Los emails se loguean automáticamente:

```php
// Éxito
Log::info('Emails de cita enviados', ['cita_id' => $cita->id]);

// Error
Log::error('Error al enviar emails', ['error' => $e->getMessage()]);
```

Ver logs:
```bash
php artisan pail
# o
tail -f storage/logs/laravel.log
```

## 🚀 Cola de Emails (Opcional)

Para enviar emails en segundo plano:

1. Implementa `ShouldQueue` en el Mailable:
```php
class NotificacionGeneral extends Mailable implements ShouldQueue
```

2. Inicia el worker de colas:
```bash
php artisan queue:work
```

## 🧪 Testing

Para probar el envío:

```php
// En tinker
php artisan tinker

use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionGeneral;

Mail::to('test@example.com')->send(new NotificacionGeneral(
    asunto: 'Prueba',
    titulo: 'Email de Prueba',
    mensaje: 'Este es un email de prueba.',
    detalles: ['Test' => 'Valor de prueba'],
    tipoIcono: 'info'
));
```

## 📚 Recursos

- [Laravel Mail Documentation](https://laravel.com/docs/mail)
- [Markdown Mailable Styling](https://laravel.com/docs/mail#customizing-the-components)
- [Email Testing Best Practices](https://mailtrap.io/blog/laravel-testing/)

---

**Última actualización**: Enero 2025
**Autor**: Sistema Calendarix
**Versión**: 1.0.0