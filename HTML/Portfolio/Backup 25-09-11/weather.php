<?php
function getWeather($city, $config) {
    $apiKey = $config['openweathermap_key'];
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($city) . "&appid=$apiKey&units=metric";

    $response = @file_get_contents($apiUrl);
    if ($response === FALSE) {
        return [
            "tempC" => "N/A",
            "tempF" => "N/A",
            "condition" => "N/A",
            "humidity" => "N/A",
            "windSpeed" => "N/A",
            "city" => $city
        ];
    }

    $data = json_decode($response, true);
    $tempC = $data['main']['temp'];
    $tempF = ($tempC * 9/5) + 32;
    $condition = $data['weather'][0]['description'];
    $humidity = $data['main']['humidity'];
    $windSpeed = $data['wind']['speed'];

    return [
        "tempC" => $tempC,
        "tempF" => $tempF,
        "condition" => $condition,
        "humidity" => $humidity,
        "windSpeed" => $windSpeed,
        "city" => $city
    ];
}
?>
