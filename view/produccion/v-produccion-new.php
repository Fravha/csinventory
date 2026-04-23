<?php
/**
 * @author      Francisco Bailaba
 * @company     Creative Spot / Bylaba Projects
 * @version     1.1 - Estética y Almacén Fijo
 */
$nomUsuario = $oUsuario->nombre;
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv='content-type' content='text/html' charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    <title><?php echo $appTitle; ?></title>
    <link rel='stylesheet' href='../../view/css/main.css' />
    <style>
        /* Estilos adicionales para la tabla de explosión */
        .table-explosion { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }
        .table-explosion th { background: #f4f4f4; text-align: left; padding: 10px; border-bottom: 2px solid #ddd; }
        .table-explosion td { padding: 10px; border-bottom: 1px solid #eee; }
        .resaltado-azul { color: #0275d8; font-weight: bold; font-size: 18px; }
        .insumo-critico { background-color: #fdf2f2; color: #d9534f; font-weight: bold; }
    </style>
</head>

<body>
<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'>Sesión: <?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step' style='width:60%'></div></div>        
        <a href='c-produccion-list.php'><div class='btn-back'></div></a>
    </div>

    <div class='form-content'>
        <div class='title'>Nueva Orden de Producción</div>
        
        <div class='x-2'>
            <div class='data-label'>Almacén de Origen (Insumos)</div>
            <select id="selAlmacen" class='input-form' onchange="actualizar()">
                <?php foreach($listaAlmacenes as $alm): ?>
                    <option value="<?php echo $alm->idAlmacen; ?>" <?php echo ($idAlmacenSel == $alm->idAlmacen) ? 'selected' : ''; ?>>
                        <?php echo $alm->nombre; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class='x-2'>
            <div class='data-label'>Producto a fabricar (Receta)</div>
            <select id="selReceta" class='input-form' onchange="actualizar()">
                <option value="">-- Seleccione una receta --</option>
                <?php foreach($listaRecetas as $rec): ?>
                    <option value="<?php echo $rec->idReceta; ?>" <?php echo ($idRecetaSel == $rec->idReceta) ? 'selected' : ''; ?>>
                        <?php echo $rec->nombre_servicio; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class='clear'></div>
        <div class='separator'></div>

        <?php if ($viabilidad): ?>
            <div class='x-1'>
                <div style="background: #e9f7fe; padding: 15px; border-radius: 5px; border-left: 5px solid #0275d8;">
                    <span style="color: #555;">Capacidad de Producción:</span><br>
                    <span class="resaltado-azul"><?php echo $viabilidad['maxima']; ?> Unidades</span>
                    <span style="float: right; color: #777;">Costo U: <?php echo number_format($viabilidad['costo_u'], 2); ?> Bs.</span>
                </div>

                <table class="table-explosion">
                    <thead>
                        <tr>
                            <th>Insumo Requerido</th>
                            <th>Cant. c/u</th>
                            <th>Stock Act.</th>
                            <th>Alcance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($viabilidad['insumos'] as $ins): 
                            $alcance = ($ins['necesario'] > 0) ? floor($ins['stock'] / $ins['necesario']) : 0;
                            $esCritico = ($alcance == $viabilidad['maxima'] && $viabilidad['maxima'] < 5);
                        ?>
                            <tr class="<?php echo $esCritico ? 'insumo-critico' : ''; ?>">
                                <td><?php echo $ins['nombre']; ?></td>
                                <td><?php echo $ins['necesario']; ?></td>
                                <td><?php echo $ins['stock']; ?></td>
                                <td><?php echo $alcance; ?> pzas</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class='separator'></div>

            <?php if ($viabilidad['maxima'] > 0): ?>
                <form action="c-produccion-save.php" method="POST">
                    <input type="hidden" name="idReceta" value="<?php echo $idRecetaSel; ?>">
                    <input type="hidden" name="idAlmacenInsumos" value="<?php echo $idAlmacenSel; ?>">
                    
                    <input type="hidden" name="idAlmacenDestino" value="4">

                    <div class='x-2'>
                        <div class='data-label'>Cantidad a producir</div>
                        <input type="number" name="cantidad" value="1" min="1" max="<?php echo $viabilidad['maxima']; ?>" class='input-form' required>
                    </div>

                    <div class='x-2'>
                        <div class='data-label'>Almacén de Destino</div>
                        <input type="text" value="PRODUCTO TERMINADO (FIJO)" class='input-form' disabled style="background:#f9f9f9;">
                    </div>

                    <div class='x-1' style="margin-top:20px;">
                        <input type='submit' value='REGISTRAR PRODUCCIÓN' class='btn-action' style='background:#5cb85c; color:white; width:100%; border:none; cursor:pointer; height: 45px; font-weight: bold;' />
                    </div>
                </form>
            <?php else: ?>
                <div class='x-1' style="text-align:center; padding: 20px; color: #d9534f; font-weight: bold;">
                    ⚠️ No hay stock suficiente para iniciar la producción.
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class='x-1' style="text-align:center; padding: 40px; color: #999;">
                Seleccione un almacén y una receta para simular la producción.
            </div>
        <?php endif; ?>

        <div class='clear'></div>
    </div>
</div>

<script>
function actualizar() {
    const rec = document.getElementById('selReceta').value;
    const alm = document.getElementById('selAlmacen').value;
    window.location.href = `?idReceta=${rec}&idAlmacen=${alm}`;
}
</script>
</body>
</html>