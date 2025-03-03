<?php
    session_start();
    include("utils/redirect.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, 'login.php');
    }

    echo $_SESSION['id_utente'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iiS Euganeo timetables | home</title>
</head>
<body>
    <head>

    </head>
    <nav>

    </nav>
    <body>
        
    </body>
    <footer>
        
    </footer>
</body>
</html>