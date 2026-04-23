<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;

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
        <div class='form-subtitle'><?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step'></div></div>
    </div>
    <!-- Body -->
    <div class='form-content'>
        <div class='title'>Menú Artículos</div>
        <!-- Menu -->
        <div class='menu'>
            <div class='menu-item'>
                <a href='../receta/c-receta-new.php'>
                    <div class='menu-item-pic ico-1'></div>
                    <div class='menu-item-title'>Recetas</div>
                    <div class='clear'></div>
                </a>
            </div>
            <div class='menu-item'>
                <a href='c-producto-list.php'>
                    <div class='menu-item-pic ico-2'></div>
                    <div class='menu-item-title'>Artículos</div>
                    <div class='clear'></div>
                </a>
            </div>
        </div>
        <div class='clear'></div>
    </div>
</div>

</body>
</html>