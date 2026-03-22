@extends('layouts.empresa')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/empresa/configuracion/negocio.css') }}">
@endpush

@section('title', 'Configuración del Negocio')

@section('content')

@php
    $resolveImg = function($path) {
        if (!$path) return null;
        if (str_starts_with($path, 'http')) return $path;
        if (str_starts_with($path, '/')) return $path;
        return asset('storage/' . $path);
    };
    $imgPortada = $resolveImg($empresa->neg_portada);
    $imgPerfil  = $resolveImg($empresa->neg_imagen);
@endphp

{{-- Breadcrumbs --}}
<nav style="display:flex; align-items:center; gap:6px; font-size:0.8rem; color:#9ca3af; margin-bottom:1rem; flex-wrap:wrap;">
    <a href="{{ route('empresa.dashboard', $empresa->id) }}" style="color:#5a31d7; text-decoration:none; font-weight:500;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
        <i class="fas fa-home" style="font-size:0.7rem;"></i> Dashboard
    </a>
    <i class="fas fa-chevron-right" style="font-size:0.55rem; color:#d1d5db;"></i>
    <span style="color:#6b7280;">Configuracion</span>
    <i class="fas fa-chevron-right" style="font-size:0.55rem; color:#d1d5db;"></i>
    <span style="color:#374151; font-weight:500;">{{ $empresa->neg_nombre_comercial ?? 'Negocio' }}</span>
</nav>

{{-- ============================== PORTADA ============================== --}}
<div class="negocio-cover-wrapper">
    <div id="coverFallback" class="negocio-cover-fallback {{ $imgPortada ? 'is-hidden' : '' }}"
         style="background: linear-gradient(135deg, #5a31d7 0%, #32ccbc 100%);">
    </div>

    @if($imgPortada)
        <img src="{{ $imgPortada }}"
             id="coverImage"
             class="negocio-cover-img"
             alt="Portada del negocio"
             onerror="this.style.display='none'; document.getElementById('coverFallback').classList.remove('is-hidden');">
    @endif

    <div class="negocio-cover-overlay"></div>

    <label for="input-cover-upload" class="negocio-cover-btn">
        <i class="fas fa-camera"></i> Cambiar portada
    </label>
</div>

{{-- ============================== INFO BAR ============================== --}}
<div class="negocio-info-bar">
    <div class="info-bar-flex">
        {{-- Foto de perfil --}}
        <div class="profile-img-container">
            @if($imgPerfil)
                <img src="{{ $imgPerfil }}" id="profilePreview"
                     alt="{{ $empresa->neg_nombre_comercial }}"
                     onerror="this.onerror=null; this.src='/images/default-user.png';">
            @else
                <div id="profilePreview" class="profile-initials">
                    {{ strtoupper(substr($empresa->neg_nombre_comercial ?? $empresa->neg_nombre ?? 'N', 0, 2)) }}
                </div>
            @endif

            <label for="input-logo-upload" class="profile-camera-btn">
                <i class="fas fa-camera" style="color: #fff; font-size: 0.75rem;"></i>
            </label>
        </div>

        {{-- Nombre e info --}}
        <div class="info-bar-details">
            <h1 class="info-bar-name">{{ $empresa->neg_nombre_comercial ?? $empresa->neg_nombre }}</h1>

            @if(is_array($empresa->neg_categorias) && count($empresa->neg_categorias))
                <div class="info-bar-cats">
                    @foreach($empresa->neg_categorias as $cat)
                        <span class="info-bar-cat-tag">{{ $cat }}</span>
                    @endforeach
                </div>
            @endif

            <div class="info-bar-meta">
                @if($empresa->neg_direccion)
                    <span><i class="fas fa-map-marker-alt"></i>{{ $empresa->neg_direccion }}</span>
                @endif
                @if($empresa->neg_telefono)
                    <span><i class="fas fa-phone"></i>{{ $empresa->neg_telefono }}</span>
                @endif
                @if($empresa->neg_virtual)
                    <span class="virtual-badge"><i class="fas fa-laptop" style="margin-right: 3px;"></i>Virtual</span>
                @endif
                @if($empresa->neg_facebook)
                    <a href="{{ $empresa->neg_facebook }}" target="_blank" class="social-icon"><i class="fab fa-facebook"></i></a>
                @endif
                @if($empresa->neg_instagram)
                    <a href="{{ $empresa->neg_instagram }}" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
                @endif
                @if($empresa->neg_sitio_web)
                    <a href="{{ $empresa->neg_sitio_web }}" target="_blank" class="social-icon"><i class="fas fa-globe"></i></a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ============================== MENSAJES ============================== --}}
@if(session('success'))
<div class="msg-success">
    <i class="fas fa-check-circle" style="margin-right: 6px;"></i>{{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="msg-error">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- ============================== FORMULARIO ============================== --}}
<form action="{{ route('negocio.guardar') }}" method="POST" enctype="multipart/form-data" id="negocio-profile-form">
    @csrf
    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">

    {{-- Inputs de archivo ocultos --}}
    <input type="file" name="confneg_portada" id="input-cover-upload" accept="image/*" style="display: none;">
    <input type="file" name="confneg_imagen" id="input-logo-upload" accept="image/*" style="display: none;">

    <div class="negocio-forms-grid">
        {{-- ========== COLUMNA IZQUIERDA: Acerca del negocio ========== --}}
        <div class="profile-card">
            <h3 class="profile-card-title">
                <i class="fas fa-info-circle"></i> Acerca del negocio
            </h3>

            <div class="field-group">
                <div>
                    <label class="field-label">Nombre del negocio</label>
                    <input type="text" name="confneg_nombre" value="{{ $empresa->neg_nombre_comercial }}" class="field-input">
                </div>

                <div>
                    <label class="field-label">Descripción del negocio</label>
                    <textarea name="confneg_descripcion" class="field-input" rows="3" maxlength="1000" placeholder="Cuéntanos brevemente sobre tu negocio..." style="resize: vertical;">{{ $empresa->neg_descripcion }}</textarea>
                </div>

                <div>
                    <label class="field-label">País</label>
                    <select name="confneg_pais" class="field-input">
                        <option selected>{{ $empresa->neg_pais ?? 'Colombia' }}</option>
                    </select>
                </div>

                <div>
                    <label class="field-label">Nombre del propietario</label>
                    <input type="text" name="confneg_nombre_real" value="{{ $empresa->neg_nombre }}" class="field-input">
                </div>

                <div>
                    <label class="field-label">Apellido del propietario</label>
                    <input type="text" name="confneg_apellido" value="{{ $empresa->neg_apellido }}" class="field-input">
                </div>

                <div>
                    <label class="field-label">Correo electrónico</label>
                    <input type="email" name="confneg_email" value="{{ $empresa->neg_email }}" class="field-input">
                </div>

                <div>
                    <label class="field-label">Teléfono</label>
                    <input type="text" name="confneg_telefono" value="{{ $empresa->neg_telefono }}" class="field-input">
                </div>

                <div>
                    <label class="field-label">Direccion</label>
                    <input type="text" name="confneg_direccion" id="confneg_direccion" value="{{ $empresa->neg_direccion }}" class="field-input" placeholder="Busca tu direccion...">
                    <input type="hidden" name="confneg_latitud" id="confneg_latitud" value="{{ $empresa->neg_latitud }}">
                    <input type="hidden" name="confneg_longitud" id="confneg_longitud" value="{{ $empresa->neg_longitud }}">
                    <input type="hidden" name="confneg_equipo" value="{{ $empresa->neg_equipo }}">
                    <div id="config-map" style="width:100%; height:250px; border-radius:8px; border:1px solid #d1d5db; margin-top:8px; display:none;"></div>
                    <p id="config-map-hint" style="font-size:0.75rem; color:#9ca3af; margin-top:4px; display:none;">Arrastra el marcador para ajustar la ubicacion</p>
                </div>

                <div class="checkbox-row">
                    <label class="checkbox-label">
                        <input type="checkbox" name="confneg_virtual" id="confneg_virtual" value="1" {{ $empresa->neg_virtual ? 'checked' : '' }}>
                        Negocio virtual
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="confneg_direccion_confirmada" value="1" {{ $empresa->neg_direccion_confirmada ? 'checked' : '' }}>
                        Direccion confirmada
                    </label>
                </div>
            </div>
        </div>

        {{-- ========== COLUMNA DERECHA: Categorías + Enlaces + Imágenes ========== --}}
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">

            {{-- Card: Categorías --}}
            <div class="profile-card">
                <h3 class="profile-card-title">
                    <i class="fas fa-tags"></i> Categorías
                </h3>

                @php
                    $gruposCat = [
                        ['nombre' => 'Belleza', 'icon' => 'fa-spa', 'color' => '#e91e63', 'subs' => ['Peluqueria','Barberia','Uñas','Depilacion','Maquillaje','Cama Solar','Tatuaje','Peluqueria canina','Bienestar','Clinicas esteticas','Spa','Masajes']],
                        ['nombre' => 'Cuidados', 'icon' => 'fa-heartbeat', 'color' => '#00bcd4', 'subs' => ['Acupuntura','Quiropractico','Nutricionista','Coaching','Fisioterapia','Psicologia','Odontologia','Kinesiologia']],
                        ['nombre' => 'Fitness', 'icon' => 'fa-dumbbell', 'color' => '#ff9800', 'subs' => ['Yoga','Gimnasio','Entrenador personal','Pilates','Ciclismo','Baile']],
                        ['nombre' => 'Deportes', 'icon' => 'fa-trophy', 'color' => '#4caf50', 'subs' => ['Cancha de padel','Cancha de futbol 5','Cancha de tenis','Cancha de pickleball']],
                    ];
                    $currentCats = is_array($empresa->neg_categorias) ? $empresa->neg_categorias : [];
                @endphp

                @foreach($gruposCat as $gi => $grupo)
                    <div style="margin-bottom: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; cursor: pointer;" onclick="this.nextElementSibling.style.display = this.nextElementSibling.style.display === 'none' ? 'flex' : 'none'">
                            <span style="width: 28px; height: 28px; border-radius: 50%; background: {{ $grupo['color'] }}; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="fas {{ $grupo['icon'] }}" style="color: #fff; font-size: 0.7rem;"></i>
                            </span>
                            <span style="font-weight: 600; font-size: 0.875rem; color: #374151;">{{ $grupo['nombre'] }}</span>
                            <i class="fas fa-chevron-down" style="font-size: 0.6rem; color: #9ca3af; margin-left: auto;"></i>
                        </div>
                        <div style="display: {{ collect($grupo['subs'])->intersect($currentCats)->count() ? 'flex' : 'none' }}; flex-wrap: wrap; gap: 6px;">
                            @foreach($grupo['subs'] as $sub)
                                @php $isChecked = in_array($sub, $currentCats); @endphp
                                <label style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 999px; font-size: 0.75rem; cursor: pointer; border: 1px solid {{ $isChecked ? '#5a31d7' : '#e5e7eb' }}; background: {{ $isChecked ? 'rgba(90,49,215,0.1)' : '#fff' }}; color: {{ $isChecked ? '#5a31d7' : '#6b7280' }}; transition: all 0.2s;">
                                    <input type="checkbox" name="confneg_categorias[]" value="{{ $sub }}" {{ $isChecked ? 'checked' : '' }} style="display: none;"
                                        onchange="var l=this.parentElement; if(this.checked){l.style.border='1px solid #5a31d7';l.style.background='rgba(90,49,215,0.1)';l.style.color='#5a31d7';}else{l.style.border='1px solid #e5e7eb';l.style.background='#fff';l.style.color='#6b7280';}">
                                    {{ $sub }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- Otro --}}
                <div style="margin-top: 0.5rem;">
                    <label class="field-label">Otra categoría</label>
                    <input type="text" name="confneg_categoria_otro" placeholder="Especifica tu categoría" class="field-input">
                </div>
            </div>

            {{-- Card: Enlaces externos --}}
            <div class="profile-card">
                <h3 class="profile-card-title">
                    <i class="fas fa-link"></i> Enlaces externos
                </h3>

                <div class="field-group">
                    <div>
                        <label class="field-label">Facebook</label>
                        <div class="field-input-icon">
                            <i class="fab fa-facebook" style="color: #1877F2;"></i>
                            <input type="url" name="confneg_facebook" value="{{ $empresa->neg_facebook }}" placeholder="https://facebook.com/tuempresa" class="field-input">
                        </div>
                    </div>

                    <div>
                        <label class="field-label">Instagram</label>
                        <div class="field-input-icon">
                            <i class="fab fa-instagram" style="color: #E4405F;"></i>
                            <input type="url" name="confneg_instagram" value="{{ $empresa->neg_instagram }}" placeholder="https://instagram.com/tuempresa" class="field-input">
                        </div>
                    </div>

                    <div>
                        <label class="field-label">Sitio Web</label>
                        <div class="field-input-icon">
                            <i class="fas fa-globe" style="color: #5a31d7;"></i>
                            <input type="url" name="confneg_web" value="{{ $empresa->neg_sitio_web }}" placeholder="https://tuempresa.com" class="field-input">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Imágenes --}}
            <div class="profile-card">
                <h3 class="profile-card-title">
                    <i class="fas fa-images"></i> Imágenes
                </h3>

                {{-- Logo preview --}}
                <div style="margin-bottom: 1rem;">
                    <label class="field-label">Logo del negocio</label>
                    <div class="img-preview-circle" id="logo-preview-area" onclick="document.getElementById('input-logo-upload').click();">
                        @if($imgPerfil)
                            <img src="{{ $imgPerfil }}" id="logo-preview-img" onerror="this.remove();">
                        @else
                            <i class="fas fa-plus" style="color: #9ca3af; font-size: 1.25rem;" id="logo-placeholder-icon"></i>
                        @endif
                    </div>
                    <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 4px;">Haz clic para cambiar</p>
                </div>

                {{-- Cover preview --}}
                <div>
                    <label class="field-label">Portada del negocio</label>
                    <div class="img-preview-rect" id="cover-preview-area" onclick="document.getElementById('input-cover-upload').click();">
                        @if($imgPortada)
                            <img src="{{ $imgPortada }}" id="cover-preview-img" onerror="this.remove();">
                        @else
                            <i class="fas fa-plus" style="color: #9ca3af; font-size: 1.25rem;" id="cover-placeholder-icon"></i>
                        @endif
                    </div>
                    <p style="font-size: 0.75rem; color: #9ca3af; margin-top: 4px;">Haz clic para cambiar</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Botón guardar --}}
    <div class="save-bar">
        <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> Guardar cambios
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============ Cover image preview ============
    var coverInput = document.getElementById('input-cover-upload');
    if (coverInput) {
        coverInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;

            var reader = new FileReader();
            reader.onload = function(ev) {
                // Update main cover
                var wrapper = document.querySelector('.negocio-cover-wrapper');
                var img = wrapper.querySelector('img.negocio-cover-img');
                var fallback = document.getElementById('coverFallback');

                if (!img) {
                    img = document.createElement('img');
                    img.className = 'negocio-cover-img';
                    img.alt = 'Portada del negocio';
                    var overlay = wrapper.querySelector('.negocio-cover-overlay');
                    wrapper.insertBefore(img, overlay);
                }
                img.src = ev.target.result;
                img.style.display = 'block';
                if (fallback) fallback.classList.add('is-hidden');

                // Update card preview
                var previewArea = document.getElementById('cover-preview-area');
                var previewImg = document.getElementById('cover-preview-img');
                var placeholder = document.getElementById('cover-placeholder-icon');
                if (!previewImg) {
                    previewImg = document.createElement('img');
                    previewImg.id = 'cover-preview-img';
                    previewArea.appendChild(previewImg);
                }
                previewImg.src = ev.target.result;
                if (placeholder) placeholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    }

    // ============ Logo image preview ============
    var logoInput = document.getElementById('input-logo-upload');
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;

            var reader = new FileReader();
            reader.onload = function(ev) {
                // Update info bar profile image
                var profileEl = document.getElementById('profilePreview');
                if (profileEl && profileEl.tagName === 'IMG') {
                    profileEl.src = ev.target.result;
                } else if (profileEl) {
                    var newImg = document.createElement('img');
                    newImg.id = 'profilePreview';
                    newImg.src = ev.target.result;
                    newImg.alt = 'Logo del negocio';
                    profileEl.parentNode.replaceChild(newImg, profileEl);
                }

                // Update card preview
                var logoArea = document.getElementById('logo-preview-area');
                var logoPreview = document.getElementById('logo-preview-img');
                var logoPlaceholder = document.getElementById('logo-placeholder-icon');
                if (!logoPreview) {
                    logoPreview = document.createElement('img');
                    logoPreview.id = 'logo-preview-img';
                    logoArea.appendChild(logoPreview);
                }
                logoPreview.src = ev.target.result;
                if (logoPlaceholder) logoPlaceholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });
    }
});

// ============ Google Maps for address ============
window.initConfigMap = function() {
    var lat = {{ $empresa->neg_latitud ?? -34.9011 }};
    var lng = {{ $empresa->neg_longitud ?? -56.1645 }};
    var hasCoords = {{ ($empresa->neg_latitud && $empresa->neg_longitud) ? 'true' : 'false' }};

    var mapDiv = document.getElementById('config-map');
    var mapHint = document.getElementById('config-map-hint');
    var input = document.getElementById('confneg_direccion');
    var virtualCb = document.getElementById('confneg_virtual');

    // Show map if not virtual
    function toggleMapVisibility() {
        if (virtualCb && virtualCb.checked) {
            mapDiv.style.display = 'none';
            mapHint.style.display = 'none';
        } else {
            mapDiv.style.display = 'block';
            mapHint.style.display = 'block';
            if (window._configMap) {
                google.maps.event.trigger(window._configMap, 'resize');
            }
        }
    }

    if (virtualCb) {
        virtualCb.addEventListener('change', toggleMapVisibility);
    }
    toggleMapVisibility();

    var map = new google.maps.Map(mapDiv, {
        center: { lat: lat, lng: lng },
        zoom: hasCoords ? 17 : 13,
        mapTypeControl: false,
        streetViewControl: false,
        fullscreenControl: false,
        styles: [
            { featureType: 'poi', stylers: [{ visibility: 'simplified' }] },
            { featureType: 'transit', stylers: [{ visibility: 'off' }] }
        ]
    });
    window._configMap = map;

    var marker = new google.maps.Marker({
        position: { lat: lat, lng: lng },
        map: map,
        draggable: true,
        title: 'Ubicacion del negocio'
    });

    var geocoder = new google.maps.Geocoder();

    try {
        var autocomplete = new google.maps.places.Autocomplete(input, {
            fields: ['geometry', 'formatted_address', 'name']
        });
        autocomplete.bindTo('bounds', map);

        autocomplete.addListener('place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) {
                geocodeAddr(input.value);
                return;
            }
            var loc = place.geometry.location;
            map.setCenter(loc);
            map.setZoom(17);
            marker.setPosition(loc);
            setCoords(loc.lat(), loc.lng());
            if (place.formatted_address) input.value = place.formatted_address;
        });
    } catch(e) {
        console.error('Places API error:', e);
    }

    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            geocodeAddr(input.value);
        }
    });

    function geocodeAddr(address) {
        if (!address || address.trim() === '') return;
        geocoder.geocode({ address: address }, function(results, status) {
            if (status === 'OK' && results[0]) {
                var loc = results[0].geometry.location;
                map.setCenter(loc);
                map.setZoom(17);
                marker.setPosition(loc);
                setCoords(loc.lat(), loc.lng());
                input.value = results[0].formatted_address;
            }
        });
    }

    marker.addListener('dragend', function() {
        var pos = marker.getPosition();
        setCoords(pos.lat(), pos.lng());
        geocoder.geocode({ location: pos }, function(results, status) {
            if (status === 'OK' && results[0]) {
                input.value = results[0].formatted_address;
            }
        });
    });

    map.addListener('click', function(e) {
        marker.setPosition(e.latLng);
        setCoords(e.latLng.lat(), e.latLng.lng());
        geocoder.geocode({ location: e.latLng }, function(results, status) {
            if (status === 'OK' && results[0]) {
                input.value = results[0].formatted_address;
            }
        });
    });

    function setCoords(lt, ln) {
        document.getElementById('confneg_latitud').value = lt;
        document.getElementById('confneg_longitud').value = ln;
    }
};
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtfFmZQIfa0vxx07f3fNzHsN7tcxcerxM&libraries=places&callback=initConfigMap" async defer></script>
@endpush
