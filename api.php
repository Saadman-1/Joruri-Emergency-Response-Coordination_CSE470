<?php
header('Content-Type: application/json');

if (!isset($_GET['lat']) || !isset($_GET['lon']) || !isset($_GET['type'])) {
    echo json_encode(["error" => "Missing parameters"]);
    exit;
}

$userLat = floatval($_GET['lat']);
$userLon = floatval($_GET['lon']);
$type = $_GET['type']; // "shelter" or "medical"

// Build Overpass query
if ($type === 'medical') {
    $overpassQuery = '[out:json][timeout:25];
        (
          node["amenity"~"hospital|clinic|doctors|pharmacy"](around:5000,' . $userLat . ',' . $userLon . ');
          way["amenity"~"hospital|clinic|doctors|pharmacy"](around:5000,' . $userLat . ',' . $userLon . ');
          relation["amenity"~"hospital|clinic|doctors|pharmacy"](around:5000,' . $userLat . ',' . $userLon . ');
        );
        out center;';
} else {
    $overpassQuery = '[out:json][timeout:25];
        (
          node["amenity"="shelter"](around:5000,' . $userLat . ',' . $userLon . ');
          way["amenity"="shelter"](around:5000,' . $userLat . ',' . $userLon . ');
          relation["amenity"="shelter"](around:5000,' . $userLat . ',' . $userLon . ');
        );
        out center;';
}

$url = "https://overpass-api.de/api/interpreter?data=" . urlencode($overpassQuery);
$response = file_get_contents($url);

if ($response === FALSE) {
    echo json_encode(["error" => "Overpass API request failed"]);
    exit;
}

$data = json_decode($response, true);

// Haversine distance function (km)
function haversine($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earthRadius * $c;
}

$results = [];
if (isset($data['elements'])) {
    foreach ($data['elements'] as $el) {
        $lat = isset($el['lat']) ? $el['lat'] : (isset($el['center']['lat']) ? $el['center']['lat'] : null);
        $lon = isset($el['lon']) ? $el['lon'] : (isset($el['center']['lon']) ? $el['center']['lon'] : null);

        if ($lat && $lon) {
            $name = isset($el['tags']['name']) ? $el['tags']['name'] : ucfirst($type) . " (Unnamed)";
            $distance = haversine($userLat, $userLon, $lat, $lon);
            $results[] = [
                "name" => $name,
                "latitude" => $lat,
                "longitude" => $lon,
                "distance" => round($distance, 2)
            ];
        }
    }
}

// Sort results by distance (ascending)
usort($results, function($a, $b) {
    return $a['distance'] <=> $b['distance'];
});

echo json_encode($results);
exit;
?>
