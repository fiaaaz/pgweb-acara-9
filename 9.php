<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Leaflet JS</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map {
            width: 100%;
            height: 600px;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Inisialisasi peta
        var map = L.map("map").setView([110.2478, -7.7774]);

        // Tile Layer Base Map
        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });


        // Menambahkan basemap ke dalam peta
        osm.addTo(map);

        // Marker
        //var marker = L.marker([-6.1753924, 106.8271528]);
        // var marker2 = L.marker([-6.1812862, 106.8286300]);
        //var marker3 = L.marker([-6.1761423, 106.8222034]);

        // Menambahkan marker ke dalam peta
        //   marker.addTo(map);
        //marker2.addTo(map);
        // marker3.addTo(map);

        <?php
        // Create connection
        $conn = new mysqli("localhost", "root", "", "pgweb8");
        // Check connection
        $sql = "SELECT * FROM pgweb8b";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $Latitude = $row["Latitude"];
                $longitude = $row["Longitude"];
                $kecamatan = $row["Kecamatan"];
                echo "L.marker([$Latitude, $longitude]).addTo(map).bindPopup('$Kecamatan');";
            }
        } else {
            echo "0 result";
        }

        $conn->close();
        ?>

        $conn -  close(); 
        
        
        // Control Layer
        var baseMaps = {
            "OpenStreetMap": osm,
            "Esri World Imagery": Esri_WorldImagery,
            "Rupa Bumi Indonesia": rupabumiindonesia,
        };

        var overlayMaps = {
            "Marker": marker,
            "Circle": circle,
            "Polyline": polyline,
            "Polygon": polygon,
            "hilllshade": imageOverlay,
        };

        var controllayer = L.control.layers(baseMaps, overlayMaps, {
            collapsed: false,
        });
        controllayer.addTo(map);

        // Scale
        var scale = L.control.scale({
            position: "bottomright",
            imperial: false,
        });
        scale.addTo(map);
    </script>
</body>

</html>