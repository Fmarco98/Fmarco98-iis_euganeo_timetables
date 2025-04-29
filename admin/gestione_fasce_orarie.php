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

    $query_t = 
        'SELECT id_tipo_orario, nome
         FROM tipo_orario
         ORDER BY nome';
    
    $query_g = 
        'SELECT id_giorno, nome
         FROM giorno
         ORDER BY id_giorno';

    $query_gt = 
        'SELECT g.id_giorno, g.nome nome_g, t.nome nome_t 
         FROM giorno g 
         JOIN tipo_orario t ON g.fk_tipo_orario = t.id_tipo_orario';

    
    $tipo = db_do_simple_query($query_t);
    $gironi = db_do_simple_query($query_g);
    $giorno_tipo = db_do_simple_query($query_gt);
    
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
        <a href="./gestione_orari.php" style="float:right">gestione orari</a>
        <section class="grid-2column">
            <div>
                <h2>giorni - tipo</h2>
                <hr>
                <?php 
                    //errori
                    if($_SESSION['error'] === ADMIN_GT_NOT_EXIST) {
                        echo '<p class="phperror">giorno e tipo orario devono essere selezionati</p>';
                        $_SESSION['error'] = NONE;
                    } elseif ($_SESSION['error'] === ADMIN_GT_ALREADY_EXIST) {
                        echo '<p class="phperror">girono-tipo orario già esistente</p>';
                        $_SESSION['error'] = NONE;
                    }
                ?>
                <table id="fhg">
                    <thead>
                        <tr>
                            <th>Giorni</th>
                            <th>Tipo orario</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($giorno_tipo -> num_rows > 0) {
                            foreach($giorno_tipo as $f) { ?>
                            <tr>
                                <td><?php echo $f['nome_g'] ?></td>
                                <td><?php echo $f['nome_t'] ?></td>
                                <td>
                                    <form action="../utils/targets/admin/girono_tipo_rimuovi.php" method="post">
                                        <fieldset>
                                            <legend>elimina giorno tipo</legend>
                                            <input type="hidden" name="id_giorno" value="<?php echo $f['id_giorno'] ?>">
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

                <button onclick="popUpShow('add_gt')">Aggiungi giorno - tipo</button>
                <div class="pop-up" id="add_gt" style="display: none">
                    <button onclick="popUpHide('add_gt')">Chiudi</button>
                    <form action="../utils/targets/admin/girono_tipo_aggiungi.php" method="post">
                        <fieldset>
                            <legend>inserisci fascia oraria giorno</legend>
                            <label for="tipo_orario_input">Orario</label>
                            <select name="id_tipo_orario" id="tipo_orario_input" required>
                                <option value="-1">Seleziona tipo orario</option>
                                <?php foreach($tipo as $r) { ?>
                                    <option value="<?php echo $r['id_tipo_orario'] ?>"><?php echo $r['nome'] ?></option>
                                <?php } ?>
                            </select>
                            <label for="giorno_input">Giorno</label>
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