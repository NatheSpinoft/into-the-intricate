<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php';

// Get all invoices for the logged-in user
$stmt = $pdo->prepare("
    SELECT * FROM invoices00 
    WHERE username = :username 
    ORDER BY invoice_date DESC, created_at DESC
");
$stmt->execute([':username' => $_SESSION['username']]);
$invoices = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Invoices</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/layout.css">
    <link rel="stylesheet" href="../assets/css/form.css">
    <style>
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .invoice-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .invoice-table tr:hover {
            background-color: #f5f5f5;
        }
        .action-btn {
            padding: 5px 10px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 3px;
            font-size: 14px;
        }
        .action-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<header>
    <div class="head-container">
        <div class="welcome">
            <h1>Welcome: <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        </div>
        <div class="button-group">
            <a href="../includes/menu.php">HOME</a>
            <a href="../includes/logout.php">LOG OUT</a>
        </div>
    </div>
</header>

<div class="container">
    <div class="sidenav">
        <ul>
            <li><a href="../timecard/timecard.php">Time</a></li>
            <li><a href="invoice.php">Invoices</a></li>
            <li><a href="payables.php">Payables</a></li>
            <li><a href="view_invoices.php">View All Invoices</a></li>
            
        </ul>
    </div>

    <div class="main">
        <h1>Your Invoices</h1>
        <p><a href="invoice.php" style="color: #4CAF50;">+ Create New Invoice</a></p>

        <?php if (count($invoices) > 0): ?>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Company</th>
                    <th>Payment Method</th>
                    <th>Card Last 4</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $inv): ?>
                <tr>
                    <td><?php echo htmlspecialchars($inv['id']); ?></td>
                    <td><?php echo htmlspecialchars($inv['invoice_date']); ?></td>
                    <td><?php echo htmlspecialchars($inv['company']); ?></td>
                    <td><?php echo htmlspecialchars($inv['payment_method']); ?></td>
                    <td><?php echo $inv['card_last4'] ? '****' . htmlspecialchars($inv['card_last4']) : 'N/A'; ?></td>
                    <td>$<?php echo number_format($inv['grand_total'], 2); ?></td>
                    <td>
                        <a href="generate_pdf.php?id=<?php echo $inv['id']; ?>" class="action-btn" target="_blank">📄 PDF</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No invoices found. <a href="invoice.php">Create your first invoice</a>.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>