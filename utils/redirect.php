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
?>
