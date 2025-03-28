<?php   
    include('../utils/utils.php');

    $r =  getSettimana($_POST['data']);
    foreach($r as $a) {
        echo $a;
        echo ' | ';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="index.php" method="post">
        <input type="date" name="data" id="data">
        <input type="submit" value="sissio">
    </form>
</body>
</html>