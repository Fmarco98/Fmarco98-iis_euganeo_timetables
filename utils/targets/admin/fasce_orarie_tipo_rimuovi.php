<?php
    session_start();

    include("../../utils.php");
    include("../../db_manager.php");
    include("../../session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../../login.php');
    }

    if(isset($_POST['id_fht'])) {
        db_setup();

        $permesso = db_do_query("SELECT ruolo FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente'])->fetch_assoc();
        
        //controllo permesso di modifica
        if($permesso['ruolo'] === 'A') {
            db_do_query("DELETE FROM tipo_orario_fascia_oraria WHERE id_tipo_orario_fascia_oraria = ?", 'i', $_POST['id_fht']);

            db_close();
            redirect(0, '../../../admin/gestione_orari.php');
        } else {
            db_close();
            $_SESSION['error'] = NO_PERMISSION;
            redirect(0, '../../../home.php');
        }
    } else {
        redirect(0, '../../../home.php');
    }
?>