<?php
    session_start();
    
    include("../redirect.php");
    
    $_SESSION['id_utente'] = NULL;
    session_destroy();
    
    redirect(0, '../../login.php');
?>