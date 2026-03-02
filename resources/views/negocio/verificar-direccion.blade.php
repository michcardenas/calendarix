
    <link rel="stylesheet" href="{{ asset('css/negocios/veri-ubi-negocios.css') }}">

<!-- Fondo animado con partículas -->
<div class="background-animation">
    @for($i = 1; $i <= 12; $i++)
        <div class="particle"></div>
    @endfor
</div>

<div class="container">
    <div class="form-wrapper">
        <div class="form-content">
            <h3>Configuracion de cuenta</h3>
            <h1><strong>Verifica la direccion de tu centro</strong></h1>
            <p>Asegurate de que el marcador esta en la ubicacion correcta. Puedes arrastrarlo para ajustar.</p>

            @if($direccion)
                <div class="direccion-preview">
                    <span class="direccion-preview-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    </span>
                    <span>{{ $direccion }}</span>
                </div>
            @endif

            @if(!$esVirtual)
                <div class="map-container" id="map"></div>
                <p class="map-hint">
                    Arrastra el marcador si necesitas ajustar la ubicacion
                </p>
            @else
                <div class="virtual-notice">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <span>Tu negocio es virtual, no se requiere ubicacion en el mapa.</span>
                </div>
            @endif

            <form action="{{ route('negocio.verificacion.store') }}" method="POST">
                @csrf
                <input type="hidden" name="neg_latitud" id="neg_latitud" value="{{ $latitud }}">
                <input type="hidden" name="neg_longitud" id="neg_longitud" value="{{ $longitud }}">
                <button type="submit">Confirmar ubicacion y continuar &rarr;</button>
            </form>
        </div>
    </div>
</div>

@if(!$esVirtual)
<script>
    function initMap() {
        var lat = {{ $latitud ?? -34.9011 }};
        var lng = {{ $longitud ?? -56.1645 }};
        var hasCoords = {{ ($latitud && $longitud) ? 'true' : 'false' }};
        var zoom = hasCoords ? 17 : 13;

        var mapDiv = document.getElementById('map');
        var map = new google.maps.Map(mapDiv, {
            center: { lat: lat, lng: lng },
            zoom: zoom,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            styles: [
                { featureType: 'poi', stylers: [{ visibility: 'simplified' }] },
                { featureType: 'transit', stylers: [{ visibility: 'off' }] }
            ]
        });

        var marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            title: 'Ubicacion de tu negocio'
        });

        marker.addListener('dragend', function () {
            var pos = marker.getPosition();
            document.getElementById('neg_latitud').value = pos.lat();
            document.getElementById('neg_longitud').value = pos.lng();
        });

        map.addListener('click', function (e) {
            marker.setPosition(e.latLng);
            document.getElementById('neg_latitud').value = e.latLng.lat();
            document.getElementById('neg_longitud').value = e.latLng.lng();
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtfFmZQIfa0vxx07f3fNzHsN7tcxcerxM&callback=initMap" async defer></script>
@endif
