<?php
/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.1
 */

$nomUsuario = $oUsuario->nombre;
$title = "Detalle de Compra";

$nomProducto = $oProducto->nombre;
$uMedida = $oProducto->unidad_medida;
$idProducto = $oProducto->idProducto;

$loteSugerido = "LOT-" . date("Ymd") . "-" . strtoupper(substr(uniqid(), -3));
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
        <div class='form-subtitle'>Registro de Entrada</div>
        <div class='bar'><div class='step' style='width:66%;'></div></div>        
        <a href='c-compra-new.php'><div class='btn-back'></div></a>
    </div>

    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>
        
        <div class='card' style='background: #f9f9f9; border-left: 5px solid #68dccf;'>
            <div class='card-title' style='color:#233251;'>Producto Seleccionado</div>
            <div class='card-subtitle'><?php echo $nomProducto; ?> (<?php echo $uMedida; ?>)</div>
        </div>

        <form action='c-compra-save.php' method='POST'>
            <input type='hidden' name='idProducto' value='<?php echo $idProducto; ?>' />

            <div class='x-1'>
                <div class='data-label' style='margin-top:15px;'>Número de Lote</div>
                <input type='text' name='lote' value='<?php echo $loteSugerido; ?>' required class='input-form' />
            </div>

            <div class='x-2'>
                <div class='data-label'>Cantidad (<?php echo $uMedida; ?>)</div>
                <input type='number' step='0.01' id='txtCantidad' name='cantidad' placeholder='0.00' required class='input-form' oninput='calcularUnitario()' />
            </div>

            <div class='x-2'>
                <div class='data-label'>Monto Total Pagado (Bs)</div>
                <input type='number' step='0.01' id='txtTotal' name='montoTotal' placeholder='0.00' class='input-form' oninput='calcularUnitario()' />
            </div>

            <div class='x-1'>
                <div class='data-label'>Precio Unitario Calculado (Bs)</div>
                <input type='number' step='0.0001' id='txtUnitario' name='precioUnitario' placeholder='0.00' required class='input-form' style='background:#f0f0f0;' readonly />
            </div>

            <div class='x-1'>
                <div class='data-label'>Almacén de Destino</div>
                <select name='idAlmacen' required class='input-form' style='height:40px;'>
                    <option value=''>-- Seleccionar Almacén --</option>
                    <?php
                    foreach($listaAlmacenes as $oAlmacen){
                        echo "<option value='".$oAlmacen->idAlmacen."'>".$oAlmacen->nombre."</option>";
                    }
                    ?>
                </select>
            </div>

            <div class='separator'></div>

            <div class='x-1'>
                <button type='submit' class='btn-action' style='background:#5cb85c; width:100%; border:none; cursor:pointer;'>
                    FINALIZAR COMPRA
                </button>
            </div>
        </form>
    </div>
</div>

<script>
/**
 * Función para automatizar el cálculo del costo unitario
 * Ayuda a la precisión del algoritmo CP que mencionas en tu PDF
 */
function calcularUnitario() {
    const cantidad = document.getElementById('txtCantidad').value;
    const total = document.getElementById('txtTotal').value;
    const inputUnitario = document.getElementById('txtUnitario');

    if (cantidad > 0 && total > 0) {
        let unitario = total / cantidad;
        // Redondeamos a 4 decimales para mayor precisión en insumos pequeños
        inputUnitario.value = unitario.toFixed(4);
    } else {
        inputUnitario.value = '';
    }
}
</script>

</body>
</html>