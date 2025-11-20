<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../config/config.php';

try {
    // Sanitize POST data
    $bill_date = $_POST['bill_date'] ?? null;
    $vendor = trim($_POST['vendor'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';
    $card_last4 = null;
    $username = $_SESSION['username'];

    if (!$bill_date || !$vendor || !$payment_method) {
        throw new Exception("Missing required bill information.");
    }

    // Validate card digits if payment method is Credit Card or Debit Card
    if ($payment_method === 'Credit Card' || $payment_method === 'Debit Card') {
        $card_last4 = trim($_POST['card_last4'] ?? '');
        if (strlen($card_last4) !== 4 || !ctype_digit($card_last4)) {
            throw new Exception("Card must have exactly 4 digits.");
        }
    }

    $descriptions = $_POST['description'] ?? [];
    $qtys = $_POST['qty'] ?? [];
    $prices = $_POST['price'] ?? [];
    $tax_types = $_POST['tax_type'] ?? [];

    if (count($descriptions) === 0) {
        throw new Exception("Bill must have at least one item.");
    }

    // Calculate totals
    $grand_total = 0;
    $items = [];

    for ($i = 0; $i < count($descriptions); $i++) {
        $desc = htmlspecialchars(trim($descriptions[$i]));
        $qty = max(0, (float)$qtys[$i]);
        $price = max(0, (float)$prices[$i]);
        $tax = $tax_types[$i] ?? '0';

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

    // Insert payable/bill
    $payable_stmt = $pdo->prepare("
        INSERT INTO payables (bill_date, vendor, payment_method, card_last4, grand_total, username)
        VALUES (:bill_date, :vendor, :payment_method, :card_last4, :grand_total, :username)
    ");

    $payable_stmt->execute([
        ':bill_date' => $bill_date,
        ':vendor' => $vendor,
        ':payment_method' => $payment_method,
        ':card_last4' => $card_last4,
        ':grand_total' => $grand_total,
        ':username' => $username
    ]);

    $payable_id = $pdo->lastInsertId();

    // Insert items
    $item_stmt = $pdo->prepare("
        INSERT INTO payable_items (payable_id, description, qty, price, tax_type, total)
        VALUES (:payable_id, :description, :qty, :price, :tax_type, :total)
    ");

    foreach ($items as $item) {
        $item_stmt->execute([
            ':payable_id' => $payable_id,
            ':description' => $item['description'],
            ':qty' => $item['qty'],
            ':price' => $item['price'],
            ':tax_type' => $item['tax_type'],
            ':total' => $item['total']
        ]);
    }

    // Commit transaction
    $pdo->commit();

    header("Location: payables.php?success=1");
    exit();

} catch (\PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    die("Database error: " . $e->getMessage());
} catch (\Exception $e) {
    die("Error: " . $e->getMessage());
}
?>