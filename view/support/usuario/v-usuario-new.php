<?php

$nomUsuario = $oUsuario->nombre;

function support_selected($value, $expected)
{
    return (string)$value === (string)$expected ? " selected" : "";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    <title><?php echo $appTitle; ?></title>
    <link rel="stylesheet" href="../../view/css/main.css">
</head>
<body>
    <div class="ctn-form">
        <div class='form-header'>
            <div class='ctn-icon'><div class='icon'></div></div>
            <div class='form-title'><?php echo $appTitle; ?></div>
            <div class='form-subtitle'>Admin: <?php echo htmlspecialchars($nomUsuario); ?></div>
            <div class='bar'><div class='step'></div></div>
            <a href='../support/c-support-panel.php'><div class='btn-back'></div></a>
        </div>

        <div class="form-content">
            <div class='title'>Crear Usuario</div>

            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'ok'): ?>
                <div style="color:green; margin-bottom:10px;">Usuario registrado correctamente.</div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div style="color:red; margin-bottom:10px;">
                    <?php
                        if ($_GET['error'] === 'required') {
                            echo 'Debes completar todos los campos.';
                        } elseif ($_GET['error'] === 'save') {
                            echo 'No se pudo registrar el usuario.';
                        } elseif ($_GET['error'] === 'csrf') {
                            echo 'Token CSRF invalido.';
                        } else {
                            echo 'Ocurrio un error.';
                        }
                    ?>
                </div>
            <?php endif; ?>

            <form action="c-usuario.php" method="POST">
                <?php echo csrf_input(); ?>

                <div class='x-1'>
                    <input type="text" name="nombre" placeholder="Nombre completo" required>
                    <div class='label'>Nombre</div>
                </div>

                <div class='x-1'>
                    <input type="text" name="username" placeholder="Nombre de usuario" required>
                    <div class='label'>Username</div>
                </div>

                <div class='x-1'>
                    <input type="password" name="pswd" placeholder="Contrasena temporal" required>
                    <div class='label'>Contrasena</div>
                </div>

                <div class='x-1'>
                    <select name="idPerfil" required>
                        <option value="">Selecciona un perfil</option>
                        <?php foreach ($listaPerfiles as $perfil): ?>
                            <?php if (isset($perfil->estado) && $perfil->estado !== 'Activo') { continue; } ?>
                            <option value="<?php echo (int)$perfil->idPerfil; ?>"<?php echo support_selected($perfil->idPerfil, 2); ?>>
                                <?php echo htmlspecialchars($perfil->nombre); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class='label'>Perfil</div>
                </div>

                <div class='x-1'>
                    <input type="submit" value="Registrar Usuario">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
