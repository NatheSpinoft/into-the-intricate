<?php
session_start();
require './assets/src/config.php'; // DB connection ($pdo)

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all open tickets
$tickets_stmt = $pdo->prepare("
    SELECT ps.*, p.model AS printer_model, p.serial_number, p.location
    FROM printer_support ps
    JOIN printers p ON ps.printer_id = p.printer_id
    WHERE ps.user_id = :user_id AND ps.status != 'closed'
    ORDER BY ps.created_at DESC
");
$tickets_stmt->execute(['user_id' => $user_id]);
$tickets = $tickets_stmt->fetchAll(PDO::FETCH_ASSOC);

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit My Tickets</title>
<link rel="stylesheet" href="./assets/css/styles-customer.css">
<link rel="stylesheet" href="./assets/css/styles-dashboard.css">
</head>
<body>
<header>
    <h1 id="header-title">Customer Tickets</h1>
    <form action="./handlers/logout.php" method="POST">
        <button id="logout-button" type="submit">Logout</button>
    </form>
</header>
<nav>
    <ul>
        <li><a href="login-customer.php">Dashboard</a></li>
        <li><a href="my-tickets-edit.php" class="active">My Tickets</a></li>
        <li><a href="submit-ticket.php">Submit Ticket</a></li>
        <li><a href="#">Account Settings</a></li>
    </ul>
</nav>
<main>
    
<h2>Edit Your Open Tickets</h2>
        <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
<?php if ($tickets): ?>
<table>
<tr>
    <th>Ticket ID</th>
    <th>Printer Model</th>
    <th>Serial</th>
    <th>Location</th>
    <th>Issue Description</th>
    <th>Status</th>
    <th>Action</th>
</tr>
<?php foreach($tickets as $ticket): ?>
<tr>
<form method="post" action="handlers/edit_ticket.php">
    <td><?= $ticket['ticket_id'] ?></td>
    <td><?= htmlspecialchars($ticket['printer_model']) ?></td>
    <td><?= htmlspecialchars($ticket['serial_number']) ?></td>
    <td><?= htmlspecialchars($ticket['location']) ?></td>
    <td>
        <textarea name="issue_description" rows="2" cols="30"><?= htmlspecialchars($ticket['issue_description']) ?></textarea>
    </td>
    <td>
        <select name="status">
            <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Open</option>
            <option value="maintenance" <?= $ticket['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
            <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
        </select>
    </td>
    <td>
        <input type="hidden" name="ticket_id" value="<?= $ticket['ticket_id'] ?>">
        <button type="submit">Update</button>
    </td>
</form>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>No open tickets found.</p>
<?php endif; ?>

</main>
<footer>
    <p>&copy; 2024 Customer Support System. All rights reserved.</p>
</footer>
</body>
</html>
