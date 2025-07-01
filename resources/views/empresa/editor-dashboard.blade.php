
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sortable/1.15.0/Sortable.min.css" />
<style>
    .editor-container {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .editor-title {
        font-weight: bold;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    .drag-zone {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    .block {
        padding: 15px;
        border-radius: 10px;
        background: #f3f4f6;
        border: 2px dashed #ccc;
        min-width: 200px;
        cursor: grab;
    }
    .canvas {
        border: 2px dashed #00304b;
        padding: 20px;
        border-radius: 10px;
        min-height: 300px;
        background: #f9f9f9;
    }
</style>

<div class="container py-4">
    <div class="editor-container">
        <div class="editor-title">🎨 Diseña tu página pública de empresa</div>

        <p>Arrastra los bloques que quieras mostrar en tu página y organízalos como desees.</p>

        <div class="drag-zone" id="component-list">
            <div class="block" data-type="galeria">🖼️ Galería de Fotos</div>
            <div class="block" data-type="horario">🕒 Horarios de Atención</div>
            <div class="block" data-type="servicios">💇 Servicios Ofrecidos</div>
            <div class="block" data-type="ubicacion">📍 Mapa / Ubicación</div>
            <div class="block" data-type="contacto">📞 Información de Contacto</div>
        </div>

        <h4>🧩 Vista Previa:</h4>
        <div id="canvas" class="canvas">
            <p class="text-muted">Aquí se armará la página...</p>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
<script>
    // Esperar a que el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM cargado, iniciando editor...');
        
        // Verificar que tenemos el negocio ID
        const negocioId = {{ $negocio->id }};
        console.log('Negocio ID cargado:', negocioId);
        
        // Verificar que existen los elementos necesarios
        const canvas = document.getElementById('canvas');
        const componentList = document.querySelectorAll('.block');
        
        if (!canvas) {
            console.error('❌ Error: No se encontró el elemento canvas');
            return;
        }
        
        if (componentList.length === 0) {
            console.error('❌ Error: No se encontraron bloques para arrastrar');
            return;
        }
        
        console.log('✅ Elementos encontrados:', {
            canvas: !!canvas,
            bloques: componentList.length
        });

        // Configurar bloques arrastrables
        componentList.forEach((block, index) => {
            console.log(`Configurando bloque ${index + 1}:`, block.dataset.type);
            
            block.addEventListener('dragstart', e => {
                console.log('Iniciando arrastre:', block.dataset.type);
                e.dataTransfer.setData('text/plain', block.dataset.type);
            });

            block.setAttribute('draggable', true);
        });

        // Configurar zona de drop
        canvas.addEventListener('dragover', e => {
            e.preventDefault();
        });

        canvas.addEventListener('drop', async e => {
            e.preventDefault();
            const type = e.dataTransfer.getData('text/plain');
            
            console.log('=== INICIANDO DROP ===');
            console.log('Tipo de bloque:', type);
            console.log('Negocio ID:', negocioId);

            // Verificar si el bloque ya existe
            if (canvas.querySelector(`[data-block-type="${type}"]`)) {
                alert('Este bloque ya está agregado');
                return;
            }

            // Crear elemento contenedor
            const element = document.createElement('div');
            element.className = 'bloque-canvas mb-3';
            element.setAttribute('data-block-type', type);
            element.style.cssText = `
                background: #ffffff;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 10px;
                margin-bottom: 15px;
                position: relative;
            `;

            // Mostrar loading
            element.innerHTML = `
                <div class="text-center p-4">
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 mb-0">Cargando ${type}...</p>
                    <small class="text-muted">ID: ${negocioId}</small>
                </div>
            `;

            canvas.appendChild(element);

            // Construir URL y hacer petición
            const url = `/bloques/${type}?negocio_id=${negocioId}`;
            console.log('URL de petición:', url);

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'text/html',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                });
                
                console.log('Status de respuesta:', response.status);
                console.log('Headers de respuesta:', Object.fromEntries(response.headers));
                
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Error en respuesta:', errorText);
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const html = await response.text();
                console.log('HTML recibido (primeros 200 chars):', html.substring(0, 200));
                
                // Verificar que recibimos HTML válido
                if (html.trim().length === 0) {
                    throw new Error('Respuesta vacía del servidor');
                }
                
                element.innerHTML = html;
                console.log('✅ Bloque cargado exitosamente');
                
            } catch (error) {
                console.error('❌ Error completo:', error);
                element.innerHTML = `
                    <div class="alert alert-danger">
                        <h6>❌ Error cargando ${type}</h6>
                        <p><strong>Error:</strong> ${error.message}</p>
                        <p><strong>URL:</strong> ${url}</p>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-danger" onclick="eliminarBloque(this)">
                                Eliminar
                            </button>
                            <button class="btn btn-sm btn-warning ms-2" onclick="reintentar('${type}', this, ${negocioId})">
                                Reintentar
                            </button>
                        </div>
                    </div>
                `;
            }
        });

        // Configurar Sortable si está disponible
        if (typeof Sortable !== 'undefined') {
            new Sortable(canvas, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag'
            });
            console.log('✅ Sortable configurado');
        } else {
            console.warn('⚠️ Sortable no está disponible');
        }

        // Prueba automática
        console.log('🧪 Ejecutando prueba de conectividad...');
        testConectivity(negocioId);
    });

    // Funciones globales
    function eliminarBloque(button) {
        const bloque = button.closest('.bloque-canvas');
        if (bloque && confirm('¿Estás seguro de que quieres eliminar este bloque?')) {
            bloque.remove();
            
            const canvas = document.getElementById('canvas');
            if (canvas && canvas.querySelectorAll('.bloque-canvas').length === 0) {
                canvas.innerHTML = '<p class="text-muted">Aquí se armará la página...</p>';
            }
        }
    }

    function reintentar(tipo, button, negocioId) {
        const bloque = button.closest('.bloque-canvas');
        if (!bloque) return;
        
        bloque.innerHTML = `
            <div class="text-center p-4">
                <div class="spinner-border spinner-border-sm" role="status"></div>
                <p class="mt-2 mb-0">Reintentando ${tipo}...</p>
            </div>
        `;
        
        setTimeout(async () => {
            const url = `/bloques/${tipo}?negocio_id=${negocioId}`;
            
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error(`HTTP ${response.status}`);
                const html = await response.text();
                bloque.innerHTML = html;
                console.log('✅ Reintento exitoso para', tipo);
            } catch (error) {
                bloque.innerHTML = `
                    <div class="alert alert-danger">
                        <p>❌ Error persistente: ${error.message}</p>
                        <button class="btn btn-sm btn-danger" onclick="eliminarBloque(this)">
                            Eliminar
                        </button>
                    </div>
                `;
                console.error('❌ Error en reintento:', error);
            }
        }, 1000);
    }

    async function testConectivity(negocioId) {
        try {
            const response = await fetch(`/bloques/servicios?negocio_id=${negocioId}`);
            console.log('✅ Prueba de conectividad - Status:', response.status);
            
            if (response.ok) {
                const html = await response.text();
                console.log('✅ Prueba exitosa - HTML recibido:', html.length, 'caracteres');
                console.log('🎉 ¡La conexión funciona correctamente!');
            } else {
                console.error('❌ Prueba falló - Status:', response.status);
            }
        } catch (error) {
            console.error('❌ Error en prueba de conectividad:', error);
        }
    }
</script>

<style>
    .sortable-ghost {
        opacity: 0.4;
    }
    
    .sortable-chosen {
        background: #f0f8ff !important;
    }
    
    .bloque-canvas {
        transition: all 0.3s ease;
    }
    
    .bloque-canvas:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
</style>

