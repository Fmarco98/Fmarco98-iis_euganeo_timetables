<?php
    session_start();
    
    include("../utils.php");
    include("../db_manager.php");
    include("../session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../login.php');
    }
    
    if(isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['new_password_conferma'])) {
        db_setup();

        $result = db_do_query("SELECT password FROM utente WHERE id_utente=?", 'i', $_SESSION['id_utente']);
        $row = $result->fetch_assoc();
        
        //verifica corretteza inserimento password
        if(MD5($_POST['old_password']) === $row['password']) {
            if($_POST['new_password'] === $_POST['new_password_conferma']) {
                db_do_query("UPDATE utente SET password=? WHERE id_utente=?", 'si', MD5($_POST['new_password']), $_SESSION['id_utente']);

            } else {
                $_SESSION['error'] = MODIFY_PWD_NON_CONFERMA;
                db_close();
                redirect(0, "../../impostazioni_utente.php");
            }

        } else {
            $_SESSION['error'] = MODIFY_PWD_ERRATA;
            db_close();
            redirect(0, "../../impostazioni_utente.php");
        }

        db_close();
    }
    
    redirect(0, '../../home.php');
?>