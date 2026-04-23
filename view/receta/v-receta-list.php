<?php
$nomUsuario = $oUsuario->nombre;
$authSession = function_exists('auth_user') ? auth_user() : null;

$buscador = "
<div class='x-1' style='margin-bottom:15px;'>
    <input type='text' id='txtBuscar' placeholder='Buscar receta o producto...' 
           style='width:100%; padding:12px; border-radius:8px; border:1px solid #ddd; font-family:arial; outline:none;' 
           onkeyup='filtrarRecetas()'>
</div>";

$menuAcciones = "";
if (auth_can_action('receta', 'manage', $authSession)) {
    $menuAcciones = "
<div class='x-1' style='margin-bottom:10px;'>
    <div class='ctn-actions'>
        <a href='c-receta-new.php' class='btn-action' style='background:#5cb85c;'>+ Nueva Receta</a>
    </div>
</div>";
}

$content = "";
foreach($listaRecetas as $oRec){
    $estadoTexto = ($oRec->estado == "0") ? "Activo" : "Inactivo";
    $classBadge = ($oRec->estado == "0") ? "bg-entrada" : "bg-salida";

    $content .= "
    <div class='x-1 item-receta'>
        <div class='card' style='height:auto;'>
            
            <div class='card-title'>
                Receta #" . $oRec->idReceta . "
                <span class='badge " . $classBadge . "' style='float:right;'>" . $estadoTexto . "</span>
            </div>
            
            <div class='card-subtitle search-target' style='margin-top:5px;'>
                " . $oRec->nombre_servicio . "
            </div>

            <div class='text search-target' style='font-size:11px; color:#777;'>
                Producto Final: " . $oRec->nomProductoFinal . "
            </div>

            <div class='separator'></div>

            <div class='x-2'>
                <div class='data-label'>Costo Operativo</div>
                <div class='data-value' style='color:#5bc0de;'>
                    Bs. " . number_format($oRec->costo_operativo_sugerido, 2) . "
                </div>
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
        <div class='form-subtitle'>Sesion: <?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step' style='width:100%;'></div></div>        
        <a href='../c-panel.php'><div class='btn-back'></div></a>
    </div>
    
    <div class='form-content'>
        
        <div class='title'><?php echo $title ?></div>
        
        <?php echo $menuAcciones ?>
        <?php echo $buscador ?>
        
        <div id='contenedorRecetas'>
            <?php echo $content ?>
        </div>
        
        <div class='clear'></div>
    </div>
</div>

<script>
function filtrarRecetas() {
    let input = document.getElementById('txtBuscar').value.toLowerCase();
    let items = document.getElementsByClassName('item-receta');

    for (let i = 0; i < items.length; i++) {
        let texto = items[i].innerText.toLowerCase();
        
        if (texto.includes(input)) {
            items[i].style.display = "";
        } else {
            items[i].style.display = "none";
        }
    }
}
</script>

</body>
</html>
