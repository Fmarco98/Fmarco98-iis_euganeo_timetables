<?php
    session_start();

    include("../../utils.php");
    include("../../db_manager.php");
    include("../../session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../../login.php');
    }

    if(isset($_POST['id_fascia_oraria']) && isset($_POST['id_giorno'])) {
        db_setup();

        $permesso = db_do_query("SELECT ruolo FROM utente WHERE id_utente=?", 'i', $_SESSION['id_utente'])->fetch_assoc();
        
        //controllo di modifica
        if($permesso['ruolo'] === 'A') {
            //controllo validità
            if($_POST['id_fascia_oraria'] != -1 && $_POST['id_giorno'] != -1) {
                $query =
                'SELECT COUNT(id_fascia_oraria_giorno) = 0 as a 
                 FROM fascia_oraria_giorno
                 WHERE fk_fascia_oraria = ? AND fk_giorno = ?';

                db_start_transaction();
                $r = db_do_query($query, 'ii', $_POST['id_fascia_oraria'], $_POST['id_giorno'])->fetch_assoc();
                
                //controllo se già esistente
                if($r['a']) {
                    db_do_query("INSERT INTO fascia_oraria_giorno(fk_fascia_oraria, fk_giorno) VALUE (?, ?)", 'ii',  $_POST['id_fascia_oraria'], $_POST['id_giorno']);
                    
                    db_end_transaction('y');
                    db_close();
                    redirect(0, '../../../admin/gestione_fasce_orarie.php');
                } else {
                    db_end_transaction('n');
                    db_close();
                    $_SESSION['error'] = ADMIN_FHG_ALREADY_EXIST;
                    redirect(0, '../../../admin/gestione_fasce_orarie.php');
                }
            } else {
                db_close();
                $_SESSION['error'] = ADMIN_FHG_NOT_EXIST;
                redirect(0, '../../../admin/gestione_fasce_orarie.php');
            }
        } else {
            db_close();
            $_SESSION['error'] = NO_PERMISSION;
            redirect(0, '../../../home.php');
        }
    } else {
        redirect(0, '../../../home.php');
    }
?>