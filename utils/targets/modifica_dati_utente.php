<?php
    session_start();
    
    include("../utils.php");
    include("../db_manager.php");
    include("../session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../login.php');
    }

    $id_utente = $_SESSION['id_utente'];

    db_setup();

    // upadate del nome
    if(isset($_POST['nome']) && $_POST['nome'] !== '') {
        db_do_query("UPDATE utente SET nome=? WHERE id_utente=?", "si", $_POST['nome'], $id_utente);
    }

    // update del cognome
    if(isset($_POST['cognome']) && $_POST['cognome'] !== '') {
        db_do_query("UPDATE utente SET cognome=? WHERE id_utente=?", "si", $_POST['cognome'], $id_utente);
    }

    // update dell'email
    if(isset($_POST['email']) && $_POST['email'] !== '') {
        db_start_transaction();

        $result = db_do_query("SELECT ? IN (SELECT email FROM utente) AS a", 's', $_POST['email']);
        $row = $result->fetch_assoc();
            
        if(!$row['a']) {
            db_do_query("UPDATE utente SET email=? WHERE id_utente=?", "si", $_POST['email'], $id_utente);
            db_end_transaction('y');
            
        } else {
            db_end_transaction('n');
            db_close();
            
            $_SESSION['error'] = MODIFY_EMAIL_IN_USO;
            redirect(0, "../../impostazioni_utente.php");
        }

    }
    
    db_close();
    redirect(0, "../../home.php");
?>