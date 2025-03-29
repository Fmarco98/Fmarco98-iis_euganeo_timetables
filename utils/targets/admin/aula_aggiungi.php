<?php
    session_start();

    include("../../utils.php");
    include("../../db_manager.php");
    include("../../session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../../login.php');
    }

    if(isset($_POST['n_aula']) && isset($_POST['piano']) && isset($_POST['nome']) && isset($_POST['plesso'])) {
        db_setup();

        $permesso = db_do_query("SELECT ruolo FROM utente WHERE id_utente=?", 'i', $_SESSION['id_utente'])->fetch_assoc();
        
        //controllo permesso di modifica
        if($permesso['ruolo'] === 'A') {
            //controllo validità valori
            if($_POST['n_aula'] > 0 && $_POST['piano'] >= 0 && $_POST['plesso'] != -1) {
                db_start_transaction();

                $query = 
                    'SELECT COUNT(id_aula) = 0 as a
                     FROM aula
                     WHERE piano = ? AND n_aula = ? AND fk_plesso = ?';

                $r = db_do_query($query, 'iii', $_POST['piano'], $_POST['n_aula'], $_POST['plesso'])->fetch_assoc();
                
                if($r['a']) {
                    $query = 
                        'INSERT INTO aula(nome, piano, n_aula, fk_plesso) 
                         VALUES (?, ?, ?, ?)';
                    
                    db_do_query($query, 'siii', $_POST['nome'], $_POST['piano'], $_POST['n_aula'], $_POST['plesso']);
                    
                    db_end_transaction('y');
                    db_close();
                    redirect(0, '../../../admin/gestione_aule.php');
                } else {
                    db_end_transaction('n');
                    db_close();
                    $_SESSION['error'] = ADMIN_AULA_ALREADY_EXIST;
                    redirect(0, '../../../admin/gestione_aule.php');
                }
            } else {
                db_close();
                $_SESSION['error'] = ADMIN_AULA_INVADIL_VALUE;
                redirect(0, '../../../admin/gestione_aule.php');
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