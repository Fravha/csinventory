<?php

/**
 * @author		Miguel Angel Macias Burgos
 * @company 	WBT
 * @copyright 	2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;

$title = "Nueva Venta";

$content = "";
foreach($listaProducto as $oProducto){
    $idProducto = $oProducto->idProducto;
    $hashProducto = $oProducto->hashProducto;
    $nomProducto = $oProducto->nombre;
    $imagen = $oProducto->imagen;
    $precio = $oProducto->precio;
    $oferta = $oProducto->oferta;

    $tagOferta = "";
    if ($oferta == "Si"){
        // Buscar su oferta
        $oOferta = $oRN_Oferta->GetDataByIdProducto($idProducto);
        $porcentajeDescuento = $oOferta->porcentajeDescuento * 1;

        $descuento = $precio * ($porcentajeDescuento / 100);
        $precioFinal = $precio - $descuento;

        $tagOferta = "<span>Descuento " . $porcentajeDescuento . "%</span> <span>Precio Final: ". $precioFinal ." Bs</span>";
    }

    $content .= "<div class='x-2'>
        <a href='c-venta-new-2.php?param=" . $hashProducto . "'>
        <div class='card' style='height:auto;'>
            <div class='card-title'>" . $nomProducto . "</div>
            <div class='card-subtitle'>" . $precio . " Bs 
                 ". $tagOferta . "
            </div>
            <div class='separator'></div>
            <img src='../../view/img/producto/" . $imagen . "' alt='" . $nomProducto . "' style='width:120px' />            
        </div>
        </a>
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
        <a href='c-venta-list.php'><div class='btn-back'></div></a>
    </div>
    <!-- Body -->
    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>
        
        <?php echo $content ?>

        <div class='clear'></div>
    </div>

</div>

</body>
</html>