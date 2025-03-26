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

    $query = 
        'SELECT id_utente, nome, cognome, email, ruolo
         FROM utente
         where id_utente != ?';
    
    $utenti = db_do_query($query, 'i', $_SESSION['id_utente']);

    db_close();
?>

<!DOCTYPE html>
<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./../imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | gestione utenti</title>
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
            <li><span>utenti</span></li>
            <li><a href="./gestione_fasce_orarie.php">fasce orarie</a></li>
            <li><a href="./../home.php">home</a></li>
        </ul>
    </nav>
    <main>
        
        <h2>Prenotazioni da confermare</h2>
        <hr>
        <table id="prenotazioni_attive">
            <thead>
                <tr>
                    <th>professore</th>
                    <th>email</th>
                    <th>ruolo</th>
                    <th>azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($utenti->num_rows > 0) {
                        foreach($utenti as $row) { ?>
                            <tr>
                                <td><?php echo ucfirst($row['cognome']).' '.ucfirst($row['nome']) ?></td>
                                <td><?php echo $row['email'] ?></td>
                                <td><?php echo $row['ruolo'] ?></td>
                                <td>
                                    <?php if($row['ruolo'] === 'A' ) { ?>
                                        <form action="./../utils/targets/admin/user_rimuovi_admin.php" method="post">
                                            <fieldset>
                                                <legend>Rimuovi amministratore</legend>
                                                <input type="hidden" name="id_utente" value="<?php echo $row['id_utente'] ?>">
                                                <input type="submit" value="rimuovi amministratore">
                                            </fieldset>
                                        </form>
                                    <?php } else { ?>
                                        <form action="./../utils/targets/admin/user_rendi_admin.php" method="post">
                                            <fieldset>
                                                <legend>Rendi amministratore</legend>
                                                <input type="hidden" name="id_utente" value="<?php echo $row['id_utente'] ?>">
                                                <input type="submit" value="rendi amministratore">
                                            </fieldset>
                                        </form>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="6">Non ci sono utenti</td>
                        </tr>
                    <?php } ?>
            </tbody>
        </table>
    </main>
    <?php 
        include("../utils/prefabs/footer.php"); 
        getFooter('../');
    ?>
</body>
</html>