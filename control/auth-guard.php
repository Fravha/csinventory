<?php
/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

require_once __DIR__ . "/auth-session.php";

function auth_redirect_to_login($redirect = "../index.php")
{
    header("Location: " . $redirect);
    exit;
}

function auth_require_login($redirect = "../index.php")
{
    $user = auth_user();

    if ($user === null) {
        auth_redirect_to_login($redirect);
    }

    return $user;
}

?>
