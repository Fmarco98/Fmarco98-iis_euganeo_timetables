<?php
    session_start();
    
    include("../utils/utils.php");
    include("../utils/db_manager.php");
    include("../utils/session_errors.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../login.php');
    }

    db_setup();

    $result = db_do_query("SELECT nome, cognome, ruolo FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente']);
    $row = $result->fetch_assoc();
    
    $nome = ucfirst($row['nome']);
    $cognome = ucfirst($row['cognome']);
    $ruolo = $row['ruolo'];

    //controllo permesso di visualizzazione
    if($ruolo !== 'A') {
        $_SESSION['error'] = NO_PERMISSION;
        redirect(0, '../home.php');
    }

    
    $query_fh = 
        'SELECT id_fascia_oraria, ora_inizio, ora_fine
         FROM fascia_oraria
         ORDER BY id_fascia_oraria';

    $query_t = 
        'SELECT id_tipo_orario, nome
         FROM tipo_orario
         ORDER BY nome';
    
    $query_fht = 
        'SELECT tfh.id_tipo_orario_fascia_oraria id_fht, fh.ora_inizio, fh.ora_fine, t.nome
         FROM fascia_oraria fh
         JOIN tipo_orario_fascia_oraria tfh ON fh.id_fascia_oraria = tfh.fk_fascia_oraria
         JOIN tipo_orario t ON tfh.fk_tipo_orario = t.id_tipo_orario
         ORDER BY t.nome, fh.ora_inizio, fh.ora_fine';
    

    $fascie_orarie = db_do_simple_query($query_fh);
    $fascie_orarie_tipo = db_do_simple_query($query_fht);
    $tipi = db_do_simple_query($query_t);
    
    
    db_close();
?>

<!DOCTYPE html>
<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./../imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | gestione orari</title>
</head>
<body class="dark-white-bg">
    <?php 
        include("../utils/prefabs/conferma_logout.php");
        getLogoutDialog('./../');
    ?>
    <?php 
        include("../utils/prefabs/header.php");
        getHeader('./../');
    ?>
    <nav>
        <ul>
            <li><button onclick="logoutDialogOpen()">logout</button></li>
            <li><a href="./../impostazioni_utente.php">impostazioni</a></li>
            <li><a href="./gestione_utenti.php">utenti</a></li>
            <li><a href="./gestione_fasce_orarie.php">fasce orarie</a></li>
            <li><a href="./gestione_aule.php">aule</a></li>
            <li><a href="./../home.php">home</a></li>
        </ul>
    </nav>
    <main>
        <section class="grid-2column">
            <div>
                <h2>fascie orarie - tipo</h2>
                <hr>
                <?php 
                    //errori
                    if($_SESSION['error'] === ADMIN_FHT_NOT_EXIST) {
                        echo '<p class="phperror">Fascia oraria e tipo devono essere selezionati</p>';
                        $_SESSION['error'] = NONE;
                    } elseif ($_SESSION['error'] === ADMIN_FHT_ALREADY_EXIST) {
                        echo '<p class="phperror">Fascia oraria-tipo già esistente</p>';
                        $_SESSION['error'] = NONE;
                    }
                ?>
                <table id="fhg">
                    <thead>
                        <tr>
                            <th>Fascia oraria</th>
                            <th>Tipo</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($fascie_orarie_tipo -> num_rows > 0) {
                            foreach($fascie_orarie_tipo as $f) { ?>
                            <tr>
                                <td><?php echo $f['ora_inizio'].' - '.$f['ora_fine'] ?></td>
                                <td><?php echo $f['nome'] ?></td>
                                <td>
                                    <form action="../utils/targets/admin/fasce_orarie_tipo_rimuovi.php" method="post">
                                        <fieldset>
                                            <legend>elimina fascia oraria giorno</legend>
                                            <input type="hidden" name="id_fht" value="<?php echo $f['id_fht'] ?>">
                                            <input type="submit" value="elimina">
                                        </fieldset>
                                    </form>
                                </td>
                            </tr>    
                        <?php   }
                            } else { ?>
                                <tr>
                                    <td colspan="3">Non ci sono fascie orarie</td>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <button onclick="popUpShow('add_fht')">Aggiungi fascia oraria - tipo</button>
                <div class="pop-up" id="add_fht" style="display: none">
                    <button onclick="popUpHide('add_fht')">Chiudi</button>
                    <form action="../utils/targets/admin/fasce_orarie_tipo_aggiungi.php" method="post">
                        <fieldset>
                            <legend>inserisci fascia oraria - tipo</legend>
                            <label for="fascia_oraria_input">Orario</label>
                            <select name="id_fascia_oraria" id="fascia_oraria_input" required>
                                <option value="-1">Seleziona orario</option>
                                <?php foreach($fascie_orarie as $r) { ?>
                                    <option value="<?php echo $r['id_fascia_oraria'] ?>"><?php echo $r['ora_inizio'].' - '.$r['ora_fine'] ?></option>
                                <?php } ?>
                            </select>
                            <label for="tipo_input">Tipo orario</label>
                            <select name="id_tipo_orario" id="tipo_input" required>
                                <option value="-1">Seleziona tipo orario </option>
                                <?php foreach($tipi as $r) { ?>
                                    <option value="<?php echo $r['id_tipo_orario'] ?>"><?php echo $r['nome'] ?></option>
                                <?php } ?>
                            </select> 
                            <input type="submit" value="Inserisci">
                        </fieldset>
                    </form>
                </div>    
            </div>
            
            <div>
                <h2>fascie orarie</h2>
                <hr>
                <?php 
                    if($_SESSION['error'] === ADMIN_FH_INVALID_VALUE) {
                        echo '<p class="phperror">L\'ora di inizio deve esserem minore dell\'ora di fine</p>';
                        $_SESSION['error'] = NONE;
                    } elseif ($_SESSION['error'] === ADMIN_FH_ALREADY_EXIST) {
                        echo '<p class="phperror">Fascia oraria già esistente</p>';
                        $_SESSION['error'] = NONE;
                    }
                ?>
                <table id="fh">
                    <thead>
                        <tr>
                            <th>ora inizio</th>
                            <th>ora fine</th>
                            <th>azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($fascie_orarie -> num_rows > 0) {
                            foreach($fascie_orarie as $f) { ?>
                            <tr>
                                <td><?php echo $f['ora_inizio'] ?></td>
                                <td><?php echo $f['ora_fine'] ?></td>
                                <td>
                                    <form action="../utils/targets/admin/fasce_orarie_rimuovi.php" method="post">
                                        <fieldset>
                                            <legend>elimina fascia oraria</legend>
                                            <input type="hidden" name="id_fascia_oraria" value="<?php echo $f['id_fascia_oraria'] ?>">
                                            <input type="submit" value="elimina">
                                        </fieldset>
                                    </form>
                                </td>
                            </tr>    
                        <?php   }
                            } else { ?>
                                <tr>
                                    <td colspan="3">Non ci sono fascie orarie</td>
                                </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <button onclick="popUpShow('add_fh')">Aggiungi fascia oraria</button>
                <div class="pop-up" id="add_fh" style="display: none">
                    <button onclick="popUpHide('add_fh')">Chiudi</button>
                    <form action="../utils/targets/admin/fasce_orarie_aggiungi.php" method="post">
                        <fieldset>
                            <legend>inserisci fascia oraria</legend>
                            <label for="ora_inizio_input">Ora inizio</label>
                            <input type="time" name="ora_inizio" id="ora_inizio_input" required>
                            <label for="ora_inizio_input">Ora fine</label>
                            <input type="time" name="ora_fine" id="ora_fine_input" required>
                            <input type="submit" value="Inserisci">
                        </fieldset>
                    </form>
                </div>   
            </div>
        </section>
    </main>
    <?php 
        include("../utils/prefabs/footer.php"); 
        getFooter('../');
    ?>

    <script src="../js/popUps.js"></script>
    <script src="../js/logout.js"></script>
</body>
</html>