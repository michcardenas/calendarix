{{-- Modal de edición de servicios --}}
<div class="modal fade" id="modalEditarServicios" tabindex="-1" aria-labelledby="modalEditarServiciosLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Burbujas decorativas -->
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            <div class="bubble"></div>
            
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarServiciosLabel">
                    <i class="fa-solid fa-concierge-bell me-2"></i>
                    Servicios que ofreces
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <form action="{{ route('empresa.servicios.guardar', $negocio->id) }}" method="POST" id="form-servicios">
                    @csrf
                    
                    <div id="contenedor-servicios">
                        @if($negocio->servicios->count() > 0)
                            @foreach($negocio->servicios as $index => $servicio)
                                <div class="servicio-item" data-servicio-id="{{ $servicio->id }}">
                                    <div class="servicio-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="servicio-number">
                                                <i class="fa-solid fa-star text-warning me-2"></i>
                                                Servicio {{ $index + 1 }}
                                            </span>
                                            <button type="button" class="btn btn-sm btn-danger-gradient" onclick="eliminarServicio(this)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="servicios[{{ $index }}][id]" value="{{ $servicio->id }}">
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                <i class="fa-solid fa-tag text-primary me-1"></i>
                                                Nombre del servicio
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   name="servicios[{{ $index }}][nombre]" 
                                                   placeholder="Ej: Corte de cabello premium"
                                                   value="{{ $servicio->nombre }}"
                                                   required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">
                                                <i class="fa-solid fa-dollar-sign text-success me-1"></i>
                                                Precio (COP)
                                            </label>
                                            <div class="price-input">
                                                <input type="number" 
                                                       class="form-control" 
                                                       name="servicios[{{ $index }}][precio]" 
                                                       placeholder="25000"
                                                       value="{{ $servicio->precio }}"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fa-solid fa-file-lines text-info me-1"></i>
                                            Descripción
                                        </label>
                                        <textarea class="form-control" 
                                                  name="servicios[{{ $index }}][descripcion]" 
                                                  placeholder="Describe detalladamente tu servicio..."
                                                  rows="3">{{ $servicio->descripcion }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="servicio-item">
                                <div class="servicio-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="servicio-number">
                                            <i class="fa-solid fa-star text-warning me-2"></i>
                                            Servicio 1
                                        </span>
                                        <button type="button" class="btn btn-sm btn-danger-gradient" onclick="eliminarServicio(this)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fa-solid fa-tag text-primary me-1"></i>
                                            Nombre del servicio
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="servicios[0][nombre]" 
                                               placeholder="Ej: Corte de cabello premium"
                                               required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            <i class="fa-solid fa-dollar-sign text-success me-1"></i>
                                            Precio (COP)
                                        </label>
                                        <div class="price-input">
                                            <input type="number" 
                                                   class="form-control" 
                                                   name="servicios[0][precio]" 
                                                   placeholder="25000"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fa-solid fa-file-lines text-info me-1"></i>
                                        Descripción
                                    </label>
                                    <textarea class="form-control" 
                                              name="servicios[0][descripcion]" 
                                              placeholder="Describe detalladamente tu servicio..."
                                              rows="3"></textarea>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="text-center mb-4">
                        <button type="button" class="btn btn-add-gradient" onclick="agregarServicio()">
                            <i class="fa fa-plus me-2"></i>
                            Agregar nuevo servicio
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <button type="button" class="btn btn-cancel-gradient" data-bs-dismiss="modal">
                        <i class="fa fa-times me-2"></i>
                        Cancelar
                    </button>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-preview-gradient" onclick="previsualizar()">
                            <i class="fa fa-eye me-2"></i>
                            Vista previa
                        </button>
                        <button type="submit" form="form-servicios" class="btn btn-save-gradient">
                            <i class="fa fa-save me-2"></i>
                            Guardar servicios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Burbujas decorativas */
.bubble {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
    animation: float 6s ease-in-out infinite;
}

.bubble:nth-child(1) {
    width: 40px;
    height: 40px;
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.bubble:nth-child(2) {
    width: 20px;
    height: 20px;
    background: linear-gradient(45deg, #4834d4, #686de0);
    top: 20%;
    right: 20%;
    animation-delay: 2s;
}

.bubble:nth-child(3) {
    width: 60px;
    height: 60px;
    background: linear-gradient(45deg, #00d2d3, #54a0ff);
    bottom: 10%;
    left: 15%;
    animation-delay: 4s;
}

.bubble:nth-child(4) {
    width: 30px;
    height: 30px;
    background: linear-gradient(45deg, #5f27cd, #a55eea);
    bottom: 30%;
    right: 10%;
    animation-delay: 1s;
}

.bubble:nth-child(5) {
    width: 25px;
    height: 25px;
    background: linear-gradient(45deg, #00d84a, #05c46b);
    top: 50%;
    left: 5%;
    animation-delay: 3s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.1;
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
        opacity: 0.3;
    }
}

/* Estilos del modal */
.modal-dialog {
    max-width: 900px;
}

.modal-content {
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    position: relative;
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 25px 30px;
    position: relative;
}

.modal-title {
    font-weight: 600;
    font-size: 1.5rem;
}

.btn-close {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 35px;
    height: 35px;
    opacity: 1;
}

.modal-body {
    padding: 30px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    position: relative;
    overflow: hidden;
}

.servicio-item {
    background: rgba(255, 255, 255, 0.9);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    position: relative;
    animation: slideInUp 0.5s ease-out;
}

.servicio-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

.servicio-header {
    background: linear-gradient(45deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%);
    margin: -25px -25px 20px -25px;
    padding: 15px 25px;
    border-radius: 15px 15px 0 0;
}

.servicio-number {
    color: #333;
    font-weight: 600;
    font-size: 1.1rem;
}

.form-control {
    border: 2px solid rgba(102, 126, 234, 0.1);
    border-radius: 10px;
    padding: 12px 15px;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.8);
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 20px rgba(102, 126, 234, 0.2);
    background: white;
}

.form-label {
    font-weight: 600;
    color: #444;
    margin-bottom: 8px;
}

.btn-gradient {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border: none;
    border-radius: 25px;
    padding: 10px 25px;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    color: white;
}

.btn-add-gradient {
    background: linear-gradient(45deg, #6f42c1, #e83e8c);
    border: none;
    border-radius: 30px;
    padding: 15px 35px;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(111, 66, 193, 0.3);
}

.btn-add-gradient:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 12px 35px rgba(111, 66, 193, 0.4);
    color: white;
    background: linear-gradient(45deg, #5a2d91, #d63384);
}

.btn-danger-gradient {
    background: linear-gradient(45deg, #ff6b6b, #ee5a24);
    border: none;
    border-radius: 20px;
    color: white;
    transition: all 0.3s ease;
}

.btn-danger-gradient:hover {
    transform: scale(1.05);
    color: white;
}

.btn-success-gradient {
    background: linear-gradient(45deg, #00d2d3, #54a0ff);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    color: white;
    font-weight: 600;
}

.btn-success-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 210, 211, 0.3);
    color: white;
}

.modal-footer {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    padding: 25px 30px;
    border-top: 2px solid rgba(102, 126, 234, 0.1);
}

.btn-cancel-gradient {
    background: linear-gradient(45deg, #6c757d, #495057);
    border: none;
    border-radius: 25px;
    padding: 12px 25px;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.2);
}

.btn-cancel-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
    color: white;
    background: linear-gradient(45deg, #5a6268, #343a40);
}

.btn-preview-gradient {
    background: linear-gradient(45deg, #17a2b8, #138496);
    border: none;
    border-radius: 25px;
    padding: 12px 25px;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.2);
}

.btn-preview-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(23, 162, 184, 0.3);
    color: white;
    background: linear-gradient(45deg, #138496, #117a8b);
}

.btn-save-gradient {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    border-radius: 25px;
    padding: 12px 30px;
    color: white;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2);
}

.btn-save-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    color: white;
    background: linear-gradient(45deg, #218838, #1e7e34);
}

.price-input::before {
    content: '$';
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #667eea;
    font-weight: bold;
    z-index: 10;
}

.price-input input {
    padding-left: 30px;
}

/* Animaciones de entrada */
@keyframes slideInUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
    let contadorServicios = {{ $negocio->servicios->count() > 0 ? $negocio->servicios->count() : 1 }};

    function agregarServicio() {
        const contenedor = document.getElementById('contenedor-servicios');
        
        const html = `
            <div class="servicio-item">
                <div class="servicio-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="servicio-number">
                            <i class="fa-solid fa-star text-warning me-2"></i>
                            Servicio ${contadorServicios + 1}
                        </span>
                        <button type="button" class="btn btn-sm btn-danger-gradient" onclick="eliminarServicio(this)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fa-solid fa-tag text-primary me-1"></i>
                            Nombre del servicio
                        </label>
                        <input type="text" 
                               class="form-control" 
                               name="servicios[${contadorServicios}][nombre]" 
                               placeholder="Ej: Corte de cabello premium"
                               required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            <i class="fa-solid fa-dollar-sign text-success me-1"></i>
                            Precio (COP)
                        </label>
                        <div class="price-input">
                            <input type="number" 
                                   class="form-control" 
                                   name="servicios[${contadorServicios}][precio]" 
                                   placeholder="25000"
                                   required>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fa-solid fa-file-lines text-info me-1"></i>
                        Descripción
                    </label>
                    <textarea class="form-control" 
                              name="servicios[${contadorServicios}][descripcion]" 
                              placeholder="Describe detalladamente tu servicio..."
                              rows="3"></textarea>
                </div>
            </div>
        `;

        contenedor.insertAdjacentHTML('beforeend', html);
        contadorServicios++;
        
        // Scroll suave al nuevo servicio
        setTimeout(() => {
            const nuevoServicio = contenedor.lastElementChild;
            nuevoServicio.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    }

    function eliminarServicio(button) {
        const servicioItem = button.closest('.servicio-item');
        const totalServicios = document.querySelectorAll('.servicio-item').length;
        
        if (totalServicios > 1) {
            if (confirm('¿Estás seguro de que quieres eliminar este servicio?')) {
                servicioItem.style.transform = 'translateX(100%)';
                servicioItem.style.opacity = '0';
                
                setTimeout(() => {
                    servicioItem.remove();
                    reindexarServicios();
                }, 300);
            }
        } else {
            alert('Debe tener al menos un servicio');
        }
    }

    function reindexarServicios() {
        const servicios = document.querySelectorAll('.servicio-item');
        servicios.forEach((servicio, index) => {
            const inputs = servicio.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    input.setAttribute('name', newName);
                }
            });
            
            const numeroServicio = servicio.querySelector('.servicio-number');
            if (numeroServicio) {
                numeroServicio.innerHTML = `<i class="fa-solid fa-star text-warning me-2"></i>Servicio ${index + 1}`;
            }
        });
    }

    function previsualizar() {
        const servicios = [];
        const servicioItems = document.querySelectorAll('.servicio-item');
        
        servicioItems.forEach(item => {
            const nombre = item.querySelector('input[name*="[nombre]"]').value;
            const precio = item.querySelector('input[name*="[precio]"]').value;
            const descripcion = item.querySelector('textarea[name*="[descripcion]"]').value;
            
            if (nombre && precio) {
                servicios.push({ nombre, precio, descripcion });
            }
        });
        
        if (servicios.length > 0) {
            let preview = 'Vista previa de tus servicios:\n\n';
            servicios.forEach((servicio, index) => {
                preview += `${index + 1}. ${servicio.nombre}\n`;
                preview += `   Precio: $${parseInt(servicio.precio).toLocaleString()} COP\n`;
                if (servicio.descripcion) {
                    preview += `   ${servicio.descripcion}\n`;
                }
                preview += '\n';
            });
            alert(preview);
        } else {
            alert('Por favor, agrega al menos un servicio con nombre y precio.');
        }
    }

    // Validación en tiempo real
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('form-servicios');
        form.addEventListener('submit', function(e) {
            const servicios = document.querySelectorAll('.servicio-item');
            let valid = true;
            
            servicios.forEach(servicio => {
                const nombre = servicio.querySelector('input[name*="[nombre]"]');
                const precio = servicio.querySelector('input[name*="[precio]"]');
                
                if (!nombre.value.trim() || !precio.value.trim()) {
                    valid = false;
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor completa todos los campos obligatorios (nombre y precio)');
            }
        });
    });
</script>