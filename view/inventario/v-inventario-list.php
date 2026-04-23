<?php

$nomUsuario = $oUsuario->nombre;

// Inicializar variable
$menuAlmacenes = "";

// Generar botones de almacenes dinámicamente
$menuAlmacenes .= "<button class='btn-filter active' onclick='filtrarAlmacen(\"todos\", this)'>Todos</button>";

foreach($listaAlmacenes as $alm) {
    $menuAlmacenes .= "<button class='btn-filter' onclick='filtrarAlmacen(\"".trim($alm->nombre)."\", this)'>".$alm->nombre."</button>";
}

$content = "";

foreach($listaInventario as $inv) {
    $esCritico = ($inv->stock_actual <= $inv->punto_critico);

    $colorAlerta = "#d9534f";
    $colorOk = "#5cb85c";

    $estiloCard = $esCritico ? "border: 2px solid $colorAlerta; background-color: #fffafa;" : "border: 1px solid #eee;";
    $colorTextoStock = $esCritico ? $colorAlerta : $colorOk;

    $content .= "
    <div class='x-1 item-inventario' data-almacen='".trim($inv->nomAlmacen)."'>
        <div class='card' style='height:auto; $estiloCard'>
            <div class='card-title'>
                ".$inv->nomProducto."
                <span class='badge' style='float:right; background:#eee; color:#333;'>".$inv->nomAlmacen."</span>
            </div>

            <div class='card-subtitle' style='color:#777; font-size:11px;'>
                Unidad: ".$inv->unidad_medida."
            </div>

            <div class='separator'></div>

            <div class='x-2'>
                <div class='data-label'>Stock Actual</div>
                <div class='data-value' style='color:".$colorTextoStock."; font-size:18px; font-weight:bold;'>
                    ".number_format($inv->stock_actual, 2)." <small style='font-size:10px;'>".$inv->unidad_medida."</small>
                </div>
            </div>

            <div class='x-2'>
                <div class='data-label'>Punto Crítico</div>
                <div class='data-value' style='color:#999;'>".number_format($inv->punto_critico, 2)."</div>
            </div>

            <div class='clear'></div>";

    if($esCritico) {
        $content .= "
            <div style='margin-top:10px; padding:5px; background:$colorAlerta; color:white; text-align:center; font-size:10px; border-radius:3px; font-weight:bold;'>
                ALERTA: REABASTECER INVENTARIO
            </div>";
    }

    $content .= "
        </div>
    </div>";
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title><?php echo $appTitle; ?></title>
    <link rel='stylesheet' href='../../view/css/main.css' />
    <style>
        .btn-filter { background: #eee; border: none; padding: 8px 15px; border-radius: 20px; cursor: pointer; transition: 0.3s; }
        .btn-filter.active { background: #233251; color: white; }
        .item-inventario { transition: all 0.3s ease; }
    </style>
</head>

<body>
<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'>Gestión de Inventarios</div>
        <a href='../c-panel.php'><div class='btn-back'></div></a>
    </div>
    
    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>
        
        <?php echo $menuAlmacenes ?>
        
        <div id='contenedorInventario'>
            <?php echo $content ?>
        </div>
    </div>
</div>

<script>
function filtrarAlmacen(nombreAlmacen, elemento) {
    // 1. Quitar clase active de todos los botones
    let botones = document.querySelectorAll('.btn-filter');
    botones.forEach(btn => btn.classList.remove('active'));
    
    // 2. Poner active al botón presionado
    elemento.classList.add('active');

    // 3. Filtrar
    let items = document.querySelectorAll('.item-inventario');
    items.forEach(item => {
        let almacenCard = item.getAttribute('data-almacen');
        if (nombreAlmacen === 'todos') {
            item.style.display = 'block';
        } else {
            // Comparamos ignorando espacios extra
            if (almacenCard.trim() === nombreAlmacen.trim()) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        }
    });
}
</script>
</body>
</html>