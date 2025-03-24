<?php
    session_start();

    include("../utils.php");
    include("../db_manager.php");
    include("../../session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../login.php');
    }

    if(isset($_POST['id_prenotazione'])) {
        db_setup();

        $r = db_do_query("SELECT fk_utente FROM prenotazione WHERE id_prenotazione=?", 'i', $_POST['id_prenotazione'])->fetch_assoc();
        $permesso = db_do_query("SELECT ruolo FROM utente WHERE id_utente=?", 'i', $_SESSION['id_utente'])->fetch_assoc();
        
        //controllo permesso di modifica per quella prenotazione 
        if($r['fk_utente'] == $_SESSION['id_utente'] || $permesso['ruolo'] === 'A') {
            db_do_query("DELETE FROM prenotazione WHERE id_prenotazione=?", 'i', $_POST['id_prenotazione']);
            db_close();
            redirect(0, '../../home.php');

        } else {
            db_close();
            $_SESSION['error'] = NO_PERMISSION
            redirect(0, '../../home.php');
        }

    }
    
    redirect(0, '../../home.php');
?>