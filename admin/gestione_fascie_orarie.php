<?php
    session_start();
    
    include("../utils/utils.php");
    include("../utils/db_manager.php");
    include("../utils/session_errors.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, '../login.php');
    }

    db_setup();

    $result = db_do_query("SELECT nome, cognome, ruolo FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente']);
    $row = $result->fetch_assoc();
    
    $nome = $row['nome'];
    $cognome = $row['cognome'];
    $ruolo = $row['ruolo'];

    if($ruolo !== 'A') {
        $_SESSION['error'] = NO_PERMISSION;
        redirect(0, '../home.php');
    }

    $query_fh = 
        'SELECT id_fascia_oraria, ora_inizio, ora_fine
         FROM fascia_oraria';
    
    $query_g = 
        'SELECT id_giorno, nome
         FROM girono';

    $query_fhg = 
        'SELECT fhg.id_fascia_oraria_giorno, fh.ora_inizio, fh.ora_fine, g.nome
         FROM fascia_oraria_giorno fhg
         JOIN fascia_oraria fh ON fhg.fk_fascia_oraria = fh.id_fascia_oraria
         JOIN giorno g ON g.id_giorno = fhg.fk_giorno';
    
    $fascie_orarie = db_do_simple_query($query_fh);
    $fascie_orarie_giorno = db_do_simple_query($query_fhg);
    $gironi = db_do_simple_query($query_fhg);

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
        include("../utils/prefabs/header.php");
        getHeader('./../');
    ?>
    <nav>
        <ul>
            <li><a href="./../utils/targets/logout.php">logout</a></li>
            <li><a href="./../impostazioni_utente.php">impostazioni</a></li>
            <li><a href="./gestione_utenti.php">utenti</a></li>
            <li><span>fascie orarie</span></li>
            <li><a href="./../home.php">home</a></li>
        </ul>
    </nav>
    <main>
        <h2>fascie orarie</h2>
        <hr>

        <section class="grid-2column">
            <div>
                <h2>fascie orarie - giorni</h2>
                <hr>
                <table id="fhg">
                    <thead>
                        <tr>
                            <th>Fascia ora</th>
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
                                    <form action="../utils/targets/admin/">
                                        <fieldset>
                                            <legend>elimina fascia oraria</legend>
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
                        <tr>
                            <td colspan="3">
                                <form action="../utils/targets/admin/">
                                    <fieldset>
                                        <legend>inserisci fascia oraria giorno</legend>
                                        <select name="id_fascia_oraria" id="fascia_oraria_input" required>
                                            <?php 
                                                //da prender giorni e ore
                                            ?>
                                        </select> 
                                        <input type="submit" value="Inserisci">
                                    </fieldset>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>   
            </div>
            
            <div>
                <h2>fascie orarie</h2>
                <hr>
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
                                <form action="../utils/targets/admin/">
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
                    <tr>
                        <td colspan="3">
                            <form action="../utils/targets/admin/">
                                <fieldset>
                                    <label for="ora_inizio_input">Ora inizio</label>
                                    <input type="time" name="ora_inizio" id="ora_inizio_input" required>
                                    <label for="ora_inizio_input">Ora fine</label>
                                    <input type="time" name="ora_fine" id="ora_fine_input" required>
                                    <input type="submit" value="Inserisci">
                                </fieldset>
                            </form>
                        </td>
                    </tr>
                    </tbody>
                </table>   
            </div>
        </section>
    </main>
    <?php 
        include("../utils/prefabs/footer.php"); 
        getFooter('../');
    ?>
</body>
</html>