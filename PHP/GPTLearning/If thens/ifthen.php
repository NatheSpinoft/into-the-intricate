<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>If then</title>
</head>
<body>
    <h1>If box says TRUE</h1>
    <form method="post">
        <label for="testing"> Enter: </label>
        <input type="text" id="testing" name="test">
        <input type="submit" value="Check">
    </form>

    <?php 
        if(isset($_POST['test'])) {
            $input =strtoupper(trim($_POST['test'])) ; //normalize input
            if ($input === "TRUE") {
                echo "<p>TRUE</p>";
            } else {
                echo "<p>Not True</p>";
            }
        }

        ?>
    <h1>If two boxes both say TRUE</h1>
    <form method="post">
        <label for="box1"> Enter: </label>
        <input type="text" id="testing" name="box1"> <br>
        <label for="box2"> Enter: </label>
        <input type="text" id="testing" name="box2">
        <input type="Submit" name="check_double" value="Check">
    </form>
    <?php
        if(isset($_POST['check_double']) && isset($_POST['box1']) && isset($_POST['box2'])){
            $input1 = strtoupper(trim($_POST['box1'])) ; //normalize input
            $input2 = strtoupper(trim($_POST['box2'])) ;

            if ($input1 === "TRUE" && $input2 === "TRUE"){
                echo "<p>TRUE</p>";
            } else {
                echo "<p>Not True</p>";
            }
        }
        ?>
    <h1>If two boxes both have the same content</h1>
    <form method="post">
        <label for="box1s"> Enter: </label>
        <input type="text" id="testing" name="box1s"> <br>
        <label for="box2s"> Enter: </label>
        <input type="text" id="testing" name="box2s">
        <input type="Submit" name="check_double" value="Check">
    </form>
    <?php
        if(isset($_POST['check_double']) && isset($_POST['box1s']) && isset($_POST['box2s'])){
            $input1 = strtoupper(trim($_POST['box1s'])) ; //normalize input
            $input2 = strtoupper(trim($_POST['box2s'])) ;

            if ($input1 ===  $input2 ){
                echo "<p>TRUE</p>";
            } else {
                echo "<p>Not True</p>";
            }
        }
    ?>
</body>
</html>