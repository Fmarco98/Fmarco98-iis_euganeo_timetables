<?php
    $ruolo = db_do_query("SELECT ruolo FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente'])->fetch_assoc()['ruolo'];
    
    //echo 'debug: '.$ruolo;
?>

<?php 
    // Segnalazione errori
    if($_SESSION['error'] === PRENOTA_ALREADY_EXIST) {
        echo '<p class="phperror">Aula già prenotata</p>';
        $_SESSION['error'] = NONE;
    }
?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <fieldset>
        <legend>filtro aula</legend>
        <label for="plesso_input">Plesso</label>
        <select name="id_plesso" id="plesso_input" onchange="submit_click()"> <!--onchange='this.form.submit()'-->
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
            }

            ?>

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

<?php if(isset($_POST['id_plesso']) && isset($_POST['id_aula']) && isset($_POST['data']) && $_POST['id_plesso'] != -1 && $_POST['id_aula'] != -1) { ?>
    <table class="prenota-table">
        <thead>
            <tr>
                <?php 
                    $data_giorni = get_settimana('d/m/Y', $_POST['data']);

                    $giorni_query = 
                        'SELECT id_giorno, nome
                         FROM giorno
                         ORDER BY id_giorno';

                    $giorni = db_do_simple_query($giorni_query);

                    // Generazione della <thead>
                    $index = 0;
                    foreach($giorni as $r) { ?>
                        <th>
                            <h3><?php echo $data_giorni[$index] ?></h3>
                            <h4><?php echo $r['nome'] ?></h4>
                        </th>
                <?php
                        $index++;
                    } 
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
                //struttura di memorizzazione dati della tabella: list[giorno][ora][valori]
                $fh_list = [];
                foreach($giorni as $r) {
                    $query = 
                        'SELECT fh.id_fascia_oraria, fh.ora_inizio, fh.ora_fine
                         FROM fascia_oraria_giorno fhg
                         JOIN fascia_oraria fh ON fh.id_fascia_oraria = fhg.fascia_oraria 
                         WHERE fhg.giorno = ?';

                    $result = db_do_query($query,'i', $r['id_giorno']);

                    $temp = [];
                    foreach($result as $r) {
                        $temp[] = array('id_fascia_oraria' => $r['id_fascia_oraria'], 'ora_inizio' => $r['ora_inizio'], 'ora_fine' => $r['ora_fine']);
                    }
                    $fh_list[] = $temp;
                }
                    
                $data_giorni = get_settimana('Y-m-d', $_POST['data']);

                $query_prenotazioni = 
                    'SELECT p.id_prenotazione, p.descrizione, p.data, p.fk_fascia_oraria, u.cognome, u.nome, p.data_approvazione IS NOT NULL as approvata, p.fk_utente
                     FROM prenotazione p
                     JOIN utente u ON u.id_utente = p.fk_utente
                     WHERE p.fk_aula = ? AND p.data BETWEEN ? AND ? AND p.data_eliminazione IS NULL';

                $prenotazioni = db_do_query($query_prenotazioni, 'iss', $_POST['id_aula'], $data_giorni[0], $data_giorni[count($data_giorni)-1]);

                // numero massimo di righe da generare
                $query_max_h = 
                    'SELECT MAX(a) max 
                     FROM (
                        SELECT giorno, COUNT(fascia_oraria) a
                        FROM fascia_oraria_giorno
                        GROUP BY giorno
                     ) as a';

                $num_h = db_do_simple_query($query_max_h)->fetch_assoc()['max'];

                //generazione righe
                for($i_h = 0; $i_h < $num_h; $i_h++) {
                    echo '<tr>';
                    for($i_g = 0; $i_g < count($fh_list); $i_g++) { 
                        if(count($fh_list[$i_g]) > $i_h) {
                            $id_prenotazione = 0;
                            $cognome_prof = '';
                            $nome_prof = '';
                            $desc = '';
                            $approvata_bool = false;
                            $fk_utente = 0;
                                    
                            //Controllo se prenotata
                            foreach($prenotazioni as $r) {
                                if($r['data'] == $data_giorni[$i_g] && $r['fk_fascia_oraria'] == $fh_list[$i_g][$i_h]['id_fascia_oraria']) {
                                    $cognome_prof = $r['cognome'];
                                    $nome_prof = $r['nome'];
                                    $desc = $r['descrizione'];
                                    $approvata_bool = $r['approvata'];
                                    $id_prenotazione = $r['id_prenotazione'];
                                    $fk_utente = $r['fk_utente'];

                                    break;
                                }
                            }

                            if(!$nome_prof) { //non prenotata?>
                                <td class="en">
                                    <p class= "ora-inizio"><?php echo $fh_list[$i_g][$i_h]['ora_inizio'] ?></p>
                                    <?php if($ruolo !== 'A') {?>
                                        <form action="prenota_conferma.php" method="post">
                                            <input type="hidden" name="id_fascia_oraria" value="<?php echo $fh_list[$i_g][$i_h]['id_fascia_oraria'] ?>">
                                            <input type="hidden" name="id_aula" value="<?php echo $_POST['id_aula'] ?>">
                                            <input type="hidden" name="data" value="<?php echo $data_giorni[$i_g] ?>">
                                            <input type="submit" value="Prenota">
                                        </form>
                                    <?php } else { ?>
                                        <div class="void"></div>
                                    <?php } ?>
                                    <p class="ora-fine"><?php echo $fh_list[$i_g][$i_h]['ora_fine'] ?></p>
                                </td>
                      <?php } else { //prenotata
                                if($approvata_bool) {
                                    echo '<td class="prenotata en">';
                                } else {
                                    echo '<td class="attesa en">';
                                } ?>
                                    <p class= "ora-inizio"><?php echo $fh_list[$i_g][$i_h]['ora_inizio'] ?></p>
                                    <h3><?php echo ucfirst($cognome_prof).' '.ucfirst($nome_prof) ?></h3>
                                    <h4><?php echo ucfirst($desc) ?></h4>
                                    <?php if(($approvata_bool || $fk_utente == $_SESSION['id_utente']) && ($ruolo === 'A' || $fk_utente == $_SESSION['id_utente'])) { ?>
                                        <div class="list">
                                            <form class="admin" action="./utils/targets/elimina_prenotazione.php" method="post">
                                                <fieldset>
                                                    <legend>Elimina</legend>
                                                    <input type="hidden" name="id_prenotazione" value="<?php echo $id_prenotazione ?>">
                                                    <input type="submit" value="elimina">
                                                </fieldset>
                                            </form>
                                        </div>
                                    <?php } elseif($ruolo === 'A') { ?>
                                        <div class="list">
                                            <form class="admin" action="./utils/targets/elimina_prenotazione.php" method="post">
                                                <fieldset>
                                                    <legend>Rifiuta</legend>
                                                    <input type="hidden" name="id_prenotazione" value="<?php echo $id_prenotazione ?>">
                                                    <input type="submit" value="rifiuta">
                                                </fieldset>
                                            </form>
                                            <form class="admin" action="./utils/targets/admin/conferma_prenotazione.php" method="post">
                                                <fieldset>
                                                    <legend>Accetta</legend>
                                                    <input type="hidden" name="id_prenotazione" value="<?php echo $id_prenotazione ?>">
                                                    <input type="submit" value="accetta">
                                                </fieldset>
                                            </form>
                                        </div>
                                    <?php } ?>
                                    
                                    <p class="ora-fine"><?php echo $fh_list[$i_g][$i_h]['ora_fine'] ?></p>
                                </td>
                      <?php } 
                            $query_rc = 
                                'SELECT id_richiesta_conferma, fk_aula, fk_fascia_oraria, data
                                 FROM richiesta_conferma
                                 WHERE fk_aula = ? AND fk_fascia_oraria = ? AND data = ?';

                            $riservate = db_do_query($query_rc, 'iis', $_POST['id_aula'], $fh_list[$i_g][$i_h]['id_fascia_oraria'], $data_giorni[$i_g]);
                            
                            if($riservate->num_rows == 0) { ?>
                                <td class="dis libera">
                                    <p class= "ora-inizio"><?php echo $fh_list[$i_g][$i_h]['ora_inizio'] ?></p>
                                    <form action="./utils/targets/admin/aula_riservata_aggiungi.php" method="post">
                                        <fieldset>
                                            <legend>rendi aula riservata</legend>
                                            <input type="hidden" name="id_fascia_oraria" value="<?php echo $fh_list[$i_g][$i_h]['id_fascia_oraria'] ?>">
                                            <input type="hidden" name="id_aula" value="<?php echo $_POST['id_aula'] ?>">
                                            <input type="hidden" name="data" value="<?php echo $data_giorni[$i_g] ?>">
                                            <input type="submit" value="Riserva"> 
                                        </fieldset>
                                    </form>
                                    <p class="ora-fine"><?php echo $fh_list[$i_g][$i_h]['ora_fine'] ?></p>
                                </td>
                      <?php } else { ?>
                                <td class="dis riservata">
                                    <p class= "ora-inizio"><?php echo $fh_list[$i_g][$i_h]['ora_inizio'] ?></p>
                                    <form action="./utils/targets/admin/aula_riservata_rimuovi.php" method="post">
                                        <fieldset>
                                            <legend>rendi aula libera</legend>
                                            <input type="hidden" name="id" value="<?php echo $riservate->fetch_assoc()['id_richiesta_conferma'] ?>">
                                            <input type="submit" value="Libera"> 
                                        </fieldset>
                                    </form>
                                    <p class="ora-fine"><?php echo $fh_list[$i_g][$i_h]['ora_fine'] ?></p>
                                </td>
                      <?php } 
                        } else {
                            // ora mancante
                            echo '<td class="no"></td>';
                        }
                    }
                    echo '</tr>';
                }
            ?>

        </tbody>
    </table>        
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