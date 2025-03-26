<?php
    session_start();
    
    include("../utils/utils.php");
    include("../utils/db_manager.php");
    include("../utils/session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../login.php');
    }

    db_setup();

    $result = db_do_query("SELECT nome, cognome, ruolo FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente']);
    $row = $result->fetch_assoc();
    
    $nome = ucfirst($row['nome']);
    $cognome = ucfirst($row['cognome']);
    $ruolo = $row['ruolo'];

    //controllo permesso di visualizzazione
    if($ruolo !== 'A') {
        $_SESSION['error'] = NO_PERMISSION;
        redirect(0, '../home.php');
    }

    $query_fh = 
        'SELECT id_fascia_oraria, ora_inizio, ora_fine
         FROM fascia_oraria
         ORDER BY id_fascia_oraria';
    
    $query_g = 
        'SELECT id_giorno, nome
         FROM giorno
         ORDER BY id_giorno';

    $query_fhg = 
        'SELECT fhg.id_fascia_oraria_giorno, fh.ora_inizio, fh.ora_fine, g.nome
         FROM fascia_oraria_giorno fhg
         JOIN fascia_oraria fh ON fhg.fk_fascia_oraria = fh.id_fascia_oraria
         JOIN giorno g ON g.id_giorno = fhg.fk_giorno
         ORDER BY g.id_giorno, fh.ora_inizio';
    
    $fascie_orarie = db_do_simple_query($query_fh);
    $fascie_orarie_giorno = db_do_simple_query($query_fhg);
    $gironi = db_do_simple_query($query_g);

    db_close();
?>

<!DOCTYPE html>
<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./../imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | gestione orari</title>
</head>
<body class="dark-white-bg">
    <?php 
        include("../utils/prefabs/header.php");
        getHeader('./../');
    ?>
    <nav>
        <ul>
            <li><a href="./../utils/targets/logout.php">logout</a></li>
            <li><a href="./../impostazioni_utente.php">impostazioni</a></li>
            <li><a href="./gestione_utenti.php">utenti</a></li>
            <li><a href="./gestione_fasce_orarie.php">fasce orarie</a></li>
            <li><span>aule</span></li>
            <li><a href="./../home.php">home</a></li>
        </ul>
    </nav>
    <main>
        
    </main>
    <?php 
        include("../utils/prefabs/footer.php"); 
        getFooter('../');
    ?>
</body>
</html>