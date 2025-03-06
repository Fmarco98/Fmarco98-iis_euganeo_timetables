<?php
    session_start();
    
    //imports
    include("utils/redirect.php");
    include("utils/db_manager.php");
    
    $password_ok = true;
    $email_gia_in_uso = false;

    if(isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_conferma'])) {
        if($_POST['password'] === $_POST['password_conferma']) {  //passwords corrispondenti

            db_setup();
            db_start_transaction();

            $result = db_select("SELECT ? IN (SELECT email FROM utente) AS a", 's', $_POST['email']);
            $row = $result->fetch_assoc();
            
            if(!$row['a']) {
                db_insert("INSERT INTO utente(email, password, nome, cognome, ruolo) VALUES (?, ?, ?, ?, 'D')", 'ssss', $_POST['email'], $_POST['password'], $_POST['nome'], $_POST['cognome']);
                db_end_transaction('y');
                db_close();

                redirect(0, 'login.php');  //login effettuato
            } else {
                db_end_transaction('n');
                db_close();

                $email_gia_in_uso = true;
            }

        } else {
            $password_ok = false;
        }

        
    }
?>

<!DOCTYPE html>
<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | Sign up</title>
</head>
<body class="dark-white-bg">
    <!--<?php 
        echo "psw_ok: ".$password_ok." | email_ok: ".!$email_gia_in_uso." |";
    ?>-->
    <main class="full-display">
        <form action="" method="post" class="sign-up center">
<<<<<<< HEAD
            <div class="border-bottom row-style" id="imagezone">
                <img src="./imgs/logo/logo_iiseuganeo.png" alt="iiS Euganeo timetables" id="logo">
                <img src="./imgs/logo/logo_name.png" alt="iiS Euganeo timetables" id="logoname">
            </div>
            <fieldset id="fieldset_dati" class="border-bottom">
                <legend>dati</legend>
=======
            <div>
                <div class="border-bottom row-style" id="imagezone">
                    <img src="./imgs/logo/logo_iiseuganeo.png" alt="iiS Euganeo timetables" id="logo">
                    <img src="./imgs/logo/logo_name.png" alt="iiS Euganeo timetables" id="logoname">
                </div>
                <fieldset id="fieldset_dati">
                    <legend>dati</legend>
>>>>>>> a862f8a1293de2789e429c7e231053667af8bd84
                    <?php 
                        if(!$password_ok || $email_gia_in_uso) {
                            if($email_gia_in_uso) {
                                echo '<p class="phperror">email già in uso</p>';
                            }
                            echo '<label for="nome_input">Nome:</label>
                            <input type="text" name="nome" id="nome_input" value="'.$_POST['nome'].'" required>
                            <label for="cognome_input">Cognome:</label>
                            <input type="text" name="cognome" id="cognome_input" value="'.$_POST['cognome'].'" required>
                            <label for="email_input">Email:</label>
                            <input type="email" name="email" id="email_input" value="'.$_POST['email'].'" required>';
                        } else {
                            echo '<label for="nome_input">Nome:</label>
                            <input type="text" name="nome" id="nome_input" required>
                            <label for="cognome_input">Cognome:</label>
                            <input type="text" name="cognome" id="cognome_input" required>
                            <label for="email_input">Email:</label>
                            <input type="email" name="email" id="email_input" required>';
                        }
                    ?>
                    
            </fieldset>
            <fieldset id="fieldset_password">
                <legend>password</legend>
                <?php
                    if(!$password_ok) {
                        echo '<p class="phperror">le password non corrispondono</p>';
                    }
                ?>
                <label for="password_input">Password:</label>
                <input type="password" name="password" id="password_input" required>
                <button class="pwd-button"></button>
                <label for="password_input">Conferma password:</label>
                <input type="password" name="password_conferma" id="password_conferma_input" required>
                <button class="pwd-button"></button>
                <input type="submit" value="Registrati" style="margin-top: auto">
            </fieldset>
        </form>
    </main>

    <script src="./js/pwd-button.js"></script>
</body>
</html>