<?php 
    //generici
    define('NONE', 'tutto ok');
    define('ERROR', 'errore');
    define('NO_PERMISSION', 'non hai il permesso');
    
    //area impostazioni utente
    define('MODIFY_EMAIL_IN_USO', 'modifica_impostazioni_utente:email_già_in_uso');
    define('MODIFY_PWD_ERRATA', 'modifica_impostazioni_utente:old_password_errata');
    define('MODIFY_PWD_NON_CONFERMA', 'modifica_impostazioni_utente:new_password_non_confermata');

    //prenotazioni
    define('PRENOTA_ALREADY_EXIST', 'prenota:già_presente');

    // ------- gestione tabelle admin --------
    //fasce orarie
    define('ADMIN_FHG_NOT_EXIST', 'admin:orari_giorni:selezione_non_esistente');
    define('ADMIN_FHG_ALREADY_EXIST', 'admin:orari_giorni:già_presente');
    define('ADMIN_FH_INVALID_VALUE', 'admin:orari:non_valido');
    define('ADMIN_FH_ALREADY_EXIST', 'admin:orari:già_presente');
    
    //aule
    define('ADMIN_AULA_INVADIL_VALUE', 'admin:aule:valori_non_validi');
    define('ADMIN_AULA_ALREADY_EXIST', 'admin:aule:già_presente');
    define('ADMIN_AULA_R_INVADIL_VALUE', 'admin:aule_riservate:valori_non_validi');
    define('ADMIN_AULA_R_ALREADY_EXIST', 'admin:aule_riservate:già_presente');
    
?>