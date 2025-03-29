<?php
    session_start();
    
    include("utils/utils.php");
    include("utils/db_manager.php");
    include("utils/session_errors.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, 'login.php');
    }
    
    db_setup();
    $result = db_do_query("SELECT nome, cognome FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente']);
    $row = $result->fetch_assoc();
    
    $nome = ucfirst($row['nome']);
    $cognome = ucfirst($row['cognome']);

    if(!(isset($_POST['id_aula']) && isset($_POST['data']) && isset($_POST['id_fascia_oraria']))) {
        redirect(0, 'prenota.php');
    }

    $query_aula = 
        'SELECT a.n_aula, a.piano, a.nome aula_nome, p.nome plesso_nome
         FROM aula a
         JOIN plesso p ON p.id_plesso = a.fk_plesso
         WHERE a.id_aula = ?';

    $query_fh = 
        'SELECT ora_inizio, ora_fine
         FROM fascia_oraria
         WHERE id_fascia_oraria = ?';

    $info_aula = db_do_query($query_aula, 'i', $_POST['id_aula'])->fetch_assoc();
    $info_fh = db_do_query($query_fh, 'i', $_POST['id_fascia_oraria'])->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | conferma prenotazione</title>
    
</head>

<body class="dark-white-bg">    
    <?php 
        include("utils/prefabs/header.php");
        getHeader('./');
    ?>
    <main>
        <h2>Conferma prenotazione</h2>
        <hr>

        <table>
            <thead>
                <th>Aula</th>
                <th>Plesso</th>
                <th>Fascia oraria</th>
                <th>Data</th>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo normalize_aula($info_aula['piano'], $info_aula['n_aula']).' ('.$info_aula['aula_nome'].')' ?></td>
                    <td><?php echo $info_aula['plesso_nome'] ?></td>
                    <td><?php echo $info_fh['ora_inizio'].' - '.$info_fh['ora_fine'] ?></td>
                    <td><?php echo format_date($_POST['data']) ?></td>
                </tr>
            </tbody>
        </table>

        <form action="utils/targets/prenota.php" method="post">
            <fieldset>
                <legend>conferma</legend>
                <input type="hidden" name="id_aula" value="<?php echo $_POST['id_aula'] ?>">
                <input type="hidden" name="id_fascia_oraria" value="<?php echo $_POST['id_fascia_oraria'] ?>">
                <input type="hidden" name="data" value="<?php echo $_POST['data'] ?>">
                <label for="desc_input">Descrizione</label>
                <textarea name="descrizione" id="desc_input" maxlength="500" rows="5"></textarea>
                <input name="submit" type="submit" value="Conferma">
                <input name="submit" type="submit" value="Annulla">
            </fieldset>
        </form>

    </main>
    <?php 
        include("utils/prefabs/footer.php"); 
        getFooter('./');
        db_close();
    ?>              
</body>
</html>