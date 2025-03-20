<?php
    session_start();
    
    include("utils/utils.php");
    include("utils/db_manager.php");
    include("utils/session_errors.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, 'login.php');
    }

    db_setup();
    $result = db_do_query("SELECT nome, cognome, ruolo FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente']);
    db_close();

    $row = $result->fetch_assoc();

    $nome = $row['nome'];
    $cognome = $row['cognome'];
    $ruolo = $row['ruolo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | home</title>
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
            <?php 
                if($ruolo === 'D') {
                    echo '<li><a href="./prenota.php">prenota</a></li>';
                } elseif($ruolo === 'A') {
                    echo '<li><a href="./admin/gestione_utenti.php">gestisci utenti</a></li>';
                }
            ?>
            <li><span>home</span></li>
        </ul>
    </nav>
    <main>
        <?php
            if($_SESSION['error'] === NO_PERMISSION) {
                echo '<p class="phperror">non hai il permesso</p>';
                $_SESSION['error'] = NONE;
            } elseif($_SESSION['error'] === ERROR) {
                echo '<p class="phperror">errore</p>';
                $_SESSION['error'] = NONE;
            }
        
            echo '<h1 class="xl-text">Buongiorno '.$cognome.' '.$nome.'</h1>';

            if($ruolo === 'D') {
                include("utils/prefabs/user_home.php");
            } elseif($ruolo === 'A') {
                include("utils/prefabs/admin_home.php");
            } else {
                die('500: ruolo non definito');
            }
        ?>
    </main>
    <?php include("utils/prefabs/footer.php") ?>
</body>
</html>