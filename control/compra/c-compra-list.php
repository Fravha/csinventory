<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Compra.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "compra", "view");
$oRN_Compra = new RN_Compra();

$listaCompras = $oRN_Compra->GetList();

include_once "../../view/compra/v-compra-list.php";

?>
