<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;
$title = "Gestión de Almacenes";

$content = "";
$i = 0;

foreach($listaAlmacenes as $oAlmacen){
    $i++;
    $hashAlmacen = $oAlmacen->hashAlmacen;
    $nombre = $oAlmacen->nombre;
    $ubicacion = $oAlmacen->ubicacion;
    $estado = $oAlmacen->estado;

    // Construcción de la tarjeta de almacé
    // //<div class='card-edit'></div>
    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>Almacén #" . $i . "</div>
            <div class='card-subtitle'>" . $nombre . "</div>
            <div class='separator'></div>
            
            <div class='card-title'>Ubicación</div>
            <div class='card-text'>" . $ubicacion . "</div>
            
            <div class='card-buttons'>
                <a href='c-almacen-edit.php?param=" . $hashAlmacen . "'>
  
                </a>
            </div>
            <div class='clear'></div>
        </div>
    </div>";
}

// Mensaje si no hay datos
if ($content == ""){
    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>¡Ups!</div>
            <div class='card-subtitle'>No hay almacenes registrados actualmente.</div>
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

    <!--<a href='c-almacen-new.php'><div class='btn-add'></div></a>-->
</div>

</body>
</html>