<?php

/**
 * @author		Miguel Angel Macias Burgos
 * @company 	WBT
 * @copyright 	2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;

$title = "Modificar Oferta";

$idProducto = $oOferta->idProducto;
$fechaInicial = $oOferta->fechaInicial;
$fechaFinal = $oOferta->fechaFinal;
$porcentajeDescuento = $oOferta->porcentajeDescuento;

$oProducto = $oRN_Producto->GetData(sha1($idProducto));

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
        <a href='c-oferta-list.php'><div class='btn-back'></div></a>
    </div>
    <!-- Body -->
    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>
        
        <form action='c-oferta-update.php' method='POST'>
        <input type='hidden' name='hashOferta' value='<?php echo $hashOferta ?>' />
        <div class='x-1'>
            <div class='card'>
                <div class='card-title'><?php echo $oProducto->nombre; ?></div>
            </div>
        </div>
        <div class='x-2'>
            <input type='date' name='fechaInicial' placeholder='Fecha Inicial' required value='<?php echo $fechaInicial ?>' />
            <div class='label'>Fecha Inicial</div>
        </div>
        <div class='x-2'>
            <input type='date' name='fechaFinal' placeholder='Fecha Final' required value='<?php echo $fechaFinal ?>' />
            <div class='label'>Fecha Final</div>
        </div>
        <div class='x-2'>
            <input type='text' name='porcentaje' placeholder='Porcentaje de Descuento' required value='<?php echo $porcentajeDescuento ?>' />
            <div class='label'>Porcentaje de Descuento</div>
        </div>
        <div class='x-1'>
            <input type='submit' value='Actualizar' />
        </div>
        </form>

        <div class='clear'></div>
    </div>

</div>

</body>
</html>