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

    $query_aule = 
        'SELECT a.id_aula, a.nome aula_nome, a.piano, a.n_aula, p.nome plesso_nome 
         FROM aula a
         JOIN plesso p ON a.fk_plesso = p.id_plesso
         ORDER BY p.id_plesso, a.piano, a.n_aula';
    
    $query_plessi = 
        'SELECT id_plesso, nome
         FROM plesso';

    $query_aule_riservate = 
        'SELECT rf.id_richiesta_conferma, a.nome aula_nome, a.piano, a.n_aula, fh.ora_inizio, fh.ora_fine, p.nome plesso_nome
         FROM richiesta_conferma rf 
         JOIN aula a ON a.id_aula = rf.fk_aula
         JOIN fascia_oraria fh ON fh.id_fascia_oraria = rf.fk_fascia_oraria
         JOIN plesso p ON a.fk_plesso = p.id_plesso
         ORDER BY p.id_plesso, a.piano, a.n_aula';
    
    $query_fh = 
        'SELECT id_fascia_oraria, ora_inizio, ora_fine
         FROM fascia_oraria
         ORDER BY id_fascia_oraria';
    
    $aule = db_do_simple_query($query_aule);
    $aule_riservate = db_do_simple_query($query_aule_riservate);
    $plessi = db_do_simple_query($query_plessi);
    $fh = db_do_simple_query($query_fh);

    db_close();
?>

<!DOCTYPE html>
<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./../imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | gestione aule</title>
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
            <li><a href="./gestione_fasce_orarie.php">fasce orarie</a></li>
            <li><span>aule</span></li>
            <li><a href="./../home.php">home</a></li>
        </ul>
    </nav>
    <main>
    <section class="grid-2column">
            <div>
                <h2>aule</h2>
                <hr>
                <?php
                    if($_SESSION['error'] === ADMIN_AULA_INVADIL_VALUE) {
                        echo '<p class="phperror">Valori non validi</p>';
                        $_SESSION['error'] = NONE;
                    } elseif ($_SESSION['error'] === ADMIN_AULA_ALREADY_EXIST) {
                        echo '<p class="phperror">aula già presente</p>';
                        $_SESSION['error'] = NONE;
                    } 
                ?>
                <table id="aule">
                    <thead>
                        <tr>
                            <th>Numero</th>
                            <th>Nome</th>
                            <th>Plesso</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($aule-> num_rows > 0) {
                            foreach($aule as $r) { ?>
                            <tr>
                                <td><?php echo normalize_aula($r['piano'], $r['n_aula']) ?></td>
                                <td><?php echo $r['aula_nome'] ?></td>
                                <td><?php echo $r['plesso_nome'] ?></td>
                                <td>
                                    <form action="../utils/targets/admin/aula_rimuovi.php" method="post">
                                        <fieldset>
                                            <legend>elimina aula</legend>
                                            <input type="hidden" name="id_aula" value="<?php echo $r['id_aula'] ?>">
                                            <input type="submit" value="elimina">
                                        </fieldset>
                                    </form>
                                </td>
                            </tr>    
                        <?php   }
                            } else { ?>
                                <tr>
                                    <td colspan="4">Non ci sono aule</td>
                                </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="4">
                                <form action="../utils/targets/admin/aula_aggiungi.php" method="post">
                                    <fieldset>
                                        <legend>inserisci aula</legend>
                                        <label for="n_aula_input">N. aula</label>
                                        <input type="number" name="n_aula" id="n_aula_input" required>
                                        <label for="piano_input">Piano</label>
                                        <input type="number" name="piano" id="piano_input" required>
                                        <label for="nome_input">Nome</label>
                                        <input type="text" name="nome" id="nome_input" maxlength="30">
                                        <label for="plesso_input">Plesso</label>
                                        <select name="plesso" id="plesso_input">
                                            <option value="-1">Seleziona plesso</option>
                                            <?php foreach($plessi as $r) { ?>
                                                <option value="<?php echo $r['id_plesso'] ?>"><?php echo $r['nome'] ?></option>
                                            <?php } ?>
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
                <h2>Aule riservate</h2>
                <hr>
                <?php 
                    //errori
                    if($_SESSION['error'] === ADMIN_AULA_R_INVADIL_VALUE) {
                        echo '<p class="phperror">Aula e fascia oraria devono essere selezionate</p>';
                        $_SESSION['error'] = NONE;
                    } elseif ($_SESSION['error'] === ADMIN_AULA_R_ALREADY_EXIST) {
                        echo '<p class="phperror">Aula già riservata</p>';
                        $_SESSION['error'] = NONE;
                    } 
                ?>
                <table id="aule_riservate">
                    <thead>
                        <tr>
                            <th>Numero</th>
                            <th>Plesso</th>
                            <th>Ora inizio</th>
                            <th>Ora fine</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($aule_riservate -> num_rows > 0) {
                            foreach($aule_riservate as $r) { ?>
                            <tr>
                                <td><?php echo normalize_aula($r['piano'], $r['n_aula']) ?></td>
                                <td><?php echo $r['plesso_nome'] ?></td>
                                <td><?php echo $r['ora_inizio'] ?></td>
                                <td><?php echo $r['ora_fine'] ?></td>
                                <td>
                                    <form action="../utils/targets/admin/aula_riservata_rimuovi.php" method="post">
                                        <fieldset>
                                            <legend>elimina aula riservata</legend>
                                            <input type="hidden" name="id_richiesta_conferma" value="<?php echo $r['id_richiesta_conferma'] ?>">
                                            <input type="submit" value="elimina">
                                        </fieldset>
                                    </form>
                                </td>
                            </tr>    
                        <?php   }
                            } else { ?>
                                <tr>
                                    <td colspan="5">Non ci sono aule riservate</td>
                                </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="5">
                                <form action="../utils/targets/admin/aula_riservata_aggiungi.php" method="post">
                                    <fieldset>
                                        <legend>inserisci aula riservata</legend>
                                        <label for="aula_input">Aula</label>
                                        <select name="aula" id="aula_input">
                                            <option value="-1">Seleziona aula</option>
                                            <?php foreach($aule as $r) { ?>
                                                <option value="<?php echo $r['id_aula'] ?>"><?php echo normalize_aula($r['piano'], $r['n_aula']).' ('.$r['aula_nome'].') - '.$r['plesso_nome'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <label for="fh_input">Fascia oraria</label>
                                        <select name="fascia_oraria" id="fh_input">
                                            <option value="-1">Seleziona fascia oraria</option>
                                            <?php foreach($fh as $r) { ?>
                                                <option value="<?php echo $r['id_fascia_oraria'] ?>"><?php echo $r['ora_inizio'].' - '.$r['ora_fine'] ?></option>
                                            <?php } ?>
                                        </select>
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