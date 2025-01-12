@extends('layouts.app')

@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Data Marker</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f9f9f9;
                margin: 0;
                padding: 0px;
            }

            .form-container {
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                padding: 10px;
                max-width: 600px;
                margin: 10px auto;
            }

            .form-container h2 {
                text-align: center;
                color: #333;
                margin-bottom: 10px;
            }

            form {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            form label {
                font-weight: bold;
                color: #555;
            }

            form input,
            form textarea,
            form select,
            form button {
                font-size: 14px;
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 5px;
                width: 100%;
                box-sizing: border-box;
                transition: border-color 0.3s;
            }

            form input:focus,
            form textarea:focus,
            form select:focus {
                border-color: #4780bd;
                outline: none;
            }

            form textarea {
                resize: vertical;
                min-height: 80px;
            }

            button.btn-submit {
                background-color: #4780bd;
                color: #fff;
                border: none;
                cursor: pointer;
                font-size: 16px;
                padding: 12px;
                border-radius: 5px;
                transition: background-color 0.3s;
            }

            button.btn-submit:hover {
                background-color: #356693;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 30px;
                background-color: #ffffff;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            table thead {
                background-color: #4780bd;
                color: white;
            }

            table th,
            table td {
                padding: 10px;
                text-align: left;
                border: 1px solid #ddd;
                font-size: 14px;
            }

            table tbody tr:nth-child(even) {
                background-color: #f9f9f9;
            }

            table tbody tr:hover {
                background-color: #eaf4ff;
            }

            img {
                max-width: 100px;
                height: auto;
                border-radius: 4px;
            }

            .btn-delete {
                background-color: #e74c3c;
                color: #fff;
                border: none;
                padding: 8px 12px;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            .btn-delete:hover {
                background-color: #c0392b;
            }

            .form-group {
                display: flex;
                align-items: center;
                gap: 10px;
                /* Jarak antara label dan input */
                margin-bottom: 1px;
            }

            .form-group label {
                flex: 0 0 150px;
                /* Lebar tetap untuk label */
                text-align: right;
                /* Agar teks label rata kanan */
            }

            .form-group input,
            .form-group textarea,
            .form-group select {
                flex: 1;
                /* Input akan mengambil sisa ruang */
            }
        </style>

    </head>

    <div class="form-container">
        <h2> <b>Tambah Data Marker</b> </h2>
        <form id="markerForm" method="POST" action="{{ url('api/markers') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="markerName">Nama Lokasi:</label>
                <input type="text" id="markerName" name="name"required />
            </div>

            <div class="form-group">
                <label for="markerLat">Latitude:</label>
                <input type="text" id="markerLat" name="latitude" required />
            </div>

            <div class="form-group">
                <label for="markerLng">Longitude:</label>
                <input type="text" id="markerLng" name="longitude" required />
            </div>

            <div class="form-group">
                <label for="layananKesehatan">Layanan Kesehatan:</label>
                <textarea id="layananKesehatan" name="layanan_kesehatan" required></textarea>
            </div>

            <div class="form-group">
                <label for="jamOperasional">Jam Operasional:</label>
                <input type="text" id="jamOperasional" name="jam_operasional" required />
            </div>

            <div class="form-group">
                <label for="noTelpon">Nomor Telepon:</label>
                <input type="text" id="noTelpon" name="no_telpon"required />
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Lengkap:</label>
                <textarea id="alamat" name="alamat" required></textarea>
            </div>

            <div class="form-group">
                <label for="foto">Foto:</label>
                <input type="file" id="foto" name="foto" accept="image/*" />
            </div>

            <button class="btn-submit" type="submit">Tambah Marker</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Layanan Kesehatan</th>
                <th>Jam Operasional</th>
                <th>No Telepon</th>
                <th>Alamat</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="markerTableBody">
        </tbody>
    </table>
    <script type="text/javascript">
        // Fungsi untuk memuat data marker
        function loadMarkers() {
            fetch("{{ url('api/markers') }}")
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.getElementById("markerTableBody");
                    tableBody.innerHTML = ""; // Kosongkan tabel
                    data.forEach(marker => {
                        const row = document.createElement("tr"); //Buat baris baru
                        row.innerHTML = `
                            <td>${marker.name}</td>
                            <td>${marker.latitude}</td>
                            <td>${marker.longitude}</td>
                            <td>${marker.layanan_kesehatan}</td>
                            <td>${marker.jam_operasional}</td>
                            <td>${marker.no_telpon}</td>
                            <td>${marker.alamat}</td>
                            <td><img src="${marker.foto}" alt="Foto" style="width: 100px; height: auto;"></td>
                            <td><button class="btn-delete" onclick="deleteMarker(${marker.id})">Hapus</button></td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(err => console.error("Gagal memuat data marker:", err));
        }
        // Fungsi untuk menghapus marker
        function deleteMarker(id) {
            if (confirm("Apakah Anda yakin ingin menghapus marker ini?")) {
                fetch(`{{ url('api/markers') }}/${id}`, {
                        //Mengirimkan permintaan HTTP DELETE ke endpoint api/markers/{id}
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                    .then(response => {
                        if (response.ok) {
                            alert("Marker berhasil dihapus.");
                            loadMarkers();
                        } else {
                            alert("Gagal menghapus marker.");
                        }
                    })
                    .catch(err => console.error("Error menghapus marker:", err));
            }
        }
        // Muat data marker saat halaman dimuat
        document.addEventListener("DOMContentLoaded", loadMarkers);
        // Tambahkan event listener untuk form
        document.getElementById("markerForm").addEventListener("submit", function(e) {
            e.preventDefault();
            //Simpan data yang diinput pada form
            const formData = new FormData(document.getElementById("markerForm"));
            //Mengirim permintaan HTTP POST ke endpoint api/markers
            fetch("{{ url('api/markers') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                    },
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    alert("Marker berhasil ditambahkan!");
                    document.getElementById("markerForm").reset();
                    loadMarkers();
                })
                .catch(err => {
                    alert("Terjadi kesalahan saat menambahkan marker.");
                    console.error(err);
                });
        });
    </script>
@endsection
