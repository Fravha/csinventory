<?php
/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.1
 */

$nomUsuario = $oUsuario->nombre;
$authSession = function_exists('auth_user') ? auth_user() : null;
$title = "Compras Realizadas";

$content = "";
$i = 0;

foreach($listaCompras as $oCompra){
    $i++;

    $hashCompra = $oCompra->hashCompra;
    $lote = $oCompra->lote;
    $cantidad = $oCompra->cantidad;
    $precio = $oCompra->precioUnitario ?? 0;
    $fecha = $oCompra->fechaCompra ?? "Sin fecha";

    $total = $cantidad * $precio;

    $nomProducto = "Producto #" . $oCompra->idProducto;
    $nomAlmacen = "Almacen #" . $oCompra->idAlmacen;

    $content .= "
    <div class='x-1 item-compra'>
        <div class='card'>

            <div class='card-title'>
                <span>Compra #" . $i . "</span>
                <span class='badge bg-entrada'>- Entrada</span>
            </div>

            <div class='card-subtitle'>
                " . $fecha . "
                <span>" . number_format($total,2) . " Bs</span>
            </div>

            <div class='separator'></div>

            <div class='card-title'>Producto</div>
            <div class='card-text'>" . $nomProducto . "</div>

            <div class='x-2'>
                <div class='data-label'>Cantidad</div>
                <div class='data-value' style='color:#5cb85c;'>+" . $cantidad . "</div>
            </div>

            <div class='x-2'>
                <div class='data-label'>Precio Unit.</div>
                <div class='data-value'>" . number_format($precio,2) . " Bs</div>
            </div>

            <div class='x-2'>
                <div class='data-label'>Lote</div>
                <div class='data-value' style='font-size:11px;'>" . $lote . "</div>
            </div>

            <div class='x-2'>
                <div class='data-label'>Almacen</div>
                <div class='data-value'>" . $nomAlmacen . "</div>
            </div>

            <div class='clear'></div>";

    if (auth_can_action('compra', 'manage', $authSession)) {
        $content .= "
            <div class='card-buttons'>
                <a href='c-compra-delete.php?param=" . $hashCompra . "'>
                    <div class='card-edit'></div>
                </a>
            </div>";
    }

    $content .= "
        </div>
    </div>";
}

if ($content == ""){
    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>Ups!</div>
            <div class='card-subtitle'>No hay compras registradas</div>
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
        <div class='form-subtitle'>Operador: <?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step' style='width:100%;'></div></div>        
        <a href='../c-panel.php'><div class='btn-back'></div></a>
    </div>

    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>

        <?php echo $content ?>

        <div class='clear'></div>
    </div>

    <?php if (auth_can_action('compra', 'manage', $authSession)): ?>
        <a href='c-compra-new.php'><div class='btn-add'></div></a>
    <?php endif; ?>
</div>

</body>
</html>
