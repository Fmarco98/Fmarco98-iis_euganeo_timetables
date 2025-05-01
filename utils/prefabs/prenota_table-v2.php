<style>
    

</style>

<?php
    $ruolo = db_do_query("SELECT ruolo FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente'])->fetch_assoc()['ruolo'];
     
    // Segnalazione errori
    if($_SESSION['error'] === PRENOTA_ALREADY_EXIST) {
        echo '<p class="phperror">Aula già prenotata</p>';
        $_SESSION['error'] = NONE;
    }
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <fieldset>
        <legend>filtro aula</legend>

        <!-- plesso -->
        <label for="plesso_input">Plesso</label>
        <select name="id_plesso" id="plesso_input" onchange="submit_click()">
            <option value="-1">Seleziona plesso</option>
            <?php
                //Valori per la selezione
                $query = 
                    'SELECT id_plesso, nome
                     FROM plesso';

                $plessi = db_do_simple_query($query);
                        
                foreach($plessi as $r) { ?>
                    <option value="<?php echo $r['id_plesso'] ?>"><?php echo $r['nome'] ?></option>
          <?php } ?>
        </select>
                
        <?php if(isset($_POST['id_plesso']) && $_POST['id_plesso'] != -1) { 
            if(isset($_POST['id_aula'])) {
                $check_query = 
                    'SELECT ? IN (
                        SELECT id_aula 
                        FROM aula 
                        WHERE fk_plesso = ?
                     ) a';
    
                $bool = db_do_query($check_query, 'ii', $_POST['id_aula'], $_POST['id_plesso'])->fetch_assoc()['a'];
                
                if(!$bool) {
                    $_POST['id_aula'] = -1;
                }
            } ?>
            <label for="aula_input">Aula</label>
            <select name="id_aula" id="aula_input">
                <option value="-1">Seleziona aula</option>
                <?php
                    // Valori selezione aule
                    $query = 
                        'SELECT id_aula, piano, nome, n_aula
                         FROM aula
                         WHERE fk_plesso = ?';

                    $aule = db_do_query($query, 'i', $_POST['id_plesso']);
                            
                    foreach($aule as $r) { ?>
                        <option value="<?php echo $r['id_aula'] ?>"><?php echo normalize_aula($r['piano'], $r['n_aula']).' ('.$r['nome'].')' ?></option>
              <?php } ?>
            </select>
                    
            <label for="data_input">Data</label>
            <input type="date" name="data" id="data_input" onfocus="this.showPicker()">
        <?php } ?>

        <input type="submit" value="Seleziona" id="submit">
    </fieldset>
</form>
<hr>

<!-- tabella -->

<?php 
    if(isset($_POST['id_plesso']) && isset($_POST['id_aula']) && isset($_POST['data']) && $_POST['id_plesso'] != -1 && $_POST['id_aula'] != -1) {
        
        $query_h = 
            'SELECT ora
             FROM (
                SELECT HOUR(ora_inizio) ora
                FROM fascia_oraria_giorno fhg
                JOIN fascia_oraria fh ON fh.id_fascia_oraria = fhg.fascia_oraria

                UNION 

                SELECT HOUR(ora_fine)
                FROM fascia_oraria_giorno fhg
                JOIN fascia_oraria fh ON fh.id_fascia_oraria = fhg.fascia_oraria
             ) a
             ORDER BY ora';

        $giorni_query = 
            'SELECT id_giorno, nome
             FROM giorno
             ORDER BY id_giorno';

        $query_celle = 
            'SELECT giorno, HOUR(ora_inizio) h_inizio, MINUTE(ora_inizio) min_inizio , HOUR(ora_fine) h_fine, MINUTE(ora_fine) min_fine, fascia_oraria
             FROM fascia_oraria_giorno fhg
             JOIN fascia_oraria fh ON fh.id_fascia_oraria = fhg.fascia_oraria 
             WHERE giorno = ?';

        $query_prenotazione = 
            'SELECT p.id_prenotazione, p.descrizione, u.cognome, u.nome, p.data_approvazione IS NOT NULL as approvata, p.fk_utente
             FROM prenotazione p
             JOIN utente u ON u.id_utente = p.fk_utente
             WHERE p.fk_aula = ? AND p.data = ? AND p.fk_fascia_oraria = ? AND p.data_eliminazione IS NULL';

        $query_rc = 
            'SELECT id_richiesta_conferma, fk_aula, fk_fascia_oraria, data
             FROM richiesta_conferma
             WHERE fk_aula = ? AND fk_fascia_oraria = ? AND data = ?';

        $ore = db_do_simple_query($query_h);
        $giorni = db_do_simple_query($giorni_query);

        $data_giorni = get_settimana('d/m', $_POST['data']);
        $data_formattata = get_settimana('Y-m-d', $_POST['data']);
    ?>  
        <style>
            :root {
                --numHours: <?php echo $ore->num_rows*12 ?>;
            }

            /* Place on Timeline */
            <?php 
                $i = 0;
                foreach($ore as $r) {
                    for($j=0; $j < 12; $j += 1) {
                        $min = strval(5*$j);
                        $min = strlen($min) < 2 ? '0'.$min : $min;

                        echo '.start-'.$r['ora'].'-'.$min.' { grid-row-start: '.$i.'; }';
                        echo '.end-'.$r['ora'].'-'.$min.' { grid-row-end: '.$i.'; }';
                        
                        $i += 1;
                    }
                }
            ?>
        </style>

        <div class="calendar">
            <div class="timeline">
                <div class="spacer"></div>
                <?php
                    foreach($ore as $r) {
                        echo '<div class="time-marker">'.$r['ora'].'</div>';
                        for($i = 0; $i < 11; $i += 1) {
                            echo '<div class="time-marker"></div>';
                        } 
                    } 
                ?>
            </div>
            <div class="days">
                <?php
                    $index = 0;
                    foreach($giorni as $r) { ?>
                        <div class="day">
                            <div class="date">
                                <p class="date-num"><?php echo $data_giorni[$index] ?></p>
                                <p class="date-day"><?php echo ucfirst($r['nome']) ?></p>
                            </div>
                            <div class="events">
                                <?php
                                $celle = db_do_query($query_celle, 'i', $r['id_giorno']);

                                foreach($celle as $rr) { 
                                    $prenotazione = db_do_query($query_prenotazione, 'isi', $_POST['id_aula'], $data_formattata[$index], $rr['fascia_oraria']);
                                    
                                    if($prenotazione->num_rows == 0) { // non prenotate?>
                                        <div class="en event start-<?php echo $rr['h_inizio'].'-'.$rr['min_inizio'] ?> end-<?php echo $rr['h_fine'].'-'.$rr['min_fine'] ?> disponibile">
                                      <?php if($ruolo !== 'A') {?>
                                                <form action="prenota_conferma.php" method="post">
                                                    <input type="hidden" name="id_fascia_oraria" value="<?php echo $rr['fascia_oraria'] ?>">
                                                    <input type="hidden" name="id_aula" value="<?php echo $_POST['id_aula'] ?>">
                                                    <input type="hidden" name="data" value="<?php echo $data_formattata[$index] ?>">
                                                    <input type="submit" value="Prenota">
                                                </form>
                                      <?php } else { ?>
                                                <div class="void"></div>
                                      <?php } ?>
                                        </div>
                              <?php } else { // prenotate
                                        $prenotazione = $prenotazione->fetch_assoc();
                                        if($prenotazione['approvata']) { ?>
                                            <div class="en event start-<?php echo $rr['h_inizio'].'-'.$rr['min_inizio'] ?> end-<?php echo $rr['h_fine'].'-'.$rr['min_fine'] ?> prenotata">  
                                  <?php } else { ?>
                                            <div class="en event start-<?php echo $rr['h_inizio'].'-'.$rr['min_inizio'] ?> end-<?php echo $rr['h_fine'].'-'.$rr['min_fine'] ?> attesa"> 
                                  <?php } ?>
                                                <p class="title"><?php echo ucfirst($prenotazione['nome']).' '.ucfirst($prenotazione['cognome']) ?></p>
                                                <p><?php echo ucfirst($prenotazione['descrizione']) ?></p>
                                  <?php if(($prenotazione['approvata'] || $prenotazione['fk_utente'] == $_SESSION['id_utente']) && ($ruolo === 'A' || $prenotazione['fk_utente'] == $_SESSION['id_utente'])) { ?>
                                                    <div class="list">
                                                        <form class="admin" action="./utils/targets/elimina_prenotazione.php" method="post">
                                                            <fieldset>
                                                                <legend>Elimina</legend>
                                                                <input type="hidden" name="id_prenotazione" value="<?php echo $prenotazione['id_prenotazione'] ?>">
                                                                <input type="submit" value="elimina">
                                                            </fieldset>
                                                        </form>
                                                    </div>
                                  <?php } elseif($ruolo === 'A') { ?>
                                                    <div class="list">
                                                        <form class="admin" action="./utils/targets/elimina_prenotazione.php" method="post">
                                                            <fieldset>
                                                                <legend>Rifiuta</legend>
                                                                <input type="hidden" name="id_prenotazione" value="<?php echo $prenotazione['id_prenotazione'] ?>">
                                                                <input type="submit" value="❌">
                                                            </fieldset>
                                                        </form>
                                                        <form class="admin" action="./utils/targets/admin/conferma_prenotazione.php" method="post">
                                                            <fieldset>
                                                                <legend>Accetta</legend>
                                                                <input type="hidden" name="id_prenotazione" value="<?php echo $prenotazione['id_prenotazione'] ?>">
                                                                <input type="submit" value="✅">
                                                            </fieldset>
                                                        </form>
                                                    </div>
                                  <?php } ?>
                                            </div>
                              <?php } 
                                    $riservate = db_do_query($query_rc, 'iis', $_POST['id_aula'], $rr['fascia_oraria'], $data_formattata[$index]);
                            
                                    if($riservate->num_rows == 0) { ?>
                                        <div class="dis event start-<?php echo $rr['h_inizio'].'-'.$rr['min_inizio'] ?> end-<?php echo $rr['h_fine'].'-'.$rr['min_fine'] ?> libera">
                                            <form action="./utils/targets/admin/aula_riservata_aggiungi.php" method="post">
                                                <input type="hidden" name="id_fascia_oraria" value="<?php echo $rr['fascia_oraria'] ?>">
                                                <input type="hidden" name="id_aula" value="<?php echo $_POST['id_aula'] ?>">
                                                <input type="hidden" name="data" value="<?php echo $data_formattata[$index] ?>">
                                                <input type="submit" value="Riserva"> 
                                            </form>
                                        </div>
                              <?php } else { ?>
                                        <div class="dis event start-<?php echo $rr['h_inizio'].'-'.$rr['min_inizio'] ?> end-<?php echo $rr['h_fine'].'-'.$rr['min_fine'] ?> riservata">
                                            <form action="./utils/targets/admin/aula_riservata_rimuovi.php" method="post">
                                                <input type="hidden" name="id" value="<?php echo $riservate->fetch_assoc()['id_richiesta_conferma'] ?>">
                                                <input type="submit" value="Libera"> 
                                            </form>
                                        </div>
                              <?php }
                                } ?>
                            </div>
                        </div>
              <?php     $index++;
                    } 
                ?>
            </div>
        </div>
<?php } ?>

<script>
    function submit_click() {
        document.getElementById('submit').click();
        console.log('click');
    }

    let select1 = document.getElementById('plesso_input');
    let select2 = document.getElementById('aula_input');
    let data = document.getElementById('data_input');
        
    // Recupera il valore salvato, se esiste
    let savedValue1 = localStorage.getItem('selectedValue1');
    let savedValue2 = localStorage.getItem('selectedValue2');
    let savedData = localStorage.getItem('data');
    try {
        if (savedValue1) {
            select1.value = savedValue1;
        }

        select1.addEventListener('change', () => {
            localStorage.setItem('selectedValue1', select1.value);
        });
    } catch(err) {}
    try {
        if (savedValue2) {
            select2.value = savedValue2;
        }

        select2.addEventListener('change', () => {
            localStorage.setItem('selectedValue2', select2.value);
        });
    } catch(err) {}
    try {
        data.addEventListener('change', () => {
            localStorage.setItem('data', data.value);
        });

        if (savedData) {
            data.value = savedData;
        } else {
            var data_oggi = new Date();

            var yyyy = data_oggi.getFullYear();
            var mm = data_oggi.getMonth() + 1; // Months start at 0!
            var dd = data_oggi.getDate();

            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            var fdate = yyyy + '-' + mm + '-' + dd;

            console.log(fdate);
            data.value = fdate;
        }
    } catch(err) {}

</script>