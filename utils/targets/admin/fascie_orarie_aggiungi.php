<?php
    session_start();

    include("../../utils.php");
    include("../../db_manager.php");
    include("../../session_errors.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../../login.php');
    }

    if(isset($_POST['ora_inizio']) && isset($_POST['ora_fine'])) {
        db_setup();

        $permesso = db_do_query("SELECT ruolo FROM utente WHERE id_utente=?", 'i', $_SESSION['id_utente'])->fetch_assoc();
        
        if($permesso['ruolo'] === 'A') {
            if($_POST['ora_inizio'] < $_POST['ora_fine']) {
                $query =
                'SELECT COUNT(id_fascia_oraria) = 0 as a 
                 FROM fascia_oraria
                 WHERE ora_inizio = ? AND ora_fine = ?';

                db_start_transaction();
                $r = db_do_query($query, 'ss', $_POST['ora_inizio'], $_POST['ora_fine'])->fetch_assoc();

                if($r['a']) {
                    db_do_query("INSERT INTO fascia_oraria(ora_inizio, ora_fine) VALUE (?, ?)", 'ss', $_POST['ora_inizio'], $_POST['ora_fine']);
                    
                    db_end_transaction('y');
                    db_close();
                    redirect(0, '../../../admin/gestione_fascie_orarie.php');
                } else {
                    db_end_transaction('n');
                    db_close();
                    $_SESSION['error'] = ADMIN_FH_ALREADY_EXIST;
                    redirect(0, '../../../admin/gestione_fascie_orarie.php');
                }
            } else {
                db_close();
                $_SESSION['error'] = ADMIN_FH_INVALID_VALUE;
                redirect(0, '../../../admin/gestione_fascie_orarie.php');
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