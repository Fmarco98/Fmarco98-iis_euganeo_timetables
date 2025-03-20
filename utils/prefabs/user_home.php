<?php
    session_start();

    include("../db_managet.php");
    include("../utils.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, 'login.php');
    }

    db_setup();

    $query_prenotazioni_attive = 
            'SELECT a.piano, a.n_aula, a.nome as aula_nome, pl.nome as plesso_nome, p.data, h.ora_inizio, h.ora_fine, p.descrizione, p.id_prenotazione 
             FROM prenotazione p
             JOIN fascia_oraria h ON h.id_fascia_oraria = p.fk_fascia_oraria
             JOIN aula a ON a.id_aula = p.fk_aula
             JOIN plesso pl ON pl.id_plesso = a.fk_plesso
             WHERE DATEDIFF(now(), p.data) <= 0 AND p.conferma = 1 AND p.fk_utente = ?';

    $query_prenotazioni_attese = 
            'SELECT a.piano, a.n_aula, a.nome as aula_nome, pl.nome as plesso_nome, p.data, h.ora_inizio, h.ora_fine, p.descrizione, p.id_prenotazione 
             FROM prenotazione p
             JOIN fascia_oraria h ON h.id_fascia_oraria = p.fk_fascia_oraria
             JOIN aula a ON a.id_aula = p.fk_aula
             JOIN plesso pl ON pl.id_plesso = a.fk_plesso
             WHERE DATEDIFF(now(), p.data) <= 0 AND p.conferma = 0 AND p.fk_utente = ?';

    $prenotazini_attive = db_do_query($query_prenotazioni_attive, 'i', $_SESSION['id_utente']);
    $prenotazini_attese = db_do_query($query_prenotazioni_attese, 'i', $_SESSION['id_utente']);

    db_close();
?>

<section class="grid-2column">
    <div>
        <h2>Prenotazioni future</h2>
        <hr>
        <table id="prenotazioni_attive">
            <thead>
                <tr>
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
                    if ($prenotazini_attive->num_rows > 0) {
                        foreach($prenotazini_attive as $row) { ?>
                            <tr>
                                <td><?php echo normalize_aula($row['piano'], $row['n_aula']).' ('.$row['aula_nome'].')' ?></td>
                                <td><?php echo $row['plesso_nome'] ?></td>
                                <td><?php echo $row['data'] ?></td>
                                <td><?php echo $row['ora_inizio'].' - '.$row['ora_fine'] ?></td>
                                <td><?php echo $row['descrizione'] ?></td>
                                <td>
                                    <form action="./utils/targets/elimina_prenotazione.php" method="post">
                                        <fieldset>
                                            <legend>Elimina</legend>
                                            <input type="hidden" name="id_prenotazione" value="'.$row['id_prenotazione'].'">
                                            <input type="submit" value="elimina">
                                        </fieldset>
                                    </form>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="6">Non ci sono prenotazioni attive</td>
                        </tr>
                <?php } ?>
            </tbody>
        </table>   
    </div>
    
    <div>
        <h2>Prenotazioni in attesa di confema</h2>
        <hr>
        <table id="prenotazioni_in_attesa">
            <thead>
                <tr>
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
                    if($prenotazini_attese->num_rows > 0) {
                        foreach($prenotazini_attese as $row) { ?>
                            <tr>
                                <td><?php echo normalize_aula($row['piano'], $row['n_aula']).' ('.$row['aula_nome'].')' ?></td>
                                <td><?php echo $row['plesso_nome'] ?></td>
                                <td><?php echo $row['data'] ?></td>
                                <td><?php echo $row['ora_inizio'].' - '.$row['ora_fine'] ?></td>
                                <td><?php echo $row['descrizione'] ?></td>
                                <td>
                                    <form action="./utils/targets/elimina_prenotazione.php" method="post">
                                        <fieldset>
                                            <legend>Elimina</legend>
                                            <input type="hidden" name="id_prenotazione" value="'.$row['id_prenotazione'].'">
                                            <input type="submit" value="elimina">
                                        </fieldset>
                                    </form>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="6">Non ci sono prenotazioni attive</td>
                        </tr>
                <?php } ?>
            </tbody>
        </table>   
    </div>
</section>
