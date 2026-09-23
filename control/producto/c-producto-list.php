<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Producto.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "producto", "view");
$oRN_Producto = new RN_Producto();

$listaProductos = $oRN_Producto->GetList();
/*
echo "<pre>";
var_dump($listaProductos);
echo "</pre>";
exit;
*/

include_once "../../view/producto/v-producto-list.php";

?>
