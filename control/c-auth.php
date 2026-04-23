<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.1
 */

require_once __DIR__ . "/auth-session.php";
require_once __DIR__ . "/c-csrf.php";
require_once __DIR__ . "/../model/RN_Usuario.php";

auth_start_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../index.php");
    exit;
}

csrf_verify();

$user = isset($_POST['user']) ? trim($_POST['user']) : '';
$pass = isset($_POST['pass']) ? (string)$_POST['pass'] : '';

if ($user === '' || $pass === '') {
    $_SESSION['login_error'] = "Debes completar usuario y contrasena.";
    header("Location: ../index.php");
    exit;
}

try {
    $oRN_Usuario = new RN_Usuario();
    $hashUsuario = $oRN_Usuario->Verificar($user, $pass);

    if ($hashUsuario !== false) {
        $oUsuario = $oRN_Usuario->GetData($hashUsuario);

        if (!$oUsuario) {
            throw new RuntimeException('No se pudo cargar el usuario autenticado.');
        }

        auth_login_user($oUsuario);
        unset($_SESSION['login_error']);
    } else {
        $_SESSION['login_error'] = "Usuario o contrasena incorrectos.";
    }
} catch (Throwable $e) {
    $_SESSION['login_error'] = "Ocurrio un error al iniciar sesion.";
}

header("Location: ../index.php");
exit;

?>
