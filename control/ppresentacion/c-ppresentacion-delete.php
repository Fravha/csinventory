<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_ProductoPresentacion.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "producto", "manage");

$idPresentacion = isset($_GET["idPresentacion"]) ? intval($_GET["idPresentacion"]) : 0;

if ($idPresentacion <= 0) {
    header("Location: c-ppresentacion-list.php?error=id_invalido");
    exit;
}

$oRN_ProductoPresentacion = new RN_ProductoPresentacion();
$oRN_ProductoPresentacion->SoftDelete($idPresentacion);

header("Location: c-ppresentacion-list.php?success=presentacion_eliminada");
exit;

?>
