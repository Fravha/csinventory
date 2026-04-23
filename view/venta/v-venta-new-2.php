<?php

/**
 * @author		Miguel Angel Macias Burgos
 * @company 	WBT
 * @copyright 	2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;

$title = "Nueva Venta";

$nomProducto = $oProducto->nombre;
$imagen = $oProducto->imagen;
$precio = $oProducto->precio;

?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv='content-type' content='text/html' charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    
	<title><?php echo $appTitle; ?></title>
        
    <!-- CSS -->
    <link rel='stylesheet' href='../../view/css/main.css' />
</head>

<body>

<!-- Main Page -->
<div class='ctn-form'>
    <!-- Header -->
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'>Bienvenido <?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step'></div></div>        
        <a href='c-oferta-list.php'><div class='btn-back'></div></a>
    </div>
    <!-- Body -->
    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>
        
        <form action='c-venta-add.php' method='POST'>
            <input type='hidden' name='hash' value='<?php echo $hashProducto; ?>' />
            <div class='x-2'>
                <div class='card' style='height:auto;'>
                    <div class='card-title'><?php echo $nomProducto ?></div>
                    <div class='card-subtitle'><?php echo $precio ?> Bs</div>
                    <img src='../../view/img/producto/<?php echo $imagen ?>' alt='<?php echo $nomProducto ?>' style='width:120px' />            
                </div>
            </div>
            <div class='x-2'>
                <input type='number' name='cantidad' placeholder='Cantidad' required />
                <div class='label'>Cantidad</div>
            </div>
            <div class='x-2'>
                <input type='submit' value='Adicionar' />
            </div>
        </form>

        <div class='clear'></div>
    </div>

</div>

</body>
</html>