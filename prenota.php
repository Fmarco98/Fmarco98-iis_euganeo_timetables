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

        .no {
            background-color:rgb(215, 215, 215);
        }
        .attesa {
            background-color:rgb(205, 161, 27); 
            color: white;
        }
        .prenotata {
            background-color:rgb(73, 126, 12); 
            color: white;
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
        <?php 
            include('utils/prefabs/prenota_table.php');
        ?>

    </main>
    
    <?php 
        include("utils/prefabs/footer.php"); 
        getFooter('./');
        db_close();
    ?>
            
</body>
</html>