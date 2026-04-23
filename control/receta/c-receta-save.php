<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

require_once __DIR__ . "/../authz.php";
require_once "../../model/RN_Receta.php";
require_once "../../model/data/Receta.php";
require_once "../../model/data/RecetaDetalle.php";
require_once "../config-app.php";

auth_require_action("receta", "manage", "../c-panel.php");

if ($_POST) {
    $oRN_Receta = new RN_Receta();

    $nombreServicio = $_POST['nombre_servicio'];
    $idProductoFinal = $_POST['idProductoFinal'];
    $costoSugerido = $_POST['costo_operativo_sugerido'];

    $oReceta = new Receta(0, '', $nombreServicio, $costoSugerido, 'Activo', null, $idProductoFinal);

    $insumosIds = $_POST['insumos'];
    $cantidades = $_POST['cantidades'];
    $listaDetalles = array();

    if (is_array($insumosIds)) {
        for ($i = 0; $i < count($insumosIds); $i++) {
            if (!empty($insumosIds[$i]) && $cantidades[$i] > 0) {
                $listaDetalles[] = new RecetaDetalle(
                    0,
                    0,
                    $insumosIds[$i],
                    $cantidades[$i]
                );
            }
        }
    }

    if (count($listaDetalles) > 0) {
        $resHash = $oRN_Receta->Save($oReceta, $listaDetalles);

        if ($resHash) {
            header("Location: c-receta-list.php?msg=ok");
        } else {
            header("Location: c-receta-new.php?error=save");
        }
    } else {
        header("Location: c-receta-new.php?error=no_insumos");
    }
}
?>
