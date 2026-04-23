<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Receta.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "receta", "view");
$oRN_Receta = new RN_Receta();

$todasLasRecetas = $oRN_Receta->GetList();
$listaRecetas = array();

foreach ($todasLasRecetas as $r) {
    $listaRecetas[] = $r;
}

$title = "Lista de Recetas de Produccion";
include_once "../../view/receta/v-receta-list.php";
?>
