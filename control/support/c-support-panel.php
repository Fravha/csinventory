<?php

require_once __DIR__ . "/../auth-user.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "support", "view");

include_once "../../view/support/v-support-panel.php";

?>
