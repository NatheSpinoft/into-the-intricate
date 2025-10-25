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

<?php
function renderWeatherColumn($weather) {
    $city = htmlspecialchars($weather['city'] ?? 'Unknown');
    $tempC = is_numeric($weather['tempC']) ? round($weather['tempC']) . "°C" : "N/A";
    $tempF = is_numeric($weather['tempF']) ? round($weather['tempF']) . "°F" : "N/A";
    $condition = !empty($weather['condition']) ? ucfirst($weather['condition']) : "N/A";
    $humidity = is_numeric($weather['humidity']) ? $weather['humidity'] . "%" : "N/A";
    $windSpeed = is_numeric($weather['windSpeed']) ? round($weather['windSpeed']) . " m/s" : "N/A";
    ?>
        <div class="weather-column">
            <h3>Weather in <?= $city ?></h3>
            <p><strong>Temperature:</strong> <?= "$tempC / $tempF" ?></p>
            <p><strong>Condition:</strong> <?= $condition ?></p>
            <p><strong>Humidity:</strong> <?= $humidity ?></p>
            <p><strong>Wind Speed:</strong> <?= $windSpeed ?></p>
        </div>
    <?php
}
