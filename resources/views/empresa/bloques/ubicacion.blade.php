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
            <p class="mb-1"><i class="fa fa-map-marker text-danger me-2"></i>{{ $negocio->direccion ?? 'Dirección no registrada' }}</p>
            <p class="mb-1"><i class="fa fa-city text-primary me-2"></i>{{ $negocio->ciudad ?? 'Ciudad no registrada' }}</p>
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
</div>