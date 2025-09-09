<!DOCTYPE html>
<html>
<head>
    <title>Locator System</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; display: flex; }
        #map { height: 100vh; width: 75%; }
        #sidebar { width: 25%; height: 100vh; overflow-y: auto; padding: 15px; border-left: 1px solid #ccc; }
        button { margin: 5px 0; padding: 10px 15px; font-size: 14px; cursor: pointer; width: 100%; }
        h2 { margin-top: 0; }
        .list-item { padding: 8px; border-bottom: 1px solid #ddd; cursor: pointer; }
        .list-item:hover { background: #f0f0f0; }
    </style>
</head>
<body>
    <div id="map"></div>
    <div id="sidebar">
        <h2>Locator</h2>
        <button onclick="enableLocation()">📍 Share Location</button>
        <button onclick="loadPlaces('shelter')">🏠 Nearby Shelters</button>
        <button onclick="loadPlaces('medical')">🏥 Nearby Medicals</button>
        <h3>Results (sorted by distance)</h3>
        <div id="results"></div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        let map = L.map('map').setView([23.8103, 90.4125], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        let userLat = null, userLon = null, locationEnabled = false;
        let markers = [];

        function enableLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    userLat = position.coords.latitude;
                    userLon = position.coords.longitude;
                    locationEnabled = true;

                    map.setView([userLat, userLon], 14);
                    L.marker([userLat, userLon])
                        .addTo(map)
                        .bindPopup("📍 You are here")
                        .openPopup();
                }, function() {
                    alert("Location access denied or unavailable.");
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function clearMarkers() {
            markers.forEach(m => map.removeLayer(m));
            markers = [];
            document.getElementById("results").innerHTML = "";
        }

        function loadPlaces(type) {
            if (!locationEnabled) {
                alert("Please share your location first.");
                return;
            }
            clearMarkers();

            fetch(`api.php?lat=${userLat}&lon=${userLon}&type=${type}`)
                .then(response => response.json())
                .then(data => {
                    if (!Array.isArray(data) || data.length === 0) {
                        alert("No nearby " + type + "s found.");
                        return;
                    }

                    data.forEach(place => {
                        const marker = L.marker([place.latitude, place.longitude], {
                            icon: type === "medical"
                                ? L.icon({ iconUrl: "https://cdn-icons-png.flaticon.com/512/2966/2966486.png", iconSize: [25, 25] })
                                : L.icon({ iconUrl: "https://cdn-icons-png.flaticon.com/512/252/252025.png", iconSize: [25, 25] })
                        })
                            .addTo(map)
                            .bindPopup(`<b>${place.name}</b><br>Distance: ${place.distance} km`);
                        markers.push(marker);

                        const item = document.createElement("div");
                        item.className = "list-item";
                        item.innerHTML = `<b>${place.name}</b><br>${place.distance} km away`;
                        item.onclick = () => {
                            map.setView([place.latitude, place.longitude], 16);
                            marker.openPopup();
                        };
                        document.getElementById("results").appendChild(item);
                    });
                })
                .catch(error => {
                    console.error("Error loading data:", error);
                    alert("Could not load nearby " + type + "s.");
                });
        }
    </script>
</body>
</html>
