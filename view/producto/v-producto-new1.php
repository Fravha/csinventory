<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;
$title = "Registrar Compra";

$msgOk = "";
$msgError = "";

if (isset($_GET["msg"]) && $_GET["msg"] == "ok") {
    $msgOk = "Compra registrada correctamente.";
}

if (isset($_GET["error"]) && $_GET["error"] != "") {
    $msgError = urldecode($_GET["error"]);
}

function renderProductoOptions($listaInsumos){
    $html = "<option value=''>Seleccionar</option>";
    foreach($listaInsumos as $prod){
        $html .= "<option value='" . $prod->idProducto . "'>" . htmlspecialchars($prod->nombre) . "</option>";
    }
    return $html;
}

$productoOptions = renderProductoOptions($listaInsumos);

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv='content-type' content='text/html' charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />

    <title><?php echo $appTitle; ?></title>

    <link rel='stylesheet' href='../../view/css/main.css' />

    <style>
        .input-control,
        .select-control,
        .textarea-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
            font-size: 12px;
        }

        .label-control {
            font-size: 12px;
            color: #555;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .btn-main {
            display: inline-block;
            padding: 10px 18px;
            background: #233251;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }

        .btn-light {
            display: inline-block;
            padding: 10px 18px;
            background: #eee;
            color: #333;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
        }

        .btn-danger {
            display: inline-block;
            padding: 8px 12px;
            background: #d9534f;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
        }

        .btn-add-row {
            display: inline-block;
            padding: 8px 12px;
            background: #5cb85c;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 11px;
            margin-bottom: 10px;
        }

        .detalle-row {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 12px;
            background: #fafafa;
        }

        .msg-ok {
            padding: 10px;
            background: #dff0d8;
            color: #3c763d;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .msg-error {
            padding: 10px;
            background: #f2dede;
            color: #a94442;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .resumen-box {
            background: #f8f9fb;
            border: 1px solid #e6e6e6;
            border-radius: 8px;
            padding: 12px;
            margin-top: 15px;
        }

        .resumen-line {
            font-size: 12px;
            margin-bottom: 6px;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'><?php echo $nomUsuario; ?></div>
        <div class='bar'><div class='step'></div></div>
        <a href='c-compra-panel.php'><div class='btn-back'></div></a>
    </div>

    <div class='form-content'>
        <div class='title'><?php echo $title; ?></div>

        <?php if ($msgOk != "") { ?>
            <div class='msg-ok'><?php echo $msgOk; ?></div>
        <?php } ?>

        <?php if ($msgError != "") { ?>
            <div class='msg-error'><?php echo htmlspecialchars($msgError); ?></div>
        <?php } ?>

        <form method='post' action='c-compra-save.php' id='formCompra'>
            <div class='x-2'>
                <div class='label-control'>Número Compra</div>
                <input type='text' name='numero_compra' class='input-control' placeholder='Ej: COMP-0001' />
            </div>

            <div class='x-2'>
                <div class='label-control'>Proveedor</div>
                <input type='text' name='proveedor_nombre' class='input-control' required />
            </div>

            <div class='x-2'>
                <div class='label-control'>Almacén destino</div>
                <select name='idAlmacen' class='select-control' required>
                    <option value=''>Seleccionar</option>
                    <?php foreach($listaAlmacenes as $alm){ ?>
                        <option value='<?php echo $alm->idAlmacen; ?>'>
                            <?php echo htmlspecialchars($alm->nombre); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class='x-2'>
                <div class='label-control'>Fecha compra</div>
                <input type='datetime-local' name='fecha_compra' class='input-control' value='<?php echo date("Y-m-d\TH:i"); ?>' />
            </div>

            <div class='x-1'>
                <div class='label-control'>Observación</div>
                <textarea name='observacion' class='textarea-control' rows='3'></textarea>
            </div>

            <div class='clear'></div>

            <div class='title'>Detalle de Productos</div>

            <button type='button' class='btn-add-row' onclick='agregarFila()'>+ Agregar producto</button>

            <div id='detalleCompra'>
                <div class='detalle-row'>
                    <div class='x-4'>
                        <div class='label-control'>Producto</div>
                        <select name='idProducto[]' class='select-control producto-select' required>
                            <?php echo $productoOptions; ?>
                        </select>
                    </div>

                    <div class='x-4'>
                        <div class='label-control'>Lote</div>
                        <input type='text' name='lote[]' class='input-control' placeholder='Lote' />
                    </div>

                    <div class='x-4'>
                        <div class='label-control'>Cantidad</div>
                        <input type='number' step='0.01' min='0' name='cantidad[]' class='input-control cantidad-input' oninput='recalcularFila(this)' required />
                    </div>

                    <div class='x-4'>
                        <div class='label-control'>Precio Unitario</div>
                        <input type='number' step='0.01' min='0' name='precio[]' class='input-control precio-input' oninput='recalcularFila(this)' required />
                    </div>

                    <div class='x-3'>
                        <div class='label-control'>Subtotal</div>
                        <input type='text' class='input-control subtotal-input' value='0.00' readonly />
                    </div>

                    <div class='x-3'>
                        <div class='label-control'>&nbsp;</div>
                        <button type='button' class='btn-danger' onclick='eliminarFila(this)'>Eliminar</button>
                    </div>

                    <div class='clear'></div>
                </div>
            </div>

            <div class='resumen-box'>
                <div class='resumen-line'><strong>Total de ítems:</strong> <span id='totalItems'>1</span></div>
                <div class='resumen-line'><strong>Total compra:</strong> Bs. <span id='totalCompra'>0.00</span></div>
            </div>

            <br />

            <button type='submit' class='btn-main'>Guardar Compra</button>
            <a href='c-compra-list.php' class='btn-light'>Cancelar</a>
        </form>
    </div>
</div>

<script>
const productoOptionsHtml = <?php echo json_encode($productoOptions); ?>;

function agregarFila() {
    const contenedor = document.getElementById('detalleCompra');

    const fila = document.createElement('div');
    fila.className = 'detalle-row';

    fila.innerHTML = `
        <div class='x-4'>
            <div class='label-control'>Producto</div>
            <select name='idProducto[]' class='select-control producto-select' required>
                ${productoOptionsHtml}
            </select>
        </div>

        <div class='x-4'>
            <div class='label-control'>Lote</div>
            <input type='text' name='lote[]' class='input-control' placeholder='Lote' />
        </div>

        <div class='x-4'>
            <div class='label-control'>Cantidad</div>
            <input type='number' step='0.01' min='0' name='cantidad[]' class='input-control cantidad-input' oninput='recalcularFila(this)' required />
        </div>

        <div class='x-4'>
            <div class='label-control'>Precio Unitario</div>
            <input type='number' step='0.01' min='0' name='precio[]' class='input-control precio-input' oninput='recalcularFila(this)' required />
        </div>

        <div class='x-3'>
            <div class='label-control'>Subtotal</div>
            <input type='text' class='input-control subtotal-input' value='0.00' readonly />
        </div>

        <div class='x-3'>
            <div class='label-control'>&nbsp;</div>
            <button type='button' class='btn-danger' onclick='eliminarFila(this)'>Eliminar</button>
        </div>

        <div class='clear'></div>
    `;

    contenedor.appendChild(fila);
    actualizarResumen();
}

function eliminarFila(btn) {
    const filas = document.querySelectorAll('#detalleCompra .detalle-row');
    if (filas.length <= 1) {
        alert('Debe existir al menos una fila de detalle.');
        return;
    }

    btn.closest('.detalle-row').remove();
    actualizarResumen();
}

function recalcularFila(elemento) {
    const fila = elemento.closest('.detalle-row');
    const cantidad = parseFloat(fila.querySelector('.cantidad-input').value) || 0;
    const precio = parseFloat(fila.querySelector('.precio-input').value) || 0;
    const subtotal = cantidad * precio;

    fila.querySelector('.subtotal-input').value = subtotal.toFixed(2);
    actualizarResumen();
}

function actualizarResumen() {
    const filas = document.querySelectorAll('#detalleCompra .detalle-row');
    let total = 0;

    filas.forEach(fila => {
        const subtotal = parseFloat(fila.querySelector('.subtotal-input').value) || 0;
        total += subtotal;
    });

    document.getElementById('totalItems').innerText = filas.length;
    document.getElementById('totalCompra').innerText = total.toFixed(2);
}

document.getElementById('formCompra').addEventListener('submit', function(e) {
    const productos = document.querySelectorAll('select[name="idProducto[]"]');
    let hayProductoValido = false;

    productos.forEach(sel => {
        if (sel.value !== '') {
            hayProductoValido = true;
        }
    });

    if (!hayProductoValido) {
        e.preventDefault();
        alert('Debes agregar al menos un producto.');
        return false;
    }
});
</script>

</body>
</html>