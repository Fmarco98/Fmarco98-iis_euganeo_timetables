<?php 
    function getHeader($folder_prefix) {
        echo '
        <header>
            <div class="row-style" id="imagezone">
                <img src="'.$folder_prefix.'imgs/logo/logo_iiseuganeo_whitebg.png" alt="iiS Euganeo timetables" id="logo">
                <img src="'.$folder_prefix.'imgs/logo/logo_name.png" alt="iiS Euganeo timetables" id="logoname">
            </div>
            <div class="row-style" id="utente_zone">
                <img src="'.$folder_prefix.'imgs/utente/img_utente.png" alt="img utente" id="img_utente">
                <p>'.$GLOBALS['cognome'].' '.$GLOBALS['nome'].'</p>
            </div>
        </header>
        ';
    }
?>