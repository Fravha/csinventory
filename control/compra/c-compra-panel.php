<?php
/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

require_once __DIR__ . "/../auth-user.php";
require_once "../../model/RN_Producto.php";
require_once "../../model/RN_Almacen.php";
require_once "../config-app.php";

$oUsuario = auth_require_domain_user("../c-panel.php", "compra", "view");
$oRN_Producto = new RN_Producto();
$oRN_Almacen = new RN_Almacen();

include_once "../../view/compra/v-compra-panel.php";

?>