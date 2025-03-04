<?php
    session_start();
    include("utils/redirect.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, 'login.php');
    }

    $mysql = new mysqli('localhost', 'iis_euganeo_timetables', 'iis_euganeo_timetables_password', 'iis_euganeo_timetables');
    $stmt = $mysql->prepare("SELECT nome, cognome FROM utente WHERE id_utente = ?");
    $stmt->bind_param('i', $_SESSION['id_utente']);
    $stmt->execute();
    $result = $stmt->get_result();
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
            <img src="./imgs/logo/logo_iiseuganeo.png" alt="iiS Euganeo timetables" id="logo">
            <img src="./imgs/logo/mokup_logo.png" alt="iiS Euganeo timetables" id="logoname">
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