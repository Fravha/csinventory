<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Produccion.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "produccion", "view");
$oRN_Produccion = new RN_Produccion();

$listaProducciones = $oRN_Produccion->GetList();

$title = "Historial de Produccion";
include_once "../../view/produccion/v-produccion-list.php";
?>
