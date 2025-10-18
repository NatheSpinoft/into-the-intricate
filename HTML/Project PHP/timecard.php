<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Include your PDO connection
include 'config.php';

$username = $_SESSION['username'];

// Determine current week: Sunday → Saturday
$today = date('Y-m-d');
$dayOfWeek = date('w', strtotime($today)); // 0=Sunday
$sunday = date('Y-m-d', strtotime("-{$dayOfWeek} days", strtotime($today)));
$saturday = date('Y-m-d', strtotime("+6 days", strtotime($sunday)));

// Fetch all timecards for this user in the current week
$stmt = $pdo->prepare("
    SELECT * FROM timecards 
    WHERE username = ? 
    AND DATE(created_at) BETWEEN ? AND ?
");
$stmt->execute([$username, $sunday, $saturday]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="tabmenu.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="head-container">
            <div class="welcome">
                <h1>Welcome: <?php echo htmlspecialchars($username); ?></h1>
            </div>
            <div class="button-group">
                <a href="menu.php">HOME</a>
                <a href="logout.php">LOG OUT</a>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidenav">
            <ul>
                <li><a href="timecard.php">Time</a></li>
                <li><a href="invoices.php">Invoices</a></li>
                <li><a href="payables.php">Payables</a></li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="main">
            <h1>Timecard</h1>

            <!-- Form to add time entries -->
            <form class="time-entry-form" method="POST" action="add_timecard.php">
                <label>Day:
                    <select name="day">
                        <?php foreach($daysOfWeek as $dayOption): ?>
                        <option value="<?= $dayOption ?>"><?= $dayOption ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>Start Time: <input type="time" name="start_time" required></label>
                <label>End Time: <input type="time" name="end_time" required></label>
                <button type="submit">Add Entry</button>
            </form>

            <p><strong>Week starting: <?= $sunday ?></strong></p>

            <!-- Timecard table -->
            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Date Started</th>
                        <th>Date Ended</th>
                        <th>Hours Worked</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($daysOfWeek as $day): 
                        $entry = $timecards[$day] ?? null;
                        $date = $entry['created_at'] ?? '';
                        $dateFormatted = $date ? date('Y-m-d', strtotime($date)) : '';
                    ?>
                        <tr>
                            <td><?= $day ?></td>
                            <td><?= $entry['start_time'] ?? '' ?></td>
                            <td><?= $entry['end_time'] ?? '' ?></td>
                            <td><?= $entry['hours'] ?? '' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
