<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Producto.php";
require_once "../../model/RN_Almacen.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "almacen", "view");
$oRN_Producto = new RN_Producto();
$oRN_Almacen = new RN_Almacen();

$listaAlmacenes = $oRN_Almacen->GetList();

include_once "../../view/almacen/v-almacen-list.php";

?>
