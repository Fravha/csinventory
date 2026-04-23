<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Producto.php";
require_once "../../model/RN_Almacen.php";
require_once "../../model/RN_Inventario.php";
require_once "../../model/RN_Movimiento.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "movimiento", "view");
$oRN_Producto = new RN_Producto();
$oRN_Almacen = new RN_Almacen();
$oRN_Movimientos = new RN_Movimiento();

$listaMovimientos = $oRN_Movimientos->GetList();

/*echo "<pre>";
var_dump($listaMovimientos);
echo "</pre>";
exit;*/

include_once "../../view/inventario/v-movimiento-list.php";

?>
