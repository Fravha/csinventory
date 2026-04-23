<?php
/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */
$nomUsuario = $oUsuario->nombre;
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title><?php echo $appTitle; ?></title>
    <link rel='stylesheet' href='../../view/css/main.css' />
    <style>
        .badge-max { background: #233251; color: white; padding: 10px 20px; border-radius: 8px; display: inline-block; margin-bottom: 15px; }
        .insumo-item { padding: 10px; border-bottom: 1px solid #eee; font-size: 13px; }
        .insumo-ok { color: #5cb85c; font-weight: bold; }
        .insumo-error { color: #d9534f; font-weight: bold; }
        .panel-simulacion { background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #ddd; }
    </style>
</head>

<body>
<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'>Simulador de Producción</div>
        <a href='c-produccion-list.php'><div class='btn-back'></div></a>
    </div>

    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>

        <div class='x-2'>
            <div class='data-label'>1. ¿De qué almacén saldrán los insumos?</div>
            <select id='selAlmacen' class='input-form' onchange="refrescarSimulador()">
                <?php foreach($listaAlmacenes as $alm){
                    $selA = ($idAlmacenSel == $alm->idAlmacen) ? "selected" : "";
                    echo "<option value='".$alm->idAlmacen."' $selA>".$alm->nombre."</option>";
                } ?>
            </select>
        </div>

        <div class='x-2'>
            <div class='data-label'>2. ¿Qué vas a fabricar?</div>
            <select id='selReceta' class='input-form' onchange="refrescarSimulador()">
                <option value=''>-- Seleccionar --</option>
                <?php foreach($listaRecetas as $rec){
                    $selR = ($idRecetaSel == $rec->idReceta) ? "selected" : "";
                    echo "<option value='".$rec->idReceta."' $selR>".$rec->nombre_servicio."</option>";
                } ?>
            </select>
        </div>

        <?php if ($viabilidad): ?>
            <div class='x-1'>
                <div class='panel-simulacion'>
                    <div class='badge-max'>
                        <small>CAPACIDAD MÁXIMA ACTUAL:</small><br>
                        <span style='font-size:24px;'><?php echo $viabilidad['maxima']; ?> Unidades</span>
                    </div>
                    
                    <div class='x-1'>
                        <div class='data-label'>Costo Estimado de Insumos (por unidad)</div>
                        <div style='font-size:18px; color:#5cb85c; font-weight:bold;'>
                            <?php echo number_format($viabilidad['costo_u'], 2); ?> Bs.
                        </div>
                    </div>

                    <div class='separator'></div>
                    
                    <div class='card-subtitle'>Estado de la Materia Prima:</div>
                    <?php foreach($viabilidad['insumos'] as $ins): 
                        $alcance = floor($ins['stock'] / $ins['necesario']);
                        $clase = ($alcance > 0) ? "insumo-ok" : "insumo-error";
                    ?>
                        <div class='insumo-item'>
                            <strong><?php echo $ins['nombre']; ?></strong><br>
                            Necesitas: <?php echo $ins['necesario']; ?> | 
                            Stock: <span class='<?php echo $clase; ?>'><?php echo $ins['stock']; ?></span>
                            <small style='float:right;'>Alcanza para: <?php echo $alcance; ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class='separator'></div>

            <form action='c-produccion-save.php' method='POST' onsubmit="return validarCantidad(<?php echo $viabilidad['maxima']; ?>)">
                <input type='hidden' name='idReceta' value='<?php echo $idRecetaSel; ?>'>
                
                <div class='x-1'>
                    <div class='data-label'>Cantidad a Producir</div>
                    <input type='number' name='cantidad' id='inputCantidad' min='1' max='<?php echo $viabilidad['maxima']; ?>' 
                           value='1' class='input-form' style='font-size:20px; text-align:center;' required>
                </div>

                <div class='x-2'>
                    <div class='data-label'>Origen (Insumos)</div>
                    <select name='idAlmacenInsumos' class='input-form' required>
                        <?php foreach($listaAlmacenes as $alm) echo "<option value='".$alm->idAlmacen."'>".$alm->nombre."</option>"; ?>
                    </select>
                </div>

                <div class='x-2'>
                    <div class='data-label'>Destino (Producto Final)</div>
                    <select name='idAlmacenDestino' class='input-form' required>
                        <?php foreach($listaAlmacenes as $alm) echo "<option value='".$alm->idAlmacen."' selected>".$alm->nombre."</option>"; ?>
                    </select>
                </div>

                <div class='x-1'>
                    <?php if($viabilidad['maxima'] > 0): ?>
                        <button type='submit' class='btn-action' style='background:#5cb85c; width:100%; border:none; cursor:pointer;'>
                            CONFIRMAR Y PROCESAR PRODUCCIÓN
                        </button>
                    <?php else: ?>
                        <div style='background:#f2dede; color:#a94442; padding:15px; text-align:center; border-radius:5px;'>
                            <strong>No puedes producir:</strong> Stock insuficiente de insumos.
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        <?php endif; ?>

        <div class='clear'></div>
    </div>
</div>

<script>
function validarCantidad(max) {
    const cant = document.getElementById('inputCantidad').value;
    if (cant > max) {
        alert("No tienes suficientes insumos para esa cantidad.");
        return false;
    }
    return confirm("¿Estás seguro? Se descontarán los insumos y se cargará el producto terminado.");
}

function refrescarSimulador() {
    const rec = document.getElementById('selReceta').value;
    const alm = document.getElementById('selAlmacen').value;
    
    // Solo refresca si tenemos la receta seleccionada
    if(rec) {
        window.location.href = `?idReceta=${rec}&idAlmacen=${alm}`;
    }
}
</script>

</body>
</html>