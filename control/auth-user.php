<?php

require_once __DIR__ . "/authz.php";
require_once __DIR__ . "/../model/RN_Usuario.php";

function auth_require_domain_user($redirect = "../index.php", $module = null, $action = 'view')
{
    if ($module === null) {
        $authUser = auth_require_login($redirect);
    } else {
        $authUser = auth_require_action($module, $action, $redirect);
    }

    $userRepo = new RN_Usuario();
    $oUsuario = $userRepo->GetData($authUser['user_hash']);

    if (!$oUsuario) {
        auth_logout_user();
        header("Location: " . $redirect);
        exit;
    }

    return $oUsuario;
}

?>
