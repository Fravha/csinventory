<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Receta.php";
require_once "../../model/RN_Produccion.php";
require_once "../../model/RN_Almacen.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "produccion", "manage");
$oRN_Receta = new RN_Receta();
$oRN_Produccion = new RN_Produccion();
$oRN_Almacen = new RN_Almacen();

$idRecetaSel = $_GET['idReceta'] ?? null;
$idAlmacenSel = $_GET['idAlmacen'] ?? 1;

$viabilidad = null;
if ($idRecetaSel && $idAlmacenSel) {
    $viabilidad = $oRN_Produccion->ConsultarViabilidad($idRecetaSel, $idAlmacenSel);
}

$listaAlmacenes = $oRN_Almacen->GetList();
$listaRecetas = $oRN_Receta->GetList();

include_once "../../view/produccion/v-produccion-new.php";

?>
