<?php
    session_start();

    include("../../utils.php");
    include("../../db_manager.php"); 
    include("../../session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../../login.php');
    }

    if(isset($_POST['id_utente'])) {
        db_setup();

        $permesso = db_do_query("SELECT ruolo FROM utente WHERE id_utente=?", 'i', $_SESSION['id_utente'])->fetch_assoc();
        
        //controllo permesso di modifica
        if($permesso['ruolo'] === 'A') {
            db_do_query("UPDATE utente SET ruolo = 'A' WHERE id_utente=?", 'i', $_POST['id_utente']);
            db_close();
            redirect(0, '../../../admin/gestione_utenti.php');
        } else {
            db_close();
            $_SESSION['error'] = NO_PERMISSION
            redirect(0, '../../../home.php');
        }
    }

    redirect(0, '../../../home.php');
?>