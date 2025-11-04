<?php
session_start();

// Initialize the list in session if not already
if (!isset($_SESSION['items'])) {
    $_SESSION['items'] = [];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_item'])) {
        $item = trim($_POST['item']);
        if ($item !== '') {
            $_SESSION['items'][] = $item;
        }
    } elseif (isset($_POST['clear_list'])) {
        $_SESSION['items'] = [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        input, button {
            padding: 5px 10px;
            margin-right: 10px;
        }
        ul {
            margin-top: 20px;
        }
        li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h2>Add Items to List</h2>
    <form method="post">
        <input type="text" name="item" placeholder="Enter item">
        <button type="submit" name="add_item">Add</button>
        <button type="submit" name="clear_list">Clear List</button>
    </form>

    <ul>
        <?php foreach ($_SESSION['items'] as $listItem): ?>
            <li><?php echo htmlspecialchars($listItem); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
