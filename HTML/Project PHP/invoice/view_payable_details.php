<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php';

// Get payable ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: view_payables.php");
    exit();
}

$payable_id = $_GET['id'];

// Get the payable details
$stmt = $pdo->prepare("
    SELECT * FROM payables 
    WHERE id = :id AND username = :username
");
$stmt->execute([
    ':id' => $payable_id,
    ':username' => $_SESSION['username']
]);
$payable = $stmt->fetch();

if (!$payable) {
    $_SESSION['message'] = "Bill/Expense not found.";
    $_SESSION['message_type'] = "error";
    header("Location: view_payables.php");
    exit();
}

// Get items from payable_items table
$items_stmt = $pdo->prepare("
    SELECT * FROM payable_items 
    WHERE payable_id = :payable_id 
    ORDER BY id
");
$items_stmt->execute([':payable_id' => $payable_id]);
$items = $items_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Details #<?php echo $payable['id']; ?></title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/layout.css">
    <link rel="stylesheet" href="../assets/css/form.css">
    <style>
        .details-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .detail-header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .items-table th, .items-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .items-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .action-buttons {
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            padding: 10px 20px;
            margin: 0 10px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .btn-danger {
            background-color: #f44336;
            color: white;
        }
        .btn-danger:hover {
            background-color: #da190b;
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
            <li><a href="view_payables.php">View All Payables</a></li>
        </ul>
    </div>

    <div class="main">
        <div class="details-container">
            <div class="detail-header">
                <h1>Bill/Expense #<?php echo htmlspecialchars($payable['id']); ?></h1>
            </div>

            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span><?php echo htmlspecialchars($payable['bill_date']); ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Vendor/Supplier:</span>
                <span><?php echo htmlspecialchars($payable['vendor']); ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span><?php echo htmlspecialchars($payable['payment_method']); ?></span>
            </div>

            <?php if ($payable['card_last4']): ?>
            <div class="detail-row">
                <span class="detail-label">Card Last 4:</span>
                <span>****<?php echo htmlspecialchars($payable['card_last4']); ?></span>
            </div>
            <?php endif; ?>

            <h3 style="margin-top: 30px;">Items</h3>
            <?php if (!empty($items) && is_array($items)): ?>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Tax Type</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['description']); ?></td>
                        <td><?php echo htmlspecialchars($item['qty']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($item['tax_type']); ?></td>
                        <td>$<?php echo number_format($item['total'], 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="4" style="text-align: right;">Grand Total:</td>
                        <td>$<?php echo number_format($payable['grand_total'], 2); ?></td>
                    </tr>
                </tbody>
            </table>
            <?php else: ?>
            <p style="color: #666; font-style: italic;">Item details not available. Only total amount is stored.</p>
            <div class="detail-row total-row" style="margin-top: 20px; font-size: 1.2em;">
                <span class="detail-label">Grand Total:</span>
                <span>$<?php echo number_format($payable['grand_total'], 2); ?></span>
            </div>
            <?php endif; ?>

            <div class="action-buttons">
                <a href="view_payables.php" class="btn btn-primary">← Back to List</a>
                <a href="delete_payable.php?id=<?php echo $payable['id']; ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Are you sure you want to delete this bill/expense? This action cannot be undone.');">
                   🗑️ Delete
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>