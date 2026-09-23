<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;
$authSession = function_exists('auth_user') ? auth_user() : null;

$title = "Lista de Presentaciones de Productos para Compras";
$msgOk = isset($_GET["success"]) ? "Operacion realizada correctamente." : "";
$msgError = isset($_GET["error"]) ? urldecode($_GET["error"]) : "";

$content = "";
$i = 0;

foreach ($listaPresentacionProducto as $oPresentacion) {
    $i++;

    $idPresentacion = $oPresentacion->idPresentacion;
    $idProducto = $oPresentacion->idProducto;
    $producto = $oPresentacion->producto;
    $sku = $oPresentacion->sku;
    $presentacion = $oPresentacion->presentacion;
    $cantidadBase = $oPresentacion->cantidad_base;
    $unidadBase = $oPresentacion->unidad_base;
    $unidadAbreviatura = $oPresentacion->unidad_abreviatura;
    $activo = $oPresentacion->activo;

    $badgeColor = ($activo == 1) ? '#5cb85c' : '#d9534f';
    $estadoTexto = ($activo == 1) ? 'Activa' : 'Inactiva';

    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>
                Item #" . $i . "
                <span style='float:right; font-size:10px; background:" . $badgeColor . "; color:white; padding:2px 8px; border-radius:10px;'>
                    " . $estadoTexto . "
                </span>
            </div>

            <div class='card-subtitle'>" . $producto . "</div>
            <div class='separator'></div>
            
            <div class='x-2'>
                <div class='card-title'>SKU</div>
                <div class='card-text'>" . $sku . "</div>
            </div>

            <div class='x-2'>
                <div class='card-title'>Presentación</div>
                <div class='card-text'>" . $presentacion . "</div>
            </div>

            <div class='clear'></div>

            <div class='x-2'>
                <div class='card-title'>Equivalencia</div>
                <div class='card-text'>" . $cantidadBase . " " . $unidadAbreviatura . "</div>
            </div>

            <div class='x-2'>
                <div class='card-title'>Unidad Base</div>
                <div class='card-text'>" . $unidadBase . "</div>
            </div>
            
            <div class='clear'></div>
            <div class='separator'></div>";

    if (auth_can_action('producto', 'manage', $authSession)) {
        $content .= "
            <div class='card-buttons'>
                <a href='#' onclick=\"openEditModal(
                    '" . $idPresentacion . "',
                    '" . $idProducto . "',
                    '" . htmlspecialchars($presentacion, ENT_QUOTES) . "',
                    '" . $cantidadBase . "',
                    '" . $oPresentacion->idUnidadBase . "'
                ); return false;\">
                    <div class='card-edit'></div>
                </a>
                <a href='c-ppresentacion-delete.php?idPresentacion=" . $idPresentacion . "'>
                    <div class='card-delete'></div>
                </a>
            </div>
            <div class='clear'></div>";
    }

    $content .= "
        </div>
    </div>";
}

if ($content == "") {
    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>Vacío</div>
            <div class='card-subtitle'>No existen presentaciones de productos registradas.</div>
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

<style>
    .modal-bg {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    z-index: 9999;
    padding: 20px;
}

.modal-box {
    background: #fff;
    max-width: 420px;
    margin: 60px auto;
    padding: 20px;
    border-radius: 12px;
}

.form-group {
    margin-bottom: 12px;
}

.form-group label {
    display: block;
    font-size: 12px;
    margin-bottom: 4px;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 8px;
}
</style>

<div id="modalEditPresentacion" class="modal-bg" style="display:none;">
    <div class="modal-box">
        <div class="card-title">Editar Presentación</div>
        <div class="separator"></div>

        <form method="POST" action="c-ppresentacion-update.php">
            <input type="hidden" id="edit_idPresentacion" name="idPresentacion">
            <input type="hidden" id="edit_idProducto" name="idProducto">

            <div class="form-group">
                <label>Nombre de presentación</label>
                <input type="text" id="edit_nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label>Cantidad base</label>
                <input type="number" step="0.0001" id="edit_cantidad_base" name="cantidad_base" required>
            </div>

            <div class="form-group">
                <label>Unidad base</label>
                <select id="edit_idUnidadBase" name="idUnidadBase" required>
                    <?php foreach ($listaUnidadesMedida as $unidad): ?>
                        <option value="<?php echo $unidad->idUnidad; ?>">
                            <?php echo $unidad->nombre; ?> (<?php echo $unidad->abreviatura; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="separator"></div>

            <button type="submit">Guardar cambios</button>
            <button type="button" onclick="closeEditModal()">Cancelar</button>
        </form>
    </div>
</div>

<div id="modalCreatePresentacion" class="modal-bg" style="display:none;">
    <div class="modal-box">
        <div class="card-title">Nueva Presentacion</div>
        <div class="separator"></div>

        <form method="POST" action="c-ppresentacion-new.php">
            <div class="form-group">
                <label>Producto</label>
                <select id="create_idProducto" name="idProducto" onchange="setCreateUnidadBase()" required>
                    <option value="">Seleccionar</option>
                    <?php foreach ($listaProductos as $producto): ?>
                        <option value="<?php echo $producto->idProducto; ?>" data-unidad-base="<?php echo (int)$producto->idUnidadBase; ?>">
                            <?php echo htmlspecialchars($producto->nombre); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Nombre de presentacion</label>
                <input type="text" name="nombre" placeholder="Caja, paquete, sobre" required>
            </div>

            <div class="form-group">
                <label>Cantidad en unidad base</label>
                <input type="number" step="0.0001" name="cantidad_base" required>
            </div>

            <div class="form-group">
                <label>Unidad base</label>
                <select id="create_idUnidadBase" name="idUnidadBase" required>
                    <?php foreach ($listaUnidadesMedida as $unidad): ?>
                        <option value="<?php echo $unidad->idUnidad; ?>">
                            <?php echo htmlspecialchars($unidad->nombre); ?> (<?php echo htmlspecialchars($unidad->abreviatura); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="separator"></div>

            <button type="submit">Guardar</button>
            <button type="button" onclick="closeCreateModal()">Cancelar</button>
        </form>
    </div>
</div>


<body>

<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'><?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step'></div></div>        
        <a href='../c-panel.php'><div class='btn-back'></div></a>
    </div>
    
    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>

        <?php if ($msgOk != "") { ?>
            <div class='msg-ok'><?php echo htmlspecialchars($msgOk); ?></div>
        <?php } ?>

        <?php if ($msgError != "") { ?>
            <div class='msg-error'><?php echo htmlspecialchars($msgError); ?></div>
        <?php } ?>
        
        <?php echo $content ?>
        
        <div class='clear'></div>
    </div>

    <?php if (auth_can_action('producto', 'manage', $authSession)): ?>
        <a href='#' onclick='openCreateModal(); return false;'><div class='btn-add'></div></a>
    <?php endif; ?>
</div>

</body>
<script>
function openEditModal(idPresentacion, idProducto, nombre, cantidadBase, idUnidadBase) {
    document.getElementById('edit_idPresentacion').value = idPresentacion;
    document.getElementById('edit_idProducto').value = idProducto;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_cantidad_base').value = cantidadBase;
    document.getElementById('edit_idUnidadBase').value = idUnidadBase;

    document.getElementById('modalEditPresentacion').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('modalEditPresentacion').style.display = 'none';
}

function openCreateModal() {
    document.getElementById('modalCreatePresentacion').style.display = 'block';
    setCreateUnidadBase();
}

function closeCreateModal() {
    document.getElementById('modalCreatePresentacion').style.display = 'none';
}

function setCreateUnidadBase() {
    const productoSelect = document.getElementById('create_idProducto');
    const unidadSelect = document.getElementById('create_idUnidadBase');
    const selected = productoSelect.options[productoSelect.selectedIndex];
    const idUnidadBase = selected ? selected.getAttribute('data-unidad-base') : '';

    if (idUnidadBase) {
        unidadSelect.value = idUnidadBase;
    }
}
</script>

</html>
