<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Producto.php";
require_once "../../model/data/Producto.php";
require_once "../../model/RN_UnidadMedida.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "producto", "manage");

$oRN_Producto = new RN_Producto();
$oRN_UnidadMedida = new RN_UnidadMedida();

// Lista de unidades
$listaUnidades = $oRN_UnidadMedida->GetList();

if (empty($listaUnidades)) {
    $errorUnidades = "No existen unidades de medida registradas.";
}

// Procesar formulario
if ($_POST) {
    if (empty($_POST["idUnidadMedida"])) {
        $error = "Debe seleccionar una unidad de medida.";
    } else {
        $oProducto = new Producto(
            0,
            "temp",
            $_POST["nombre"],
            $_POST["sku"],
            (int) $_POST["idUnidadMedida"],
            $_POST["tipo"],
            1,
            null
        );

        $res = $oRN_Producto->Save($oProducto);

        if ($res) {
            header("Location: c-producto-list.php?msg=ok");
            exit;
        } else {
            $error = "No se pudo guardar el producto.";
        }
    }
}

$title = "Registrar Nuevo Item";
include_once "../../view/producto/v-producto-new.php";

?>
