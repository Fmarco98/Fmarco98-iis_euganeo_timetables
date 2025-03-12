<?php 
    //Evitare warning inutili
    error_reporting(E_ERROR | E_PARSE);

    //Config del server casa
    define('DB_SERVER', 'localhost');
    define('DB_USER', 'iis_euganeo_timetables');
    define('DB_PASSWORD', 'iis_euganeo_timetables_password');
    define('DB_DB', 'iis_euganeo_timetables');
    
    //Config server scuola
    /*define('DB_SERVER', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'root');
    define('DB_DB', 'SITO_cmc_euganeo_timetables');*/
    

    //Variabili globali
    $mysql = NULL;


    function db_setup() {
        //Inizia sessione con il db

        $GLOBALS['mysql'] = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DB);
    }

    function db_close() {
        //Chiude sessione con il db

        mysqli_close($GLOBALS['mysql']);
        $GLOBALS['mysql'] = NULL;
    }
    
    function db_do_query($query, ...$params) {
        /*
        Effettua operazione di select:
        sintassi: db_select("select * from..", "<param_type>", <...params>)
        */

        $mysql = $GLOBALS['mysql'];

        $stmt = $mysql->prepare($query);
        call_user_func_array(array($stmt, 'bind_param'), $params);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function db_start_transaction() {
        // Inizia una transazione

        $mysql = $GLOBALS['mysql'];
        $mysql->query("START TRANSACTION");
    }

    function db_end_transaction($state) {
        /*
        Conclude una transazione:
        valori di 'state':
         - "y" -> COMMIT
         - "n" -> ROLLBACK
        */

        $mysql = $GLOBALS['mysql'];

        if($state === 'y') {
            $mysql->query("COMMIT");
        } elseif($state === 'n') {
            $mysql->query("ROLLBACK");
        } else {
            throw new Exception("invalid ending state");
        }
    }
?>
