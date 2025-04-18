<?php function getLogoutDialog($folder_prefix) { 
        //ottieni il footer ?>
    <section id="popUp_logout" class="popUpDialog" style="display: none">
        <div id="content">
            <p>Sei sicuro di voler effettuare il Logout</p>
            <div id="buttons">
                <button onclick="logoutDialogClose()">Annulla</button>
                <a href="<?php echo $folder_prefix ?>utils/targets/logout.php">Esci</a>
            </div>
        </div>
    </section>
<?php } ?>