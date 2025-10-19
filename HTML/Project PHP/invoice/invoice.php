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
            <a href="menu.php">HOME</a>
            <a href="logout.php">LOG OUT</a>
        </div>
    </div>
</header>

<div class="container">
    <div class="sidenav">
        <ul>
            <li><a href="../timecard/timecard.php">Time</a></li>
            <li><a href="invoice.php">Invoices</a></li>
            <li><a href="payables.php">Payables</a></li>
        </ul>
    </div>

    <div class="main">
        <h1>Create Invoice</h1>

        <form class="invoice-form" method="POST" action="add_invoice.php">
    <label>Date:
        <input type="date" name="invoice_date" required>
    </label>
    <label>Company:
        <input type="text" name="company" placeholder="Company Name" required>
    </label>

    <h3>Items</h3>
    <table class="invoice-items" id="invoice-items">
    <thead>
        <tr>
            <th>Description</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Tax Type</th>
            <th>Total</th>
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
        <select name="payment_method" required>
            <option value="">Select...</option>
            <option value="Cash">Cash</option>
            <option value="Credit Card">Credit Card</option>
            <option value="On Account">On Account</option>
        </select>
    </label>

    <button type="submit">Save Invoice</button>
</form>

<script>
function addItem() {
    const tableBody = document.getElementById('invoice-items').querySelector('tbody');
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
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
    `;
    tableBody.appendChild(newRow);
    attachListeners(newRow);
    updateTotals();
}


// Attach listeners to a row
function attachListeners(row) {
    const inputs = row.querySelectorAll('input, select'); // include select now
    inputs.forEach(input => {
        input.addEventListener('input', updateTotals);
        input.addEventListener('change', updateTotals);
    });
}

// Initial listener attachment
document.querySelectorAll('#invoice-items tbody tr').forEach(attachListeners);

function updateTotals() {
    let grandTotal = 0;

    document.querySelectorAll('#invoice-items tbody tr').forEach(row => {
        const qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
        const price = parseFloat(row.querySelector('input[name="price[]"]').value) || 0;
        const taxType = row.querySelector('select[name="tax_type[]"]').value;

        let total = qty * price;

        // Map tax types to percentages
        let taxRate = 0;
        switch(taxType) {
            case "HST": taxRate = 0.13; break;
            case "PST": taxRate = 0.08; break;
            case "QST": taxRate = 0.09975; break;
            case "GST": taxRate = 0.05; break;
            default: taxRate = 0; break; // "None"
        }

        total *= (1 + taxRate);
        row.querySelector('.row-total').textContent = total.toFixed(2);
        grandTotal += total;
    });

    document.getElementById('grand-total').textContent = grandTotal.toFixed(2);
}
// Ensure initial rows are tracked
document.querySelectorAll('#invoice-items tbody tr').forEach(attachListeners);

</script>


</body>
</html>
