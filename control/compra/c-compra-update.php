<?php

require_once __DIR__ . "/../authz.php";
require_once "../../model/RN_Compra.php";
require_once "../../model/data/Compra.php";
require_once "../../model/data/CompraDetalle.php";
require_once "../config-app.php";

auth_require_action("compra", "manage", "../c-panel.php");

if (!$_POST) {
    header("Location: c-compra-list.php");
    exit();
}

try {
    $hashCompra = isset($_POST["hashCompra"]) ? trim($_POST["hashCompra"]) : "";
    $proveedor_nombre = isset($_POST["proveedor_nombre"]) ? trim($_POST["proveedor_nombre"]) : "";
    $idAlmacen = isset($_POST["idAlmacen"]) ? (int)$_POST["idAlmacen"] : 0;
    $fecha_compra_raw = isset($_POST["fecha_compra"]) ? trim($_POST["fecha_compra"]) : "";
    $observacion = isset($_POST["observacion"]) ? trim($_POST["observacion"]) : "";
    $estado = isset($_POST["estado"]) ? trim($_POST["estado"]) : "CONFIRMADA";

    $idProducto = isset($_POST["idProducto"]) ? $_POST["idProducto"] : array();
    $tipoCompra = isset($_POST["tipoCompra"]) ? $_POST["tipoCompra"] : array();
    $idUnidadMedida = isset($_POST["idUnidadMedida"]) ? $_POST["idUnidadMedida"] : array();
    $idPresentacion = isset($_POST["idPresentacion"]) ? $_POST["idPresentacion"] : array();
    $cantidad = isset($_POST["cantidad"]) ? $_POST["cantidad"] : array();
    $precio = isset($_POST["precio"]) ? $_POST["precio"] : array();
    $total_linea = isset($_POST["total_linea"]) ? $_POST["total_linea"] : array();

    if ($hashCompra === "") {
        throw new Exception("Compra no valida.");
    }

    $fecha_compra = ($fecha_compra_raw != "")
        ? date("Y-m-d H:i:s", strtotime($fecha_compra_raw))
        : date("Y-m-d H:i:s");

    if ($proveedor_nombre == "") {
        throw new Exception("Debes ingresar el nombre del proveedor.");
    }

    if ($idAlmacen <= 0) {
        throw new Exception("Debes seleccionar un almacen.");
    }

    $listaDetalle = array();
    $subtotalCompra = 0;

    for ($i = 0; $i < count($idProducto); $i++) {
        $idProd = isset($idProducto[$i]) ? (int)$idProducto[$i] : 0;
        $tipo = isset($tipoCompra[$i]) ? strtoupper(trim($tipoCompra[$i])) : "UNIDAD";
        $idUnidad = isset($idUnidadMedida[$i]) ? (int)$idUnidadMedida[$i] : 0;
        $idPres = isset($idPresentacion[$i]) ? (int)$idPresentacion[$i] : 0;
        $cant = isset($cantidad[$i]) ? (float)$cantidad[$i] : 0;
        $prec = isset($precio[$i]) ? (float)$precio[$i] : 0;
        $totalLinea = isset($total_linea[$i]) ? (float)$total_linea[$i] : 0;

        if ($idProd <= 0 && $idUnidad <= 0 && $idPres <= 0 && $cant <= 0 && $prec <= 0 && $totalLinea <= 0) {
            continue;
        }

        if ($idProd <= 0) {
            throw new Exception("Existe una fila con datos pero sin producto seleccionado.");
        }

        if ($tipo === "PRESENTACION") {
            if ($idPres <= 0) {
                throw new Exception("Debes seleccionar una presentacion en todas las filas compradas como presentacion.");
            }
            $idUnidad = 0;
        } elseif ($idUnidad <= 0) {
            throw new Exception("Debes seleccionar una unidad de medida en todas las filas con producto.");
        } else {
            $tipo = "UNIDAD";
            $idPres = 0;
        }

        if ($cant <= 0) {
            throw new Exception("La cantidad debe ser mayor a 0 en todas las filas con producto.");
        }

        if ($prec > 0) {
            $subtotalDetalle = $cant * $prec;
        } elseif ($totalLinea > 0) {
            $subtotalDetalle = $totalLinea;
            $prec = $subtotalDetalle / $cant;
        } else {
            throw new Exception("Debes ingresar precio unitario o total de linea en todas las filas con producto.");
        }

        $subtotalDetalle = round($subtotalDetalle, 2);
        $prec = round($prec, 2);
        $subtotalCompra += $subtotalDetalle;

        $listaDetalle[] = new CompraDetalle(
            0,
            "",
            0,
            $idProd,
            "",
            $cant,
            0,
            $prec,
            0,
            $subtotalDetalle,
            null,
            null,
            $idUnidad,
            $idPres > 0 ? $idPres : null,
            $tipo
        );
    }

    if (count($listaDetalle) == 0) {
        throw new Exception("Debes agregar al menos un producto valido.");
    }

    $subtotalCompra = round($subtotalCompra, 2);

    $oCompra = new Compra(
        0,
        $hashCompra,
        "",
        $proveedor_nombre,
        $idAlmacen,
        $fecha_compra,
        $observacion,
        $estado,
        $subtotalCompra,
        $subtotalCompra,
        null
    );

    $oRN_Compra = new RN_Compra();
    $res = $oRN_Compra->Update($oCompra, $listaDetalle);

    if ($res) {
        header("Location: c-compra-list.php?msg=updated");
        exit();
    }

    header("Location: c-compra-edit.php?param=" . urlencode($hashCompra) . "&error=" . urlencode("No se pudo actualizar la compra."));
    exit();
} catch (Exception $ex) {
    $hashCompra = isset($hashCompra) ? $hashCompra : "";
    header("Location: c-compra-edit.php?param=" . urlencode($hashCompra) . "&error=" . urlencode($ex->getMessage()));
    exit();
}
?>
