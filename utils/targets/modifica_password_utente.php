<?php
    session_start();
    
    include("../redirect.php");
    include("../db_manager.php");
    include("../session_errors.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../../login.php');
    }

    $id_utente = $_SESSION['id_utente'];

    db_setup();

    //coltrollo pwd

    db_close();

    redirect(0, "../../home.php");
?>