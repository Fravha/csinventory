<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Movimiento.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "movimiento", "view");
$oRN_Movimiento = new RN_Movimiento();

$listaMovimientos = $oRN_Movimiento->GetList();

include_once "../../view/inventario/v-movimiento-list.php";
?>
