<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Peta & Analitik Geospasial') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        #map-container {
            position: relative;
        }
        #map { 
            height: 75vh; 
            border-radius: 0.5rem;
        }
        #map-filter {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.65);
        }
        #map-filter h4 {
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 16px;
        }
        .filter-group { margin-bottom: 10px; }
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .leaflet-popup-content-wrapper { border-radius: 5px; }
        .leaflet-popup-content { font-size: 14px; line-height: 1.8; }
        .popup-title { font-weight: bold; margin-bottom: 5px; font-size: 16px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        .popup-status { font-style: italic; color: #555; }
        .share-button {
            margin-top: 8px; 
            display: inline-block; 
            background-color: #4285F4; 
            color: white !important; 
            padding: 5px 10px; 
            border-radius: 3px; 
            text-decoration: none;
            font-size: 12px;
        }
        .share-button:hover {
            background-color: #357ae8;
            color: white !important;
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" id="map-container">
                    <div id="map"></div>
                    <div id="map-filter">
                        <h4>Filter Peta</h4>
                        <div class="filter-group">
                            <label>Tipe Laporan:</label>
                            <div><input type="checkbox" id="filter-pengaduan" value="pengaduan" checked> Pengaduan</div>
                            <div><input type="checkbox" id="filter-infrastruktur" value="infrastruktur" checked> Infrastruktur</div>
                        </div>
                        <div class="filter-group">
                            <label>Status:</label>
                            <div><input type="checkbox" class="status-filter" value="baru,Baru" checked> Baru / Verifikasi</div>
                            <div><input type="checkbox" class="status-filter" value="pengerjaan,Pengerjaan" checked> Pengerjaan</div>
                            <div><input type="checkbox" class="status-filter" value="selesai,Selesai" checked> Selesai</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inisialisasi Peta, berpusat di Garut
            const map = L.map('map').setView([-7.216, 107.900], 13);

            // Layer Peta dari OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Fitur Pencarian Lokasi
            const searchProvider = new GeoSearch.OpenStreetMapProvider();
            const searchControl = new GeoSearch.GeoSearchControl({
                provider: searchProvider,
                style: 'bar',
                showMarker: true,
                showPopup: false,
                autoClose: true,
                retainZoomLevel: false,
            });
            map.addControl(searchControl);

            // Definisikan ikon kustom
            const icons = {
                pengaduan: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34],
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png', shadowSize: [41, 41]
                }),
                infrastruktur: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34],
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png', shadowSize: [41, 41]
                })
            };

            let allMarkers = [];
            let markersLayer = L.layerGroup().addTo(map);

            // Ambil data lokasi dari endpoint
            fetch('{{ route("admin.map.locations") }}')
                .then(response => response.json())
                .then(data => {
                    allMarkers = data.map(loc => {
                        let icon = icons[loc.type] || icons['pengaduan'];

                        // === TAMBAHAN FITUR "BAGIKAN LOKASI" DI SINI ===
                        let googleMapsUrl = `https://www.google.com/maps?q=${loc.latitude},${loc.longitude}`;
                        
                        let popupContent = `
                            <div class="popup-title">${loc.title}</div>
                            <div class="popup-status">Status: ${loc.status}</div>
                            <a href="${loc.url}" target="_blank">Lihat Detail &rarr;</a>
                            <br>
                            <a href="${googleMapsUrl}" target="_blank" class="share-button">
                                Bagikan Lokasi
                            </a>
                        `;
                        // ===============================================

                        const marker = L.marker([loc.latitude, loc.longitude], {icon: icon})
                                        .bindPopup(popupContent);
                        
                        marker.options.reportType = loc.type;
                        marker.options.reportStatus = loc.status.toLowerCase();

                        return marker;
                    });
                    
                    updateMapMarkers();
                })
                .catch(error => console.error('Error fetching locations:', error));

            // Fitur Filter Laporan
            const typeFilters = ['filter-pengaduan', 'filter-infrastruktur'];
            const statusFilters = document.querySelectorAll('.status-filter');

            function updateMapMarkers() {
                markersLayer.clearLayers();
                
                const activeTypes = typeFilters.filter(id => document.getElementById(id).checked).map(id => document.getElementById(id).value);
                const activeStatuses = Array.from(statusFilters).filter(el => el.checked).flatMap(el => el.value.split(',').map(s => s.toLowerCase()));

                allMarkers.forEach(marker => {
                    const typeMatch = activeTypes.includes(marker.options.reportType);
                    const statusMatch = activeStatuses.some(status => marker.options.reportStatus.includes(status));
                    
                    if (typeMatch && statusMatch) {
                        marker.addTo(markersLayer);
                    }
                });
            }

            typeFilters.forEach(id => document.getElementById(id).addEventListener('change', updateMapMarkers));
            statusFilters.forEach(el => el.addEventListener('change', updateMapMarkers));
        });
    </script>
    @endpush
</x-app-layout>