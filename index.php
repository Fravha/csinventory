<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */
 
require_once __DIR__ . "/control/auth-session.php";

auth_start_session();

if (!auth_is_logged_in()) {
    $nameFile = "login.php";
}else{
    $nameFile = "panel.php";
}

header("location: control/c-".$nameFile);

?>
