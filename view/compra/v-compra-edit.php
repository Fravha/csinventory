<?php

$nomUsuario = $oUsuario->nombre;
$title = "Editar Compra";
$msgError = isset($error) ? $error : "";

function compra_edit_producto_options($listaInsumos, $selectedId)
{
    $html = "<option value=''>Seleccionar</option>";
    foreach ($listaInsumos as $prod) {
        $selected = ((int)$selectedId === (int)$prod->idProducto) ? " selected" : "";
        $html .= "<option value='" . (int)$prod->idProducto . "'" . $selected . ">" . htmlspecialchars($prod->nombre) . "</option>";
    }
    return $html;
}

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
    <style>
        .input-control, .select-control { width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; margin-top:5px; margin-bottom:10px; box-sizing:border-box; font-size:12px; }
        .label-control { font-size:12px; color:#555; font-weight:bold; margin-bottom:4px; }
        .detalle-row { border:1px solid #eee; border-radius:8px; padding:12px; margin-bottom:12px; background:#fafafa; }
        .btn-main { display:inline-block; padding:10px 18px; background:#233251; color:white; border:none; border-radius:6px; cursor:pointer; font-size:12px; }
        .btn-light { display:inline-block; padding:10px 18px; background:#eee; color:#333; border:none; border-radius:6px; text-decoration:none; font-size:12px; }
        .btn-danger { display:inline-block; padding:8px 12px; background:#d9534f; color:white; border:none; border-radius:6px; cursor:pointer; font-size:11px; }
        .btn-add-row { display:inline-block; padding:8px 12px; background:#5cb85c; color:white; border:none; border-radius:6px; cursor:pointer; font-size:11px; margin-bottom:10px; }
        .msg-error { padding:10px; background:#f2dede; color:#a94442; border-radius:6px; margin-bottom:15px; font-size:12px; }
        .row-title { font-size:12px; font-weight:bold; color:#233251; margin-bottom:10px; }
        .conversion-hint { font-size:11px; color:#54627a; margin-top:-4px; margin-bottom:10px; min-height:14px; }
    </style>
</head>
<body>
<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'><?php echo htmlspecialchars($nomUsuario); ?></div>
        <div class='bar'><div class='step'></div></div>
        <a href='c-compra-list.php'><div class='btn-back'></div></a>
    </div>
    <div class='form-content'>
        <div class='title'><?php echo $title; ?></div>

        <?php if ($msgError != "") { ?>
            <div class='msg-error'><?php echo htmlspecialchars($msgError); ?></div>
        <?php } ?>

        <form method='post' action='c-compra-update.php' id='formCompraEdit'>
            <input type='hidden' name='hashCompra' value='<?php echo htmlspecialchars($oCompra->hashCompra); ?>' />

            <div class='x-2'>
                <div class='label-control'>Proveedor</div>
                <input type='text' name='proveedor_nombre' class='input-control' value='<?php echo htmlspecialchars($oCompra->proveedor_nombre); ?>' required />
            </div>

            <div class='x-2'>
                <div class='label-control'>Almacen destino</div>
                <select name='idAlmacen' class='select-control' required>
                    <option value=''>Seleccionar</option>
                    <?php foreach($listaAlmacenes as $alm){ ?>
                        <option value='<?php echo $alm->idAlmacen; ?>' <?php echo ((int)$oCompra->idAlmacen === (int)$alm->idAlmacen) ? "selected" : ""; ?>>
                            <?php echo htmlspecialchars($alm->nombre); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class='x-2'>
                <div class='label-control'>Fecha compra</div>
                <input type='datetime-local' name='fecha_compra' class='input-control' value='<?php echo date("Y-m-d\TH:i", strtotime($oCompra->fecha_compra)); ?>' />
            </div>

            <div class='x-2'>
                <div class='label-control'>Estado</div>
                <select name='estado' class='select-control'>
                    <option value='CONFIRMADA' <?php echo $oCompra->estado === "CONFIRMADA" ? "selected" : ""; ?>>CONFIRMADA</option>
                    <option value='BORRADOR' <?php echo $oCompra->estado === "BORRADOR" ? "selected" : ""; ?>>BORRADOR</option>
                </select>
            </div>

            <div class='x-1'>
                <div class='label-control'>Observacion</div>
                <input type='text' name='observacion' class='input-control' value='<?php echo htmlspecialchars($oCompra->observacion); ?>' />
            </div>

            <div class='clear'></div>

            <div class='title'>Detalle</div>
            <button type='button' class='btn-add-row' onclick='agregarFilaEdit()'>+ Agregar producto</button>

            <div id='detalleCompra'>
                <?php $row = 0; foreach ($listaDetalle as $det): $row++; $tipoDetalle = !empty($det->idPresentacion) ? "PRESENTACION" : "UNIDAD"; ?>
                    <div class='detalle-row'>
                        <div class='row-title'>Producto #<?php echo $row; ?></div>
                        <div class='x-4'>
                            <div class='label-control'>Producto</div>
                            <select name='idProducto[]' class='select-control producto-select' onchange='cambioProductoEdit(this)' required>
                                <?php echo compra_edit_producto_options($listaInsumos, $det->idProducto); ?>
                            </select>
                        </div>
                        <div class='x-4'>
                            <div class='label-control'>Comprar como</div>
                            <select name='tipoCompra[]' class='select-control tipo-compra-select' onchange='cambioTipoCompraEdit(this)' required>
                                <option value='UNIDAD' <?php echo $tipoDetalle === "UNIDAD" ? "selected" : ""; ?>>Unidad directa</option>
                                <option value='PRESENTACION' <?php echo $tipoDetalle === "PRESENTACION" ? "selected" : ""; ?>>Presentacion</option>
                            </select>
                        </div>
                        <div class='x-4 bloque-unidad'>
                            <div class='label-control'>Unidad comprada</div>
                            <select name='idUnidadMedida[]' class='select-control unidad-select' onchange='actualizarConversionEdit(this.closest(".detalle-row"))' required></select>
                        </div>
                        <div class='x-4 bloque-presentacion' style='display:none;'>
                            <div class='label-control'>Presentacion</div>
                            <select name='idPresentacion[]' class='select-control presentacion-select' onchange='actualizarConversionEdit(this.closest(".detalle-row"))'>
                                <option value=''>Seleccionar</option>
                            </select>
                        </div>
                        <div class='x-4'>
                            <div class='label-control'>Cantidad</div>
                            <input type='number' step='0.01' min='0' name='cantidad[]' class='input-control cantidad-input' value='<?php echo htmlspecialchars($det->cantidad); ?>' oninput='compraRecalcularFila(this)' required />
                            <div class='conversion-hint'
                                 data-selected-unit='<?php echo (int)$det->idUnidadMedida; ?>'
                                 data-selected-presentation='<?php echo (int)$det->idPresentacion; ?>'
                                 data-selected-product='<?php echo (int)$det->idProducto; ?>'
                                 data-cantidad-base='<?php echo htmlspecialchars((string)$det->cantidad_base); ?>'></div>
                        </div>
                        <div class='x-4'>
                            <div class='label-control'>Precio Unitario</div>
                            <input type='number' step='0.01' min='0' name='precio[]' class='input-control precio-input' value='<?php echo htmlspecialchars($det->precio_unitario_compra); ?>' oninput='compraRecalcularFila(this)' />
                        </div>
                        <div class='x-4'>
                            <div class='label-control'>Total Linea</div>
                            <input type='number' step='0.01' min='0' name='total_linea[]' class='input-control total-linea-input' value='<?php echo htmlspecialchars($det->subtotal); ?>' oninput='compraRecalcularDesdeTotal(this)' />
                        </div>
                        <div class='x-3'>
                            <div class='label-control'>&nbsp;</div>
                            <button type='button' class='btn-danger' onclick='eliminarFilaEdit(this)'>Eliminar</button>
                        </div>
                        <div class='clear'></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type='submit' class='btn-main'>Actualizar Compra</button>
            <a href='c-compra-list.php' class='btn-light'>Cancelar</a>
        </form>
    </div>
</div>

<script>
const productoOptionsEdit = <?php echo json_encode(compra_edit_producto_options($listaInsumos, 0)); ?>;
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

function getPresentacionesByProducto(idProducto) {
    return presentacionesMeta[String(idProducto)] || presentacionesMeta[idProducto] || [];
}

function buildPresentacionOptions(productId, selectedId = "") {
    const presentaciones = getPresentacionesByProducto(productId);
    let html = "<option value=''>Seleccionar</option>";

    presentaciones.forEach((p) => {
        const selected = String(selectedId) === String(p.id) ? " selected" : "";
        html += `<option value="${p.id}"${selected}>${p.nombre} = ${p.cantidad_base} ${p.unidad_abreviatura}</option>`;
    });

    return html;
}

function actualizarOpcionesUnidadEdit(fila, selectedId = "") {
    const productoId = fila.querySelector(".producto-select").value;
    const unidadSelect = fila.querySelector(".unidad-select");

    if (!productoId) {
        unidadSelect.innerHTML = "<option value=''>Seleccionar</option>";
        fila.querySelector(".conversion-hint").innerText = "";
        return;
    }

    const unidadElegida = selectedId || getDefaultUnitId(productoId);
    unidadSelect.innerHTML = buildUnitOptions(productoId, unidadElegida);
    actualizarConversionEdit(fila);
}

function actualizarConversionEdit(fila) {
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

    if (tipoCompra === "PRESENTACION") {
        const idPresentacion = fila.querySelector(".presentacion-select").value;
        const presentacion = getPresentacionesByProducto(productoId).find((p) => String(p.id) === String(idPresentacion));

        if (!presentacion) {
            hint.innerText = "";
            return;
        }

        const cantidadBase = cantidad * parseFloat(presentacion.cantidad_base || 0);
        hint.innerText = `Equivale a ${cantidadBase.toFixed(4)} ${presentacion.unidad_abreviatura} en inventario.`;
        return;
    }

    const unidadId = fila.querySelector(".unidad-select").value;
    const unidad = getUnidadById(unidadId);

    if (!unidad) {
        hint.innerText = "";
        return;
    }

    const cantidadBase = cantidad * parseFloat(unidad.factor_base || 0);
    hint.innerText = `Equivale a ${cantidadBase.toFixed(4)} ${unidadBase.abreviatura} en inventario.`;
}

function renumerarFilasEdit() {
    document.querySelectorAll("#detalleCompra .detalle-row").forEach((fila, idx) => {
        fila.querySelector(".row-title").innerText = "Producto #" + (idx + 1);
    });
}

function compraRecalcularFila(input) {
    const fila = input.closest(".detalle-row");
    const cantidad = parseFloat(fila.querySelector(".cantidad-input").value) || 0;
    const precio = parseFloat(fila.querySelector(".precio-input").value) || 0;
    if (cantidad > 0 && precio >= 0) {
        fila.querySelector(".total-linea-input").value = (cantidad * precio).toFixed(2);
    }
    actualizarConversionEdit(fila);
}

function compraRecalcularDesdeTotal(input) {
    const fila = input.closest(".detalle-row");
    const cantidad = parseFloat(fila.querySelector(".cantidad-input").value) || 0;
    const total = parseFloat(fila.querySelector(".total-linea-input").value) || 0;
    if (cantidad > 0 && total >= 0) {
        fila.querySelector(".precio-input").value = (total / cantidad).toFixed(2);
    }
}

function cambioProductoEdit(select) {
    const fila = select.closest(".detalle-row");
    const idProducto = select.value;
    actualizarOpcionesUnidadEdit(fila);
    fila.querySelector(".presentacion-select").innerHTML = buildPresentacionOptions(idProducto);
    actualizarConversionEdit(fila);
}

function cambioTipoCompraEdit(select) {
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

    actualizarConversionEdit(fila);
}

function agregarFilaEdit() {
    const contenedor = document.getElementById("detalleCompra");
    const numero = contenedor.querySelectorAll(".detalle-row").length + 1;
    const fila = document.createElement("div");
    fila.className = "detalle-row";
    fila.innerHTML = `
        <div class='row-title'>Producto #${numero}</div>
        <div class='x-4'>
            <div class='label-control'>Producto</div>
            <select name='idProducto[]' class='select-control producto-select' onchange='cambioProductoEdit(this)' required>${productoOptionsEdit}</select>
        </div>
        <div class='x-4'>
            <div class='label-control'>Comprar como</div>
            <select name='tipoCompra[]' class='select-control tipo-compra-select' onchange='cambioTipoCompraEdit(this)' required>
                <option value='UNIDAD'>Unidad directa</option>
                <option value='PRESENTACION'>Presentacion</option>
            </select>
        </div>
        <div class='x-4 bloque-unidad'>
            <div class='label-control'>Unidad comprada</div>
            <select name='idUnidadMedida[]' class='select-control unidad-select' onchange='actualizarConversionEdit(this.closest(".detalle-row"))' required>
                <option value=''>Seleccionar</option>
            </select>
        </div>
        <div class='x-4 bloque-presentacion' style='display:none;'>
            <div class='label-control'>Presentacion</div>
            <select name='idPresentacion[]' class='select-control presentacion-select' onchange='actualizarConversionEdit(this.closest(".detalle-row"))'>
                <option value=''>Seleccionar</option>
            </select>
        </div>
        <div class='x-4'>
            <div class='label-control'>Cantidad</div>
            <input type='number' step='0.01' min='0' name='cantidad[]' class='input-control cantidad-input' oninput='compraRecalcularFila(this)' required />
            <div class='conversion-hint'></div>
        </div>
        <div class='x-4'>
            <div class='label-control'>Precio Unitario</div>
            <input type='number' step='0.01' min='0' name='precio[]' class='input-control precio-input' oninput='compraRecalcularFila(this)' />
        </div>
        <div class='x-4'>
            <div class='label-control'>Total Linea</div>
            <input type='number' step='0.01' min='0' name='total_linea[]' class='input-control total-linea-input' oninput='compraRecalcularDesdeTotal(this)' />
        </div>
        <div class='x-3'>
            <div class='label-control'>&nbsp;</div>
            <button type='button' class='btn-danger' onclick='eliminarFilaEdit(this)'>Eliminar</button>
        </div>
        <div class='clear'></div>
    `;
    contenedor.appendChild(fila);
    renumerarFilasEdit();
}

function eliminarFilaEdit(btn) {
    const filas = document.querySelectorAll("#detalleCompra .detalle-row");
    if (filas.length <= 1) {
        alert("Debe existir al menos una fila de detalle.");
        return;
    }
    btn.closest(".detalle-row").remove();
    renumerarFilasEdit();
}

document.querySelectorAll("#detalleCompra .detalle-row").forEach((fila) => {
    const hint = fila.querySelector(".conversion-hint");
    const productoId = hint.getAttribute("data-selected-product");
    const selectedPresentacion = hint.getAttribute("data-selected-presentation") || "";

    actualizarOpcionesUnidadEdit(
        fila,
        hint.getAttribute("data-selected-unit") || getDefaultUnitId(productoId)
    );

    fila.querySelector(".presentacion-select").innerHTML = buildPresentacionOptions(productoId, selectedPresentacion);
    cambioTipoCompraEdit(fila.querySelector(".tipo-compra-select"));
});

document.getElementById("formCompraEdit").addEventListener("submit", function(e) {
    const filas = document.querySelectorAll("#detalleCompra .detalle-row");

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
});
</script>
</body>
</html>
