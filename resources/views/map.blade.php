@extends('layouts.viewLayout')

<head>
    <title>Lokasi Rumah Sakit</title>
    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Leaflet.js CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    {{-- Google Maps --}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4lKVb0eLSNyhEO-C_8JoHhAvba6aZc3U"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Source Sans Pro', Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Layout */
        .wrapper {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 20px;
        }

        #leaflet-map,
        #google-map {
            width: 48%;
            height: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .table-container {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table-container h3 {
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: #555;
            text-align: center;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        table thead {
            background-color: #f0f0f0;
        }

        th,
        td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            font-weight: bold;
            text-align: center;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 8px 12px;
            font-size: 0.9rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .btn-view {
            background-color: #23812a;
            color: white;
        }

        .btn-view:hover {
            background-color: #23812a;
        }

        .btn-detail {
            background-color: #3070b4;
            color: white;
        }

        .btn-detail:hover {
            background-color: #3070b4;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            /* Set z-index higher than the map */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 70%;
            display: flex;
            justify-content: space-between;
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            /* Menambahkan padding di seluruh modal-content */
            border-radius: 8px;
            width: 70%;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            /* Menambahkan jarak antar elemen dalam modal-content */
        }

        .modal-body {
            margin-top: 10px;
            padding: 15px;
            /* Menambahkan padding untuk memberikan jarak dengan border */
        }

        .modal-header {
            font-size: 1.2rem;
            font-weight: bold;
            padding-bottom: 10px;
            /* Menambahkan jarak bawah agar tidak terlalu rapat */
        }

        .modal-content img {
            width: 300px;
            height: auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

@section('content')
    <div class="wrapper">
        <!-- Peta Leaflet -->
        <div id="leaflet-map"></div>
        <div id="google-map"></div>
    </div>
    <!-- Tabel Data -->
    <div class="table-container">
        <h3>Daftar Rumah Sakit di Kabupaten Badung & Kota Denpasar</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="markerTableBody">
                <!-- Data Marker diload di sini -->
            </tbody>
        </table>
    </div>

    <!-- Modal untuk Detail Marker -->
    <div id="markerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <div>
                <h2 id="modalName"></h2>
                <p style="padding-top: 10px"><strong>Layanan Kesehatan :</strong> <span id="modalLayananKesehatan"></span>
                </p>
                <p><strong>Jam Operasional :</strong> <span id="modalJamOperasional"></span></p>
                <p><strong>No.Telepon :</strong> <span id="modalNoTelpon"></span></p>
                <p><strong>Alamat :</strong> <span id="modalAlamat"></span></p>
            </div>
            <img id="modalImage" src="https://via.placeholder.com/300" alt="Image" />
        </div>
    </div>

    <script type="text/javascript">
        //Peta Leaflet.js
        const leafletMap = L.map('leaflet-map').setView([-8.7443705, 115.161377], 11);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
        }).addTo(leafletMap);
        //Peta Google Maps
        const googleMapDiv = document.getElementById('google-map');
        const googleMap = new google.maps.Map(googleMapDiv, {
            center: {
                lat: -8.7443705,
                lng: 115.161377
            },
            zoom: 11,
        });

        const markerTableBody = document.getElementById('markerTableBody');

        // // Fungsi untuk menghapus semua marker dari peta
        // function clearMarkers() {
        //     leafletMap.eachLayer(layer => {
        //         if (layer instanceof L.Marker) leafletMap.removeLayer(layer);
        //     });
        // }

        // Fungsi untuk memuat data marker
        function loadMarkers() {
            // clearMarkers();
            fetch("{{ url('api/markers') }}")
                .then(response => response.json())
                .then(data => {
                    markerTableBody.innerHTML = '';
                    data.forEach(marker => {
                        markerTableBody.innerHTML += `
                            <tr>
                                <td>${marker.name}</td>
                                <td>${marker.latitude}</td>
                                <td>${marker.longitude}</td>
                                <td>
                                    <button class="btn btn-detail" onclick="showDetails(${marker.id})"><b>Details</b></button> 
                                    <button class="btn btn-view" onclick="viewMarker(${marker.id})"><b>View on Map</b></button>
                                </td>
                            </tr>`;
                        const markerView = L.marker([marker.latitude, marker.longitude]).addTo(leafletMap)
                            .bindPopup(`<b>${marker.name}</b>`);
                        markerView.on('click', () => markerView.openPopup());
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
        }

        // Fungsi untuk melihat lokasi marker di peta
        function viewMarker(id) {
            fetch(`{{ url('api/markers') }}/${id}`)
                .then(response => response.json())
                .then(marker => {
                    // Mengatur tampilan di Leaflet.js
                    leafletMap.setView([marker.latitude, marker.longitude], 15);
                    const markerView = L.marker([marker.latitude, marker.longitude]).addTo(leafletMap);
                    markerView.bindPopup(`<b>${marker.name}</b>`).openPopup();
                    // Mengatur tampilan di Google Maps
                    googleMap.setCenter({
                        lat: parseFloat(marker.latitude),
                        lng: parseFloat(marker.longitude)
                    });
                    googleMap.setZoom(15); // Mengatur zoom pada Google Maps
                    const googleMarkerView = new google.maps.Marker({
                        position: {
                            lat: parseFloat(marker.latitude),
                            lng: parseFloat(marker.longitude)
                        },
                        map: googleMap,
                        title: marker.name,
                    });
                    const googleMarkerWindow = new google.maps.InfoWindow({
                        content: `<b>${marker.name}</b>`
                    });
                    googleMarkerWindow.open(googleMap, googleMarkerView); // Menampilkan popup
                });
        }

        // Fungsi untuk menampilkan detail marker di modal
        function showDetails(id) {
            fetch(`{{ url('api/markers') }}/${id}`)
                .then(response => response.json())
                .then(marker => {
                    document.getElementById("modalName").textContent = marker.name;
                    document.getElementById("modalLayananKesehatan").textContent = marker.layanan_kesehatan;
                    document.getElementById("modalJamOperasional").textContent = marker.jam_operasional;
                    document.getElementById("modalNoTelpon").textContent = marker.no_telpon;
                    document.getElementById("modalAlamat").textContent = marker.alamat;

                    // Ganti sumber foto dengan yang ada di database
                    const modalImage = document.getElementById("modalImage");
                    modalImage.src = marker.foto;
                    modalImage.alt = marker.name;

                    // Tampilkan modal
                    document.getElementById("markerModal").style.display = "block";
                });
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById("markerModal").style.display = "none";
        }

        loadMarkers();
    </script>
@endsection
