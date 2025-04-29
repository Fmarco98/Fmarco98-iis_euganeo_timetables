<?php
    session_start();

    include("../../utils.php");
    include("../../db_manager.php");
    include("../../session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../../login.php');
    }

    if(isset($_POST['id_tipo_orario']) && isset($_POST['id_giorno'])) {
        db_setup();

        $permesso = db_do_query("SELECT ruolo FROM utente WHERE id_utente=?", 'i', $_SESSION['id_utente'])->fetch_assoc();
        
        //controllo di modifica
        if($permesso['ruolo'] === 'A') {
            //controllo validità
            if($_POST['id_fascia_oraria'] != -1 && $_POST['id_giorno'] != -1) {
                $query =
                'SELECT fk_tipo_orario IS NULL as a 
                 FROM giorno';

                db_start_transaction();
                $r = db_do_simple_query($query)->fetch_assoc();
                
                //controllo se già esistente
                if($r['a']) {
                    db_do_query("UPDATE giorno SET fk_tipo_orario = ? WHERE id_giorno = ?", 'ii',  $_POST['id_tipo_orario'], $_POST['id_giorno']);
                    
                    db_end_transaction('y');
                    db_close();
                    redirect(0, '../../../admin/gestione_fasce_orarie.php');
                } else {
                    db_end_transaction('n');
                    db_close();
                    $_SESSION['error'] = ADMIN_GT_ALREADY_EXIST;
                    redirect(0, '../../../admin/gestione_fasce_orarie.php');
                }
            } else {
                db_close();
                $_SESSION['error'] = ADMIN_GT_NOT_EXIST;
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