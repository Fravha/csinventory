<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Producto.php";
require_once "../../model/RN_Almacen.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "inventario", "view");
$oRN_Producto = new RN_Producto();
$oRN_Almacen = new RN_Almacen();

include_once "../../view/inventario/v-inventario-panel.php";

?>
