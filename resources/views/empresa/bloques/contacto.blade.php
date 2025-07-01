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
            <span>{{ $negocio->telefono ?? 'No registrado' }}</span>
        </div>
        
        <div class="d-flex align-items-center mb-2">
            <i class="fa fa-envelope text-primary me-3"></i>
            <span>{{ $negocio->email ?? 'No registrado' }}</span>
        </div>
        
        <div class="d-flex align-items-center mb-2">
            <i class="fa fa-globe text-info me-3"></i>
            <span>{{ $negocio->sitio_web ?? 'No registrado' }}</span>
        </div>
        
        <div class="d-flex align-items-center mb-3">
            <i class="fab fa-whatsapp text-success me-3"></i>
            <span>{{ $negocio->whatsapp ?? 'No registrado' }}</span>
        </div>
        
        <div class="text-center">
            <button type="button" class="btn btn-outline-secondary btn-sm">
                <i class="fa fa-edit"></i> Editar contacto
            </button>
        </div>
    </div>
</div>