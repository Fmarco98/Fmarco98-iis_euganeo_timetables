<?php 
    session_start();

    include("utils/utils.php");
    include("utils/db_manager.php");
    include("utils/session_errors.php");

    $dati_ok = true;

    //check del postback
    if(isset($_POST['email']) && isset($_POST['password'])) {
        
        db_setup();
        $result = db_do_query("SELECT id_utente FROM utente WHERE email=? AND password=?", "ss", $_POST['email'], MD5($_POST['password']));
        db_close();

        //validità login
        if($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            $_SESSION['id_utente'] = $row['id_utente'];

            redirect(0, 'home.php');
        } else {
            $dati_ok = false;
        }
    } 

    $_SESSION['error'] = NONE;
?>

<!DOCTYPE html>
<html lang="it-IT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css" media="screen"> <!--and (min-width:1000px)-->
    <link rel="shortcut icon" href="./imgs/logo/logo_iiseuganeo.png" type="image/x-icon">
    <title>iiS Euganeo timetables | login</title>
</head>
<body class="dark-white-bg">
    <main class="full-display">
        <form action="login.php" method="post" class="login center">
            <div>
                <div class="column-style center" id="imagezone">
                    <img src="./imgs/logo/logo_iiseuganeo.png" alt="iiS Euganeo timetables" id="logo">
                    <img src="./imgs/logo/logo_name.png" alt="iiS Euganeo timetables" id="logoname">
                </div>
            </div>
            <?php
                //login errato
                if(!$dati_ok) {
                    echo '<p class="phperror">email o password non validi</p>';
                }
            ?>
            <fieldset>
                <legend>Login</legend>
                <label for="email_input">email</label>
                <input type="email" name="email" id="email_input" placeholder="example@ex.ex" required>
                <label for="password_input">password</label>
                <div>
                    <input type="password" name="password" id="password_input" placeholder="ciao1234" required>
                    <button class="pwd-button"></button>
                </div>
                <input type="submit" value="Login">
            </fieldset>
            <hr>
            <div>
                <p class="small-text center">Non hai un account? <a href="./sign-up.php">registrati</a></p>
            </div>
        </form>
    </main>

    <script src="./js/pwd-button.js"></script>
</body>
</html>