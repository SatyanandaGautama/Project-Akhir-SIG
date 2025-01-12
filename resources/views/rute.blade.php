@extends('layouts.viewLayout')

<head>
    <title>Rute Rumah Sakit</title>
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Leaflet.js --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    {{-- Google Maps API --}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBwlgebS3bplkEr9NEFBhut66Xo-m4muW4&libraries=places">
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 10px;
        }

        .map-container {
            display: flex;
            flex-direction: column;
            /* Ubah menjadi kolom */
            align-items: center;
            gap: 10px;
            /* Jarak antar peta */
            padding: 10px;
            width: 100%;
            /* Memenuhi lebar layar */
            box-sizing: border-box;
        }

        #leaflet-map,
        #google-map {
            width: 100%;
            /* Penuh lebar layar */
            height: 300px;
            /* Tinggi tetap */
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-container label {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-container input {
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            font-size: 16px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
        }

        #suggestions {
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            width: 100%;
            z-index: 1000;
        }

        #suggestions li {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
            font-size: 14px;
        }

        #suggestions li:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col text-center">
                <h2 class="fw-bold">Rute Rumah Sakit</h2>
                <p class="text-muted">Temukan rute terbaik menuju rumah sakit tujuan Anda</p>
            </div>
        </div>

        <div class="map-container">
            <div id="leaflet-map"></div>
            <div id="google-map"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 form-container">
                <form id="routeForm">
                    <div class="mb-3">
                        <label for="currentLat" class="form-label">Latitude Lokasi Saat Ini:</label>
                        <input type="text" id="currentLat" name="currentLat" class="form-control"
                            placeholder="Contoh: -8.7961228" required>
                    </div>

                    <div class="mb-3">
                        <label for="currentLng" class="form-label">Longitude Lokasi Saat Ini:</label>
                        <input type="text" id="currentLng" name="currentLng" class="form-control"
                            placeholder="Contoh: 115.1735968" required>
                    </div>

                    <div class="mb-3">
                        <label for="searchMarker" class="form-label">Cari Rumah Sakit Tujuan:</label>
                        <input type="text" id="searchMarker" class="form-control" placeholder="Nama Rumah Sakit ..."
                            required>
                        <ul id="suggestions" class="list-unstyled"></ul>
                    </div>

                    <div class="text-center">
                        <button type="button" id="showRoute" class="btn btn-primary w-100">Tampilkan Rute</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Leaflet JS
            const leafletMap = L.map('leaflet-map').setView([-8.7443705, 115.161377], 11);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(leafletMap);
            //Variabel untuk searching (menyimpan daftar semua marker rumah sakit)
            let markers = [];
            let markerLayer = L.layerGroup().addTo(leafletMap); // Grouping Lokasi Marker
            let routingControl = null;
            // Google Maps API Map
            const googleMapDiv = document.getElementById('google-map');
            const googleMap = new google.maps.Map(googleMapDiv, {
                center: {
                    lat: -8.7443705,
                    lng: 115.161377
                },
                zoom: 11,
            });
            //Mengambil data marker dari API dengan endpoint /api/markers
            fetch('/api/markers')
                .then(response => response.json())
                .then(data => {
                    markers = data;
                    //Iterasi pada setiap data marker yang didapat
                    data.forEach(marker => {
                        const leafletMarker = L.marker([marker.latitude, marker.longitude])
                            .addTo(markerLayer)
                            .bindPopup(`<b>${marker.name}</b>`);
                        leafletMarker.on('click', () => leafletMarker.openPopup());
                        const googleMarker = new google.maps.Marker({
                            position: {
                                lat: parseFloat(marker.latitude),
                                lng: parseFloat(marker.longitude)
                            },
                            map: googleMap,
                            title: marker.name,
                        });
                        const windowMarker = new google.maps.InfoWindow({
                            content: `<b>${marker.name}</b>`
                        });
                        googleMarker.addListener('click', () => {
                            windowMarker.open(googleMap, googleMarker);
                        });
                    });
                });

            // Fitur auto-suggestion untuk searching Data Marker Rumah Sakit 
            const searchInput = document.getElementById('searchMarker');
            const suggestions = document.getElementById('suggestions');

            searchInput.addEventListener('input', function() {
                //Ambil teks yang diinput pengguna dalam elemen searchInput
                const query = searchInput.value.toLowerCase();
                suggestions.innerHTML = '';

                if (query) {
                    //Memfilter data marker berdasarkan nama marker
                    const filteredMarkers = markers.filter(marker => marker.name.toLowerCase().includes(
                        query));
                    filteredMarkers.forEach(marker => {
                        //Tambah element li untuk setiap data hasil filter
                        const li = document.createElement('li');
                        li.textContent = marker.name;
                        li.style.cursor = 'pointer';
                        //Saat pengguna mengklik sebuah hasil
                        li.addEventListener('click', () => {
                            //Isi searchInput.value dengan nama marker yang dipilih
                            searchInput.value = marker.name;
                            //Kosongkan daftar hasil search/saran
                            suggestions.innerHTML = '';
                        });
                        suggestions.appendChild(li);
                    });
                }
            });

            // Show route
            document.getElementById('showRoute').addEventListener('click', function() {
                const currentLat = parseFloat(document.getElementById('currentLat').value);
                const currentLng = parseFloat(document.getElementById('currentLng').value);
                //Ambil teks yang diinput pengguna dalam elemen searchInput
                const searchValue = searchInput.value.toLowerCase();

                //Mencari Marker yang cocok
                const destination = markers.find(marker => marker.name.toLowerCase() === searchValue);
                if (!destination) {
                    alert('Marker tujuan tidak ditemukan!');
                    return;
                }

                //===Rute Leaflet.js===//
                // Clear previous routing
                if (routingControl) {
                    leafletMap.removeControl(routingControl);
                }
                // Add new routing
                routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(currentLat, currentLng),
                        L.latLng(destination.latitude, destination.longitude)
                    ],
                    routeWhileDragging: false
                }).addTo(leafletMap);
                // Add current location marker and popup
                const currentMarker = L.marker([currentLat, currentLng], {
                    title: "Lokasi Saat Ini"
                }).addTo(markerLayer);
                const currentPopup = L.popup({
                        offset: [0, -30] // Menggeser pop-up ke atas marker
                    })
                    .setLatLng([currentLat, currentLng])
                    .setContent("<b>Lokasi Saat Ini</b>")
                    .addTo(leafletMap);
                // Add destination marker and popup
                const destinationMarker = L.marker([destination.latitude, destination.longitude], {
                    title: destination.name
                }).addTo(markerLayer);
                const destinationPopup = L.popup({
                        offset: [0, -30] // Menggeser pop-up ke atas marker
                    })
                    .setLatLng([destination.latitude, destination.longitude])
                    .setContent(`<b>${destination.name}</b>`)
                    .addTo(leafletMap);
                // Fit map bounds to show all waypoints
                const bounds = L.latLngBounds([
                    [currentLat, currentLng],
                    [destination.latitude, destination.longitude]
                ]);
                leafletMap.fitBounds(bounds, {
                    padding: [50, 50], // Padding di sekitar batas
                    maxZoom: 12 // Atur zoom maksimum
                })
                currentMarker.bindPopup("<b>Lokasi Saat Ini</b>").openPopup();
                currentMarker.on('click', () => {
                    currentMarker.openPopup();
                });
                destinationMarker.bindPopup(`<b>${destination.name}</b>`).openPopup();
                destinationMarker.on('click', () => {
                    destinationMarker.openPopup();
                });

                //====Rute Google Maps====//
                // Layanan Directions
                const directionsService = new google.maps.DirectionsService();
                const directionsRenderer = new google.maps.DirectionsRenderer({
                    suppressMarkers: true
                }); // Menonaktifkan marker bawaan
                // Tampilkan marker posisi saat ini
                const currentGoogleMarker = new google.maps.Marker({
                    position: {
                        lat: currentLat,
                        lng: currentLng
                    },
                    map: googleMap,
                    title: "<b>Lokasi Saat Ini</b>"
                });
                const currentWindowMarker = new google.maps.InfoWindow({
                    content: "<b>Lokasi Saat Ini</b>"
                });
                currentWindowMarker.open(googleMap, currentGoogleMarker);
                currentGoogleMarker.addListener('click', () => {
                    currentWindowMarker.open(googleMap, currentGoogleMarker);
                });
                // Tampilkan rute di peta
                directionsRenderer.setMap(googleMap)
                // Tentukan titik awal dan akhir
                const request = {
                    origin: {
                        lat: currentLat,
                        lng: currentLng
                    },
                    destination: {
                        lat: parseFloat(destination.latitude),
                        lng: parseFloat(destination.longitude)
                    },
                    travelMode: google.maps.TravelMode.DRIVING,
                };
                // Hitung rute
                directionsService.route(request, (result, status) => {
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(result);
                    } else {
                        console.error(`Error fetching directions ${result}`);
                    }
                });
            });
        });
    </script>
@endsection
