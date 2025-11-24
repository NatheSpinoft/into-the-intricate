<?php
require './assets/src/config.php'; // PDO connection ($pdo)

$stmt = $pdo->query("SELECT c.chirp, c.created_at, u.username
                     FROM chirps c
                     JOIN users u ON c.user_id = u.id
                     ORDER BY c.created_at DESC");

$chirps = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($chirps) {
    foreach ($chirps as $row) {
        echo '<div class="chirp-box">';
        echo '  <div class="chirp-header">';
        echo '    <span class="username">' . htmlspecialchars($row['username']) . '</span>';
        echo '    <span class="chirp-timestamp">' . $row['created_at'] . '</span>';
        echo '  </div>';
        echo '  <div class="chirp-text">' . htmlspecialchars($row['chirp']) . '</div>';
        echo '</div>';

    }
} else {
    echo '<div class="chirp-box">No chirps yet.</div>';
}
?>
