<?php

require_once "../model/RN_Producto.php";

$oRN_Producto = new RN_Producto();

$oProducto = new Producto(0, "", "Anillo 4", "anillo-4.png", 2710, 'No', 3, "Activo");

$res = $oRN_Producto->Save($oProducto);
if ($res){
    echo "Producto guardado correctamente.";
}

?>