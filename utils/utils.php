<?php
    function redirect($time, $page_path, $msg='Redirecting...') {
		/*
		Reindirizzamento su un'altra pagina
		Prametri:
		 - 'time' -> tempo di delay
		 - 'page_path' -> pagina
		*/

		header("Refresh: ".$time."; url=".$page_path);
        die($msg);
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

    function get_settimana($format, $data) {
        /*
        Ottieni lista delle date della settimana che comprende una determinata data

        Parametri:
        - 'format' -> formato data
        - 'data'
        
        Ritorna:
         - lista di date
        */

        $timestamp = strtotime($data);
        $giornoSettimana = date('N', $timestamp); // 1 (Lunedì) - 7 (Domenica)
        $inizioSettimana = strtotime('-' . ($giornoSettimana - 1) . ' days', $timestamp);
        $settimana = [];
        for ($i = 0; $i < 7; $i++) {
            $settimana[] = date($format, strtotime('+' . $i . ' days', $inizioSettimana));
        }
        return $settimana;
    }

    function format_date($data, $format='d/m/Y') {
        /*
        Riformatta la data in un formato a scelta

        Parametri:
         - 'data'
         - 'format' -> formato data
        
        Ritorna:
         - data riformatta
        */
        $timestamp = strtotime($data);
        return date($format, $timestamp);
    }
?>
