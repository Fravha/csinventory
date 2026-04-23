<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */
 
require_once __DIR__ . "/authz.php";
require_once "../model/RN_Usuario.php";
require_once "config-app.php";

$authUser = auth_require_action("dashboard", "view", "../index.php");
$oRN_Usuario = new RN_Usuario();
$oUsuario = $oRN_Usuario->GetData($authUser["user_hash"]);

if ($oUsuario) {
    include_once "../view/v-panel.php";
    exit;
}

auth_logout_user();
header("Location: ../index.php");
exit;

?>
