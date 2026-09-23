<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_ProductoPresentacion.php";
require_once "../../model/RN_UnidadMedida.php";
require_once "../../model/RN_Producto.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "producto", "view");
$oRN_UnidadMedida = new RN_UnidadMedida();
$oRN_ProductoPresentacion = new RN_ProductoPresentacion();
$oRN_Producto = new RN_Producto();

$listaPresentacionProducto = $oRN_ProductoPresentacion->GetList();
$listaUnidadesMedida = $oRN_UnidadMedida->GetList();
$listaProductos = $oRN_Producto->GetListInsumo();

/*
echo "<pre>";
var_dump($listaPresentacionProducto);
echo "</pre>";
exit;
*/

include_once "../../view/ppresentacion/v-ppresentacion-list.php";

?>
