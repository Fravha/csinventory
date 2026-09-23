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

function renderProductoOptions($listaInsumos)
{
    $html = "<option value=''>Seleccionar</option>";
    foreach ($listaInsumos as $prod) {
        $html .= "<option value='" . (int)$prod->idProducto . "'>" . htmlspecialchars($prod->nombre) . "</option>";
    }
    return $html;
}

$productoOptions = renderProductoOptions($listaInsumos);

$productosMeta = array();
foreach ($listaInsumos as $prod) {
    $productosMeta[(int)$prod->idProducto] = array(
        "id" => (int)$prod->idProducto,
        "nombre" => $prod->nombre,
        "idUnidadBase" => (int)$prod->idUnidadBase,
        "idUnidadMedida" => (int)$prod->idUnidadMedida,
    );
}

$unidadesMeta = array();
foreach ($listaUnidades as $unidad) {
    $unidadesMeta[(int)$unidad->idUnidad] = array(
        "id" => (int)$unidad->idUnidad,
        "nombre" => $unidad->nombre,
        "abreviatura" => $unidad->abreviatura,
        "tipo" => $unidad->tipo,
        "factor_base" => (float)$unidad->factor_base,
        "unidad_base" => $unidad->unidad_base,
    );
}

$presentacionesMeta = array();

foreach ($listaProductosPresentacion as $pres) {
    $idProducto = (int)$pres->idProducto;

    if (!isset($presentacionesMeta[$idProducto])) {
        $presentacionesMeta[$idProducto] = array();
    }

    $presentacionesMeta[$idProducto][] = array(
        "id" => (int)$pres->idPresentacion,
        "nombre" => $pres->presentacion,
        "cantidad_base" => (float)$pres->cantidad_base,
        "idUnidadBase" => (int)$pres->idUnidadBase,
        "unidad_abreviatura" => $pres->unidad_abreviatura
    );
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
                <div class='label-control'>Proveedor</div>
                <input type='text' name='proveedor_nombre' class='input-control' required />
            </div>

            <div class='x-2'>
                <div class='label-control'>Almacen destino</div>
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

            <div class='x-2'>
                <div class='label-control'>Observacion</div>
                <input type='text' name='observacion' class='input-control' />
            </div>

            <div class='clear'></div>

            <div class='title'>Detalle de Productos</div>
            <div class='help-text'>
                El cliente puede comprar en cualquier unidad compatible con la base del producto. El inventario siempre se acumulara en unidad base.
            </div>

            <button type='button' class='btn-add-row' onclick='agregarFila()'>+ Agregar producto</button>

            <div id='detalleCompra'></div>

            <div class='resumen-box'>
                <div class='resumen-line'><strong>Total de items:</strong> <span id='totalItems'>0</span></div>
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
const productosMeta = <?php echo json_encode($productosMeta); ?>;
const unidadesMeta = <?php echo json_encode($unidadesMeta); ?>;
const presentacionesMeta = <?php echo json_encode($presentacionesMeta); ?>;

function getUnidadById(idUnidad) {
    return unidadesMeta[String(idUnidad)] || unidadesMeta[idUnidad] || null;
}

function getProductoById(idProducto) {
    return productosMeta[String(idProducto)] || productosMeta[idProducto] || null;
}

function getCompatibleUnits(productId) {
    const producto = getProductoById(productId);
    if (!producto) {
        return [];
    }

    const unidadBase = getUnidadById(producto.idUnidadBase);
    if (!unidadBase) {
        return [];
    }

    return Object.values(unidadesMeta).filter((unidad) => unidad.unidad_base === unidadBase.abreviatura);
}

function buildUnitOptions(productId, selectedId) {
    const compatibles = getCompatibleUnits(productId);
    let html = "<option value=''>Seleccionar</option>";

    compatibles.forEach((unidad) => {
        const selected = String(selectedId) === String(unidad.id) ? " selected" : "";
        html += `<option value="${unidad.id}"${selected}>${unidad.nombre} (${unidad.abreviatura})</option>`;
    });

    return html;
}

function getDefaultUnitId(productId) {
    const producto = getProductoById(productId);
    if (!producto) {
        return "";
    }

    const compatibles = getCompatibleUnits(productId);
    const preferida = compatibles.find((unidad) => String(unidad.id) === String(producto.idUnidadMedida));
    if (preferida) {
        return preferida.id;
    }

    return compatibles.length > 0 ? compatibles[0].id : "";
}

function actualizarOpcionesUnidad(fila, selectedId = "") {
    const productoId = fila.querySelector(".producto-select").value;
    const unidadSelect = fila.querySelector(".unidad-select");

    if (!productoId) {
        unidadSelect.innerHTML = "<option value=''>Seleccionar</option>";
        fila.querySelector(".conversion-hint").innerText = "";
        return;
    }

    const unidadElegida = selectedId || getDefaultUnitId(productoId);
    unidadSelect.innerHTML = buildUnitOptions(productoId, unidadElegida);
    actualizarConversion(fila);
}

function actualizarConversion(fila) {
    const productoId = fila.querySelector(".producto-select").value;
    const tipoCompra = fila.querySelector(".tipo-compra-select").value;
    const cantidad = parseFloat(fila.querySelector(".cantidad-input").value) || 0;
    const hint = fila.querySelector(".conversion-hint");

    if (!productoId || cantidad <= 0) {
        hint.innerText = "";
        return;
    }

    const producto = getProductoById(productoId);
    const unidadBase = getUnidadById(producto.idUnidadBase);

    if (!producto || !unidadBase) {
        hint.innerText = "";
        return;
    }

    let cantidadBase = 0;

    if (tipoCompra === "PRESENTACION") {
        const idPresentacion = fila.querySelector(".presentacion-select").value;

        if (!idPresentacion) {
            hint.innerText = "";
            return;
        }

        const presentaciones = getPresentacionesByProducto(productoId);
        const presentacion = presentaciones.find(p => String(p.id) === String(idPresentacion));

        if (!presentacion) {
            hint.innerText = "";
            return;
        }

        cantidadBase = cantidad * parseFloat(presentacion.cantidad_base || 0);
        hint.innerText = `Equivale a ${cantidadBase.toFixed(4)} ${presentacion.unidad_abreviatura} en inventario.`;
        return;
    }

    const unidadId = fila.querySelector(".unidad-select").value;

    if (!unidadId) {
        hint.innerText = "";
        return;
    }

    const unidad = getUnidadById(unidadId);

    if (!unidad) {
        hint.innerText = "";
        return;
    }

    cantidadBase = cantidad * parseFloat(unidad.factor_base || 0);
    hint.innerText = `Equivale a ${cantidadBase.toFixed(4)} ${unidadBase.abreviatura} en inventario.`;
}

function actualizarResumen() {
    const filas = document.querySelectorAll("#detalleCompra .detalle-row");
    let total = 0;

    filas.forEach((fila) => {
        const totalLinea = parseFloat(fila.querySelector(".total-linea-input").value) || 0;
        total += totalLinea;
    });

    document.getElementById("totalItems").innerText = filas.length;
    document.getElementById("totalCompra").innerText = total.toFixed(2);
}

function renumerarFilas() {
    document.querySelectorAll("#detalleCompra .detalle-row").forEach((fila, index) => {
        fila.querySelector(".row-title").innerText = "Producto #" + (index + 1);
    });
}

function cambioCantidad(input) {
    const fila = input.closest(".detalle-row");
    const cantidad = parseFloat(fila.querySelector(".cantidad-input").value) || 0;
    const precio = parseFloat(fila.querySelector(".precio-input").value) || 0;
    const total = parseFloat(fila.querySelector(".total-linea-input").value) || 0;

    if (cantidad <= 0) {
        fila.querySelector(".precio-input").value = "";
        fila.querySelector(".total-linea-input").value = "";
        actualizarConversion(fila);
        actualizarResumen();
        return;
    }

    if (precio > 0) {
        fila.querySelector(".total-linea-input").value = (cantidad * precio).toFixed(2);
    } else if (total > 0) {
        fila.querySelector(".precio-input").value = (total / cantidad).toFixed(2);
    }

    actualizarConversion(fila);
    actualizarResumen();
}

function cambioPrecioUnitario(input) {
    const fila = input.closest(".detalle-row");
    const cantidad = parseFloat(fila.querySelector(".cantidad-input").value) || 0;
    const precio = parseFloat(fila.querySelector(".precio-input").value) || 0;

    if (cantidad > 0 && precio >= 0) {
        fila.querySelector(".total-linea-input").value = (cantidad * precio).toFixed(2);
    } else {
        fila.querySelector(".total-linea-input").value = "";
    }

    actualizarResumen();
}

function cambioTotalLinea(input) {
    const fila = input.closest(".detalle-row");
    const cantidad = parseFloat(fila.querySelector(".cantidad-input").value) || 0;
    const total = parseFloat(fila.querySelector(".total-linea-input").value) || 0;

    if (cantidad > 0 && total >= 0) {
        fila.querySelector(".precio-input").value = (total / cantidad).toFixed(2);
    } else {
        fila.querySelector(".precio-input").value = "";
    }

    actualizarResumen();
}

function agregarFila() {
    const contenedor = document.getElementById("detalleCompra");
    const numero = contenedor.querySelectorAll(".detalle-row").length + 1;
    const fila = document.createElement("div");
    fila.className = "detalle-row";
    fila.innerHTML = `
        <div class='row-title'>Producto #${numero}</div>

        <div class='x-4'>
            <div class='label-control'>Producto</div>
            <select name='idProducto[]' class='select-control producto-select' onchange='cambioProducto(this)' required>
                ${productoOptionsHtml}
            </select>
        </div>

        <div class='x-4'>
            <div class='label-control'>Comprar como</div>
            <select name='tipoCompra[]' class='select-control tipo-compra-select' onchange='cambioTipoCompra(this)' required>
                <option value='UNIDAD'>Unidad directa</option>
                <option value='PRESENTACION'>Presentacion</option>
            </select>
        </div>

        <div class='x-4 bloque-unidad'>
            <div class='label-control'>Unidad comprada</div>
            <select name='idUnidadMedida[]' class='select-control unidad-select' onchange='actualizarConversion(this.closest(".detalle-row"))'>
                <option value=''>Seleccionar</option>
            </select>
        </div>

        <div class='x-4 bloque-presentacion' style='display:none;'>
            <div class='label-control'>Presentacion</div>
            <div style='display:flex; gap:5px;'>
                <select name='idPresentacion[]' class='select-control presentacion-select' onchange='actualizarConversion(this.closest(".detalle-row"))'>
                    <option value=''>Seleccionar</option>
                </select>
                <button type='button' class='btn-add-row' onclick='openModalPresentacion(this)'>+</button>
            </div>
        </div>

        <div class='x-4'>
            <div class='label-control'>Cantidad</div>
            <input type='number' step='0.01' min='0' name='cantidad[]' class='input-control cantidad-input' oninput='cambioCantidad(this)' required />
            <div class='conversion-hint'></div>
        </div>

        <div class='x-4'>
            <div class='label-control'>Precio Unitario</div>
            <input type='number' step='0.01' min='0' name='precio[]' class='input-control precio-input' oninput='cambioPrecioUnitario(this)' />
        </div>

        <div class='x-4'>
            <div class='label-control'>Total Linea</div>
            <input type='number' step='0.01' min='0' name='total_linea[]' class='input-control total-linea-input' oninput='cambioTotalLinea(this)' />
        </div>

        <div class='x-3'>
            <div class='label-control'>&nbsp;</div>
            <button type='button' class='btn-danger' onclick='eliminarFila(this)'>Eliminar</button>
        </div>

        <div class='clear'></div>
    `;

    contenedor.appendChild(fila);
    renumerarFilas();
    actualizarResumen();
}

function cambioProducto(select) {
    const fila = select.closest(".detalle-row");
    const idProducto = select.value;

    actualizarOpcionesUnidad(fila);

    const presentacionSelect = fila.querySelector(".presentacion-select");
    presentacionSelect.innerHTML = buildPresentacionOptions(idProducto);

    actualizarConversion(fila);
}

function eliminarFila(btn) {
    const filas = document.querySelectorAll("#detalleCompra .detalle-row");
    if (filas.length <= 1) {
        alert("Debe existir al menos una fila de detalle.");
        return;
    }

    btn.closest(".detalle-row").remove();
    renumerarFilas();
    actualizarResumen();
}

function getPresentacionesByProducto(idProducto) {
    return presentacionesMeta[String(idProducto)] || presentacionesMeta[idProducto] || [];
}

function buildPresentacionOptions(productId) {
    const presentaciones = getPresentacionesByProducto(productId);
    let html = "<option value=''>Seleccionar</option>";

    presentaciones.forEach((p) => {
        html += `<option value="${p.id}">${p.nombre} = ${p.cantidad_base} ${p.unidad_abreviatura}</option>`;
    });

    return html;
}

function cambioTipoCompra(select) {
    const fila = select.closest(".detalle-row");
    const tipo = select.value;

    const bloqueUnidad = fila.querySelector(".bloque-unidad");
    const bloquePresentacion = fila.querySelector(".bloque-presentacion");

    const unidadSelect = fila.querySelector(".unidad-select");
    const presentacionSelect = fila.querySelector(".presentacion-select");

    if (tipo === "PRESENTACION") {
        bloqueUnidad.style.display = "none";
        bloquePresentacion.style.display = "block";

        unidadSelect.required = false;
        presentacionSelect.required = true;
    } else {
        bloqueUnidad.style.display = "block";
        bloquePresentacion.style.display = "none";

        unidadSelect.required = true;
        presentacionSelect.required = false;
    }

    actualizarConversion(fila);
}

function openModalPresentacion(btn) {
    window.location.href = "../ppresentacion/c-ppresentacion-list.php";
}

document.getElementById("formCompra").addEventListener("submit", function(e) {
    const filas = document.querySelectorAll("#detalleCompra .detalle-row");
    let hayProductoValido = false;

    for (let i = 0; i < filas.length; i++) {
        const fila = filas[i];
        const producto = fila.querySelector(".producto-select").value;
        const tipo = fila.querySelector(".tipo-compra-select").value;
        const unidad = fila.querySelector(".unidad-select").value;
        const presentacion = fila.querySelector(".presentacion-select").value;
        const cantidad = parseFloat(fila.querySelector(".cantidad-input").value) || 0;
        const precio = parseFloat(fila.querySelector(".precio-input").value) || 0;
        const total = parseFloat(fila.querySelector(".total-linea-input").value) || 0;

        if (producto !== "") {
            hayProductoValido = true;

            if (tipo === "PRESENTACION" && presentacion === "") {
                e.preventDefault();
                alert("Toda fila comprada como presentacion debe tener una presentacion seleccionada.");
                return false;
            }

            if (tipo !== "PRESENTACION" && unidad === "") {
                e.preventDefault();
                alert("Toda fila con producto debe tener una unidad de medida.");
                return false;
            }

            if (cantidad <= 0) {
                e.preventDefault();
                alert("Toda fila con producto debe tener cantidad mayor a 0.");
                return false;
            }

            if (precio <= 0 && total <= 0) {
                e.preventDefault();
                alert("Toda fila con producto debe tener precio unitario o total de linea.");
                return false;
            }

            if (precio <= 0 && total > 0 && cantidad > 0) {
                fila.querySelector(".precio-input").value = (total / cantidad).toFixed(2);
            }

            if (total <= 0 && precio > 0 && cantidad > 0) {
                fila.querySelector(".total-linea-input").value = (cantidad * precio).toFixed(2);
            }
        }
    }

    if (!hayProductoValido) {
        e.preventDefault();
        alert("Debes agregar al menos un producto.");
        return false;
    }
});

agregarFila();
</script>

</body>
</html>
