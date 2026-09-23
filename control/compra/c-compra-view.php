<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Compra.php";
require_once "../../model/RN_Producto.php";
require_once "../../model/RN_Almacen.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "compra", "view");

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

$productos = $oRN_Producto->GetList();
$almacenes = $oRN_Almacen->GetList();

$mapProductos = array();
foreach ($productos as $prod) {
    $mapProductos[(int)$prod->idProducto] = $prod;
}

$mapAlmacenes = array();
foreach ($almacenes as $alm) {
    $mapAlmacenes[(int)$alm->idAlmacen] = $alm;
}

include_once "../../view/compra/v-compra-view.php";

?>
