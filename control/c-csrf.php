<?php
/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

require_once __DIR__ . "/auth-session.php";

auth_start_session();

function csrf_token()
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function csrf_input()
{
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
}

function csrf_verify()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $sessionToken = isset($_SESSION['_csrf']) ? $_SESSION['_csrf'] : '';
    $postedToken = isset($_POST['_csrf']) ? $_POST['_csrf'] : '';

    if (!$sessionToken || !$postedToken || !hash_equals($sessionToken, $postedToken)) {
        $_SESSION['login_error'] = 'Token CSRF invalido.';
        header('Location: ../index.php');
        exit;
    }
}

?>
