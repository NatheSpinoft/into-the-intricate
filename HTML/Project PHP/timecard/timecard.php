<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

// Include PDO connection
require_once '../config/config.php';

$username = $_SESSION['username'];

// Determine current week: Sunday → Saturday
$today = date('Y-m-d');
$dayOfWeek = date('w', strtotime($today)); // 0 = Sunday
$sunday = date('Y-m-d', strtotime("-{$dayOfWeek} days", strtotime($today)));
$saturday = date('Y-m-d', strtotime("+6 days", strtotime($sunday)));

// Fetch all timecards for this user in the current week
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
foreach ($results as $row) {
    $timecards[$row['day']] = $row;
}

// Days of the week
$daysOfWeek = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
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
                <li><a href="payables.php">Payables</a></li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="main">
            <h1>Timecard</h1>

            <!-- Inline Date + Company Fields -->
            <div class="inline-fields">
                <label>Date: <input type="date" name="entry_date" value="<?= htmlspecialchars($today) ?>"></label>
                <label>Company: <input type="text" name="company_name" placeholder="Enter company name"></label>
            </div>

            <!-- Form to add time entries -->
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

            <p><strong>Week starting: <?= $sunday ?> — ending: <?= $saturday ?></strong></p>

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
                            <td><?= htmlspecialchars($entry['start_time'] ?? '') ?></td>
                            <td><?= htmlspecialchars($entry['end_time'] ?? '') ?></td>
                            <td><?= htmlspecialchars($entry['hours'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
