<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

// Include PDO connection
require_once '../config/config.php';

$username = $_SESSION['username'];

// Get week offset from URL (0 = current week, -1 = last week, 1 = next week)
$weekOffset = isset($_GET['week']) ? (int)$_GET['week'] : 0;

// Calculate the reference date based on offset
$referenceDate = date('Y-m-d', strtotime("+{$weekOffset} weeks"));

// Determine week: Sunday → Saturday
$dayOfWeek = date('w', strtotime($referenceDate)); // 0 = Sunday
$sunday = date('Y-m-d', strtotime("-{$dayOfWeek} days", strtotime($referenceDate)));
$saturday = date('Y-m-d', strtotime("+6 days", strtotime($sunday)));

// Fetch all timecards for this user in the selected week
try {
    $stmt = $pdo->prepare("
        SELECT * FROM timecards 
        WHERE username = ? 
        AND DATE(created_at) BETWEEN ? AND ?
        ORDER BY FIELD(day, 'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')
    ");
    $stmt->execute([$username, $sunday, $saturday]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

// Map entries by day for quick lookup
$timecards = [];
$totalHours = 0;
foreach ($results as $row) {
    $timecards[$row['day']] = $row;
    $totalHours += (float)$row['hours'];
}

// Days of the week
$daysOfWeek = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

// Calculate previous and next week offsets
$prevWeek = $weekOffset - 1;
$nextWeek = $weekOffset + 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timecard</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/layout.css">
    <link rel="stylesheet" href="../assets/css/table.css">
    <link rel="stylesheet" href="../assets/css/form.css">
    <style>
       
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="head-container">
            <div class="welcome">
                <h1>Welcome: <?php echo htmlspecialchars($username); ?></h1>
            </div>
            <div class="button-group">
                <a href="../includes/menu.php">HOME</a>
                <a href="../includes/logout.php">LOG OUT</a>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidenav">
            <ul>
                <li><a href="timecard.php" class="active">Time</a></li>
                <li><a href="../invoice/invoice.php">Invoices</a></li>
                <li><a href="../invoice/payables.php">Payables</a></li>
                <li><a href="../invoice/view_invoices.php">View All Invoices</a></li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="main">
            <h1>Timecard</h1>

            <!-- Week Navigation -->
            <div class="week-navigation">
                <a href="?week=<?= $prevWeek ?>">← Previous Week</a>
                <div class="week-info">
                    <span class="<?= $weekOffset === 0 ? 'current-week' : '' ?>">
                        Week: <?= date('M d', strtotime($sunday)) ?> - <?= date('M d, Y', strtotime($saturday)) ?>
                    </span>
                    <?php if ($weekOffset === 0): ?>
                        <span style="color: #7b2f2fa7; margin-left: 10px;">(Current Week)</span>
                    <?php endif; ?>
                </div>
                <a href="?week=<?= $nextWeek ?>">Next Week →</a>
            </div>

            <!-- Form to add time entries (only show for current week) -->
            <?php if ($weekOffset === 0): ?>
            <form class="time-entry-form" method="POST" action="add_timecard.php">
                <label>Day:
                    <select name="day" required>
                        <?php foreach($daysOfWeek as $dayOption): ?>
                        <option value="<?= $dayOption ?>"><?= $dayOption ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Start Time: <input type="time" name="start_time" required></label>
                <label>End Time: <input type="time" name="end_time" required></label>
                <button type="submit">Add Entry</button>
            </form>
            <?php else: ?>
            <p style="padding: 10px; background-color: #fff3cd; border-left: 4px solid #ffc107;">
                ℹ️ You're viewing a <?= $weekOffset < 0 ? 'past' : 'future' ?> week. 
                <a href="timecard.php">Go to current week</a> to add entries.
            </p>
            <?php endif; ?>

            <!-- Timecard table -->
            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Hours Worked</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($daysOfWeek as $day): 
                        $entry = $timecards[$day] ?? null;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($day) ?></td>
                            <td><?= htmlspecialchars($entry['start_time'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($entry['end_time'] ?? '-') ?></td>
                            <td><?= $entry ? number_format($entry['hours'], 2) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: bold;">Total Hours:</td>
                        <td style="font-weight: bold;"><?= number_format($totalHours, 2) ?></td>
                    </tr>
                </tfoot>
            </table>

            <?php if ($totalHours > 0): ?>
            <div class="total-hours">
                📊 Total hours for this week: <?= number_format($totalHours, 2) ?> hours
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>