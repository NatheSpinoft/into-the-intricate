<?php
session_start();
require 'db/database.php';

// Only allow admin access
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// Week calculation with proper rollover: Monday → Sunday
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

// Calculate Sunday
$sunday = clone $monday;
$sunday->modify('+6 days');

// Generate week days and dates
$week_days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
$week_dates = [];
for($i=0; $i<7; $i++){
    $day = clone $monday;
    $day->modify("+$i day");
    $week_dates[$day->format('l')] = $day->format('Y-m-d'); // e.g., Monday => 2025-11-11
}

// Fetch all users
$users = $pdo->query("SELECT id, username FROM users ORDER BY username")->fetchAll(PDO::FETCH_ASSOC);

// Selected user
$selected_user = $_POST['selected_user'] ?? ($users[0]['id'] ?? null);

// Handle approval / reset / edit
if(isset($_POST['approve'])){
    $pdo->prepare("UPDATE time_entries SET approved=1 WHERE id=?")->execute([$_POST['entry_id']]);
}
if(isset($_POST['reset_approval'])){
    $pdo->prepare("UPDATE time_entries SET approved=0 WHERE id=?")->execute([$_POST['entry_id']]);
}
if(isset($_POST['edit_hours'])){
    $pdo->prepare("UPDATE time_entries SET time_in=?, time_out=?, approved=0 WHERE id=?")
        ->execute([$_POST['time_in'], $_POST['time_out'], $_POST['entry_id']]);
}

// Ensure every day of current week exists for the selected user
foreach($week_days as $day){
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM time_entries WHERE user_id=? AND day=?");
    $stmt->execute([$selected_user, $day]);
    if($stmt->fetchColumn() == 0){
        $insert = $pdo->prepare("INSERT INTO time_entries (user_id, day, time_in, time_out, approved) VALUES (?, ?, '00:00:00', '00:00:00', 0)");
        $insert->execute([$selected_user, $day]);
    }
}

// Fetch updated entries
$stmt = $pdo->prepare("SELECT * FROM time_entries WHERE user_id=? AND day IN ('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')");
$stmt->execute([$selected_user]);
$entries_raw = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Re-index by weekday
$entries = [];
foreach($entries_raw as $entry){
    $entries[$entry['day']] = $entry;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Timecard</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .approved { background-color: #d4edda; }  /* green-ish */
        .pending  { background-color: #f8d7da; }  /* red-ish */
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #34495e; color: white; }
        input[type="time"] { width: 90px; }
        button { padding: 4px 8px; margin: 2px; }
    </style>
</head>
<body>
<div class="sidebar">
    <h2>Admin Dashboard</h2>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <h2>Time Entries: <?= $monday->format('M d') ?> - <?= $sunday->format('M d, Y') ?></h2>

    <!-- User selection dropdown -->
    <form method="post" style="margin-bottom:20px;">
        <label for="selected_user">Select User:</label>
        <select name="selected_user" id="selected_user" onchange="this.form.submit()">
            <?php foreach($users as $user): ?>
                <option value="<?= $user['id'] ?>" <?= $selected_user == $user['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user['username']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <!-- Time entries table -->
    <table>
        <tr>
            <th>Day</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Total Hours</th>
            <th>Approved</th>
            <th>Actions</th>
        </tr>

        <?php foreach($week_days as $day):
            $entry = $entries[$day];
            $total = ($entry['time_in'] && $entry['time_out']) ? round((strtotime($entry['time_out']) - strtotime($entry['time_in']))/3600,2) : '';
            $row_class = $entry['approved'] ? 'approved' : 'pending';
        ?>
        <tr class="<?= $row_class ?>">
            <td><?= $day ?></td>
            <td><?= $week_dates[$day] ?></td>
            <td>
                <form method="post" style="display:inline-block">
                    <input type="hidden" name="entry_id" value="<?= $entry['id'] ?>">
                    <input type="hidden" name="selected_user" value="<?= $selected_user ?>">
                    <input type="time" name="time_in" value="<?= $entry['time_in'] ?>" required>
            </td>
            <td>
                    <input type="time" name="time_out" value="<?= $entry['time_out'] ?>" required>
            </td>
            <td><?= $total ?></td>
            <td style="color: <?= $entry['approved'] ? 'green':'red' ?>"><?= $entry['approved'] ? 'Approved':'Pending' ?></td>
            <td>
                    <button type="submit" name="edit_hours">Update</button>
                </form>
                <?php if(!$entry['approved']): ?>
                    <form method="post" style="display:inline-block">
                        <input type="hidden" name="entry_id" value="<?= $entry['id'] ?>">
                        <input type="hidden" name="selected_user" value="<?= $selected_user ?>">
                        <button type="submit" name="approve">Approve</button>
                    </form>
                <?php else: ?>
                    <form method="post" style="display:inline-block">
                        <input type="hidden" name="entry_id" value="<?= $entry['id'] ?>">
                        <input type="hidden" name="selected_user" value="<?= $selected_user ?>">
                        <button type="submit" name="reset_approval">Reset</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>