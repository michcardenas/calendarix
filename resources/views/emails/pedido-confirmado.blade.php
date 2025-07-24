<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Pedido</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f5ff;
            color: #333;
            padding: 30px;
        }

        .container {
            background: white;
            max-width: 600px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(98, 116, 201, 0.1);
            overflow: hidden;
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #6274c9;
            margin-bottom: 20px;
        }

        p {
            margin: 6px 0;
        }

        .resumen {
            background-color: #f0f3ff;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }

        .resumen ul {
            padding-left: 18px;
        }

        .resumen li {
            margin-bottom: 8px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>¡Gracias por tu pedido, {{ $pedido->nombre }}!</h2>

        <p><strong>Correo:</strong> {{ $pedido->email }}</p>
        <p><strong>Teléfono:</strong> {{ $pedido->telefono }}</p>
        <p><strong>Dirección:</strong> {{ $pedido->direccion }}</p>

        <div class="resumen">
            <h3 style="margin-bottom: 10px;">Resumen del pedido:</h3>
            <ul>
                @foreach($pedido->detalles as $detalle)
                    <li>
                        {{ $detalle->producto->nombre ?? $detalle->servicio->nombre }}
                        × {{ $detalle->cantidad }} →
                        <strong>${{ number_format($detalle->precio_total, 0, ',', '.') }}</strong>
                    </li>
                @endforeach
            </ul>
            <p><strong>Total:</strong> ${{ number_format($pedido->total, 0, ',', '.') }}</p>
        </div>

        <div class="footer">
            Este correo fue generado automáticamente por Calendarix.  
            <br>Si tienes dudas, contáctanos a soporte@calendarix.com
        </div>
    </div>
</body>
</html>
