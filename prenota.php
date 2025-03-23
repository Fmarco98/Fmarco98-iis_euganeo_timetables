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
    db_close();

    $row = $result->fetch_assoc();

    $nome = $row['nome'];
    $cognome = $row['cognome'];
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
        table {
            width: 90%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            border: 4px solid #000000;
        }

        th, td {
            width: 14%;
            height: 80px;
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
            border-color: black
        
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
        <h2>Tabella Prenotazioni - Scuola</h2>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Lunedì</th>
                    <th>Martedì</th>
                    <th>Mercoledì</th>
                    <th>Giovedì</th>
                    <th>Venerdì</th>
                    <th>Sabato</th>
                </tr>
            </thead>
            <tbody>
                <!-- Fasce Orarie -->
                <tr>
                    <td>
                        <p class="ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                   
                </tr>
                <!-- Altri orari simili -->
                <tr>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                    <td>
                        <p class= "ora-inizio">7.55</p>
                        <form action="pren">
                            <input type="hidden" name="fasdadsa">
                            <input type="submit" value="Prenota">
                        </form>
                        <p class="ora-fine">8.55</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
    
    <?php 
        include("utils/prefabs/footer.php"); 
        getFooter('./');
    ?>
</body>
</html>