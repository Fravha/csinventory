<?php
/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.1
 */
$nomUsuario = $oUsuario->nombre;
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
        <div class='bar'><div class='step' style='width:30%'></div></div>        
        <a href='c-producto-list.php'><div class='btn-back'></div></a>
    </div>

    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>
        
        <form action='c-producto-new.php' method='POST'>
            
            <div class='x-1'>
                <div class='data-label'>Nombre del Producto / Insumo</div>
                <input type='text' name='nombre' placeholder='Ej: Polera de Algodón Blanca' required class='input-form' />
            </div>

            <div class='x-2'>
                <div class='data-label'>SKU / Código Interno</div>
                <input type='text' name='sku' placeholder='POL-BLA-001' required class='input-form' />
            </div>

            <div class='x-2'>
                <div class='data-label'>Unidad de Medida</div>
                <select name='unidad_medida' class='input-form' required>
                    <option value='Unidad'>Unidad (pza)</option>
                    <option value='Litros'>Litros (lt)</option>
                    <option value='Metros'>Metros (m)</option>
                    <option value='Hojas'>Hojas (pág)</option>
                </select>
            </div>

            <div class='x-1'>
                <div class='data-label'>Tipo de Ítem</div>
                <select name='tipo' class='input-form' required style='border: 2px solid #5bc0de;'>
                    <option value='Insumo'>Materia Prima / Insumo (Para recetas)</option>
                    <option value='Producto Final'>Producto Final (Para la venta)</option>
                </select>
                <p style='font-size: 10px; color: #777; margin-top: 5px;'>
                    * Los <b>Insumos</b> se compran, los <b>Productos</b> se fabrican mediante recetas.
                </p>
            </div>

            <div class='separator'></div>

            <div class='x-1'>
                <input type='submit' value='GUARDAR PRODUCTO' class='btn-action' style='background:#5cb85c; color:white; width:100%; border:none; cursor:pointer;' />
            </div>
        </form>

        <div class='clear'></div>
    </div>
</div>
</body>
</html>