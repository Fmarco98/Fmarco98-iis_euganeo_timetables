<?php
    session_start();

    include("../../utils.php");
    include("../../db_manager.php");
    include("../../session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../../login.php');
    }

    if(isset($_POST['aula']) && isset($_POST['fascia_oraria'])) {
        db_setup();

        $permesso = db_do_query("SELECT ruolo FROM utente WHERE id_utente=?", 'i', $_SESSION['id_utente'])->fetch_assoc();
        
        //controllo permesso di modifica
        if($permesso['ruolo'] === 'A') {
            //controllo validità valori
            if($_POST['aula'] != -1 && $_POST['fascia_oraria'] != -1) {
                db_start_transaction();

                $query = 
                    'SELECT COUNT(id_richiesta_conferma) = 0 as a
                     FROM richiesta_conferma
                     WHERE fk_aula = ? AND fk_fascia_oraria = ?';

                $r = db_do_query($query, 'ii', $_POST['aula'], $_POST['fascia_oraria'])->fetch_assoc();
                
                if($r['a']) {
                    $query = 
                        'INSERT INTO richiesta_conferma(fk_aula, fk_fascia_oraria) 
                         VALUES (?, ?)';
                    
                    db_do_query($query, 'ii', $_POST['aula'], $_POST['fascia_oraria']);
                    
                    db_end_transaction('y');
                    db_close();
                    redirect(0, '../../../admin/gestione_aule.php');
                } else {
                    db_end_transaction('n');
                    db_close();
                    $_SESSION['error'] = ADMIN_AULA_R_ALREADY_EXIST;
                    redirect(0, '../../../admin/gestione_aule.php');
                }
            } else {
                db_close();
                $_SESSION['error'] = ADMIN_AULA_R_INVADIL_VALUE;
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