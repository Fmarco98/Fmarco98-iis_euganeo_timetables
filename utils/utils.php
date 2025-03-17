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
        $s = $piano;
        if(strlen(strval($aula)) < 2) {
            $s .= '0'.$aula;
        } else {
            $s .= $aula;
        }
        return $s;
    }
?>
