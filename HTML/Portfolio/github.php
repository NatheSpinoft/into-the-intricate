<?php
function fetchGitHubRepos($username, $limit = 5) {
    $url = "https://api.github.com/users/$username/repos?sort=updated&per_page=$limit";
    
    // GitHub requires a User-Agent header
    $options = [
        "http" => [
            "header" => "User-Agent: PHP"
        ]
    ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    
    if ($response === FALSE) {
        return [];
    }
    
    return json_decode($response, true);
}

function renderGitHubColumn($repos) {
    echo '<div class="project-category"><h2>GitHub Projects</h2>';
    foreach ($repos as $repo) {
        echo '<div class="project-card">';
        echo '<p><strong>' . htmlspecialchars($repo['name']) . '</strong></p>';
        echo '<p>' . htmlspecialchars($repo['description'] ?? 'No description') . '</p>';
        echo '<a href="' . $repo['html_url'] . '" target="_blank">View Repo</a>';
        echo '</div>';
    }
    echo '</div>';
}
?>
