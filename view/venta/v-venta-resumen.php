<?php

/**
 * @author		Miguel Angel Macias Burgos
 * @company 	WBT
 * @copyright 	2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;

$title = "Detalle de la Venta";

$content = "";
$i = 0;
$importeTotal = 0;

foreach($listaDetalle as $item){
    $i++;
    $hashProducto = $item['hashProducto'];
    $cantidad = $item['cantidad'];

    $oProducto = $oRN_Producto->GetData($hashProducto);
    $nomProducto = $oProducto->nombre;
    $precio = $oProducto->precio;
    $sutotal = $cantidad * $precio;

    $oferta = $oProducto->oferta;
    $info = $cantidad . " x " . $precio. " Bs";

    if ($oferta == "Si"){
        // Buscar su oferta
        $oOferta = $oRN_Oferta->GetDataByIdProducto($oProducto->idProducto);
        $porcentajeDescuento = $oOferta->porcentajeDescuento * 1;

        $descuento = $precio * ($porcentajeDescuento / 100);
        $precioFinal = $precio - $descuento;

        $sutotal = $cantidad * $precioFinal;
        $info = $cantidad . " x " . $precioFinal. " Bs";
    }

    $importeTotal += $sutotal;

    $content .= "
    <div class='x-1'>
        <div class='card' style='height:auto;'>
            <div class='x-2' style='width:50px;'>
                <img src='../../view/img/producto/". $oProducto->imagen ."' style='width:40px'>
            </div>
            <div class='x-2' style='width:calc(100% - 50px);'>
                <div class='card-title'>" . $nomProducto . "</div>
                <div class='card-subtitle'>" . $info . "
                    <span>" . $sutotal . " Bs</span>
                </div>
            </div>
        </div>
    </div>";
}

if ($content == ""){
    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>Ups!</div>
            <div class='card-subtitle'>No ha adicionado ningún producto al carrito</div>
        </div>
    </div>";
}

$cboCliente = CreateCbo($listaCliente, "cliente", "idCliente", "nombre");

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
        <a href='c-venta-new.php?continue'><div class='btn-back'></div></a>
    </div>
    <!-- Body -->
    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>
        <form action='c-venta-save.php' method='post'>
            <div class='x-1'>
                <?php echo $cboCliente ?>
                <div class='label'>Cliente</div>
            </div>
            <div class='x-1'>
                <input type='text' name='obs' placeholder="Observación">
                <div class='label'>Observación</div>
            </div>
            <div class='clear'></div>
            <?php echo $content ?>

            <div class='x-1'>
                <div class='card'>
                    <div class='card-title'>Importe Total</div>
                    <div class='card-subtitle'><?php echo $importeTotal ?> Bs</div>
                </div>
            </div>

            <div class='x-2'>
                <a href='c-venta-new.php?continue'><input type='button' value='Continuar Comprando'></a>
            </div>
            <div class='x-2'>
                <input type='submit' value='Finalizar Compra'>
            </div>
        </form>
        <div class='clear'></div>
    </div>

</div>

</body>
</html>