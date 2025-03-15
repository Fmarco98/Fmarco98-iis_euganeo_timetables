<?php
    session_start();

    include("../redirect.php");
    include("../db_manager.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../login.php');
    }

    if(isset($_POST['id_prenotazione'])) {
        db_setup();

        db_start_transaction();
        $r = db_do_query("SELECT fk_utente FROM prenotazione WHERE id_prenotazione=?", 'i', $_POST['id_prenotazione'])->fetch_assoc();
        
        if($r['fk_utente'] == $_SESSION['id_utente']) {
            db_do_query("DELETE FROM prenotazione WHERE id_prenotazione=?", 'i', $_POST['id_prenotazione']);
            db_end_transaction('y');
            db_close();
            redirect(0, '../../home.php');

        } else {
            echo 'non hai il permesso';
            db_end_transaction('n');
            db_close();
            redirect(3, '../../home.php');
        }
    }

    redirect(0, '../../home.php');
?>