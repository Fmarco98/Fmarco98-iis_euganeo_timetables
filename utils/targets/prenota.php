<?php
    session_start();
    
    include("../utils.php");
    include("../db_manager.php");
    include("../session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../login.php');
    }

    //controllo se non annullato
    if(isset($_POST['submit']) && strtolower($_POST['submit']) === 'annulla') {
        redirect(0, '../../prenota.php');
    }

    //se confermato
    if(isset($_POST['id_aula']) && isset($_POST['data']) && isset($_POST['id_fascia_oraria']) && isset($_POST['descrizione'])) {
        db_setup();
        db_start_transaction();
            
        //controllo se già prenotata
        $query = 
            'SELECT COUNT(id_prenotazione) = 0 a 
             FROM prenotazione 
             WHERE data = ? AND fk_fascia_oraria = ? AND fk_aula = ?';
    
        $r = db_do_query($query, 'sii', $_POST['data'], $_POST['id_fascia_oraria'], $_POST['id_aula'])->fetch_assoc();
    
        if($r['a']) {
            //controllo se la prenotazione è da confermare
            $query_riservata = 
                'SELECT COUNT(id_richiesta_conferma) = 0 a
                 FROM richiesta_conferma
                 WHERE fk_aula = ? AND fk_fascia_oraria = ?';

            $conferma = db_do_query($query_riservata, 'ii', $_POST['id_aula'], $_POST['id_fascia_oraria'])->fetch_assoc()['a'];
            
            if($conferma) {
                //prenotazione su aula non riservata
                $query = 
                    'INSERT INTO prenotazione(descrizione, data_approvazione, data, fk_utente, fk_fascia_oraria, fk_aula)
                     VALUES (?, now() ,?,?,?,?)';
            } else {
                //prenotazione su aula riservata
                $query = 
                    'INSERT INTO prenotazione(descrizione, data_approvazione, data, fk_utente, fk_fascia_oraria, fk_aula)
                     VALUES (?, NULL ,?,?,?,?)';
            }

            db_do_query($query, 'ssiii', $_POST['descrizione'], $_POST['data'], $_SESSION['id_utente'], $_POST['id_fascia_oraria'], $_POST['id_aula']);
    
            db_end_transaction('y');
            db_close();
            
        } else {
            db_end_transaction('n');
            db_close();
            $_SESSION['error'] = PRENOTA_ALREADY_EXIST;
            redirect(0, '../../prenota.php');
        }
    }

    redirect(0, '../../home.php');
?>