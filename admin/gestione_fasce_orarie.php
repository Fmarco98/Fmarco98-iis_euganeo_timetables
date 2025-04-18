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

    /*
    $query_fh = 
        'SELECT id_fascia_oraria, ora_inizio, ora_fine
         FROM fascia_oraria
         ORDER BY id_fascia_oraria';
    
    $query_g = 
        'SELECT id_giorno, nome
         FROM giorno
         ORDER BY id_giorno';

    $query_fhg = 
        'SELECT fhg.id_fascia_oraria_giorno, fh.ora_inizio, fh.ora_fine, g.nome
         FROM fascia_oraria_giorno fhg
         JOIN fascia_oraria fh ON fhg.fk_fascia_oraria = fh.id_fascia_oraria
         JOIN giorno g ON g.id_giorno = fhg.fk_giorno
         ORDER BY g.id_giorno, fh.ora_inizio';
    
    $fascie_orarie = db_do_simple_query($query_fh);
    $fascie_orarie_giorno = db_do_simple_query($query_fhg);
    $gironi = db_do_simple_query($query_g);
    */
    
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
            <li><span>fasce orarie</span></li>
            <li><a href="./gestione_aule.php">aule</a></li>
            <li><a href="./../home.php">home</a></li>
        </ul>
    </nav>
    <main>
        <h1>PAGINA NON FUNZIONANTE</h1>
        <?php /*
        <section class="grid-2column">
            <div>
                <h2>fascie orarie - giorni</h2>
                <hr>
                <?php 
                    //errori
                    if($_SESSION['error'] === ADMIN_FHG_NOT_EXIST) {
                        echo '<p class="phperror">Fascia oraria e giorno devono essere selezionati</p>';
                        $_SESSION['error'] = NONE;
                    } elseif ($_SESSION['error'] === ADMIN_FHG_ALREADY_EXIST) {
                        echo '<p class="phperror">Fascia oraria-girono già esistente</p>';
                        $_SESSION['error'] = NONE;
                    }
                ?>
                <table id="fhg">
                    <thead>
                        <tr>
                            <th>Fascia oraria</th>
                            <th>Giorni</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($fascie_orarie_giorno -> num_rows > 0) {
                            foreach($fascie_orarie_giorno as $f) { ?>
                            <tr>
                                <td><?php echo $f['ora_inizio'].' - '.$f['ora_fine'] ?></td>
                                <td><?php echo $f['nome'] ?></td>
                                <td>
                                    <form action="../utils/targets/admin/fasce_orarie_girono_rimuovi.php" method="post">
                                        <fieldset>
                                            <legend>elimina fascia oraria giorno</legend>
                                            <input type="hidden" name="id_fascia_oraria_giorno" value="<?php echo $f['id_fascia_oraria_giorno'] ?>">
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

                <button onclick="popUpShow('add_fhg')">Aggiungi fascia oraria - giorno</button>
                <div class="pop-up" id="add_fhg" style="display: none">
                    <button onclick="popUpHide('add_fhg')">Chiudi</button>
                    <form action="../utils/targets/admin/fasce_orarie_girono_aggiungi.php" method="post">
                        <fieldset>
                            <legend>inserisci fascia oraria giorno</legend>
                            <label for="fascia_oraria_input">Orario</label>
                            <select name="id_fascia_oraria" id="fascia_oraria_input" required>
                                <option value="-1">Seleziona orario</option>
                                <?php foreach($fascie_orarie as $r) { ?>
                                    <option value="<?php echo $r['id_fascia_oraria'] ?>"><?php echo $r['ora_inizio'].' - '.$r['ora_fine'] ?></option>
                                <?php } ?>
                            </select>
                            <label for="fascia_oraria_input">Giorno</label>
                            <select name="id_giorno" id="giorno_input" required>
                                <option value="-1">Seleziona giorno </option>
                                <?php foreach($gironi as $r) { ?>
                                    <option value="<?php echo $r['id_giorno'] ?>"><?php echo $r['nome'] ?></option>
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
        */ ?>
    </main>
    <?php 
        include("../utils/prefabs/footer.php"); 
        getFooter('../');
    ?>

    <script src="../js/popUps.js"></script>
    <script src="../js/logout.js"></script>
</body>
</html>