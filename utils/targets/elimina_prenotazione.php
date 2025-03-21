<?php
    session_start();

    include("../utils.php");
    include("../db_manager.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../login.php');
    }

    if(isset($_POST['id_prenotazione'])) {
        db_setup();

        $r = db_do_query("SELECT fk_utente FROM prenotazione WHERE id_prenotazione=?", 'i', $_POST['id_prenotazione'])->fetch_assoc();
        $permesso = db_do_query("SELECT ruolo FROM utente WHERE id_utente=?", 'i', $_SESSION['id_utente'])->fetch_assoc();
        
        if($r['fk_utente'] == $_SESSION['id_utente'] || $permesso['ruolo'] === 'A') {
            db_do_query("DELETE FROM prenotazione WHERE id_prenotazione=?", 'i', $_POST['id_prenotazione']);
            db_close();
            redirect(0, '../../home.php');

        } else {
            echo 'non hai il permesso';
            db_close();
            redirect(10, '../../home.php');
        }
    } else {
        redirect(0, '../../home.php');
    }
?>