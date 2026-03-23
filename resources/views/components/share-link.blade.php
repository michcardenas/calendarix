{{-- Barra de enlace público compartible --}}
@php $shareUrl = 'https://calendarix.uy/negocios/' . ($slug ?? ''); @endphp

<div class="share-link-bar">
    <span class="share-link-label"><i class="fas fa-link" style="margin-right:4px;"></i>Tu enlace publico</span>
    <div style="display:flex; flex:1; min-width:0; gap:6px; align-items:center; flex-wrap:wrap;">
        <input type="text" class="share-link-input" value="{{ $shareUrl }}" readonly onclick="this.select()">
        <div class="share-link-actions">
            <button type="button" class="share-link-btn share-link-btn--copy" onclick="copyShareLink(this)">
                <i class="fas fa-copy"></i> Copiar
            </button>
            <a href="https://wa.me/?text={{ urlencode('Agenda tu cita aqui: ' . $shareUrl) }}" target="_blank" class="share-link-btn share-link-btn--wa">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
        </div>
    </div>
</div>

<style>
    .share-link-bar { display:flex; align-items:center; gap:8px; padding:10px 14px; background:#f8f6ff; border:1px dashed #c4b5fd; border-radius:12px; margin-bottom:1rem; flex-wrap:wrap; }
    .share-link-label { font-size:0.75rem; font-weight:600; color:#5a31d7; white-space:nowrap; }
    .share-link-input { flex:1; min-width:140px; padding:6px 10px; border:1px solid #e5e7eb; border-radius:8px; font-size:0.76rem; color:#374151; background:#fff; font-family:'IBM Plex Mono',monospace; cursor:text; }
    .share-link-input:focus { outline:none; border-color:#5a31d7; box-shadow:0 0 0 2px rgba(90,49,215,0.12); }
    .share-link-actions { display:flex; gap:6px; flex-shrink:0; }
    .share-link-btn { display:inline-flex; align-items:center; gap:4px; padding:6px 12px; border-radius:8px; font-size:0.72rem; font-weight:600; border:none; cursor:pointer; transition:all 0.15s; white-space:nowrap; text-decoration:none; font-family:inherit; }
    .share-link-btn--copy { background:#5a31d7; color:#fff; }
    .share-link-btn--copy:hover { background:#4a22b8; }
    .share-link-btn--wa { background:#25d366; color:#fff; }
    .share-link-btn--wa:hover { background:#1ebe57; color:#fff; }
    @media (max-width:767px) {
        .share-link-bar { flex-direction:column; align-items:stretch; gap:6px; padding:10px 12px; }
        .share-link-input { min-width:unset; width:100%; }
        .share-link-actions { display:flex; gap:6px; }
        .share-link-btn { flex:1; justify-content:center; min-height:40px; font-size:0.75rem; }
    }
</style>

<script>
    function copyShareLink(btn) {
        var input = btn.closest('.share-link-bar').querySelector('.share-link-input');
        navigator.clipboard.writeText(input.value).then(function() {
            var orig = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copiado!';
            btn.style.background = '#10b981';
            setTimeout(function() { btn.innerHTML = orig; btn.style.background = ''; }, 2000);
        });
    }
</script>
