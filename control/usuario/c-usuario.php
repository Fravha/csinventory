<?php
/**
 * @author       Francisco Bailaba
 * @company      Bylaba Projects
 * @copyright    2026
 * @version      1.3
 */

require_once __DIR__ . "/../authz.php";
require_once "../../model/RN_Usuario.php";
require_once "../../model/data/Usuario.php";
require_once "../c-csrf.php";

auth_require_action("support", "manage", "../c-panel.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: c-usuario-new.php");
    exit();
}

csrf_verify();

$oRN_Usuario = new RN_Usuario();

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['pswd']) ? (string)$_POST['pswd'] : '';
$idPerfil = isset($_POST['idPerfil']) ? (int)$_POST['idPerfil'] : 0;
$estado = "Activo";

if ($nombre === '' || $username === '' || $password === '' || $idPerfil < 1) {
    header("Location: c-usuario-new.php?error=required");
    exit();
}

$tempHash = sha1($username . microtime(true));

$oUsuario = new Usuario(
    0,
    $tempHash,
    $nombre,
    $username,
    $password,
    $idPerfil,
    $estado
);

$res = $oRN_Usuario->Save($oUsuario);

if ($res) {
    header("Location: c-usuario-new.php?msg=ok");
} else {
    header("Location: c-usuario-new.php?error=save");
}
exit();
?>
