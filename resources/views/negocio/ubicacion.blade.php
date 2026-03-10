
    <link rel="stylesheet" href="{{ asset('css/negocios/ubicacion-negocios.css') }}">

<!-- Fondo animado con partículas -->
<div class="background-animation">
    @for($i = 1; $i <= 10; $i++)
        <div class="particle"></div>
    @endfor
</div>

<div class="container">
    <div class="form-wrapper">
        <div class="form-content">
            <h3>Configuracion de cuenta</h3>
            <h1><strong>Indica la direccion de tu centro</strong></h1>
            <p>Busca tu direccion en el mapa y ajusta el marcador a la ubicacion exacta de tu negocio.</p>

            <form action="{{ route('negocio.ubicacion.store') }}" method="POST" id="ubicacionForm">
                @csrf

                <label for="neg_direccion">¿Cual es la ubicacion de tu negocio?</label>
                <input
                    type="text"
                    name="neg_direccion"
                    id="neg_direccion"
                    placeholder="Ej: Av. 18 de Julio 1234, Montevideo..."
                    value="{{ old('neg_direccion') }}"
                >
                <div id="mapError" class="error-message" style="display:none;">
                    No se pudo cargar Google Maps. Verifica que la API key tenga habilitadas las APIs: Maps JavaScript API y Places API.
                </div>
                @error('neg_direccion')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <!-- Mapa -->
                <div class="map-section" id="mapSection">
                    <div class="map-container" id="map"></div>
                    <p class="map-hint">
                        <i class="map-hint-icon"></i>
                        Arrastra el marcador para ajustar la ubicacion exacta
                    </p>
                </div>

                <!-- Campos ocultos para coordenadas -->
                <input type="hidden" name="neg_latitud" id="neg_latitud" value="{{ old('neg_latitud') }}">
                <input type="hidden" name="neg_longitud" id="neg_longitud" value="{{ old('neg_longitud') }}">

                <label>
                    <input type="checkbox" name="neg_virtual" value="1" id="neg_virtual" {{ old('neg_virtual') ? 'checked' : '' }}>
                    Mi negocio no tiene una direccion fisica (solo ofrezco servicios por telefono y online)
                </label>
                @error('neg_virtual')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <div class="buttons-row">
                    <a href="{{ route('negocio.equipo') }}" class="btn-volver">← Volver</a>
                    <button type="submit">Continuar &rarr;</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var mapInstance = null;
    var marker = null;
    var geocoder = null;
    var autocomplete = null;

    function initMap() {
        var defaultLat = {{ old('neg_latitud', -34.9011) }};
        var defaultLng = {{ old('neg_longitud', -56.1645) }};
        var hasOldCoords = {{ old('neg_latitud') ? 'true' : 'false' }};
        var defaultZoom = hasOldCoords ? 17 : 13;

        var mapDiv = document.getElementById('map');
        mapInstance = new google.maps.Map(mapDiv, {
            center: { lat: defaultLat, lng: defaultLng },
            zoom: defaultZoom,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            styles: [
                { featureType: 'poi', stylers: [{ visibility: 'simplified' }] },
                { featureType: 'transit', stylers: [{ visibility: 'off' }] }
            ]
        });

        marker = new google.maps.Marker({
            position: { lat: defaultLat, lng: defaultLng },
            map: mapInstance,
            draggable: true,
            animation: google.maps.Animation.DROP,
            title: 'Ubicacion de tu negocio'
        });

        geocoder = new google.maps.Geocoder();

        // Places Autocomplete
        var input = document.getElementById('neg_direccion');

        try {
            autocomplete = new google.maps.places.Autocomplete(input, {
                fields: ['geometry', 'formatted_address', 'name']
            });

            // Bind autocomplete to the map bounds for better local results
            autocomplete.bindTo('bounds', mapInstance);

            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();

                if (!place.geometry || !place.geometry.location) {
                    // User pressed Enter without selecting a suggestion — geocode manually
                    geocodeAddress(input.value);
                    return;
                }

                var loc = place.geometry.location;
                mapInstance.setCenter(loc);
                mapInstance.setZoom(17);
                marker.setPosition(loc);
                updateCoords(loc.lat(), loc.lng());

                if (place.formatted_address) {
                    input.value = place.formatted_address;
                }
            });
        } catch (e) {
            console.error('Places API error:', e);
            document.getElementById('mapError').style.display = 'flex';
        }

        // Prevent form submit on Enter while autocomplete is open
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                var pacContainer = document.querySelector('.pac-container');
                if (pacContainer && pacContainer.style.display !== 'none') {
                    e.preventDefault();
                    return;
                }
                // If no autocomplete visible, geocode manually
                e.preventDefault();
                geocodeAddress(input.value);
            }
        });

        // Geocode fallback: search by text when autocomplete doesn't trigger
        function geocodeAddress(address) {
            if (!address || address.trim() === '') return;
            geocoder.geocode({ address: address }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    var loc = results[0].geometry.location;
                    mapInstance.setCenter(loc);
                    mapInstance.setZoom(17);
                    marker.setPosition(loc);
                    updateCoords(loc.lat(), loc.lng());
                    input.value = results[0].formatted_address;
                }
            });
        }

        // Drag marker → reverse geocode
        marker.addListener('dragend', function () {
            var pos = marker.getPosition();
            updateCoords(pos.lat(), pos.lng());

            geocoder.geocode({ location: pos }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    input.value = results[0].formatted_address;
                }
            });
        });

        // Click on map → move marker
        mapInstance.addListener('click', function (e) {
            marker.setPosition(e.latLng);
            updateCoords(e.latLng.lat(), e.latLng.lng());

            geocoder.geocode({ location: e.latLng }, function (results, status) {
                if (status === 'OK' && results[0]) {
                    input.value = results[0].formatted_address;
                }
            });
        });

        // Virtual checkbox toggle
        var virtualCb = document.getElementById('neg_virtual');
        var mapSection = document.getElementById('mapSection');

        function toggleMap() {
            if (virtualCb.checked) {
                mapSection.style.display = 'none';
            } else {
                mapSection.style.display = 'block';
                google.maps.event.trigger(mapInstance, 'resize');
            }
        }

        virtualCb.addEventListener('change', toggleMap);
        toggleMap();
    }

    function updateCoords(lat, lng) {
        document.getElementById('neg_latitud').value = lat;
        document.getElementById('neg_longitud').value = lng;
    }

    function gm_authFailure() {
        document.getElementById('mapError').style.display = 'flex';
        console.error('Google Maps authentication failed. Check your API key and enabled APIs.');
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtfFmZQIfa0vxx07f3fNzHsN7tcxcerxM&libraries=places&callback=initMap" async defer></script>
