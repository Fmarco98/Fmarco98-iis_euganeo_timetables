<?php
    session_start();
    
    include("utils/utils.php");
    include("utils/db_manager.php");
    include("utils/session_errors.php");

    // controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, 'login.php');
    }
    
    db_setup();

    $result = db_do_query("SELECT nome, cognome FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente']);
    $row = $result->fetch_assoc();
    
    $nome = ucfirst($row['nome']);
    $cognome = ucfirst($row['cognome']);
?>

<!DOCTYPE html>
<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | prenota</title>
    
</head>

<body class="dark-white-bg">    
    <?php 
        include("utils/prefabs/header.php");
        getHeader('./');
    ?>
    <nav>
        <ul>
            <li><a href="./utils/targets/logout.php">logout</a></li>
            <li><a href="./impostazioni_utente.php">impostazioni</a></li>
            <li><span>prenota</span></li>
            <li><a href="./home.php">home</a></li>
        </ul>
    </nav>
    <main>
        <h2>Prenotazioni</h2>
        <hr>
        <?php 
            include('utils/prefabs/prenota_table.php');
        ?>

    </main>
    
    <?php 
        include("utils/prefabs/footer.php"); 
        getFooter('./');
        db_close();
    ?>
            
</body>
</html>