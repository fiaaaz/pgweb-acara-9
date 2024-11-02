<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaflet Map with PHP</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        #map {
            width: 100%;
            height: 400px;
        }

        main {
            margin-top: 20px;
        }

        table {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <main>
        <div class="container border border-warning rounded p-3">
            <div class="alert alert-warning text-center" role="alert">
                <h1 style="color: maroon;">WEB GIS KABUPATEN SLEMAN</h1>
                <h2>Provinsi Daerah Istimewa Yogyakarta</h2>
            </div>

            <!-- Map Container -->
            <div id="map" class="mb-4"></div>

            <!-- Data Table -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Kecamatan</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Luas</th>
                            <th>Jumlah Penduduk</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Create connection
                        $conn = new mysqli("localhost", "root", "", "pgweb8");
                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM pgweb8b";
                        $result = $conn->query($sql);
                        $markers = []; // Initialize array to hold markers
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $longitude = $row['longitude'];
                                $latitude = $row['Latitude'];
                                $info = $row['Kecamatan'];
                                $luas = $row['luas'];
                                $jumlah_penduduk = $row['jumlah_penduduk'];

                                // Display Table Data
                                echo "<tr>
                                    <td>$info</td>
                                    <td>$latitude</td>
                                    <td>$longitude</td>
                                    <td>$luas</td>
                                    <td>$jumlah_penduduk</td>
                                </tr>";

                                // Collect markers data
                                $markers[] = ['latitude' => $latitude, 'longitude' => $longitude, 'info' => $info];
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>No data found</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Initialize Map
        var map = L.map("map").setView([-7.6881964, 110.3425598], 10);

        // Base Map Layer
        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });

        var Esri_WorldImagery = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        });

        var rupabumiindonesia = L.tileLayer('https://geoservices.big.go.id/rbi/rest/services/BASEMAP/Rupabumi_Indonesia/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Badan Informasi Geospasial'
        });

        // Add default map layer
        rupabumiindonesia.addTo(map);

        // Control Layer for base maps
        var baseMaps = {
            "OpenStreetMap": osm,
            "Esri World Imagery": Esri_WorldImagery,
            "Rupa Bumi Indonesia": rupabumiindonesia,
        };

        L.control.layers(baseMaps).addTo(map);

        // Add Scale
        L.control.scale({
            position: "bottomright",
            imperial: false,
        }).addTo(map);

        // Add markers for each kecamatan
        <?php if (!empty($markers)): ?>
            var markers = <?php echo json_encode($markers); ?>;
            markers.forEach(function(marker) {
                L.marker([marker.latitude, marker.longitude]).addTo(map).bindPopup(marker.info);
            });
        <?php endif; ?>
    </script>
</body>

</html>