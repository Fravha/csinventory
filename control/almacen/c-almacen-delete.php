<?php
require_once "../../model/RN_Almacen.php";

$oRN_Almacen = new RN_Almacen();
$oActual = $oRN_Almacen->GetData($_GET["param"]);

if ($oActual != null) {
    $esProtegido = (
        $oActual->tipo_almacen == "VIRTUAL" &&
        in_array($oActual->subtipo_almacen, array("TRANSITO", "PT", "MERMA"))
    );

    if (!$esProtegido) {
        $oRN_Almacen->Delete($_GET["param"]);
    }
}

header("Location: c-almacen-gestion.php");
exit;
?>