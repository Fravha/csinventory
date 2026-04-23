<?php
/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv='content-type' content='text/html' charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    <title><?php echo $appTitle; ?></title>
    <link rel='stylesheet' href='../../view/css/main.css' />
    <style>
        .btn-add-row { background: #5bc0de; color: white; padding: 5px 10px; border-radius: 5px; cursor: pointer; font-size: 12px; display: inline-block; margin-bottom: 10px; }
        .btn-del-row { background: #d9534f; color: white; padding: 3px 8px; border-radius: 3px; cursor: pointer; border:none; }
        .tabla-receta { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .tabla-receta th { text-align: left; font-size: 11px; color: #777; padding: 5px; border-bottom: 1px solid #eee; }
        .tabla-receta td { padding: 8px 5px; }
    </style>
</head>

<body>
<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'>Configuración de Producción</div>
        <div class='bar'><div class='step' style='width:50%;'></div></div>        
        <a href='c-receta-list.php'><div class='btn-back'></div></a>
    </div>

    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>

        <form action='c-receta-save.php' method='POST' id='formReceta'>
            
            <div class='x-1'>
                <div class='data-label'>Nombre del Servicio / Técnica</div>
                <input type='text' name='nombre_servicio' placeholder='Ej: Sublimación Polera Algodón' required class='input-form' />
            </div>

            <div class='x-2'>
                <div class='data-label'>Producto Final a Generar</div>
                <select name='idProductoFinal' required class='input-form'>
                    <option value=''>-- Seleccionar Producto --</option>
                    <?php foreach($listaProductosFinales as $pf){
                        echo "<option value='".$pf->idProducto."'>".$pf->nombre."</option>";
                    } ?>
                </select>
            </div>

            <div class='x-2'>
                <div class='data-label'>Costo Operativo Sugerido (Bs)</div>
                <input type='number' step='0.01' name='costo_operativo_sugerido' placeholder='0.00' class='input-form' title='Mano de obra, luz, etc.' />
            </div>

            <div class='separator'></div>

            <div class='x-1'>
                <div style='display: flex; justify-content: space-between; align-items: center;'>
                    <div class='card-title' style='color:#233251;'>Insumos / Materia Prima</div>
                    <div class='btn-add-row' onclick='agregarFila()'>+ Agregar Insumo</div>
                </div>

                <table class='tabla-receta' id='tablaInsumos'>
                    <thead>
                        <tr>
                            <th style='width: 60%;'>Insumo</th>
                            <th style='width: 30%;'>Cant. Necesaria</th>
                            <th style='width: 10%;'></th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
            </div>

            <div class='separator'></div>

            <div class='x-1'>
                <button type='submit' class='btn-action' style='background:#5cb85c; width:100%; border:none; cursor:pointer;'>
                    GUARDAR RECETA ESTRUCTURADA
                </button>
            </div>
        </form>
    </div>
</div>

<div id='divInsumosSource' style='display:none;'>
    <select name='insumos[]' class='input-form' style='margin-bottom:0; height:35px;'>
        <option value=''>-- Seleccionar --</option>
        <?php foreach($listaInsumos as $ins){
            echo "<option value='".$ins->idProducto."'>".$ins->nombre." (".$ins->unidad_medida.")</option>";
        } ?>
    </select>
</div>

<script>
    function agregarFila() {
        const table = document.getElementById('tablaInsumos').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        
        // Columna 1: Select de Insumos
        const cell1 = newRow.insertCell(0);
        const selectSource = document.getElementById('divInsumosSource').innerHTML;
        cell1.innerHTML = selectSource;

        // Columna 2: Cantidad
        const cell2 = newRow.insertCell(1);
        cell2.innerHTML = "<input type='number' step='0.0001' name='cantidades[]' placeholder='0.00' required class='input-form' style='margin-bottom:0; height:35px;' />";

        // Columna 3: Botón Eliminar
        const cell3 = newRow.insertCell(2);
        cell3.innerHTML = "<button type='button' class='btn-del-row' onclick='eliminarFila(this)'>x</button>";
    }

    function eliminarFila(btn) {
        const row = btn.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    // Al cargar, agregar la primera fila por defecto
    window.onload = function() {
        agregarFila();
    };
</script>

</body>
</html>