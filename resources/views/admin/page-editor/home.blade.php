@extends('layouts.admin')

@section('title', 'Editor - Pagina de Inicio')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-page-editor.css') }}">
@endpush

@section('admin-content')

<header class="admin-header">
    <div>
        <h1>
            <i class="fas fa-home icon"></i>
            Pagina de Inicio
        </h1>
        <div class="admin-date">
            <a href="{{ route('admin.page-editor.index') }}" class="pe-btn-back">
                <i class="fas fa-arrow-left"></i> Volver al editor
            </a>
        </div>
    </div>
</header>

@if(session('success'))
    <div class="pe-alert pe-alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('admin.page-editor.home.update') }}" enctype="multipart/form-data" class="pe-form">
    @csrf
    @method('PUT')

    {{-- ═══════════════════════════════════════════
         SECCION: HERO
         ═══════════════════════════════════════════ --}}
    <div class="pe-section expanded">
        <div class="pe-section-header" onclick="this.closest('.pe-section').classList.toggle('expanded')">
            <div class="pe-section-icon"><i class="fas fa-image"></i></div>
            <span class="pe-section-title">Hero (Banner Principal)</span>
            <i class="fas fa-chevron-down pe-section-toggle"></i>
        </div>
        <div class="pe-section-body">
            <div class="pe-field">
                <label class="pe-label">Titulo base <small>(parte fija, sin la ultima palabra)</small></label>
                <input type="text" name="hero_title" class="pe-input"
                       value="{{ $hero['title'] ?? '' }}"
                       placeholder="Reserva cualquier servicio cerca de">
            </div>

            <div class="pe-field">
                <label class="pe-label">Palabras rotativas <small>(se muestran una a una al final del titulo)</small></label>
                <div class="pe-repeater" id="wordsRepeater">
                    <div class="pe-repeater-header">
                        <span>Palabra / Frase</span>
                    </div>
                    @php $words = $hero['rotating_words'] ?? []; @endphp
                    @forelse($words as $i => $word)
                        <div class="pe-repeater-item">
                            <span class="pe-repeater-num">{{ $i + 1 }}</span>
                            <input type="text" name="hero_words[]" class="pe-input"
                                   value="{{ $word }}" placeholder="ti">
                            <button type="button" class="pe-btn-remove" onclick="this.closest('.pe-repeater-item').remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @empty
                        <div class="pe-repeater-item">
                            <span class="pe-repeater-num">1</span>
                            <input type="text" name="hero_words[]" class="pe-input" placeholder="ti">
                            <button type="button" class="pe-btn-remove" onclick="this.closest('.pe-repeater-item').remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="pe-btn-add" onclick="addWord()">
                    <i class="fas fa-plus"></i> Agregar palabra
                </button>
            </div>

            <div class="pe-field">
                <label class="pe-label">Subtitulo</label>
                <textarea name="hero_subtitle" class="pe-textarea" rows="2"
                          placeholder="Encuentra y agenda con los mejores profesionales y negocios cerca de vos">{{ $hero['subtitle'] ?? '' }}</textarea>
            </div>

            <div class="pe-field">
                <label class="pe-label">Placeholder del buscador</label>
                <input type="text" name="hero_placeholder" class="pe-input pe-input-sm"
                       value="{{ $hero['placeholder'] ?? '' }}"
                       placeholder="Buscar negocio, servicio o ubicacion...">
            </div>

            <div class="pe-field">
                <label class="pe-label">Tipo de fondo</label>
                @php $bgType = $hero['bg_type'] ?? 'images'; @endphp
                <div class="pe-bg-type-selector">
                    <label class="pe-radio-card {{ $bgType === 'images' ? 'active' : '' }}">
                        <input type="radio" name="hero_bg_type" value="images" {{ $bgType === 'images' ? 'checked' : '' }}
                               onchange="toggleBgType(this.value)">
                        <i class="fas fa-images"></i>
                        <span>Imagenes</span>
                    </label>
                    <label class="pe-radio-card {{ $bgType === 'video' ? 'active' : '' }}">
                        <input type="radio" name="hero_bg_type" value="video" {{ $bgType === 'video' ? 'checked' : '' }}
                               onchange="toggleBgType(this.value)">
                        <i class="fas fa-video"></i>
                        <span>Video</span>
                    </label>
                </div>
            </div>

            {{-- IMAGENES --}}
            <div class="pe-field pe-bg-panel" id="bgPanelImages" style="{{ $bgType !== 'images' ? 'display:none' : '' }}">
                <label class="pe-label">Imagenes de fondo <small>(si hay varias, rotan automaticamente)</small></label>
                @php $images = $hero['images'] ?? []; @endphp

                <div class="pe-images-grid" id="heroImagesGrid">
                    @foreach($images as $i => $img)
                        <div class="pe-image-thumb" data-index="{{ $i }}">
                            <img src="{{ asset($img) }}" alt="Hero {{ $i + 1 }}">
                            <input type="hidden" name="hero_existing_images[]" value="{{ $img }}">
                            <button type="button" class="pe-thumb-remove" onclick="this.closest('.pe-image-thumb').remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="pe-image-upload" style="margin-top:0.75rem;">
                    <div>
                        <input type="file" name="hero_images[]" accept="image/*" multiple class="pe-file-input">
                        <p class="pe-file-hint">JPG, PNG o WebP. Recomendado: 1920x800px. Selecciona una o varias.</p>
                    </div>
                </div>
            </div>

            {{-- VIDEO --}}
            <div class="pe-field pe-bg-panel" id="bgPanelVideo" style="{{ $bgType !== 'video' ? 'display:none' : '' }}">
                <label class="pe-label">Video de fondo <small>(MP4, WebM. Se reemplaza el anterior al subir uno nuevo)</small></label>

                @if(!empty($hero['video_path']))
                    <div class="pe-video-current">
                        <video muted loop playsinline style="width:100%;max-width:400px;border-radius:8px;border:2px solid var(--pe-border);">
                            <source src="{{ asset($hero['video_path']) }}" type="video/mp4">
                        </video>
                        <div style="margin-top:0.5rem;display:flex;align-items:center;gap:0.5rem;">
                            <i class="fas fa-check-circle" style="color:var(--pe-success);"></i>
                            <span style="font-size:0.8125rem;color:var(--pe-text-light);">
                                {{ basename($hero['video_path']) }}
                            </span>
                        </div>
                    </div>
                @endif

                <div class="pe-image-upload" style="margin-top:0.75rem;">
                    <div>
                        <input type="file" name="hero_video" accept="video/mp4,video/webm" class="pe-file-input">
                        <p class="pe-file-hint">MP4 o WebM. Se reproduce en loop, sin sonido, como fondo del hero.</p>
                    </div>
                </div>
            </div>

            <div class="pe-field">
                <label class="pe-label">Pills de categorias</label>
                <div class="pe-repeater" id="pillsRepeater">
                    <div class="pe-repeater-header">
                        <span>Icono / Label / Slug</span>
                    </div>
                    @php $pills = $hero['pills'] ?? []; @endphp
                    @forelse($pills as $i => $pill)
                        <div class="pe-repeater-item">
                            <span class="pe-repeater-num">{{ $i + 1 }}</span>
                            <input type="text" name="pill_icon[]" class="pe-input pe-input-icon"
                                   value="{{ $pill['icon'] ?? '' }}" placeholder="fas fa-spa">
                            <input type="text" name="pill_label[]" class="pe-input"
                                   value="{{ $pill['label'] ?? '' }}" placeholder="Belleza">
                            <input type="text" name="pill_slug[]" class="pe-input pe-input-slug"
                                   value="{{ $pill['slug'] ?? '' }}" placeholder="belleza">
                            <button type="button" class="pe-btn-remove" onclick="this.closest('.pe-repeater-item').remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @empty
                        <div class="pe-repeater-item">
                            <span class="pe-repeater-num">1</span>
                            <input type="text" name="pill_icon[]" class="pe-input pe-input-icon" placeholder="fas fa-spa">
                            <input type="text" name="pill_label[]" class="pe-input" placeholder="Belleza">
                            <input type="text" name="pill_slug[]" class="pe-input pe-input-slug" placeholder="belleza">
                            <button type="button" class="pe-btn-remove" onclick="this.closest('.pe-repeater-item').remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="pe-btn-add" onclick="addPill()">
                    <i class="fas fa-plus"></i> Agregar pill
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SECCION: NEGOCIOS
         ═══════════════════════════════════════════ --}}
    <div class="pe-section">
        <div class="pe-section-header" onclick="this.closest('.pe-section').classList.toggle('expanded')">
            <div class="pe-section-icon" style="background:#10b981;"><i class="fas fa-store"></i></div>
            <span class="pe-section-title">Seccion Negocios</span>
            <i class="fas fa-chevron-down pe-section-toggle"></i>
        </div>
        <div class="pe-section-body">
            <div class="pe-field">
                <label class="pe-label">Titulo de la seccion</label>
                <input type="text" name="businesses_title" class="pe-input pe-input-sm"
                       value="{{ $businesses['title'] ?? '' }}"
                       placeholder="Negocios en Calendarix">
            </div>
            <div class="pe-field-row">
                <div class="pe-field">
                    <label class="pe-label">Texto del link "Ver todos"</label>
                    <input type="text" name="businesses_link_text" class="pe-input"
                           value="{{ $businesses['link_text'] ?? '' }}"
                           placeholder="Ver todos">
                </div>
                <div class="pe-field">
                    <label class="pe-label">Texto del boton explorar</label>
                    <input type="text" name="businesses_btn_text" class="pe-input"
                           value="{{ $businesses['btn_text'] ?? '' }}"
                           placeholder="Explorar mas negocios">
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SECCION: PRICING
         ═══════════════════════════════════════════ --}}
    <div class="pe-section">
        <div class="pe-section-header" onclick="this.closest('.pe-section').classList.toggle('expanded')">
            <div class="pe-section-icon" style="background:#f59e0b;"><i class="fas fa-tags"></i></div>
            <span class="pe-section-title">Seccion Precios</span>
            <i class="fas fa-chevron-down pe-section-toggle"></i>
        </div>
        <div class="pe-section-body">
            <div class="pe-field">
                <label class="pe-label">Titulo</label>
                <input type="text" name="pricing_title" class="pe-input pe-input-sm"
                       value="{{ $pricing['title'] ?? '' }}"
                       placeholder="Planes para tu negocio">
            </div>
            <div class="pe-field">
                <label class="pe-label">Subtitulo</label>
                <input type="text" name="pricing_subtitle" class="pe-input"
                       value="{{ $pricing['subtitle'] ?? '' }}"
                       placeholder="Elige el plan que mejor se adapte al tamano y necesidades de tu negocio">
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SECCION: FEATURES
         ═══════════════════════════════════════════ --}}
    <div class="pe-section">
        <div class="pe-section-header" onclick="this.closest('.pe-section').classList.toggle('expanded')">
            <div class="pe-section-icon" style="background:#8b5cf6;"><i class="fas fa-star"></i></div>
            <span class="pe-section-title">Seccion Features (Por que elegirnos)</span>
            <i class="fas fa-chevron-down pe-section-toggle"></i>
        </div>
        <div class="pe-section-body">
            <div class="pe-field">
                <label class="pe-label">Titulo</label>
                <input type="text" name="features_title" class="pe-input pe-input-sm"
                       value="{{ $features['title'] ?? '' }}"
                       placeholder="¿Por que elegir Calendarix?">
            </div>
            <div class="pe-field">
                <label class="pe-label">Subtitulo</label>
                <input type="text" name="features_subtitle" class="pe-input"
                       value="{{ $features['subtitle'] ?? '' }}"
                       placeholder="La plataforma mas confiable para reservar servicios profesionales">
            </div>

            <div class="pe-field">
                <label class="pe-label">Cards de features <small>(hasta 6)</small></label>
                <div class="pe-repeater" id="featuresRepeater">
                    <div class="pe-repeater-header">
                        <span>Icono / Titulo / Descripcion</span>
                    </div>
                    @php $cards = $features['cards'] ?? []; @endphp
                    @forelse($cards as $i => $card)
                        <div class="pe-repeater-item" style="flex-direction:column;align-items:stretch;">
                            <div style="display:flex;gap:0.625rem;align-items:center;">
                                <span class="pe-repeater-num">{{ $i + 1 }}</span>
                                <input type="text" name="feature_icon[]" class="pe-input pe-input-icon"
                                       value="{{ $card['icon'] ?? '' }}" placeholder="fas fa-clock">
                                <input type="text" name="feature_title[]" class="pe-input"
                                       value="{{ $card['title'] ?? '' }}" placeholder="Reserva 24/7">
                                <button type="button" class="pe-btn-remove" onclick="this.closest('.pe-repeater-item').remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <textarea name="feature_desc[]" class="pe-textarea" rows="2"
                                      placeholder="Descripcion de la feature..." style="margin-top:0.5rem;">{{ $card['description'] ?? '' }}</textarea>
                        </div>
                    @empty
                        @for($i = 0; $i < 3; $i++)
                        <div class="pe-repeater-item" style="flex-direction:column;align-items:stretch;">
                            <div style="display:flex;gap:0.625rem;align-items:center;">
                                <span class="pe-repeater-num">{{ $i + 1 }}</span>
                                <input type="text" name="feature_icon[]" class="pe-input pe-input-icon" placeholder="fas fa-clock">
                                <input type="text" name="feature_title[]" class="pe-input" placeholder="Titulo feature">
                                <button type="button" class="pe-btn-remove" onclick="this.closest('.pe-repeater-item').remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <textarea name="feature_desc[]" class="pe-textarea" rows="2"
                                      placeholder="Descripcion de la feature..." style="margin-top:0.5rem;"></textarea>
                        </div>
                        @endfor
                    @endforelse
                </div>
                <button type="button" class="pe-btn-add" onclick="addFeature()">
                    <i class="fas fa-plus"></i> Agregar feature
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         SECCION: CTA
         ═══════════════════════════════════════════ --}}
    <div class="pe-section">
        <div class="pe-section-header" onclick="this.closest('.pe-section').classList.toggle('expanded')">
            <div class="pe-section-icon" style="background:#ef4444;"><i class="fas fa-bullhorn"></i></div>
            <span class="pe-section-title">Seccion CTA (Llamada a la Accion)</span>
            <i class="fas fa-chevron-down pe-section-toggle"></i>
        </div>
        <div class="pe-section-body">
            <div class="pe-field">
                <label class="pe-label">Titulo</label>
                <input type="text" name="cta_title" class="pe-input pe-input-sm"
                       value="{{ $cta['title'] ?? '' }}"
                       placeholder="¿Tenes un negocio?">
            </div>
            <div class="pe-field">
                <label class="pe-label">Subtitulo</label>
                <textarea name="cta_subtitle" class="pe-textarea" rows="2"
                          placeholder="Unite a miles de profesionales que usan Calendarix...">{{ $cta['subtitle'] ?? '' }}</textarea>
            </div>
            <div class="pe-field-row">
                <div class="pe-field">
                    <label class="pe-label">Texto boton primario</label>
                    <input type="text" name="cta_btn1_text" class="pe-input"
                           value="{{ $cta['btn1_text'] ?? '' }}"
                           placeholder="Registrar mi Negocio">
                </div>
                <div class="pe-field">
                    <label class="pe-label">Icono boton primario <small>(clase FA)</small></label>
                    <input type="text" name="cta_btn1_icon" class="pe-input"
                           value="{{ $cta['btn1_icon'] ?? '' }}"
                           placeholder="fas fa-rocket">
                </div>
            </div>
            <div class="pe-field-row">
                <div class="pe-field">
                    <label class="pe-label">Texto boton secundario</label>
                    <input type="text" name="cta_btn2_text" class="pe-input"
                           value="{{ $cta['btn2_text'] ?? '' }}"
                           placeholder="Ver como funciona">
                </div>
                <div class="pe-field">
                    <label class="pe-label">Icono boton secundario <small>(clase FA)</small></label>
                    <input type="text" name="cta_btn2_icon" class="pe-input"
                           value="{{ $cta['btn2_icon'] ?? '' }}"
                           placeholder="fas fa-play-circle">
                </div>
            </div>
        </div>
    </div>

    {{-- SUBMIT --}}
    <div class="pe-submit-bar">
        <button type="submit" class="pe-btn-save">
            <i class="fas fa-save"></i>
            Guardar Cambios
        </button>
        <a href="{{ route('admin.page-editor.index') }}" class="pe-btn-back">
            Cancelar
        </a>
    </div>
</form>

@endsection

@push('scripts')
<script>
function toggleBgType(type) {
    document.querySelectorAll('.pe-bg-panel').forEach(function(el) { el.style.display = 'none'; });
    document.querySelectorAll('.pe-radio-card').forEach(function(el) { el.classList.remove('active'); });
    if (type === 'images') {
        document.getElementById('bgPanelImages').style.display = '';
    } else {
        document.getElementById('bgPanelVideo').style.display = '';
    }
    // Mark active radio card
    document.querySelectorAll('.pe-radio-card input').forEach(function(input) {
        if (input.checked) input.closest('.pe-radio-card').classList.add('active');
    });
}

function previewImage(input, previewId) {
    var preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function addWord() {
    var repeater = document.getElementById('wordsRepeater');
    var count = repeater.querySelectorAll('.pe-repeater-item').length + 1;
    var html = '<div class="pe-repeater-item">' +
        '<span class="pe-repeater-num">' + count + '</span>' +
        '<input type="text" name="hero_words[]" class="pe-input" placeholder="palabra">' +
        '<button type="button" class="pe-btn-remove" onclick="this.closest(\'.pe-repeater-item\').remove()">' +
        '<i class="fas fa-times"></i></button>' +
        '</div>';
    repeater.insertAdjacentHTML('beforeend', html);
}

function addPill() {
    var repeater = document.getElementById('pillsRepeater');
    var count = repeater.querySelectorAll('.pe-repeater-item').length + 1;
    var html = '<div class="pe-repeater-item">' +
        '<span class="pe-repeater-num">' + count + '</span>' +
        '<input type="text" name="pill_icon[]" class="pe-input pe-input-icon" placeholder="fas fa-spa">' +
        '<input type="text" name="pill_label[]" class="pe-input" placeholder="Categoria">' +
        '<input type="text" name="pill_slug[]" class="pe-input pe-input-slug" placeholder="slug">' +
        '<button type="button" class="pe-btn-remove" onclick="this.closest(\'.pe-repeater-item\').remove()">' +
        '<i class="fas fa-times"></i></button>' +
        '</div>';
    repeater.insertAdjacentHTML('beforeend', html);
}

function addFeature() {
    var repeater = document.getElementById('featuresRepeater');
    var count = repeater.querySelectorAll('.pe-repeater-item').length + 1;
    if (count > 6) {
        window.adminShowToast && window.adminShowToast('Maximo 6 features permitidas.', 'info');
        return;
    }
    var html = '<div class="pe-repeater-item" style="flex-direction:column;align-items:stretch;">' +
        '<div style="display:flex;gap:0.625rem;align-items:center;">' +
        '<span class="pe-repeater-num">' + count + '</span>' +
        '<input type="text" name="feature_icon[]" class="pe-input pe-input-icon" placeholder="fas fa-clock">' +
        '<input type="text" name="feature_title[]" class="pe-input" placeholder="Titulo feature">' +
        '<button type="button" class="pe-btn-remove" onclick="this.closest(\'.pe-repeater-item\').remove()">' +
        '<i class="fas fa-times"></i></button></div>' +
        '<textarea name="feature_desc[]" class="pe-textarea" rows="2" ' +
        'placeholder="Descripcion de la feature..." style="margin-top:0.5rem;"></textarea></div>';
    repeater.insertAdjacentHTML('beforeend', html);
}
</script>
@endpush
