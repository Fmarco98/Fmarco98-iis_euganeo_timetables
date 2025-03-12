<?php
    session_start();
    
    include("utils/redirect.php");
    include("utils/db_manager.php");
    include("utils/session_errors.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, 'login.php');
    }

    db_setup();
    $result = db_do_query("SELECT nome, cognome FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente']);
    db_close();

    $row = $result->fetch_assoc();

    $nome = $row['nome'];
    $cognome = $row['cognome'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | prenota</title>
</head>
<body class="dark-white-bg">
    <header>
        <div class="row-style" id="imagezone">
            <img src="./imgs/logo/logo_iiseuganeo_whitebg.png" alt="iiS Euganeo timetables" id="logo">
            <img src="./imgs/logo/logo_name.png" alt="iiS Euganeo timetables" id="logoname">
        </div>
        <div class="row-style" id="utente_zone">
            <img src="./imgs/utente/img_utente.png" alt="img utente" id="img_utente" onclick="f()">
            <?php 
                echo "<p>".$cognome." ".$nome."</p>";
            ?>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="./utils/targets/logout.php">logout</a></li>
            <li><a href="./impostazioni_utente.php">impostazioni</a></li>
            <li><span>prenota</span></li>
            <li><a href="./home.php">home</a></li>
        </ul>
    </nav>
    <main>
        <h1>prenota page</h1>
    </main>
    <footer>
        <p class="center">Creato da: Cascello Marco, Colturato Davide e Mattiolo Luca</p>
    </footer>
    <script src="./js/user.js"></script>
</body>
</html>