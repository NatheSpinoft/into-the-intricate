<?php
// Initialize variables
$fname = '';
$lname = '';
$displayData = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Reset button pressed
    if (isset($_POST['reset'])) {
        $fname = '';
        $lname = '';
        $displayData = '';
    } else {
        // Regular submit
        $fname = isset($_POST['fname']) ? htmlspecialchars($_POST['fname']) : '';
        $lname = isset($_POST['lname']) ? htmlspecialchars($_POST['lname']) : '';

        if ($fname || $lname) {
            $displayData = "<p>First Name: $fname</p><p>Last Name: $lname</p>";
        }
    }
}

// Function to output display data
function displayFormData($displayData) {
    echo $displayData;
}
?>
