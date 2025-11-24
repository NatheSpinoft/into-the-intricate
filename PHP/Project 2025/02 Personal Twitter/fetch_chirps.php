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
        echo '<strong>' . htmlspecialchars($row['username']) . ':</strong> ';
        echo htmlspecialchars($row['chirp']);
        echo '<br><small>' . $row['created_at'] . '</small>';
        echo '</div>';
    }
} else {
    echo '<div class="chirp-box">No chirps yet.</div>';
}
?>
