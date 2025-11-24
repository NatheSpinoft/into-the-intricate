<?php
session_start();
require './assets/src/config.php'; // PDO connection ($pdo)

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$username = $_SESSION['username'] ?? 'User';
$user_id = $_SESSION['user_id'];

// Handle posting a new chirp
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['chirp'])) {
    $chirp = trim($_POST['chirp']);
    
    $stmt = $pdo->prepare("INSERT INTO chirps (user_id, chirp) VALUES (:user_id, :chirp)");
    $stmt->execute([
        'user_id' => $user_id,
        'chirp' => $chirp
    ]);

    // Redirect to avoid form resubmission
    header('Location: chirper.php');
    exit;
}

// Fetch chirps
$stmt = $pdo->query("SELECT c.chirp, c.created_at, u.username
                     FROM chirps c
                     JOIN users u ON c.user_id = u.id
                     ORDER BY c.created_at DESC");
$chirps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Chirper</title>
    <link rel="stylesheet" href="./assets/css/styles-chirper.css">
    <link rel="icon" href="./assets/bird.png" type="image/png">
</head>
<body>
    <header class="chirper-header">
        <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <a href="logout.php" class="logout-btn">Logout</a>
    </header>

    <div class="chirp-container">
        <div class="chirp-thread">
            <h2>Chirp Thread</h2>
            <div class="chirp-content">
                <?php
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
            </div>
        </div>

        <div class="chirp-box">
            <form action="chirper.php" method="POST">
                <label for="chirp">What's on your mind?</label><br>
                <textarea id="chirp" name="chirp" rows="4" cols="50" maxlength="280" required></textarea><br>
                <input type="submit" value="Chirp">
            </form>
        </div>
    </div>
</body>
</html>
