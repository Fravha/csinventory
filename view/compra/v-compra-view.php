<?php

$nomUsuario = $oUsuario->nombre;
$title = "Detalle de Compra";
$nombreAlmacen = isset($mapAlmacenes[(int)$oCompra->idAlmacen]) ? $mapAlmacenes[(int)$oCompra->idAlmacen]->nombre : ("Almacen #" . $oCompra->idAlmacen);

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv='content-type' content='text/html' charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    <title><?php echo $appTitle; ?></title>
    <link rel='stylesheet' href='../../view/css/main.css' />
    <style>
        .detail-box { background:#fafafa; border:1px solid #eee; border-radius:8px; padding:12px; margin-bottom:15px; }
        .detail-line { font-size:12px; margin-bottom:6px; }
        .table-detail { width:100%; border-collapse: collapse; font-size:12px; }
        .table-detail th, .table-detail td { padding:8px; border-bottom:1px solid #eee; text-align:left; }
    </style>
</head>
<body>
<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'><?php echo htmlspecialchars($nomUsuario); ?></div>
        <div class='bar'><div class='step' style='width:100%;'></div></div>
        <a href='c-compra-list.php'><div class='btn-back'></div></a>
    </div>
    <div class='form-content'>
        <div class='title'><?php echo $title; ?></div>

        <div class='detail-box'>
            <div class='detail-line'><strong>Numero:</strong> <?php echo htmlspecialchars($oCompra->numero_compra); ?></div>
            <div class='detail-line'><strong>Proveedor:</strong> <?php echo htmlspecialchars($oCompra->proveedor_nombre); ?></div>
            <div class='detail-line'><strong>Almacen:</strong> <?php echo htmlspecialchars($nombreAlmacen); ?></div>
            <div class='detail-line'><strong>Fecha:</strong> <?php echo htmlspecialchars($oCompra->fecha_compra); ?></div>
            <div class='detail-line'><strong>Estado:</strong> <?php echo htmlspecialchars($oCompra->estado); ?></div>
            <div class='detail-line'><strong>Observacion:</strong> <?php echo htmlspecialchars($oCompra->observacion); ?></div>
            <div class='detail-line'><strong>Total:</strong> <?php echo number_format((float)$oCompra->total, 2); ?> Bs</div>
        </div>

        <table class='table-detail'>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Lote</th>
                    <th>Unidad comprada</th>
                    <th>Cantidad comprada</th>
                    <th>Cantidad base</th>
                    <th>P. Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaDetalle as $det): ?>
                    <?php $nomProducto = isset($det->nomProducto) ? $det->nomProducto : ("Producto #" . $det->idProducto); ?>
                    <?php $usaPresentacion = !empty($det->idPresentacion); ?>
                    <?php $unidadComprada = $usaPresentacion ? ($det->nomPresentacion ?? "Presentacion") : (($det->nomUnidadMedida ?? "Unidad") . " (" . ($det->abrevUnidadMedida ?? "-") . ")"); ?>
                    <?php $cantidadComprada = $usaPresentacion ? (number_format((float)$det->cantidad, 2) . " " . ($det->nomPresentacion ?? "presentacion")) : (number_format((float)$det->cantidad, 2) . " " . ($det->abrevUnidadMedida ?? "")); ?>
                    <tr>
                        <td><?php echo htmlspecialchars($nomProducto); ?></td>
                        <td><?php echo htmlspecialchars($det->lote); ?></td>
                        <td><?php echo htmlspecialchars($unidadComprada); ?></td>
                        <td><?php echo htmlspecialchars($cantidadComprada); ?></td>
                        <td><?php echo number_format((float)$det->cantidad_base, 4) . " " . htmlspecialchars($det->abrevUnidadBase ?? ""); ?></td>
                        <td><?php echo number_format((float)$det->precio_unitario_compra, 2); ?> Bs</td>
                        <td><?php echo number_format((float)$det->subtotal, 2); ?> Bs</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
