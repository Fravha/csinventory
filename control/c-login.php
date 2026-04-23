<?php
/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

require_once "config-app.php";
require_once __DIR__ . "/auth-session.php";

auth_start_session();

if (auth_is_logged_in()) {
    header("Location: c-panel.php");
    exit;
}

include_once "../view/v-login.php";

?>
