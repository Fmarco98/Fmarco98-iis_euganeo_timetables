<?php
    session_start();
    
    include("utils/redirect.php");
    include("utils/db_manager.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, 'login.php');
    }

    db_setup();
    $result = db_select("SELECT nome, cognome FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente']);
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
    <title>iiS Euganeo timetables | home</title>
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
            <li><a href="./utils/logout.php">logout</a></li>
            <li><a href="">impostazioni</a></li>
            <li><a href="">prenota</a></li>
            <li><span>home</span></li>
        </ul>
    </nav>
    <main>
        <?php 
            echo '<h1 class="xl-text">Buongiorno '.$cognome.' '.$nome.'</h1>';
        ?>
        <section>
            <table id="prenotazioni_attive">

            </table>   
            <table id="prenotazioni_in_attesa">

            </table>   
        </section>
    </main>
    <footer>
        
    </footer>
    <script src="./js/user.js"></script>
</body>
</html>