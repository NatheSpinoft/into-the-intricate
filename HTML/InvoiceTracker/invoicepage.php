<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Not logged in, redirect to login page
    header("Location: index.php");
    exit();
}
include 'config.php';
$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer = trim($_POST['customer']);
    $item = trim($_POST['item']);
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];
    $total = $quantity * $price;

    if ($customer && $item && $quantity > 0 && $price > 0) {
        // Create invoices table if it doesn't exist
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS invoices (
                id INT AUTO_INCREMENT PRIMARY KEY,
                customer VARCHAR(100) NOT NULL,
                item VARCHAR(100) NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                total DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Insert invoice
        $stmt = $pdo->prepare("INSERT INTO invoices (customer, item, quantity, price, total) VALUES (:customer, :item, :quantity, :price, :total)");
        $stmt->execute([
            'customer' => $customer,
            'item' => $item,
            'quantity' => $quantity,
            'price' => $price,
            'total' => $total
        ]);

        $message = "Invoice created successfully!";
    } else {
        $message = "Please fill in all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="login-container">
        <form method="POST" action="invoicepage.php">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
        <h2>Create Invoice</h2>
        <?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>

            <input type="text" name="customer" placeholder="Customer Name" required>
            <input type="text" name="item" placeholder="Item Name" required>
            <input type="number" name="quantity" placeholder="Quantity" min="1" required>
            <input type="number" step="0.01" name="price" placeholder="Price" min="0" required>
            <input type="submit" value="Create Invoice">
        <div style="margin-top:10px;">
            <a href="index.php">Back to Login</a>
            <a href="logout.php">Logout</a>
        </div>
        </form>

    </div>
</body>
</html>
