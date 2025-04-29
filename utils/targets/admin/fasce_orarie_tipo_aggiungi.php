<?php
    session_start();

    include("../../utils.php");
    include("../../db_manager.php");
    include("../../session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../../login.php');
    }

    if(isset($_POST['id_fascia_oraria']) && isset($_POST['id_tipo_orario'])) {
        db_setup();

        $permesso = db_do_query("SELECT ruolo FROM utente WHERE id_utente=?", 'i', $_SESSION['id_utente'])->fetch_assoc();
        
        //controllo di modifica
        if($permesso['ruolo'] === 'A') {
            //controllo validità
            if($_POST['id_fascia_oraria'] != -1 && $_POST['id_fht'] != -1) {
                $query =
                'SELECT COUNT(id_tipo_orario_fascia_oraria) = 0 as a 
                 FROM tipo_orario_fascia_oraria
                 WHERE fk_fascia_oraria = ? AND fk_tipo_orario = ?';

                db_start_transaction();
                $r = db_do_query($query, 'ii', $_POST['id_fascia_oraria'], $_POST['id_tipo_orario'])->fetch_assoc();
                
                //controllo se già esistente
                if($r['a']) {
                    db_do_query("INSERT INTO tipo_orario_fascia_oraria(fk_fascia_oraria, fk_tipo_orario) VALUE (?, ?)", 'ii',  $_POST['id_fascia_oraria'], $_POST['id_tipo_orario']);
                    
                    db_end_transaction('y');
                    db_close();
                    redirect(0, '../../../admin/gestione_orari.php');
                } else {
                    db_end_transaction('n');
                    db_close();
                    $_SESSION['error'] = ADMIN_FHT_ALREADY_EXIST;
                    redirect(0, '../../../admin/gestione_orari.php');
                }
            } else {
                db_close();
                $_SESSION['error'] = ADMIN_FHT_NOT_EXIST;
                redirect(0, '../../../admin/gestione_orari.php');
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