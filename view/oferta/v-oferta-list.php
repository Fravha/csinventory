<?php

/**
 * @author		Miguel Angel Macias Burgos
 * @company 	WBT
 * @copyright 	2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;

$title = "Ofertas";

$content = "";
$i = 0;
foreach($listaOferta as $oOferta){
    $i++;
    $fechaInicial = $oOferta->fechaInicial;
    $fechaFinal = $oOferta->fechaFinal;
    $porcentajeDescuento = $oOferta->porcentajeDescuento;
    $idProducto = $oOferta->idProducto;
    $estado = $oOferta->estado;

    $idProducto = $oOferta->idProducto;
    $hashProducto = sha1($idProducto);
    $oProducto = $oRN_Producto->GetData($hashProducto);
    $nomProducto = $oProducto->nombre;

    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>Oferta #" . $i . "</div>
            <div class='card-subtitle'>" . $fechaInicial . " - " . $fechaFinal . "
                <span>" . $porcentajeDescuento . "%</span><br>
                
            </div>
            <div class='separator'></div>
            <div class='card-title'>Producto</div>
            <div class='card-text'>".$nomProducto."</div>
            <div class='card-buttons'>
                <a href='c-oferta-edit.php?param=" . $oOferta->hashOferta . "'><div class='card-edit'></div></a>
                <a href='c-oferta-delete.php?param=" . $oOferta->hashOferta . "'><div class='card-delete'></div></a>
            </div>
            <div class='clear'></div>
        </div>
    </div>";
}

if ($content == ""){
    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>Ups!</div>
            <div class='card-subtitle'>No hay ofertas registradas</div>
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
        
    <!-- CSS -->
    <link rel='stylesheet' href='../../view/css/main.css' />
</head>

<body>

<!-- Main Page -->
<div class='ctn-form'>
    <!-- Header -->
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'>Bienvenido <?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step'></div></div>        
        <a href='../c-panel.php'><div class='btn-back'></div></a>
    </div>
    <!-- Body -->
    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>
        <?php echo $content ?>
        <div class='clear'></div>
    </div>

    <a href='c-oferta-new.php'><div class='btn-add'></div></a>
</div>

</body>
</html>