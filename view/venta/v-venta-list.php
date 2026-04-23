<?php

/**
 * @author		Miguel Angel Macias Burgos
 * @company 	WBT
 * @copyright 	2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;

$title = "Ventas";

$content = "";
$i = 0;
foreach($listaVenta as $oVenta){
    $i++;
    $idVenta = $oVenta->idVenta;
    $fechaVenta = $oVenta->fechaVenta;
    $horaVenta = $oVenta->horaVenta;
    $observacion = $oVenta->observacion;
    $total = $oVenta->total;
    $descuento = $oVenta->descuento;
    $totalFinal = $total - $descuento;

    $idCliente = $oVenta->idCliente;
    $hashCliente = sha1($idCliente);
    $oCliente = $oRN_Cliente->GetData($hashCliente);
    $nomCliente = $oCliente->nombre;

    $listaVentaItem = $oRN_VentaItem->GetListByIdVenta($idVenta);
    $content2 = "";
    foreach($listaVentaItem as $oVentaItem){
        $idProducto = $oVentaItem->idProducto;
        $hashProducto = sha1($idProducto);
        $oProducto = $oRN_Producto->GetData($hashProducto);
        $nomProducto = $oProducto->nombre;
        $precioVenta = $oVentaItem->precioVenta;
        $cantidad = $oVentaItem->cantidad;

        $info = $cantidad . " x " . $precioVenta. " Bs";
        $sutotal = $cantidad * $precioVenta;

        $content2 .= "
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

    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>Venta #" . $i . "</div>
            <div class='card-subtitle'>" . $fechaVenta . ", " . $horaVenta . "
                <span>" . $totalFinal . " Bs</span><br>
                
            </div>
            <div class='separator'></div>
            <div class='card-title'>Cliente</div>
            <div class='card-text'>".$nomCliente."</div>
            <div class='separator'></div>
            ". $content2 ."
            <div class='card-buttons'>
                <a href='c-venta-delete.php?param=" . $oVenta->hashVenta . "'><div class='card-delete'></div></a>
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
            <div class='card-subtitle'>No hay ventas registradas</div>
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

    <a href='c-venta-new.php'><div class='btn-add'></div></a>
</div>

</body>
</html>