<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;
$title = "Nueva Compra: Seleccionar Item";

$content = "";
foreach($listaInsumos as $oProducto){
    $hashProducto = $oProducto->hashProducto;
    $nomProducto = $oProducto->nombre;
    $sku = $oProducto->sku;
    $tipo = $oProducto->tipo; // Insumo o Producto Final
    $uMedida = $oProducto->unidad_medida;

    $badgeColor = ($tipo == 'Insumo') ? '#5bc0de' : '#5cb85c';

    // El enlace ahora lleva al paso 2: completar datos de la compra (cantidad, precio, lote)
    $content .= "
    <div class='x-2'>
        <a href='c-compra-new-step2.php?param=" . $hashProducto . "' style='text-decoration:none;'>
            <div class='card' style='height:160px; position:relative;'>
                <span class='badge' style='background:".$badgeColor."; position:absolute; top:10px; right:10px; font-size:9px;'>".$tipo."</span>
                
                <div class='card-title' style='margin-top:15px; font-size:13px;'>" . $nomProducto . "</div>
                <div class='card-subtitle'>" . $sku . "</div>
                
                <div class='separator'></div>
                
                <div class='data-label'>Unidad</div>
                <div class='data-value' style='font-size:11px;'>" . $uMedida . "</div>
                
                <div style='margin-top:10px; color:#68dccf; font-weight:bold; font-size:10px;'>SELECCIONAR ></div>
            </div>
        </a>
    </div>";
}

if ($content == ""){
    $content = "<div class='x-1'><div class='card'><div class='card-text'>No hay productos registrados para comprar.</div></div></div>";
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv='content-type' content='text/html' charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    <title><?php echo $appTitle; ?></title>
    <link rel='stylesheet' href='../../view/css/main.css' />
</head>

<body>

<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'>Operador: <?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step' style='width:33%;'></div></div>        
        <a href='c-compra-list.php'><div class='btn-back'></div></a>
    </div>

    <div class='form-content card-grid'>
        <div class='title'><?php echo $title ?></div>
        <div class='card-text' style='margin-bottom:15px;'>Haz clic en el insumo que acabas de adquirir:</div>
        
        <?php echo $content ?>

        <div class='clear'></div>
    </div>
</div>

</body>
</html>