<?php
function fetchRandomProgrammingGif($apiKey) {
    $tag = urlencode("programming,meme,funny");
    $url = "https://api.giphy.com/v1/gifs/random?api_key=$apiKey&tag=$tag&rating=pg";

    $response = file_get_contents($url);
    if ($response === FALSE) {
        return null;
    }

    $data = json_decode($response, true);
    return $data['data']['images']['downsized']['url'] ?? null;
}

function renderGiphySidebar($gifUrl) {
    if (!$gifUrl) {
        echo "<p>No GIF available.</p>";
        return;
    }

    echo '<h2>Programming Meme</h2>';
    echo '<img class="giphy-gif" src="' . htmlspecialchars($gifUrl) . '" alt="Programming Meme" style="width:100%; border-radius:8px;">';

}
?>
