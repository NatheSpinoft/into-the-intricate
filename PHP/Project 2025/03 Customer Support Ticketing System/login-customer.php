<?php
session_start();

// Include database connection
require './assets/src/config.php'; // Make sure path is correct for your setup

// Handle flash messages
$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch summary data
$total_printers = $pdo->prepare("SELECT COUNT(*) FROM printers WHERE user_id = :user_id");
$total_printers->execute(['user_id' => $user_id]);
$total_printers = $total_printers->fetchColumn();

$open_tickets = $pdo->prepare("SELECT COUNT(*) FROM printer_support WHERE user_id = :user_id AND status='open'");
$open_tickets->execute(['user_id' => $user_id]);
$open_tickets = $open_tickets->fetchColumn();

$resolved_tickets = $pdo->prepare("SELECT COUNT(*) FROM printer_support WHERE user_id = :user_id AND status='resolved'");
$resolved_tickets->execute(['user_id' => $user_id]);
$resolved_tickets = $resolved_tickets->fetchColumn();

$printers_under_warranty = $pdo->prepare("SELECT COUNT(*) FROM printers WHERE user_id = :user_id AND warranty_expiry > CURDATE()");
$printers_under_warranty->execute(['user_id' => $user_id]);
$printers_under_warranty = $printers_under_warranty->fetchColumn();

// Fetch printer details
$printers_stmt = $pdo->prepare("SELECT * FROM printers WHERE user_id = :user_id");
$printers_stmt->execute(['user_id' => $user_id]);
$printers = $printers_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch tickets details
$tickets_stmt = $pdo->prepare("
    SELECT ps.*, p.model AS printer_model 
    FROM printer_support ps
    JOIN printers p ON ps.printer_id = p.printer_id
    WHERE ps.user_id = :user_id
");
$tickets_stmt->execute(['user_id' => $user_id]);
$tickets = $tickets_stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support System</title>
    <link rel="stylesheet" href="./assets/css/styles-customer.css">
    <link rel="stylesheet" href="./assets/css/styles-dashboard.css">
</head>
<body>
    <header>
        <h1 id="header-title">Customer Dashbaord</h1>
        <form action="./handlers/logout.php" method="POST">
            <button id="logout-button" type="submit">Logout</button>
        </form>
    </header>
    <nav>
    <ul>
        <li><a href="login-customer.php" class="active">Dashboard</a></li>
        <li><a href="#">My Tickets</a></li>
        <li><a href="submit-ticket.php">Submit Ticket</a></li>
        <li><a href="#">Account Settings</a></li>
    </ul>
    </nav>
    <main>
        <?php if (!empty($error)): ?>
    <p style="color:red; padding:10px; background-color:#ffe6e6; border-radius:5px; margin-bottom:15px;">
        <?php echo htmlspecialchars($error); ?>
    </p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color:green; padding:10px; background-color:#e6ffe6; border-radius:5px; margin-bottom:15px;">
        <?php echo htmlspecialchars($success); ?>
    </p>
<?php endif; ?>
        <h2>Welcome, Customer!</h2>
        <p>This is your dashboard where you can manage your support tickets and view your account details.</p>
        <a href="employee-table.php">Go to Employee Table View</a>
        <br>
<div class="cards">
        <div class="card">
            <h3>Total Printers</h3>
            <p><?php echo $total_printers; ?></p>
        </div>
        <div class="card">
            <h3>Open Tickets</h3>
            <p><?php echo $open_tickets; ?></p>
        </div>
        <div class="card">
            <h3>Resolved Tickets</h3>
            <p><?php echo $resolved_tickets; ?></p>
        </div>
        <div class="card">
            <h3>Printers Under Warranty</h3>
            <p><?php echo $printers_under_warranty; ?></p>
        </div>
    </div>

    <h2>My Printers</h2>
    <table>
        <tr>
            <th>Serial Number</th>
            <th>Model</th>
            <th>Description</th>
            <th>Location</th>
            <th>Purchase Date</th>
            <th>Warranty Expiry</th>
            <th>Status</th>
            <th>Last Service</th>
        </tr>
        <?php foreach($printers as $printer): ?>
        <tr>
            <td><?php echo htmlspecialchars($printer['serial_number']); ?></td>
            <td><?php echo htmlspecialchars($printer['model']); ?></td>
            <td><?php echo htmlspecialchars($printer['description']); ?></td>
            <td><?php echo htmlspecialchars($printer['location']); ?></td>
            <td><?php echo htmlspecialchars($printer['purchase_date']); ?></td>
            <td><?php echo htmlspecialchars($printer['warranty_expiry']); ?></td>
            <td><?php echo htmlspecialchars($printer['status']); ?></td>
            <td><?php echo htmlspecialchars($printer['last_service_date']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>My Tickets</h2>
    <table>
        <tr>
            <th>Ticket ID</th>
            <th>Printer Model</th>
            <th>Issue Description</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Resolved At</th>
        </tr>
        <?php foreach($tickets as $ticket): ?>
        <tr>
            <td><?php echo $ticket['ticket_id']; ?></td>
            <td><?php echo htmlspecialchars($ticket['printer_model']); ?></td>
            <td><?php echo htmlspecialchars($ticket['issue_description']); ?></td>
            <td class="status-<?php echo $ticket['status']; ?>"><?php echo htmlspecialchars($ticket['status']); ?></td>
            <td><?php echo htmlspecialchars($ticket['created_at']); ?></td>
            <td><?php echo htmlspecialchars($ticket['resolved_at']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    </main>
    <footer>
        <p>&copy; 2024 Customer Support System. All rights reserved.</p>
    </footer>
    
</body>
</html>