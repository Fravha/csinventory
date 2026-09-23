<?php

require_once __DIR__ . "/../authz.php";
require_once "../../model/RN_Compra.php";

auth_require_action("compra", "manage", "../c-panel.php");

$hashCompra = isset($_GET["param"]) ? trim($_GET["param"]) : "";
if ($hashCompra === "") {
    header("Location: c-compra-list.php?error=Compra no encontrada.");
    exit();
}

$oRN_Compra = new RN_Compra();
$res = $oRN_Compra->Delete($hashCompra);

if ($res) {
    header("Location: c-compra-list.php?msg=deleted");
    exit();
}

header("Location: c-compra-list.php?error=No se pudo anular la compra.");
exit();
?>
