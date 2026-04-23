<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

function auth_is_https()
{
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return true;
    }

    if (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443) {
        return true;
    }

    return false;
}

function auth_start_session()
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $params = session_get_cookie_params();

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $params['path'] ?: '/',
        'domain' => $params['domain'] ?: '',
        'secure' => auth_is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

function auth_build_session_payload($user)
{
    return [
        'logged_in' => true,
        'user_hash' => isset($user->hashUsuario) ? (string)$user->hashUsuario : '',
        'user_id' => isset($user->idUsuario) ? (int)$user->idUsuario : 0,
        'role_id' => isset($user->idPerfil) ? (int)$user->idPerfil : 0,
        'username' => isset($user->username) ? (string)$user->username : '',
        'nombre' => isset($user->nombre) ? (string)$user->nombre : '',
        'login_at' => date('c'),
        'last_activity_at' => time(),
    ];
}

function auth_sync_legacy_session(array $auth)
{
    $_SESSION['Delux'] = [
        'Key' => base64_encode($auth['user_hash']),
        'Id' => base64_encode((string)$auth['user_id']),
        'Role' => base64_encode((string)$auth['role_id']),
        'Username' => base64_encode($auth['username']),
    ];
}

function auth_login_user($user)
{
    auth_start_session();
    session_regenerate_id(true);

    $auth = auth_build_session_payload($user);
    $_SESSION['auth'] = $auth;

    auth_sync_legacy_session($auth);
}

function auth_touch_session()
{
    if (isset($_SESSION['auth']) && is_array($_SESSION['auth'])) {
        $_SESSION['auth']['last_activity_at'] = time();
        auth_sync_legacy_session($_SESSION['auth']);
    }
}

function auth_user()
{
    auth_start_session();

    if (isset($_SESSION['auth']) && is_array($_SESSION['auth']) && !empty($_SESSION['auth']['logged_in'])) {
        auth_touch_session();
        return $_SESSION['auth'];
    }

    if (isset($_SESSION['Delux']) && is_array($_SESSION['Delux']) && !empty($_SESSION['Delux']['Key'])) {
        $userHash = base64_decode($_SESSION['Delux']['Key'], true);

        if ($userHash === false || $userHash === '') {
            return null;
        }

        $auth = [
            'logged_in' => true,
            'user_hash' => (string)$userHash,
            'user_id' => !empty($_SESSION['Delux']['Id']) ? (int)base64_decode($_SESSION['Delux']['Id'], true) : 0,
            'role_id' => !empty($_SESSION['Delux']['Role']) ? (int)base64_decode($_SESSION['Delux']['Role'], true) : 0,
            'username' => !empty($_SESSION['Delux']['Username']) ? (string)base64_decode($_SESSION['Delux']['Username'], true) : '',
            'nombre' => '',
            'login_at' => date('c'),
            'last_activity_at' => time(),
        ];

        $_SESSION['auth'] = $auth;
        auth_sync_legacy_session($auth);

        return $_SESSION['auth'];
    }

    return null;
}

function auth_is_logged_in()
{
    return auth_user() !== null;
}

function auth_logout_user()
{
    auth_start_session();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}

?>
