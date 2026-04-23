<?php
/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.1
 */

require_once "../control/c-csrf.php";

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv='content-type' content='text/html' charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    
    <title><?php echo $appTitle; ?></title>
        
    <!-- CSS -->
    <link rel='stylesheet' href='../view/css/main.css' />
    <link rel="manifest" href="manifest.json">
</head>

<body>

<div class='ctn-form'>

    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'><?php echo $appSubtitle ?></div>
        <div class='bar'><div class='step'></div></div>
    </div>

    <div class='form-content'>

        <!-- Mostrar error -->
        <?php if (isset($_SESSION['login_error'])): ?>
            <div style="color:red; margin-bottom:10px;">
                <?php echo htmlspecialchars($_SESSION['login_error']); ?>
            </div>
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>

        <form action='c-auth.php' method='POST'>

            <!-- CSRF -->
            <?php echo csrf_input(); ?>

            <div class='x-2'>                
                <input type='text' name='user' placeholder="Ingrese su nombre de usuario" required>
                <div class='label'>Username</div>
            </div>

            <div class='x-2'>                
                <input type='password' name='pass' placeholder="Ingrese su contrasena" required>
                <div class='label'>Contrasena</div>
            </div>

            <div class='x-1'>                
                <input type='submit' value='Ingresar'>
            </div>

        </form>

        <div class='clear'></div>
    </div>
</div>

</body>
</html>
