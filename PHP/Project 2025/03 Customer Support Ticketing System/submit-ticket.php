<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Support System</title>
    <link rel="stylesheet" href="./assets/css/styles-customer.css">
</head>
<body>
    <header>
        <h1 id="header-title">Customer Support System</h1>

        <form action="handlers/logout.php" method="POST">
            <button id="logout-button" type="submit">Logout</button>
        </form>
    </header>

    <nav>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">My Tickets</a></li>
            <li><a href="#">Submit Ticket</a></li>
            <li><a href="#">Account Settings</a></li>
        </ul>
    </nav>

    <main>


        <form action="handlers/ticket.php" method="POST">
            <h2>Submit a Support Ticket</h2>
            
            <label for="serial">Printer Serial Number:</label>
            <input type="text" id="serial" name="serial" required>

            <label for="title">Printer Model:</label>
            <input type="text" id="title" name="title" required>

            <label for="location">Printer Location:</label>
            <input type="text" id="location" name="location" required>

            <label for="purchase_date">Purchase Date:</label>
            <input type="date" id="purchase_date" name="purchase_date" required>

            <label for="warranty_date">Warranty Expiry Date:</label>
            <input type="date" id="warranty_date" name="warranty_date" required>

            <label for="status">Printer Status:</label>
            <select id="status" name="status" required>
                <option value="active">Active</option>
                <option value="maintenance">Maintenance</option>
                <option value="retired">Retired</option>
            </select>

            <label for="priority">Issue Priority:</label>
            <select id="priority" name="priority" required>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>

            <label for="description">Issue Description:</label>
            <textarea id="description" name="description" rows="4" minlength="10" required></textarea>

            <input type="submit" value="Submit Ticket">
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Customer Support System. All rights reserved.</p>
    </footer>

</body>
</html>
