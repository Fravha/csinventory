<?php
require_once "../../model/RN_Almacen.php";
require_once "../../model/data/Almacen.php";

$oRN_Almacen = new RN_Almacen();

$oAlmacen = new Almacen(
    0,
    "",
    $_POST["nombre"],
    $_POST["ubicacion"],
    "REAL",
    "NORMAL",
    (int)$_POST["genera_alerta"],
    1
);

$oRN_Almacen->Save($oAlmacen);

header("Location: c-almacen-gestion.php");
exit;
?>