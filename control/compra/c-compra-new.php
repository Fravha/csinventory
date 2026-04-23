<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Producto.php";
require_once "../../model/RN_Almacen.php";
require_once "../../model/RN_Inventario.php";
require_once "../../model/RN_Compra.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "compra", "manage");
$oRN_Producto = new RN_Producto();
$oRN_Almacen = new RN_Almacen();
$oRN_Movimientos = new RN_Inventario();
$oRN_Compra = new RN_Compra();

$listaInsumos = $oRN_Producto->GetListInsumo();

include_once "../../view/compra/v-compra-new.php";

?>
