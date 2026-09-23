<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Compra.php";
require_once "../../model/RN_Producto.php";
require_once "../../model/RN_Almacen.php";
require_once "../../model/RN_UnidadMedida.php";
require_once "../../model/RN_ProductoPresentacion.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "compra", "manage");

$hashCompra = isset($_GET["param"]) ? trim($_GET["param"]) : "";
if ($hashCompra === "") {
    header("Location: c-compra-list.php?error=Compra no encontrada.");
    exit();
}

$oRN_Compra = new RN_Compra();
$oCompra = $oRN_Compra->GetData($hashCompra);

if ($oCompra === null) {
    header("Location: c-compra-list.php?error=Compra no encontrada.");
    exit();
}

$listaDetalle = $oRN_Compra->GetDetalle($oCompra->idCompra);

$oRN_Producto = new RN_Producto();
$oRN_Almacen = new RN_Almacen();
$oRN_UnidadMedida = new RN_UnidadMedida();
$oRN_ProductoPresentacion = new RN_ProductoPresentacion();

$listaInsumos = $oRN_Producto->GetListInsumo();
$listaAlmacenes = $oRN_Almacen->GetList();
$listaUnidades = $oRN_UnidadMedida->GetList();
$listaProductosPresentacion = $oRN_ProductoPresentacion->GetList();

$error = isset($_GET["error"]) ? urldecode($_GET["error"]) : "";

include_once "../../view/compra/v-compra-edit.php";

?>
