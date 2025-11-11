<?php
session_start();
require 'db/database.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch username for display
$stmt_user = $pdo->prepare("SELECT username, role FROM users WHERE id=?");
$stmt_user->execute([$user_id]);
$user_row = $stmt_user->fetch(PDO::FETCH_ASSOC);
$username = $user_row['username'] ?? 'Unknown';
$role = $user_row['role'] ?? 'user';

// Get current date and time
$now = new DateTime();

// If it's past midnight on Monday (technically Monday 00:00 or later), 
// we want the week starting from that Monday
// Otherwise, get the previous Monday
if ($now->format('N') == 1 && $now->format('H:i:s') >= '00:00:00') {
    // It's Monday at or after midnight - use this Monday
    $monday = clone $now;
    $monday->setTime(0, 0, 0);
} else {
    // Any other day or before Monday midnight - get the Monday of this week
    $monday = clone $now;
    $monday->modify('Monday this week');
    $monday->setTime(0, 0, 0);
}

// Generate week days (Monday to Sunday)
$week_days = [];
for($i = 0; $i < 7; $i++){
    $date = clone $monday;
    $date->modify("+$i day");
    $week_days[] = [
        'name' => $date->format('l'), 
        'date' => $date->format('Y-m-d')
    ];
}

// Handle time submission (user)
if(isset($_POST['submit_hours']) && $role == 'user'){
    $day = $_POST['day'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];

    $stmt = $pdo->prepare("
        INSERT INTO time_entries (user_id, day, time_in, time_out) 
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE time_in=?, time_out=?, approved=0
    ");
    $stmt->execute([$user_id, $day, $time_in, $time_out, $time_in, $time_out]);
}

// Handle admin approvals
if($role == 'admin'){
    if(isset($_POST['approve'])){
        $entry_id = $_POST['entry_id'];
        $pdo->prepare("UPDATE time_entries SET approved=1 WHERE id=?")->execute([$entry_id]);
    }
}

// Fetch entries for this week
$entries = $pdo->query("SELECT * FROM time_entries WHERE user_id=$user_id")->fetchAll(PDO::FETCH_ASSOC);
$entries_by_day = [];
foreach($entries as $entry){
    $entries_by_day[$entry['day']] = $entry;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Timecard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="sidebar">
    <h2>Welcome, <?= htmlspecialchars($username) ?> (<?= htmlspecialchars($role) ?>)</h2>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <h2>Weekly Timecard (<?= $week_days[0]['date'] ?> - <?= end($week_days)['date'] ?>)</h2>

    <?php if($role=='user'): ?>
    <form method="post">
        <label for="day">Select Day:</label>
        <select name="day" id="day" required>
            <?php foreach($week_days as $day): ?>
                <option value="<?= $day['name'] ?>"><?= $day['name'] ?> (<?= $day['date'] ?>)</option>
            <?php endforeach; ?>
        </select>
        <br><br>
        <label for="time_in">Time In:</label>
        <input type="time" name="time_in" required>
        <label for="time_out">Time Out:</label>
        <input type="time" name="time_out" required>
        <button type="submit" name="submit_hours">Submit Hours</button>
    </form>
    <?php endif; ?>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Day</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Total Hours</th>
            <?php if($role=='admin') echo "<th>User</th>"; ?>
            <th>Approved</th>
            <?php if($role=='admin') echo "<th>Actions</th>"; ?>
        </tr>
        <?php foreach($week_days as $day): 
            $entry = $entries_by_day[$day['name']] ?? ['time_in'=>'','time_out'=>'','approved'=>0,'user_id'=>$user_id];
            $total = ($entry['time_in'] && $entry['time_out']) ? round((strtotime($entry['time_out'])-strtotime($entry['time_in']))/3600,2) : '';
        ?>
        <tr style="background-color: <?= $entry['approved'] ? '#d4edda':'#f8d7da' ?>;">
            <td><?= $day['name'] ?></td>
            <td><?= $day['date'] ?></td>
            <td><?= $entry['time_in'] ?></td>
            <td><?= $entry['time_out'] ?></td>
            <td><?= $total ?></td>
            <?php if($role=='admin'): 
                $stmt_user_name = $pdo->prepare("SELECT username FROM users WHERE id=?");
                $stmt_user_name->execute([$entry['user_id']]);
                $entry_user = $stmt_user_name->fetchColumn();
            ?>
                <td><?= htmlspecialchars($entry_user) ?></td>
            <?php endif; ?>
            <td><?= $entry['approved'] ? 'Approved':'Pending' ?></td>
            <?php if($role=='admin'): ?>
            <td>
                <?php if(!$entry['approved']): ?>
                <form method="post" style="display:inline-block">
                    <input type="hidden" name="entry_id" value="<?= $entry['id'] ?? 0 ?>">
                    <button type="submit" name="approve">Approve</button>
                </form>
                <?php else: ?>-
                <?php endif; ?>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>