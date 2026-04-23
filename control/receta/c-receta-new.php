<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Producto.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "receta", "manage");
$oRN_Producto = new RN_Producto();

$todosLosProductos = $oRN_Producto->GetList();
$listaProductosFinales = array();
$listaInsumos = array();

foreach ($todosLosProductos as $p) {
    if ($p->tipo == 'Producto Final') {
        $listaProductosFinales[] = $p;
    } else {
        $listaInsumos[] = $p;
    }
}

$title = "Nueva Receta de Produccion";
include_once "../../view/receta/v-receta-new.php";
?>
