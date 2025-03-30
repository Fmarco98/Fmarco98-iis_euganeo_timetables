<?php
    session_start();
    
    include("utils/utils.php");
    include("utils/db_manager.php");
    include("utils/session_errors.php");

    // controllo login
    if(!isset($_SESSION['id_utente'])) {
        redirect(0, 'login.php');
    }
    
    db_setup();

    $result = db_do_query("SELECT nome, cognome FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente']);
    $row = $result->fetch_assoc();
    
    $nome = ucfirst($row['nome']);
    $cognome = ucfirst($row['cognome']);
?>

<!DOCTYPE html>
<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | prenota</title>
    
    <style>
        /* ---------- stile della tabelle ---------- */
        table {
            width: 90%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            border: 4px solid #000000;
        }

        th, td {
            width: 14%;
            text-align: center;
            border: 1px solid #ddd;
            position: relative;
            color: black;
        }
        
        th {
            background-color: light-blue;
            font-weight: bold;
            border-color: blue
        }
        
        tr{
            color : black;
            border-color: black;
        }
        
        td{
            background-color:#ADD8E6;
            border-color: black;
        }
        
        td form {
            display: grid;
            grid-template-columns: auto;
            grid-template-rows: auto;
        }
        
        td input[type="submit"]{
            width: 100%;
            height: auto;
            height: 12vh;
            opacity: 0;
        }

        .time-slot {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .time-slot p {
            margin: 0;
        }

        .btn-booked {
            background-color: #d9534f;
            color: white;
            cursor: not-allowed;
        }

        h2{
            font-size: 30px
        }

    </style>
</head>

<body class="dark-white-bg">    
    <?php 
        include("utils/prefabs/header.php");
        getHeader('./');
    ?>
    <nav>
        <ul>
            <li><a href="./utils/targets/logout.php">logout</a></li>
            <li><a href="./impostazioni_utente.php">impostazioni</a></li>
            <li><span>prenota</span></li>
            <li><a href="./home.php">home</a></li>
        </ul>
    </nav>
    <main>
        <h2>Prenotazioni</h2>
        <hr>
        <form action="prenota.php" method="post">
            <fieldset>
                <legend>filtro aula</legend>
                <label for="plesso_input">Plesso</label>
                <select name="id_plesso" id="plesso_input">
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
                
                <?php if(isset($_POST['id_plesso']) && $_POST['id_plesso'] != -1) { ?>
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
                    <input type="date" name="data" id="data_input" required>
                <?php } ?>

                <input type="submit" value="Seleziona">
            </fieldset>
        </form>
        <hr>
        <?php 
            // Segnalazione errori
            if($_SESSION['error'] === PRENOTA_ALREADY_EXIST) {
                echo '<p class="phperror">Aula già prenotata</p>';
                $_SESSION['error'] = NONE;
            }
        ?>
        <?php if(isset($_POST['id_plesso']) && isset($_POST['id_aula']) && isset($_POST['data']) && $_POST['id_plesso'] != -1 && $_POST['id_aula'] != -1) { ?>
            <table>
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
                             JOIN fascia_oraria fh ON fh.id_fascia_oraria = fhg.fk_fascia_oraria 
                             WHERE fk_giorno = ?';

                            $result = db_do_query($query,'i', $r['id_giorno']);

                            $temp = [];
                            foreach($result as $r) {
                                $temp[] = array('id_fascia_oraria' => $r['id_fascia_oraria'], 'ora_inizio' => $r['ora_inizio'], 'ora_fine' => $r['ora_fine']);
                            }
                            $fh_list[] = $temp;
                        }
                    
                        $data_giorni = get_settimana('Y-m-d', $_POST['data']);

                        $query_prenotazioni = 
                            'SELECT p.descrizione, p.data, p.fk_fascia_oraria, u.cognome, u.nome 
                             FROM prenotazione p
                             JOIN utente u ON u.id_utente = p.fk_utente
                             WHERE p.fk_aula = ? AND p.data BETWEEN ? AND ?';

                        $prenotazioni = db_do_query($query_prenotazioni, 'iss', $_POST['id_aula'], $data_giorni[0], $data_giorni[count($data_giorni)-1]);

                        // numero massimo di righe da generare
                        $query_max_h = 
                            'SELECT MAX(a) max 
                             FROM (
                                SELECT fk_giorno, COUNT(fk_fascia_oraria) a
                                FROM fascia_oraria_giorno
                                GROUP BY fk_giorno
                             ) as a';

                        $num_h = db_do_simple_query($query_max_h)->fetch_assoc()['max'];

                        //generazione righe
                        for($i_h = 0; $i_h < $num_h; $i_h++) {
                            echo '<tr>';
                            for($i_g = 0; $i_g < count($fh_list); $i_g++) { 
                                if(count($fh_list[$i_g]) > $i_h) {
                                    $cognome_prof = '';
                                    $nome_prof = '';
                                    $desc = '';
                                    
                                    //Controllo se prenotata
                                    foreach($prenotazioni as $r) {
                                        if($r['data'] == $data_giorni[$i_g] && $r['fk_fascia_oraria'] == $fh_list[$i_g][$i_h]['id_fascia_oraria']) {
                                            $cognome_prof = $r['cognome'];
                                            $nome_prof = $r['nome'];
                                            $desc = $r['descrizione'];

                                            break;
                                        }
                                    }

                                    if(!$nome_prof) { //non prenotata?>
                                        <td>
                                            <p class= "ora-inizio"><?php echo $fh_list[$i_g][$i_h]['ora_inizio'] ?></p>
                                            <form action="prenota_conferma.php" method="post">
                                                <input type="hidden" name="id_fascia_oraria" value="<?php echo $fh_list[$i_g][$i_h]['id_fascia_oraria'] ?>">
                                                <input type="hidden" name="id_aula" value="<?php echo $_POST['id_aula'] ?>">
                                                <input type="hidden" name="data" value="<?php echo $data_giorni[$i_g] ?>">
                                                <input type="submit" value="Prenota">
                                            </form>
                                            <p class="ora-fine"><?php echo $fh_list[$i_g][$i_h]['ora_fine'] ?></p>
                                        </td>
                              <?php } else { //prenotata?>
                                        <td style="background-color:rgb(205, 161, 27); color: white;">
                                            <p class= "ora-inizio"><?php echo $fh_list[$i_g][$i_h]['ora_inizio'] ?></p>
                                            <h3><?php echo ucfirst($cognome_prof).' '.ucfirst($nome_prof) ?></h3>
                                            <h4><?php echo ucfirst($desc) ?></h4>
                                            <p class="ora-fine"><?php echo $fh_list[$i_g][$i_h]['ora_fine'] ?></p>
                                        </td>
                              <?php }
                                } else {
                                    // ora mancante
                                    echo '<td style="background-color:rgb(215, 215, 215)"></td>';
                                }
                            }
                            echo '</tr>';
                        }
                    ?>

                </tbody>
            </table>        
        <?php } ?>

    </main>
    
    <?php 
        include("utils/prefabs/footer.php"); 
        getFooter('./');
        db_close();
    ?>
    
    <script>
        let select1 = document.getElementById('plesso_input');
        let select2 = document.getElementById('aula_input');
        
        // Recupera il valore salvato, se esiste
        let savedValue1 = localStorage.getItem('selectedValue1');
        let savedValue2 = localStorage.getItem('selectedValue2');
        if (savedValue1) {
            select1.value = savedValue1;
        }
        if (savedValue2) {
            select2.value = savedValue2;
        }

        // Salva il valore quando cambia
        select1.addEventListener('change', () => {
            localStorage.setItem('selectedValue1', select1.value);
        });
        
        select2.addEventListener('change', () => {
            localStorage.setItem('selectedValue2', select2.value);
        });
    </script>               
</body>
</html>