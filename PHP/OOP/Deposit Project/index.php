<?php

class Deposits{
    public $fname;
    public $deposit;

    function __construct($fname, $dep){
        $this->fname = $fname;
        $this->deposit = $dep;
    }

    function displayBalance(){
        return "Hello $this->fname, your balance is $this->deposit";
    }
}

// Initialize the method
$message = "";

//Check if form submitted
if(isset($_POST['submit'])){
    $fname = $_POST['fname'] ?? '';
    $deposit = $_POST['deposit'] ?? 0;

    //Make sure deposit is numeric
    if (!is_numeric($deposit)){
        $message = "Please enter a valid number for deposit.";
    }else{
        $account = new Deposits($fname, $deposit);
        $message = $account->displayBalance();
    }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposits</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h1>Deposit</h1>
    <form method="post" action="">
        <label for="fname"> First name: </label>
        <input type="text" id="fname" name="fname" required>
        <label for="deposit"> Deposit: </label>
        <input type="text" id="deposit" name="deposit" required>

        <button type="submit" name="submit">Deposit</button>

    </form>
    <?php if($message): ?>
        <p id="balance"><?php echo $message; ?></p>
    <?php endif; ?>

</body>
</html>