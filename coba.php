<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'pgweb8'; // Ganti dengan nama database Anda
$username = 'root'; // Ganti dengan username database Anda
$password = ''; // Ganti dengan password database Anda

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mengambil data dari tabel acara8
    $query = $pdo->query("SELECT Kecamatan, longitude, Latitude, luas, jumlah_penduduk FROM pgweb8b");
    $data = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web GIS Kabupaten Sleman</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 0;
            background-color: #edb9d2; /* Contoh warna latar belakang */
        }
        h1, h2 {
            text-align: center;
        }
        /* Navbar */
        .navbar {
            width: 100%;
            background-color: #333; /* Warna latar belakang navbar */
            overflow: hidden;
        }
        .navbar a {
            float: left; /* Mengatur elemen a untuk berada di sebelah kiri */
            display: block;
            color: white; /* Warna teks navbar */
            text-align: center;
            padding: 14px 16px; /* Padding untuk navbar */
            text-decoration: none; /* Menghilangkan garis bawah */
        }
        .navbar a:hover {
            background-color: #ddd; /* Warna latar belakang saat hover */
            color: black; /* Warna teks saat hover */
        }
        .container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            margin-top: 20px;
        }
        #table-container {
            width: 40%;
            margin-right: 20px;
            overflow-y: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #ed0575; /* Warna latar belakang untuk judul kolom */
            color: white; /* Warna teks untuk judul kolom */
            padding: 10px; /* Padding untuk ruang di dalam elemen judul */
        }
        #map {
            width: 65%;
            height: 500px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="#home">Home</a>
        <a href="#about">About</a>
        <a href="#services">Services</a>
        <a href="#contact">Contact</a>
    </div>

    <h1>Web GIS</h1>
    <h2>Kabupaten Sleman</h2>

    <div class="container">
        <!-- Tabel Data -->
        <div id="table-container">
            <table>
                <tr>
                    <th>Kecamatan</th>
                    <th>Longitude</th>
                    <th>Latitude</th>
                    <th>Luas</th>
                    <th>Jumlah Penduduk</th>
                </tr>
                <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['Kecamatan'] ?></td>
                    <td><?= $row['longitude'] ?></td>
                    <td><?= $row['Latitude'] ?></td>
                    <td><?= $row['luas'] ?></td>
                    <td><?= $row['jumlah_penduduk'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Peta -->
        <div id="map"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Inisialisasi peta
        var map = L.map("map").setView([-7.77, 110.30], 12);

        // Tile Layer
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);

        // Data Marker dari PHP
        <?php foreach ($data as $row): ?>
            L.marker([<?= $row['Latitude'] ?>, <?= $row['longitude'] ?>])
                .bindPopup("<b>Kecamatan: <?= $row['kecamatan'] ?></b><br>Luas: <?= $row['luas'] ?> km²<br>Jumlah Penduduk: <?= $row['jumlah_penduduk'] ?>")
                .addTo(map);
        <?php endforeach; ?>
    </script>
</body>
</html>