<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Perfil.php";
require_once "../config-app.php";
require_once "../c-csrf.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "support", "manage");
$oRN_Perfil = new RN_Perfil();
$listaPerfiles = $oRN_Perfil->GetList();

include_once "../../view/support/usuario/v-usuario-new.php";

?>
