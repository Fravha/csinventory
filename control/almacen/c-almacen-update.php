<?php
require_once "../../model/RN_Almacen.php";
require_once "../../model/data/Almacen.php";

$oRN_Almacen = new RN_Almacen();

$oActual = $oRN_Almacen->GetData($_POST["hashAlmacen"]);

if ($oActual != null) {
    $esProtegido = (
        $oActual->tipo_almacen == "VIRTUAL" &&
        in_array($oActual->subtipo_almacen, array("TRANSITO", "PT", "MERMA"))
    );

    if (!$esProtegido) {
        $oAlmacen = new Almacen(
            $oActual->idAlmacen,
            $oActual->hashAlmacen,
            $_POST["nombre"],
            $_POST["ubicacion"],
            $oActual->tipo_almacen,
            $oActual->subtipo_almacen,
            (int)$_POST["genera_alerta"],
            $oActual->estado
        );

        $oRN_Almacen->Update($oAlmacen);
    }
}

header("Location: c-almacen-gestion.php");
exit;
?>