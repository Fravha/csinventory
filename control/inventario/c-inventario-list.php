<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Producto.php";
require_once "../../model/RN_Almacen.php";
require_once "../../model/RN_Inventario.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "inventario", "view");
$oRN_Producto = new RN_Producto();
$oRN_Almacen = new RN_Almacen();
$oRN_Inventario = new RN_Inventario();

$listaAlmacenes = $oRN_Almacen->GetList();
$listaInventario = $oRN_Inventario->GetList();

/*echo "<pre>";
var_dump($listaAlmacenes);
echo "</pre>";
exit;
*/

$title = "Stock de Inventario";
include_once "../../view/inventario/v-inventario-list.php";

?>
