<?php
    session_start();

    include("../db_managet.php");
    include("../utils.php");

    //controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, 'login.php');
    }

    db_setup();

    $query = 
        'SELECT u.nome as docente_nome, u.cognome as docente_cognome, a.piano, a.n_aula, a.nome as aula_nome, pl.nome as plesso_nome, p.data, h.ora_inizio, h.ora_fine, p.descrizione, p.id_prenotazione 
         FROM prenotazione p
         JOIN fascia_oraria h ON h.id_fascia_oraria = p.fk_fascia_oraria
         JOIN aula a ON a.id_aula = p.fk_aula
         JOIN plesso pl ON pl.id_plesso = a.fk_plesso
         JOIN utente u ON u.id_utente = p.fk_utente
         WHERE DATEDIFF(now(), p.data) <= 0 AND p.data_approvazione IS NULL AND p.data_eliminazione IS NULL';

    $prenotazini = db_do_simple_query($query);

    db_close();
?>

<h2>Gestisci prenotazioni</h2>
<hr>
<?php /*
<table id="prenotazioni_in_attesa">
    <thead>
        <tr>
            <th>professore</th>
            <th>aula</th>
            <th>plesso</th>
            <th>data</th>
            <th>fascia oraria</th>
            <th>descrizione</th>
            <th>azioni</th>
        </tr>
    </thead>
    <tbody>
        <?php
            if ($prenotazini->num_rows > 0) {
                foreach($prenotazini as $row) { ?> 
                    <tr>
                        <td><?php echo $row['docente_cognome'].' '.$row['docente_nome'] ?></td>
                        <td><?php echo normalize_aula($row['piano'], $row['n_aula']).' ('.$row['aula_nome'].')' ?></td>
                        <td><?php echo $row['plesso_nome'] ?></td>
                        <td><?php echo $row['data'] ?></td>
                        <td><?php echo $row['ora_inizio'].' - '.$row['ora_fine'] ?></td>
                        <td><?php echo $row['descrizione'] ?></td>
                        <td>
                            <form action="./utils/targets/elimina_prenotazione.php" method="post">
                                <fieldset>
                                    <legend>Rifiuta</legend>
                                    <input type="hidden" name="id_prenotazione" value="<?php echo $row['id_prenotazione'] ?>">
                                    <input type="submit" value="rifiuta">
                                </fieldset>
                            </form>
                            <form action="./utils/targets/admin/conferma_prenotazione.php" method="post">
                                <fieldset>
                                    <legend>Accetta</legend>
                                    <input type="hidden" name="id_prenotazione" value="<?php echo $row['id_prenotazione'] ?>">
                                    <input type="submit" value="accetta">
                                </fieldset>
                            </form>
                        </td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="7">Non ci sono prenotazioni da confermare</td>
                </tr>
        <?php } ?>
    </tbody>
</table>

<br><hr><br>

*/ ?>
<div>
    <input id ="blocco_aule_selector" type="checkbox" onclick="changeView()">
    <label for="blocco_aule_selector">Riserva aule</label>
</div>

<?php 
    include('prenota_table-v2.php');
?>

<script>
    window.onload = function() {
        if(document.getElementById("blocco_aule_selector").checked) {
            changeView();
        }
    };

    function changeView() {
        var enabledRows = Array.from(document.getElementsByClassName('en'));
        var disabledRows = Array.from(document.getElementsByClassName('dis'));

        for(var i=0; i < enabledRows.length; i++) {
            enabledRows[i].classList.remove("en");
            enabledRows[i].classList.add("dis");
        }

        for(var i=0; i < disabledRows.length; i++) {
            disabledRows[i].classList.remove("dis");
            disabledRows[i].classList.add("en");
        }
    }
</script>