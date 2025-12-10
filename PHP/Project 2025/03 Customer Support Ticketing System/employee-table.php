<?php
session_start();
require './assets/src/config.php';

// Function to display any table
function displayTable($pdo, $tableName) {
    echo "<h2>Table: $tableName</h2>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    
    // Fetch columns
    $columns = $pdo->query("DESCRIBE $tableName")->fetchAll(PDO::FETCH_COLUMN);
    
    // Table headers
    echo "<tr>";
    foreach ($columns as $col) {
        echo "<th>" . htmlspecialchars($col) . "</th>";
    }
    echo "</tr>";
    
    // Table rows
    $stmt = $pdo->query("SELECT * FROM $tableName");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        foreach ($columns as $col) {
            echo "<td>" . htmlspecialchars($row[$col]) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table><br><br>";
}

// Display each table
displayTable($pdo, 'users');
displayTable($pdo, 'printers');
displayTable($pdo, 'printer_support');


?>
