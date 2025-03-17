<?php
    session_start();

    include("../utils.php");
    include("../db_manager.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../login.php');
    }

    if(isset($_POST['id_prenotazione'])) {
        db_setup();

        $permesso = db_do_query("SELECT ruolo FROM utente WHERE id_utente=?", 'i', $_SESSION['id_utente'])->fetch_assoc();
        
        if($permesso['ruolo'] === 'A') {
            db_do_query("UPDATE prenotazione SET conferma = 1 WHERE id_prenotazione=?", 'i', $_POST['id_prenotazione']);
            db_close();
            redirect(0, '../../home.php');
        } else {
            db_close();
            redirect(3, '../../home.php');
        }
    }

    redirect(0, '../../home.php');
?>