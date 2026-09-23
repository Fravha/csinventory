<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;
$authSession = function_exists('auth_user') ? auth_user() : null;

$title = "Lista de Productos e Insumos";

$content = "";
$i = 0;

foreach($listaProductos as $oProducto){
    $i++;
    $hashProducto = $oProducto->hashProducto;
    $nombre = $oProducto->nombre;
    $sku = $oProducto->sku;
    $unidad_nombre = $oProducto->unidad_nombre;
    $tipo = $oProducto->tipo;
    $badgeColor = ($tipo == 'Insumo') ? '#5bc0de' : '#5cb85c';

    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>Item #" . $i . " <span style='float:right; font-size:10px; background:".$badgeColor."; color:white; padding:2px 8px; border-radius:10px;'> " . $tipo . "</span></div>
            <div class='card-subtitle'>" . $nombre . "</div>
            <div class='separator'></div>
            
            <div class='x-2'>
                <div class='card-title'>SKU</div>
                <div class='card-text'>" . $sku . "</div>
            </div>
            <div class='x-2'>
                <div class='card-title'>Medida</div>
                <div class='card-text'>" . $unidad_nombre . "</div>
            </div>
            
            <div class='clear'></div>
            <div class='separator'></div>";

    if (auth_can_action('producto', 'manage', $authSession)) {
        $content .= "
            <div class='card-buttons'>
                <a href='c-producto-edit.php?param=" . $hashProducto . "'>
                    <div class='card-edit'></div>
                </a>
                <a href='c-producto-delete.php?param=" . $hashProducto . "'>
                    <div class='card-delete'></div>
                </a>
            </div>
            <div class='clear'></div>";
    }

    $content .= "
        </div>
    </div>";
}

if ($content == ""){
    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>Vacio</div>
            <div class='card-subtitle'>No existen productos o insumos registrados.</div>
        </div>
    </div>";
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
        <div class='form-subtitle'><?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step'></div></div>        
        <a href='../c-panel.php'><div class='btn-back'></div></a>
    </div>
    
    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>
        
        <?php echo $content ?>
        
        <div class='clear'></div>
    </div>

    <?php if (auth_can_action('producto', 'manage', $authSession)): ?>
        <a href='c-producto-new.php'><div class='btn-add'></div></a>
    <?php endif; ?>
</div>

</body>
</html>
