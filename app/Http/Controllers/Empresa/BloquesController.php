<?php
// app/Http/Controllers/Empresa/BloquesController.php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Negocio;

class BloquesController extends Controller
{
    public function mostrarBloque($tipo, Request $request)
    {
        try {
            $negocioId = $request->get('negocio_id');
            
            if (!$negocioId) {
                return response('<div class="alert alert-danger">Error: negocio_id requerido</div>', 400);
            }
            
            $negocio = Negocio::with('servicios')->find($negocioId);
            
            if (!$negocio) {
                return response('<div class="alert alert-danger">Error: Negocio no encontrado</div>', 404);
            }
            
            switch ($tipo) {
                case 'servicios':
                    return $this->bloqueServicios($negocio);
                    
                case 'galeria':
                    return $this->bloqueGaleria($negocio);
                    
                case 'horario':
                    return $this->bloqueHorario($negocio);
                    
                case 'ubicacion':
                    return $this->bloqueUbicacion($negocio);
                    
                case 'contacto':
                    return $this->bloqueContacto($negocio);
                    
                default:
                    return response('<div class="alert alert-warning">Tipo de bloque "' . $tipo . '" no soportado</div>', 400);
            }
            
        } catch (\Exception $e) {
            \Log::error('Error en BloquesController: ' . $e->getMessage());
            return response('<div class="alert alert-danger">Error interno: ' . $e->getMessage() . '</div>', 500);
        }
    }
    
    private function bloqueServicios($negocio)
    {
        $html = '
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fa-solid fa-concierge-bell me-2"></i>
                <strong>Servicios que ofreces</strong>
                <button type="button" class="btn btn-sm btn-light ms-auto" onclick="eliminarBloque(this)">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="card-body">';
        
        if ($negocio->servicios && $negocio->servicios->count() > 0) {
            $html .= '<div class="row">';
            foreach ($negocio->servicios as $servicio) {
                $precio = number_format($servicio->precio, 0, ',', '.');
                $html .= '
                    <div class="col-md-6 mb-3">
                        <div class="card border-light">
                            <div class="card-body p-3">
                                <h6 class="card-title text-primary mb-2">
                                    <i class="fa fa-check-circle text-success me-1"></i>
                                    ' . htmlspecialchars($servicio->nombre) . '
                                </h6>
                                <p class="card-text text-muted small mb-2">' . htmlspecialchars($servicio->descripcion ?? '') . '</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">$' . $precio . '</span>
                                    <small class="text-muted">COP</small>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
            $html .= '</div>';
            
            $html .= '
                <div class="text-center mt-3">
                    <a href="/empresa/' . $negocio->id . '/servicios" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-edit"></i> Editar servicios
                    </a>
                </div>';
        } else {
            $html .= '
                <div class="text-center py-4">
                    <i class="fa fa-exclamation-circle text-warning fa-2x mb-2"></i>
                    <p class="text-muted">No tienes servicios registrados aún</p>
                    <a href="/empresa/' . $negocio->id . '/servicios" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Agregar servicios
                    </a>
                </div>';
        }
        
        $html .= '
            </div>
        </div>';
        
        return response($html);
    }
    
    private function bloqueGaleria($negocio)
    {
        $html = '
        <div class="card shadow-sm">
            <div class="card-header bg-info text-white d-flex align-items-center">
                <i class="fa-solid fa-images me-2"></i>
                <strong>Galería de Fotos</strong>
                <button type="button" class="btn btn-sm btn-light ms-auto" onclick="eliminarBloque(this)">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">';
        
        for ($i = 1; $i <= 6; $i++) {
            $html .= '
                <div class="col-4 mb-2">
                    <div class="placeholder-img bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px; border: 2px dashed #ddd;">
                        <i class="fa fa-image text-muted"></i>
                    </div>
                </div>';
        }
        
        $html .= '
                </div>
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-upload"></i> Subir fotos
                    </button>
                </div>
            </div>
        </div>';
        
        return response($html);
    }
    
    private function bloqueHorario($negocio)
    {
        $html = '
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark d-flex align-items-center">
                <i class="fa-solid fa-clock me-2"></i>
                <strong>Horarios de Atención</strong>
                <button type="button" class="btn btn-sm btn-dark ms-auto" onclick="eliminarBloque(this)">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <h6>📅 Lunes - Viernes</h6>
                        <p class="mb-1">🕘 9:00 AM - 6:00 PM</p>
                    </div>
                    <div class="col-6">
                        <h6>📅 Sábados</h6>
                        <p class="mb-1">🕘 9:00 AM - 2:00 PM</p>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-outline-warning btn-sm">
                        <i class="fa fa-edit"></i> Editar horarios
                    </button>
                </div>
            </div>
        </div>';
        
        return response($html);
    }
    
    private function bloqueUbicacion($negocio)
    {
        // CORRECCIÓN: Usar los campos correctos con prefijo neg_
        $direccion = $negocio->neg_direccion ?? 'Dirección no registrada';
        $pais = $negocio->neg_pais ?? 'País no registrado';
        
        $html = '
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white d-flex align-items-center">
                <i class="fa-solid fa-map-marker-alt me-2"></i>
                <strong>Ubicación</strong>
                <button type="button" class="btn btn-sm btn-light ms-auto" onclick="eliminarBloque(this)">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p class="mb-1"><i class="fa fa-map-marker text-danger me-2"></i>' . htmlspecialchars($direccion) . '</p>
                    <p class="mb-1"><i class="fa fa-globe text-primary me-2"></i>' . htmlspecialchars($pais) . '</p>
                </div>
                
                <div class="bg-light rounded p-3 text-center">
                    <i class="fa fa-map fa-2x text-muted mb-2"></i>
                    <p class="text-muted small">Aquí aparecerá el mapa interactivo</p>
                </div>
                
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-edit"></i> Actualizar ubicación
                    </button>
                </div>
            </div>
        </div>';
        
        return response($html);
    }
    
    private function bloqueContacto($negocio)
    {
        // CORRECCIÓN: Usar los campos correctos con prefijo neg_
        $telefono = $negocio->neg_telefono ?? 'No registrado';
        $email = $negocio->neg_email ?? 'No registrado';
        $sitioWeb = $negocio->neg_sitio_web ?? 'No registrado';
        
        $html = '
        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white d-flex align-items-center">
                <i class="fa-solid fa-phone me-2"></i>
                <strong>Información de Contacto</strong>
                <button type="button" class="btn btn-sm btn-light ms-auto" onclick="eliminarBloque(this)">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <i class="fa fa-phone text-success me-3"></i>
                    <span>' . htmlspecialchars($telefono) . '</span>
                </div>
                
                <div class="d-flex align-items-center mb-2">
                    <i class="fa fa-envelope text-primary me-3"></i>
                    <span>' . htmlspecialchars($email) . '</span>
                </div>
                
                <div class="d-flex align-items-center mb-2">
                    <i class="fa fa-globe text-info me-3"></i>
                    <span>' . htmlspecialchars($sitioWeb) . '</span>
                </div>
                
                <div class="d-flex align-items-center mb-3">
                    <i class="fab fa-whatsapp text-success me-3"></i>
                    <span>' . htmlspecialchars($telefono) . '</span>
                </div>
                
                <div class="text-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-edit"></i> Editar contacto
                    </button>
                </div>
            </div>
        </div>';
        
        return response($html);
    }
}