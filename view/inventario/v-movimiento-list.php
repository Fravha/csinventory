<?php
/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.2
 */

$nomUsuario = $oUsuario->nombre;
$title = "Kardex de Movimientos";

// Agregamos el buscador justo debajo del menú de acciones
$buscador = "
<div class='x-1' style='margin-bottom:15px;'>
    <input type='text' id='txtBuscar' placeholder='Buscar producto o motivo...' 
           style='width:100%; padding:12px; border-radius:8px; border:1px solid #ddd; font-family:arial; outline:none;' 
           onkeyup='filtrarMovimientos()'>
</div>";

$menuAcciones = "
<div class='x-1' style='margin-bottom:10px;'>
    <div class='ctn-actions'>
        <a href='../compra/c-compra-new.php' class='btn-action' style='background:#5cb85c;'>+ Compra</a>
        <a href='c-baja-new.php' class='btn-action' style='background:#d9534f;'>! Baja/Merma</a>
    </div>
</div>";

$content = "";
foreach($listaMovimientos as $oMov){
    $tipoMov = $oMov->tipo; 
    $classBadge = "bg-ajuste";
    $simbolo = "";

    if($tipoMov == 'Entrada'){ $classBadge = "bg-entrada"; $simbolo = "+"; } 
    elseif($tipoMov == 'Salida'){ $classBadge = "bg-salida"; $simbolo = "-"; }

    // Agregamos la clase 'item-movimiento' para el filtro de JS
    $content .= "
    <div class='x-1 item-movimiento'>
        <div class='card' style='height:auto;'>
            <div class='card-title'>
                Mov. #" . $oMov->idMovimiento . "
                <span class='badge " . $classBadge . "' style='float:right;'>" . $tipoMov . "</span>
            </div>
            
            <div class='card-subtitle search-target' style='margin-top:5px;'>" . $oMov->nomProducto . "</div>
            <div class='text' style='font-size:11px; color:#777;'>" . $oMov->FechaHora . " | " . $oMov->nomAlmacen . "</div>
            
            <div class='separator'></div>
            
            <div class='x-2'>
                <div class='data-label'>Cantidad</div>
                <div class='data-value' style='color:" . ($simbolo == '-' ? '#d9534f' : '#5cb85c') . ";'>
                    " . $simbolo . $oMov->Cantidad . "
                </div>
            </div>
            
            <div class='x-2'>
                <div class='data-label'>Referencia / Motivo</div>
                <div class='data-value search-target' style='font-size:12px;'>" . $oMov->Motivo . "</div>
            </div>
            
            <div class='clear'></div>
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

<body>
<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'>Sesión: <?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step' style='width:100%;'></div></div>        
        <a href='../c-panel.php'><div class='btn-back'></div></a>
    </div>
    
    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>
        
        <?php echo $menuAcciones ?>
        <?php echo $buscador ?>
        
        <div id='contenedorMovimientos'>
            <?php echo $content ?>
        </div>
        
        <div class='clear'></div>
    </div>
</div>

<script>
/**
 * Filtro de búsqueda en tiempo real
 */
function filtrarMovimientos() {
    let input = document.getElementById('txtBuscar').value.toLowerCase();
    let items = document.getElementsByClassName('item-movimiento');

    for (let i = 0; i < items.length; i++) {
        // Buscamos dentro de los elementos con clase 'search-target' (Producto y Motivo)
        let textoCard = items[i].innerText.toLowerCase();
        
        if (textoCard.includes(input)) {
            items[i].style.display = ""; // Mostrar
        } else {
            items[i].style.display = "none"; // Ocultar
        }
    }
}
</script>

</body>
</html>