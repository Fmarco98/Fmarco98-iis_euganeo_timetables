<?php function getFooter($folder_prefix) { 
        //ottieni il footer ?>
        <footer>
            <div class="container">  
                <img src="<?php echo $folder_prefix ?>imgs/logo/logo_iiseuganeo.png" alt="iiS Euganeo timetables" id="logoname">
                <div id="content">
                    <div>
                        <h3>Bisogni di aiuto?</h3>
                        <p>Scarica il <a href="<?php echo $folder_prefix ?>files/manuale.pdf" target="_blank">manuale</a></p>
                    </div>
                </div>
            </div>
            <div class="container" style="min-height: auto;">
                <p>Tutti i diritti riservati 2025™</p>
                <p id="credits">Creato da: Casciello Marco, Colturato Davide e Mattiolo Luca</p>
            </div>
        </footer>
<?php } ?>