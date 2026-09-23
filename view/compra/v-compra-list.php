<?php
/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     2.1
 */

$nomUsuario = $oUsuario->nombre;
$authSession = function_exists('auth_user') ? auth_user() : null;
$title = "Compras Realizadas";

$msgOk = "";
if (isset($_GET["msg"])) {
    if ($_GET["msg"] == "ok") {
        $msgOk = "Compra registrada correctamente.";
    } elseif ($_GET["msg"] == "updated") {
        $msgOk = "Compra actualizada correctamente.";
    } elseif ($_GET["msg"] == "deleted") {
        $msgOk = "Compra anulada correctamente.";
    }
}

$msgError = "";
if (isset($_GET["error"]) && $_GET["error"] != "") {
    $msgError = urldecode($_GET["error"]);
}

$content = "";
$i = 0;

foreach($listaCompras as $oCompra){
    $i++;

    $hashCompra = $oCompra->hashCompra;
    $numeroCompra = $oCompra->numero_compra != "" ? $oCompra->numero_compra : ("COMP-" . str_pad($i, 6, "0", STR_PAD_LEFT));
    $proveedor = $oCompra->proveedor_nombre != "" ? $oCompra->proveedor_nombre : "Sin proveedor";
    $fecha = $oCompra->fecha_compra != "" ? $oCompra->fecha_compra : "Sin fecha";
    $estado = $oCompra->estado != "" ? $oCompra->estado : "SIN ESTADO";
    $subtotal = (float)$oCompra->subtotal;
    $total = (float)$oCompra->total;
    $idAlmacen = $oCompra->idAlmacen;

    $nomAlmacen = "Almacen #" . $idAlmacen;

    $badgeEstado = "<span class='badge badge-confirmada'>" . htmlspecialchars($estado) . "</span>";

    if ($estado == "ANULADA") {
        $badgeEstado = "<span class='badge badge-anulada'>ANULADA</span>";
    } elseif ($estado == "BORRADOR") {
        $badgeEstado = "<span class='badge badge-borrador'>BORRADOR</span>";
    }

    $textoBusqueda = strtolower(
        $numeroCompra . " " .
        $proveedor . " " .
        $nomAlmacen . " " .
        $fecha . " " .
        $estado
    );

    $content .= "
    <div class='x-1 item-compra' data-search='" . htmlspecialchars($textoBusqueda, ENT_QUOTES, 'UTF-8') . "'>
        <div class='card'>

            <div class='card-title'>
                <span>" . htmlspecialchars($numeroCompra) . "</span>
                " . $badgeEstado . "
            </div>

            <div class='card-subtitle'>
                " . htmlspecialchars($fecha) . "
                <span>" . number_format($total, 2) . " Bs</span>
            </div>

            <div class='separator'></div>

            <div class='card-title'>Proveedor</div>
            <div class='card-text'>" . htmlspecialchars($proveedor) . "</div>

            <div class='x-2'>
                <div class='data-label'>Almacen</div>
                <div class='data-value'>" . htmlspecialchars($nomAlmacen) . "</div>
            </div>

            <div class='x-2'>
                <div class='data-label'>Estado</div>
                <div class='data-value'>" . htmlspecialchars($estado) . "</div>
            </div>

            <div class='x-2'>
                <div class='data-label'>Subtotal</div>
                <div class='data-value'>" . number_format($subtotal, 2) . " Bs</div>
            </div>

            <div class='x-2'>
                <div class='data-label'>Total</div>
                <div class='data-value' style='color:#5cb85c; font-weight:bold;'>" . number_format($total, 2) . " Bs</div>
            </div>

            <div class='clear'></div>";

    if (auth_can_action('compra', 'manage', $authSession)) {
        $content .= "
            <div class='card-buttons'>
                <a href='c-compra-view.php?param=" . urlencode($hashCompra) . "' class='btn-mini'>Ver detalle</a>
                <a href='c-compra-edit.php?param=" . urlencode($hashCompra) . "' class='btn-mini'>Editar</a>
                <a href='c-compra-delete.php?param=" . urlencode($hashCompra) . "' class='btn-mini btn-danger' onclick='return confirm(\"Deseas anular esta compra?\")'>Anular</a>
            </div>";
    } elseif (auth_can_action('compra', 'view', $authSession)) {
        $content .= "
            <div class='card-buttons'>
                <a href='c-compra-view.php?param=" . urlencode($hashCompra) . "' class='btn-mini'>Ver detalle</a>
            </div>";
    }

    $content .= "
        </div>
    </div>";
}

if ($content == ""){
    $content .= "
    <div class='x-1' id='emptyStateStatic'>
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

    <style>
        .search-box { margin-bottom: 15px; }
        .search-input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-size: 12px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: bold; float: right; }
        .badge-confirmada { background: #dff0d8; color: #3c763d; }
        .badge-borrador { background: #fcf8e3; color: #8a6d3b; }
        .badge-anulada { background: #f2dede; color: #a94442; }
        .btn-mini { display: inline-block; padding: 8px 12px; background: #233251; color: white; border-radius: 6px; text-decoration: none; font-size: 11px; margin-right: 5px; margin-top: 10px; }
        .btn-danger { background: #d9534f; }
        .msg-ok { padding: 10px; background: #dff0d8; color: #3c763d; border-radius: 6px; margin-bottom: 15px; font-size: 12px; }
        .msg-error { padding: 10px; background: #f2dede; color: #a94442; border-radius: 6px; margin-bottom: 15px; font-size: 12px; }
        .empty-search { display: none; }
    </style>
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

        <?php if ($msgOk != "") { ?>
            <div class='msg-ok'><?php echo $msgOk; ?></div>
        <?php } ?>

        <?php if ($msgError != "") { ?>
            <div class='msg-error'><?php echo htmlspecialchars($msgError); ?></div>
        <?php } ?>

        <div class='search-box'>
            <input
                type='text'
                id='buscadorCompras'
                class='search-input'
                placeholder='Buscar por numero, proveedor, almacen, fecha o estado...'
                onkeyup='filtrarCompras()'
            />
        </div>

        <div id='contenedorCompras'>
            <?php echo $content ?>
        </div>

        <div class='x-1 empty-search' id='emptySearch'>
            <div class='card'>
                <div class='card-title'>Sin resultados</div>
                <div class='card-subtitle'>No se encontraron compras con ese criterio de busqueda.</div>
            </div>
        </div>

        <div class='clear'></div>
    </div>

    <?php if (auth_can_action('compra', 'manage', $authSession)): ?>
        <a href='c-compra-new.php'><div class='btn-add'></div></a>
    <?php endif; ?>
</div>

<script>
function filtrarCompras() {
    let input = document.getElementById('buscadorCompras');
    let filtro = input.value.toLowerCase().trim();
    let items = document.querySelectorAll('.item-compra');
    let visibles = 0;

    items.forEach(item => {
        let texto = item.getAttribute('data-search') || '';

        if (texto.indexOf(filtro) !== -1) {
            item.style.display = 'block';
            visibles++;
        } else {
            item.style.display = 'none';
        }
    });

    let emptySearch = document.getElementById('emptySearch');
    let emptyStatic = document.getElementById('emptyStateStatic');

    if (items.length > 0 && visibles === 0) {
        emptySearch.style.display = 'block';
    } else {
        emptySearch.style.display = 'none';
    }

    if (emptyStatic) {
        if (items.length === 0) {
            emptyStatic.style.display = 'block';
        } else {
            emptyStatic.style.display = 'none';
        }
    }
}
</script>

</body>
</html>
