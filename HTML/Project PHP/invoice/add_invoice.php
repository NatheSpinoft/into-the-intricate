<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

// Include PDO config
require_once '../config/config.php';

try {
    // Sanitize POST data
    $invoice_date = $_POST['invoice_date'] ?? null;
    $company = trim($_POST['company'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';
    $username = $_SESSION['username'];

    if (!$invoice_date || !$company || !$payment_method) {
        throw new Exception("Missing required invoice information.");
    }

    $descriptions = $_POST['description'] ?? [];
    $qtys = $_POST['qty'] ?? [];
    $prices = $_POST['price'] ?? [];
    $tax_types = $_POST['tax_type'] ?? [];

    if (count($descriptions) === 0) {
        throw new Exception("Invoice must have at least one item.");
    }

    // Calculate totals
    $grand_total = 0;
    $items = [];

    for ($i = 0; $i < count($descriptions); $i++) {
        $desc = htmlspecialchars(trim($descriptions[$i]));
        $qty = max(0, (float)$qtys[$i]);
        $price = max(0, (float)$prices[$i]);
        $tax = $tax_types[$i] ?? '0';

        // Tax calculation
        $tax_rate = match($tax) {
            "HST" => 0.13,
            "PST" => 0.08,
            "QST" => 0.09975,
            "GST" => 0.05,
            default => 0
        };

        $total = round($qty * $price * (1 + $tax_rate), 2);
        $grand_total += $total;

        $items[] = [
            'description' => $desc,
            'qty' => $qty,
            'price' => $price,
            'tax_type' => $tax,
            'total' => $total
        ];
    }

    // Begin transaction
    $pdo->beginTransaction();

    // Insert invoice
    $invoice_stmt = $pdo->prepare("
        INSERT INTO invoices00 (invoice_date, company, payment_method, grand_total, username)
        VALUES (:invoice_date, :company, :payment_method, :grand_total, :username)
    ");

    $invoice_stmt->execute([
        ':invoice_date' => $invoice_date,
        ':company' => $company,
        ':payment_method' => $payment_method,
        ':grand_total' => $grand_total,
        ':username' => $username
    ]);

    $invoice_id = $pdo->lastInsertId();

    // Insert items
    $item_stmt = $pdo->prepare("
        INSERT INTO invoice_items (invoice_id, description, qty, price, tax_type, total)
        VALUES (:invoice_id, :description, :qty, :price, :tax_type, :total)
    ");

    foreach ($items as $item) {
        $item_stmt->execute([
            ':invoice_id' => $invoice_id,
            ':description' => $item['description'],
            ':qty' => $item['qty'],
            ':price' => $item['price'],
            ':tax_type' => $item['tax_type'],
            ':total' => $item['total']
        ]);
    }

    // Commit transaction
    $pdo->commit();

    // Redirect with success
    header("Location: invoice.php?success=1");
    exit();

} catch (\PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    die("Database error: " . $e->getMessage());
} catch (\Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
