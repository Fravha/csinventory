<?php
/**
 * @author      Francisco Bailaba
 * @company     Creative Spot
 * @version     1.0 - Historial de Producción
 */
$nomUsuario = $oUsuario->nombre;
$content = "";

if (empty($listaProducciones)) {
    $content = "<div class='x-1' style='text-align:center; padding:50px; color:#999;'>No hay registros de producción aún.</div>";
} else {
    foreach($listaProducciones as $p) {
        // Ahora usamos -> porque $p es un objeto de la clase Produccion
        $fecha = date("d/m/Y H:i", strtotime($p->fecha_produccion)); 
        
        $content .= "
        <div class='x-1 item-produccion'>
            <div class='card' style='border-left: 5px solid #5cb85c;'>
                <div class='card-title'>
                    " . $p->nomProductoFinal . " 
                    <span class='badge' style='float:right; background:#233251; color:white;'>#" . $p->idProduccion . "</span>
                </div>
                
                <div class='card-subtitle' style='color:#777; font-size:11px;'>
                    Receta: " . $p->nomReceta . " | Usuario: " . $p->nomUsuario . "
                </div>
                
                <div class='separator'></div>
                
                <div class='x-3'>
                    <div class='data-label'>Cantidad</div>
                    <div class='data-value' style='font-weight:bold; color:#333;'>" . $p->cantidad_producida . " uds.</div>
                </div>
                
                <div class='x-3'>
                    <div class='data-label'>Costo Lote</div>
                    <div class='data-value' style='color:#5cb85c;'>" . number_format($p->costo_total_lote, 2) . " Bs.</div>
                </div>

                <div class='x-3'>
                    <div class='data-label'>Fecha</div>
                    <div class='data-value' style='font-size:11px;'>" . $fecha . "</div>
                </div>
                <div class='clear'></div>
            </div>
        </div>";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title><?php echo $appTitle; ?></title>
    <link rel='stylesheet' href='../../view/css/main.css' />
</head>

<body>
<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'>Historial de Producción</div>
        <a href='../c-panel.php'><div class='btn-back'></div></a>
    </div>
    
    <div class='form-content'>
        <div class='title' style='margin-bottom:20px;'><?php echo $title ?></div>
        
        <div class='x-1' style='margin-bottom:20px;'>
            <a href='c-produccion-new.php'>
                <button class='btn-action' style='background:#233251; color:white; border:none; padding:10px; width:100%; cursor:pointer; border-radius:5px;'>
                    + NUEVA ORDEN DE PRODUCCIÓN
                </button>
            </a>
        </div>

        <div id='contenedorProduccion'>
            <?php echo $content ?>
        </div>
    </div>
</div>
</body>
</html>