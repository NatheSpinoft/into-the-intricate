<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices</title>
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/layout.css">
    <link rel="stylesheet" href="../assets/css/form.css"> <!-- optional form styling -->
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
        

        <form class="invoice-form" method="POST" action="add_invoice.php">
            <h1>Create Invoice</h1>
            <div class="invoice-header">
                <label>Date:
                    <input type="date" name="invoice_date" required>
                </label>
                <label>Company:
                    <input type="text" name="company" placeholder="Company Name" required>
                </label>
            </div>


    <h3>Items</h3>
    <table class="invoice-items" id="invoice-items">
    <thead>
        <tr>
            <th>Description</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Tax Type</th>
            <th>Total</th>
            <th>Action</th> <!-- new column -->
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><input type="text" name="description[]" required></td>
            <td><input type="number" name="qty[]" min="1" value="1" required></td>
            <td><input type="number" name="price[]" step="0.01" min="0" required></td>
            <td>
                <select name="tax_type[]">
                    <option value="0">None</option>
                    <option value="HST">HST</option>
                    <option value="PST">PST</option>
                    <option value="QST">QST</option>
                    <option value="GST">GST</option>
                </select>
            </td>
            <td class="row-total">0.00</td>
            <td><button type="button" class="remove-item">Remove</button></td> <!-- new button -->
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="text-align:right; font-weight:bold;">Grand Total:</td>
            <td id="grand-total">0.00</td>
        </tr>
    </tfoot>
</table>


    <button type="button" onclick="addItem()">Add Another Item</button>

    <label>Payment Method:
        <select name="payment_method" id="payment_method" required>
            <option value="">Select...</option>
            <option value="Cash">Cash</option>
            <option value="Credit Card">Credit Card</option>
            <option value="On Account">On Account</option>
        </select>
    </label>

    <div id="card-digits-container" style="display:none;">
    <label>Card Last 4 Digits:
        <input type="text" name="card_last4" id="card_last4" 
               pattern="[0-9]{4}" 
               maxlength="4" 
               placeholder="1234">
    </label>
    </div>

    <button type="submit">Save Invoice</button>
</form>

<script src="../assets/js/invoice.js"></script>




</body>
</html>
