<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;
$authSession = function_exists('auth_user') ? auth_user() : null;

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
        <div class='form-subtitle'><?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step'></div></div>
    </div>

    <div class='form-content'>
        <div class='title'>Menu Compras</div>
        <div class='menu'>
            <?php if (auth_can_action('compra', 'manage', $authSession)): ?>
                <div class='menu-item'>
                    <a href='c-compra-new.php'>
                        <div class='menu-item-pic ico-1'></div>
                        <div class='menu-item-title'>Nueva Compra</div>
                        <div class='clear'></div>
                    </a>
                </div>
            <?php endif; ?>

            <?php if (auth_can_action('compra', 'view', $authSession)): ?>
                <div class='menu-item'>
                    <a href='c-compra-list.php'>
                        <div class='menu-item-pic ico-2'></div>
                        <div class='menu-item-title'>Lista de Compras</div>
                        <div class='clear'></div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if (auth_can_action('compra', 'view', $authSession)): ?>
                <div class='menu-item'>
                    <a href='../ppresentacion/c-ppresentacion-list.php'>
                        <div class='menu-item-pic ico-2'></div>
                        <div class='menu-item-title'>Lista de Presentacion de Productos</div>
                        <div class='clear'></div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
        <div class='clear'></div>
    </div>
</div>

</body>
</html>
