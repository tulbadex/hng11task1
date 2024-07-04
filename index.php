<?php
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
}

function get_location($ip) {
    $location = "Not found";

    // Use geolocation-db.com for location
    $location_api_url = "https://geolocation-db.com/jsonp/$ip";
    $location_data = file_get_contents($location_api_url);

    if ($location_data === FALSE) {
        return $location;
    }

    // Remove the JSONP padding and decode the JSON
    $location_data = substr($location_data, strpos($location_data, '(') + 1, -1);
    $location_info = json_decode($location_data);

    if (!empty($location_info->city)) {
        $location = $location_info->city;
    }
    return $location;
}

$visitor_name = isset($_GET['visitor_name']) ? $_GET['visitor_name'] : "Guest";
$visitor_name = preg_replace('~^"?(.*?)"?$~', '$1', $visitor_name);
$client_ip = get_client_ip();
$location = get_location($client_ip);

$response = array(
    "client_ip" => $client_ip,
    "location" => $location,
    "greeting" => "Hello, $visitor_name!, the temperature is 11 degrees Celsius in $location"
);

header('Content-Type: application/json');
echo json_encode($response);
?>