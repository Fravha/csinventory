<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_ProductoPresentacion.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "producto", "manage");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: c-ppresentacion-list.php");
    exit;
}

$idProducto = isset($_POST["idProducto"]) ? intval($_POST["idProducto"]) : 0;
$nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : "";
$cantidadBase = isset($_POST["cantidad_base"]) ? floatval($_POST["cantidad_base"]) : 0;
$idUnidadBase = isset($_POST["idUnidadBase"]) ? intval($_POST["idUnidadBase"]) : 0;

if ($idProducto <= 0 || $nombre === "" || $cantidadBase <= 0 || $idUnidadBase <= 0) {
    header("Location: c-ppresentacion-list.php?error=datos_invalidos");
    exit;
}

try {
    $oRN_ProductoPresentacion = new RN_ProductoPresentacion();
    $oRN_ProductoPresentacion->Save(
        $idProducto,
        $nombre,
        $cantidadBase,
        $idUnidadBase
    );

    header("Location: c-ppresentacion-list.php?success=presentacion_creada");
    exit;
} catch (Throwable $e) {
    header("Location: c-ppresentacion-list.php?error=" . urlencode($e->getMessage()));
    exit;
}

?>
