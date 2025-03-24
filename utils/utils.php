<?php
    function redirect($time, $page_path) {
		/*
		Reindirizzamento su un'altra pagina
		Prametri:
		 - 'time' -> tempo di delay
		 - 'page_path' -> pagina
		*/

		header("Refresh: ".$time."; url=".$page_path);
	}

	function normalize_aula($piano, $aula) {
        /*
        Normalizza la nomenclatura dell'aula
        es: aula 1 di piano 1 => 101

        Parametri:
         - 'piano' -> numero piano
         - 'aula' -> numero aula
        
        Ritorna:
         - formato normalizzato
        */
        $s = $piano;
        if(strlen(strval($aula)) < 2) {
            $s .= '0'.$aula;
        } else {
            $s .= $aula;
        }
        return $s;
    }
?>
