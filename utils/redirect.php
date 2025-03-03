<?php
    function redirect($time, $page_path) {
		header("Refresh: ".$time."; url=".$page_path); // redirect
	}
?>
