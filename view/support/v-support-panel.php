<?php

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
        <div class='form-subtitle'><?php echo htmlspecialchars($nomUsuario); ?></div>
        <div class='bar'><div class='step'></div></div>
        <a href='../c-panel.php'><div class='btn-back'></div></a>
    </div>

    <div class='form-content'>
        <div class='title'>Panel de Soporte</div>

        <?php if (isset($_SESSION['auth_error'])): ?>
            <div style="color:red; margin-bottom:10px;">
                <?php echo htmlspecialchars($_SESSION['auth_error']); ?>
            </div>
            <?php unset($_SESSION['auth_error']); ?>
        <?php endif; ?>

        <div class='menu'>
            <?php if (auth_can_action('support', 'manage', $authSession)): ?>
                <div class='menu-item'>
                    <a href='../usuario/c-usuario-new.php'>
                        <div class='menu-item-pic ico-1'></div>
                        <div class='menu-item-title'>Crear Usuario</div>
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
