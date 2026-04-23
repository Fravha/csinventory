<?php

require_once __DIR__ . "/auth-guard.php";

function authz_action_roles()
{
    return [
        'dashboard.view' => [1, 2],

        'almacen.view' => [1],
        'almacen.manage' => [1],

        'producto.view' => [1],
        'producto.manage' => [1],

        'receta.view' => [1],
        'receta.manage' => [1],

        'compra.view' => [1, 2],
        'compra.manage' => [1, 2],

        'inventario.view' => [1, 2],
        'inventario.manage' => [1],

        'movimiento.view' => [1, 2],
        'movimiento.manage' => [1],

        'produccion.view' => [1, 2],
        'produccion.manage' => [1, 2],

        'support.view' => [1],
        'support.manage' => [1],
    ];
}

function auth_role_id($authUser = null)
{
    if ($authUser === null) {
        $authUser = auth_user();
    }

    if (!$authUser || !array_key_exists('role_id', $authUser)) {
        return null;
    }

    return (int)$authUser['role_id'];
}

function auth_can_action($resource, $action = 'view', $authUser = null)
{
    $rolesByAction = authz_action_roles();
    $roleId = auth_role_id($authUser);
    $key = $resource . '.' . $action;

    if ($roleId === null || !isset($rolesByAction[$key])) {
        return false;
    }

    return in_array($roleId, $rolesByAction[$key], true);
}

function auth_can_module($module, $authUser = null)
{
    return auth_can_action($module, 'view', $authUser)
        || auth_can_action($module, 'manage', $authUser);
}

function auth_forbidden($redirect = "../c-panel.php", $message = "No tienes permisos para acceder a este modulo.")
{
    auth_start_session();
    $_SESSION['auth_error'] = $message;
    header("Location: " . $redirect);
    exit;
}

function auth_require_action($resource, $action = 'view', $redirect = "../c-panel.php")
{
    $authUser = auth_require_login($redirect);

    if (!auth_can_action($resource, $action, $authUser)) {
        auth_forbidden($redirect, "No tienes permisos para " . $action . " en " . $resource . ".");
    }

    return $authUser;
}

function auth_require_module($module, $redirect = "../c-panel.php")
{
    return auth_require_action($module, 'view', $redirect);
}

?>
