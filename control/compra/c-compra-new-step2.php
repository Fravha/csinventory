<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Producto.php";
require_once "../../model/RN_Almacen.php";
require_once "../../model/RN_Compra.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "compra", "manage");
$oRN_Producto = new RN_Producto();
$oRN_Almacen = new RN_Almacen();
$oRN_Compra = new RN_Compra();

if (isset($_GET['param'])) {
    $hashProducto = $_GET['param'];
    $oProducto = $oRN_Producto->GetData($hashProducto);

    if (!$oProducto) {
        header("Location: c-compra-new.php");
        exit();
    }
} else {
    header("Location: c-compra-new.php");
    exit();
}

$listaAlmacenes = $oRN_Almacen->GetList();

include_once "../../view/compra/v-compra-new-step2.php";

?>
