<?php
    session_start();
    
    include("utils/utils.php");
    include("utils/db_manager.php");
    include("utils/session_errors.php");

    if(!isset($_SESSION['id_utente'])) {
        redirect(0, 'login.php');
    }

    db_setup();
    $result = db_do_query("SELECT nome, cognome, email, ruolo FROM utente WHERE id_utente = ?", 'i', $_SESSION['id_utente']);
    db_close();

    $row = $result->fetch_assoc();

    $nome = ucfirst($row['nome']);
    $cognome = ucfirst($row['cognome']);
    $email = $row['email'];
    $ruolo = $row['ruolo'];
?>

<!DOCTYPE html>
<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | impostazioni account</title>
</head>
<body class="dark-white-bg">
    <?php 
        include("utils/prefabs/header.php");
        getHeader('./')
    ?>
    <nav>
        <ul>
            <li><a href="./utils/targets/logout.php">logout</a></li>
            <li><span>impostazioni</span></li>
            <?php 
                if($ruolo === 'D') {
                    echo '<li><a href="./prenota.php">prenota</a></li>';
                } elseif($ruolo === 'A') {
                    echo '<li><a href="./admin/gestione_utenti.php">utenti</a></li>';
                    echo '<li><a href="./admin/gestione_fasce_orarie.php">fasce orarie</a></li>';
                }
            ?>
            <li><a href="./home.php">home</a></li>
        </ul>
    </nav>
    <main>
        <h1>Impostazioni account</h1>
        <h2>Modifica dati utente</h2>
        <form action="./utils/targets/modifica_dati_utente.php" method="post">
            <fieldset>
                <legend>modifica dati utente</legend>
                <?php 
                    if($_SESSION['error'] === MODIFY_EMAIL_IN_USO) {
                        echo '<p class="phperror">email già in uso</p>';
                        $_SESSION['error'] = NONE;
                    }

                    echo '<label for="nome_input">Nome: </label>
                    <input type="text" name="nome" id="nome_input" placeholder="'.$nome.'">
                    <label for="nome_input">Cognome: </label>
                    <input type="text" name="cognome" id="cognome_input" placeholder="'.$cognome.'">
                    <label for="nome_input">Email: </label>
                    <input type="email" name="email" id="email_input" placeholder="'.$email.'">'
                ?>
                <input type="submit" value="Applica">
            </fieldset>
        </form>
        <hr>
        <h2>Modifica password</h2>
        <form action="./utils/targets/modifica_password_utente.php" method="post">
            <fieldset>
                <legend>modifica password</legend>
                
                <?php
                    if($_SESSION['error'] === MODIFY_PWD_ERRATA) {
                        echo '<p class="phperror">vecchia password errata</p>';
                        $_SESSION['error'] = NONE;
                    }
                    if($_SESSION['error'] === MODIFY_PWD_NON_CONFERMA) {
                        echo '<p class="phperror">nuova password non coincide</p>';
                        $_SESSION['error'] = NONE;
                    }
                ?>

                <label for="old_password_input">Vecchia password: </label>
                <div class="border-bottom">
                    <input type="password" name="old_password" id="old_password_input" required>
                    <button class="pwd-button"></button>
                </div>
                <label for="new_password_input">Nuova password: </label>
                <div>
                    <input type="password" name="new_password" id="new_password_input" required>
                    <button class="pwd-button"></button>
                </div>
                <label for="new_password_c_input">Nuova password conferma: </label>
                <div>
                    <input type="password" name="new_password_conferma" id="new_password_c_input" required>
                    <button class="pwd-button"></button>
                </div>

                <input type="submit" value="Applica">
            </fieldset>
        </form>
    </main>
    <?php 
        include("utils/prefabs/footer.php"); 
        getFooter('./');
    ?>
    
    <script src="./js/pwd-button.js"></script>
</body>
</html>