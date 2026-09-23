<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.1
 */

$nomUsuario = isset($oUsuario->nombre) ? $oUsuario->nombre : 'Usuario';
$authSession = function_exists('auth_user') ? auth_user() : null;

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv='content-type' content='text/html' charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    
    <title><?php echo $appTitle; ?></title>
        
    <link rel='stylesheet' href='../view/css/main.css' />
    <link rel="manifest" href="../manifest.json">

    <style>
        .section-title {
            margin: 15px 0 10px 0;
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }

        .logout-box {
            margin-top: 20px;
            text-align: center;
        }

        .logout-btn {
            display: inline-block;
            padding: 10px 18px;
            background: #b71c1c;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .logout-btn:hover {
            background: #8f1515;
        }
    </style>
</head>

<body>

<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'>Bienvenido <?php echo htmlspecialchars($nomUsuario); ?></div>
        <div class='bar'><div class='step'></div></div>
    </div>

    <div class='form-content'>
        <div class='title'>Panel principal</div>

        <?php if (isset($_SESSION['auth_error'])): ?>
            <div style="color:red; margin-bottom:10px;">
                <?php echo htmlspecialchars($_SESSION['auth_error']); ?>
            </div>
            <?php unset($_SESSION['auth_error']); ?>
        <?php endif; ?>

        <?php if (auth_can_module('almacen', $authSession) || auth_can_module('producto', $authSession) || auth_can_module('receta', $authSession)): ?>
            <div class='section-title'>Maestros</div>
            <div class='menu'>
                <?php if (auth_can_module('almacen', $authSession)): ?>
                    <div class='menu-item'>
                        <a href='almacen/c-almacen-gestion.php'>
                            <div class='menu-item-pic ico-1'></div>
                            <div class='menu-item-title'>Almacenes</div>
                            <div class='clear'></div>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (auth_can_module('producto', $authSession)): ?>
                    <div class='menu-item'>
                        <a href='producto/c-producto-panel.php'>
                            <div class='menu-item-pic ico-2'></div>
                            <div class='menu-item-title'>Articulos</div>
                            <div class='clear'></div>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (auth_can_module('receta', $authSession)): ?>
                    <div class='menu-item'>
                        <a href='receta/c-receta-list.php'>
                            <div class='menu-item-pic ico-4'></div>
                            <div class='menu-item-title'>Recetas</div>
                            <div class='clear'></div>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class='clear'></div>
        <?php endif; ?>

        <?php if (auth_can_module('compra', $authSession) || auth_can_module('inventario', $authSession) || auth_can_module('produccion', $authSession)): ?>
            <div class='section-title'>Operaciones</div>
            <div class='menu'>
                <?php if (auth_can_module('compra', $authSession)): ?>
                    <div class='menu-item'>
                        <a href='compra/c-compra-panel.php'>
                            <div class='menu-item-pic ico-3'></div>
                            <div class='menu-item-title'>Compras</div>
                            <div class='clear'></div>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (auth_can_module('inventario', $authSession)): ?>
                    <div class='menu-item'>
                        <a href='inventario/c-inventario-panel.php'>
                            <div class='menu-item-pic ico-3'></div>
                            <div class='menu-item-title'>Inventario</div>
                            <div class='clear'></div>
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (auth_can_module('produccion', $authSession)): ?>
                    <div class='menu-item'>
                        <a href='produccion/c-produccion-panel.php'>
                            <div class='menu-item-pic ico-4'></div>
                            <div class='menu-item-title'>Produccion</div>
                            <div class='clear'></div>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class='clear'></div>
        <?php endif; ?>

        <?php if (auth_can_module('support', $authSession) && file_exists(__DIR__ . '/../control/support/c-support-panel.php')): ?>
            <div class='section-title'>Administracion</div>
            <div class='menu'>
                <div class='menu-item'>
                    <a href='support/c-support-panel.php'>
                        <div class='menu-item-pic ico-1'></div>
                        <div class='menu-item-title'>Soporte</div>
                        <div class='clear'></div>
                    </a>
                </div>
            </div>

            <div class='clear'></div>
        <?php endif; ?>

        <div class='logout-box'>
            <a class='logout-btn' href='c-logout.php'>Cerrar sesion</a>
        </div>

        <div class='clear'></div>
    </div>
</div>

</body>
</html>
