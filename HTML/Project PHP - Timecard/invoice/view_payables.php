<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php';

// Get all payables for the logged-in user
$stmt = $pdo->prepare("
    SELECT * FROM payables 
    WHERE username = :username 
    ORDER BY bill_date DESC, created_at DESC
");
$stmt->execute([':username' => $_SESSION['username']]);
$payables = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payables</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/layout.css">
    <link rel="stylesheet" href="../assets/css/form.css">
    <style>
        .payables-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .payables-table th, .payables-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .payables-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .payables-table tr:hover {
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
            display: inline-block;
        }
        .action-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .delete-btn:hover {
            background-color: #da190b;
        }
        .view-btn {
            background-color: #2196F3;
        }
        .view-btn:hover {
            background-color: #0b7dda;
        }
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
        <h1>Your Bills & Expenses</h1>
        <p><a href="payables.php" style="color: #4CAF50;">+ Record New Bill/Expense</a></p>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="message <?php echo $_SESSION['message_type']; ?>">
                <?php 
                    echo htmlspecialchars($_SESSION['message']);
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (count($payables) > 0): ?>
        <table class="payables-table">
            <thead>
                <tr>
                    <th>Bill #</th>
                    <th>Date</th>
                    <th>Vendor/Supplier</th>
                    <th>Payment Method</th>
                    <th>Card Last 4</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payables as $payable): ?>
                <tr>
                    <td><?php echo htmlspecialchars($payable['id']); ?></td>
                    <td><?php echo htmlspecialchars($payable['bill_date']); ?></td>
                    <td><?php echo htmlspecialchars($payable['vendor']); ?></td>
                    <td><?php echo htmlspecialchars($payable['payment_method']); ?></td>
                    <td><?php echo $payable['card_last4'] ? '****' . htmlspecialchars($payable['card_last4']) : 'N/A'; ?></td>
                    <td>$<?php echo number_format($payable['grand_total'], 2); ?></td>
                    <td>
                        <a href="view_payable_details.php?id=<?php echo $payable['id']; ?>" class="action-btn view-btn">👁️ View</a>
                        <a href="delete_payable.php?id=<?php echo $payable['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this bill/expense? This action cannot be undone.');">🗑️ Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>No bills or expenses found. <a href="payables.php">Record your first bill</a>.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>