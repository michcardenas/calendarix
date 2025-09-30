<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $asunto }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f6f5f7 0%, #e8e6f0 100%);
            padding: 40px 20px;
            line-height: 1.6;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(98, 116, 201, 0.12);
            overflow: hidden;
        }

        .email-header {
            background: linear-gradient(135deg, {{ $colorIcono }}, {{ $colorIcono }}dd);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .email-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }

        .email-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            position: relative;
            z-index: 1;
        }

        .email-title {
            color: #ffffff;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .email-subtitle {
            color: rgba(255, 255, 255, 0.95);
            font-size: 15px;
            position: relative;
            z-index: 1;
        }

        .email-body {
            padding: 40px 30px;
        }

        .email-message {
            color: #374151;
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.7;
        }

        .email-details {
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 30px;
            border-left: 4px solid {{ $colorIcono }};
        }

        .email-details-title {
            color: #1f2937;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
        }

        .email-details-title::before {
            content: '📋';
            margin-right: 8px;
            font-size: 16px;
        }

        .detail-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid rgba(156, 163, 175, 0.15);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #6b7280;
            min-width: 140px;
            font-size: 14px;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 500;
            flex: 1;
            font-size: 14px;
        }

        .email-action {
            text-align: center;
            margin: 30px 0;
        }

        .email-button {
            display: inline-block;
            background: linear-gradient(135deg, {{ $colorIcono }}, {{ $colorIcono }}dd);
            color: #ffffff;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 4px 15px rgba(98, 116, 201, 0.3);
            transition: all 0.3s ease;
        }

        .email-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(98, 116, 201, 0.4);
        }

        .email-footer {
            background: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .email-footer-text {
            color: #9ca3af;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .email-footer-brand {
            color: #6b7280;
            font-weight: 600;
            font-size: 14px;
            margin-top: 12px;
        }

        .email-footer-brand span {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                border-radius: 0;
            }

            .email-header {
                padding: 30px 20px;
            }

            .email-body {
                padding: 30px 20px;
            }

            .email-title {
                font-size: 22px;
            }

            .detail-row {
                flex-direction: column;
            }

            .detail-label {
                margin-bottom: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header con ícono -->
        <div class="email-header">
            <div class="email-icon">
                @switch($tipoIcono)
                    @case('success')
                        ✅
                        @break
                    @case('warning')
                        ⚠️
                        @break
                    @case('error')
                        ❌
                        @break
                    @default
                        📬
                @endswitch
            </div>
            <h1 class="email-title">{{ $titulo }}</h1>
            <p class="email-subtitle">{{ $asunto }}</p>
        </div>

        <!-- Cuerpo del email -->
        <div class="email-body">
            <p class="email-message">{{ $mensaje }}</p>

            <!-- Detalles (si existen) -->
            @if(!empty($detalles))
            <div class="email-details">
                <div class="email-details-title">Detalles</div>
                @foreach($detalles as $label => $valor)
                <div class="detail-row">
                    <div class="detail-label">{{ $label }}:</div>
                    <div class="detail-value">{{ $valor }}</div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- Botón de acción (si existe) -->
            @if($accionTexto && $accionUrl)
            <div class="email-action">
                <a href="{{ $accionUrl }}" class="email-button">{{ $accionTexto }}</a>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p class="email-footer-text">
                Este correo fue generado automáticamente por el sistema.
            </p>
            <p class="email-footer-text">
                Si tienes alguna duda, por favor responde a este correo.
            </p>
            <p class="email-footer-brand">
                Enviado con <span>Calendarix</span> 💜
            </p>
        </div>
    </div>
</body>
</html>