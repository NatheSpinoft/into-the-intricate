<?php
session_start();

// Get the current date
$today = new DateTime();
$currentWeekStart = (clone $today)->modify('monday this week');

// Handle week navigation
$weekOffset = isset($_GET['week']) ? (int)$_GET['week'] : 0;

// Prevent future weeks
if ($weekOffset > 0) {
    $weekOffset = 0;
}

// Calculate the week to display
$displayWeekStart = (clone $currentWeekStart)->modify("$weekOffset weeks");
$displayWeekEnd = (clone $displayWeekStart)->modify('+6 days');

// Initialize timecard data in session if not exists
if (!isset($_SESSION['timecards'])) {
    $_SESSION['timecards'] = [];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $date = $_POST['date'];
    $hours = floatval($_POST['hours']);
    $notes = htmlspecialchars($_POST['notes']);
    
    $_SESSION['timecards'][$date] = [
        'hours' => $hours,
        'notes' => $notes
    ];
}

// Create array of days for the week
$daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$weekDates = [];

for ($i = 0; $i < 7; $i++) {
    $date = (clone $displayWeekStart)->modify("+$i days");
    $weekDates[] = [
        'dayName' => $daysOfWeek[$i],
        'date' => $date->format('Y-m-d'),
        'displayDate' => $date->format('M j, Y'),
        'isToday' => $date->format('Y-m-d') === $today->format('Y-m-d')
    ];
}

// Calculate total hours for the week
$totalHours = 0;
foreach ($weekDates as $day) {
    if (isset($_SESSION['timecards'][$day['date']])) {
        $totalHours += $_SESSION['timecards'][$day['date']]['hours'];
    }
}

// Check if this is the current week
$isCurrentWeek = ($weekOffset === 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timecard System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .week-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .week-nav h2 {
            color: #555;
            font-size: 18px;
        }
        
        .nav-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        
        .btn:hover {
            background: #0056b3;
        }
        
        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #007bff;
            color: white;
            font-weight: bold;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .today {
            background: #e7f3ff;
        }
        
        input[type="number"],
        input[type="text"] {
            width: 100%;
            padding: 6px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .save-btn {
            padding: 6px 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .save-btn:hover {
            background: #218838;
        }
        
        .total-row {
            font-weight: bold;
            background: #f8f9fa;
        }
        
        .total-row td {
            border-top: 2px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Weekly Timecard</h1>
        
        <div class="week-nav">
            <h2>Week of <?php echo $displayWeekStart->format('M j') . ' - ' . $displayWeekEnd->format('M j, Y'); ?></h2>
            <div class="nav-buttons">
                <a href="?week=<?php echo $weekOffset - 1; ?>" class="btn">← Previous Week</a>
                <?php if (!$isCurrentWeek): ?>
                    <a href="?week=<?php echo $weekOffset + 1; ?>" class="btn">Next Week →</a>
                <?php else: ?>
                    <button class="btn" disabled>Next Week →</button>
                <?php endif; ?>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Date</th>
                    <th>Hours</th>
                    <th>Notes</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($weekDates as $day): ?>
                    <?php
                    $timecard = isset($_SESSION['timecards'][$day['date']]) 
                        ? $_SESSION['timecards'][$day['date']] 
                        : ['hours' => 0, 'notes' => ''];
                    ?>
                    <tr class="<?php echo $day['isToday'] ? 'today' : ''; ?>">
                        <td><strong><?php echo $day['dayName']; ?></strong></td>
                        <td><?php echo $day['displayDate']; ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="date" value="<?php echo $day['date']; ?>">
                                <input type="number" name="hours" step="0.5" min="0" max="24" 
                                       value="<?php echo $timecard['hours']; ?>" required>
                        </td>
                        <td>
                                <input type="text" name="notes" 
                                       value="<?php echo $timecard['notes']; ?>" 
                                       placeholder="Optional notes">
                        </td>
                        <td>
                                <button type="submit" name="save" class="save-btn">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="2">Total Hours</td>
                    <td><strong><?php echo number_format($totalHours, 2); ?></strong></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>