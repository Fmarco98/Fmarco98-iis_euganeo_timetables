<?php 
    function getHeader($folder_prefix) { ?>
        <header>
            <div class="row-style" id="imagezone">
                <img src="<?php echo $folder_prefix ?>imgs/logo/logo_iiseuganeo_whitebg.png" alt="iiS Euganeo timetables" id="logo">
                <img src="<?php echo $folder_prefix ?>imgs/logo/logo_name.png" alt="iiS Euganeo timetables" id="logoname">
            </div>
            <div class="row-style" id="utente_zone">
                <img src="<?php echo $folder_prefix ?>imgs/utente/img_utente.png" alt="img utente" id="img_utente">
                <p><?php echo $GLOBALS['cognome'].' '.$GLOBALS['nome'] ?></p>
            </div>
        </header>
    <?php }
?>