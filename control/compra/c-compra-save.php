<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

require_once __DIR__ . "/../authz.php";
require_once "../../model/RN_Compra.php";
require_once "../../model/data/Compra.php";
require_once "../config-app.php";

auth_require_action("compra", "manage", "../c-panel.php");

if ($_POST) {
    $idProducto = $_POST['idProducto'];
    $idAlmacen = $_POST['idAlmacen'];
    $lote = $_POST['lote'];
    $cantidad = $_POST['cantidad'];
    $precioUnitario = $_POST['precioUnitario'];

    $oRN_Compra = new RN_Compra();

    $oCompra = new Compra(
        0,
        'temp',
        $idProducto,
        $idAlmacen,
        $lote,
        $cantidad,
        $precioUnitario,
        date("Y-m-d H:i:s"),
        null
    );

    $res = $oRN_Compra->Save($oCompra);

    if ($res) {
        header("Location: ../movimiento/c-movimiento-list.php?msg=ok");
    } else {
        header("Location: c-compra-new-step2.php?param=" . sha1($idProducto) . "&error=1");
    }
} else {
    header("Location: c-compra-new.php");
}
?>
