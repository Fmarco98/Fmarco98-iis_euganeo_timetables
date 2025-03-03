<?php
    include("utils/redirect.php");
    
    $password_ok = true;
    $email_gia_in_uso = false;

    if(isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_conferma'])) {
        if($_POST['password'] === $_POST['password_conferma']) {
            //passwords uguali

            $mysql = new mysqli('localhost', 'iis_euganeo_timetables', 'iis_euganeo_timetables_password', 'iis_euganeo_timetables');
            $mysql->query("START TRANSACTION");

            $stmt = $mysql->prepare("SELECT ? IN (SELECT email FROM utente) AS a");
            $stmt->bind_param('s', $_POST['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if(!$row['a']) {
                $stmt = $mysql->prepare("INSERT INTO utente(email, password, nome, cognome, ruolo) VALUES (?, ?, ?, ?, 'D')");
                $stmt->bind_param('ssss', $_POST['email'], $_POST['password'], $_POST['nome'], $_POST['cognome']);
                $stmt->execute();

                $mysql->query("COMMIT");
                
                redirect(0, 'login.php');
            } else {
                $mysql->query("ROLLBACK");
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
    <link rel="shortcut icon" href="./imgs/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | Sign up</title>
</head>
<body class="dark-white-bg">
    <?php 
        echo "psw_ok: ".$password_ok." | email_ok: ".!$email_gia_in_uso." |";
    ?>
    <main class="full-display">
        <form action="" method="post" class="sign-up center">
            <div>
                <div class="border-bottom row-style" id="imagezone">
                    <img src="./imgs/logo_iiseuganeo.png" alt="iiS Euganeo timetables" id="logo">
                    <img src="./imgs/mokup_logo.png" alt="" id="logoname">
                </div>
                <fieldset>
                    <legend>dati</legend>
                    <label for="nome_input">Nome:</label>
                    <input type="text" name="nome" id="nome_input" required>
                    <label for="cognome_input">Cognome:</label>
                    <input type="text" name="cognome" id="cognome_input" required>
                    <label for="email_input">Email:</label>
                    <input type="email" name="email" id="email_input" required>
                    <p></p>
                </fieldset>
            </div>
            <fieldset class="border-left">
                <legend>password</legend>
                <label for="password_input">Password:</label>
                <input type="password" name="password" id="password_input" required>
                <label for="password_input">Conferma password:</label>
                <input type="password" name="password_conferma" id="password_conferma_input" required>
                <input type="submit" value="Registrati" style="margin-top: auto">
            </fieldset>
        </form>
    </main>
</body>
</html>