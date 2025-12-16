<!DOCTYPE html>
<html>
<head>
    <title>Edit Support Ticket</title>
</head>
<body>

<h2>Edit Printer Support Ticket</h2>

<h3>Printer Info</h3>
<ul>
    <li><strong>Model:</strong> <?= htmlspecialchars($ticket['model']) ?></li>
    <li><strong>Serial:</strong> <?= htmlspecialchars($ticket['serial_number']) ?></li>
    <li><strong>Location:</strong> <?= htmlspecialchars($ticket['location']) ?></li>
</ul>

<form method="post">

    <label>Issue Description</label><br>
    <textarea name="issue_description" rows="5" cols="50" required>
<?= htmlspecialchars($ticket['issue_description']) ?>
    </textarea>
    <br><br>

    <label>Status</label><br>
    <select name="status">
        <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Open</option>
        <option value="maintenance" <?= $ticket['status'] === 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
        <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
    </select>

    <br><br>

    <button type="submit">Update Ticket</button>

</form>

</body>
</html>
