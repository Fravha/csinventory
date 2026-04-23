<?php

require_once __DIR__ . "/../authz.php";
require_once "../../model/RN_Produccion.php";

if ($_POST) {
    $authUser = auth_require_action("produccion", "manage", "../c-panel.php");
    $oRN = new RN_Produccion();

    $idReceta = isset($_POST['idReceta']) ? (int)$_POST['idReceta'] : 0;
    $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;
    $idAlmacenInsumos = isset($_POST['idAlmacenInsumos']) ? (int)$_POST['idAlmacenInsumos'] : 0;
    $idAlmacenDestino = isset($_POST['idAlmacenDestino']) ? (int)$_POST['idAlmacenDestino'] : 0;
    $idUsuario = (int)$authUser["user_id"];

    if ($cantidad <= 0) {
        header("Location: c-produccion-new.php?error=cantidad_invalida");
        exit();
    }

    try {
        $resultado = $oRN->RegistrarProduccion($idReceta, $cantidad, $idUsuario, $idAlmacenInsumos, $idAlmacenDestino);

        if ($resultado) {
            header("Location: c-produccion-list.php?msg=ok");
            exit();
        }

        header("Location: c-produccion-new.php?idReceta={$idReceta}&idAlmacen={$idAlmacenInsumos}&error=stock");
        exit();
    } catch (Throwable $e) {
        header("Location: c-produccion-new.php?idReceta={$idReceta}&idAlmacen={$idAlmacenInsumos}&error=save");
        exit();
    }
}

header("Location: c-produccion-new.php");
exit();
?>
