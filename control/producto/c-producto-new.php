<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Producto.php";
require_once "../../model/data/Producto.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "producto", "manage");
$oRN_Producto = new RN_Producto();

if ($_POST) {
    $oProducto = new Producto(
        0,
        'temp',
        $_POST['nombre'],
        $_POST['sku'],
        $_POST['unidad_medida'],
        $_POST['tipo'],
        1,
        null
    );

    $res = $oRN_Producto->Save($oProducto);

    if ($res) {
        header("Location: c-producto-list.php?msg=ok");
    } else {
        $error = "No se pudo guardar el producto.";
    }
}

$title = "Registrar Nuevo Item";
include_once "../../view/producto/v-producto-new.php";
?>
