<?php
require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Almacen.php";
require_once "../../model/data/Almacen.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "almacen", "view");
$oRN_Almacen = new RN_Almacen();

$listaAlmacenes = $oRN_Almacen->GetList();
$modo = "new";
$oAlmacenActual = null;

if (isset($_GET["mode"]) && $_GET["mode"] == "edit" && isset($_GET["param"])) {
    $modo = "edit";
    $oAlmacenActual = $oRN_Almacen->GetData($_GET["param"]);

    if ($oAlmacenActual != null) {
        $esProtegido = (
            $oAlmacenActual->tipo_almacen == "VIRTUAL" &&
            in_array($oAlmacenActual->subtipo_almacen, array("TRANSITO", "PT", "MERMA"))
        );

        if ($esProtegido) {
            $modo = "new";
            $oAlmacenActual = null;
        }
    }
}

require_once "../../view/almacen/v-almacen-gestion.php";
?>