<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

$nomUsuario = $oUsuario->nombre;
$title = "Gestión de Almacenes";
$titleList = "Lista de Almacenes";

if (!isset($modo)) {
    $modo = "new";
}

if (!isset($oAlmacenActual) || $oAlmacenActual == null) {
    $oAlmacenActual = new Almacen("", "", "", "", "REAL", "NORMAL", 1, 1);
}

$formAction = ($modo == "edit") ? "c-almacen-update.php" : "c-almacen-save.php";
$txtBoton = ($modo == "edit") ? "Actualizar Almacén" : "Crear Almacén";

$hashAlmacen = $oAlmacenActual->hashAlmacen;
$nombre = $oAlmacenActual->nombre;
$ubicacion = $oAlmacenActual->ubicacion;
$genera_alerta = $oAlmacenActual->genera_alerta;

$content = "";
$i = 0;

foreach($listaAlmacenes as $oAlmacen){
    $i++;

    $hashItem = $oAlmacen->hashAlmacen;
    $nombreItem = $oAlmacen->nombre;
    $ubicacionItem = $oAlmacen->ubicacion;
    $tipoItem = $oAlmacen->tipo_almacen;
    $subtipoItem = $oAlmacen->subtipo_almacen;
    $alertaItem = $oAlmacen->genera_alerta;

    $esProtegido = (
        $tipoItem == "VIRTUAL" &&
        in_array($subtipoItem, array("TRANSITO", "PT", "MERMA"))
    );

    $badgeSistema = $esProtegido
        ? "<span style='display:inline-block; padding:3px 8px; background:#233251; color:#fff; border-radius:12px; font-size:10px;'>Sistema</span>"
        : "<span style='display:inline-block; padding:3px 8px; background:#5cb85c; color:#fff; border-radius:12px; font-size:10px;'>Editable</span>";

    $acciones = "";

    if (!$esProtegido) {
        $acciones .= "
            <a href='c-almacen-gestion.php?mode=edit&param=".$hashItem."' class='btn-action'>Editar</a>
            <a href='c-almacen-delete.php?param=".$hashItem."' class='btn-action btn-danger' onclick='return confirm(\"¿Deseas eliminar este almacén?\")'>Eliminar</a>
        ";
    }

    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>Almacén #".$i."</div>
            <div class='card-subtitle'>".$nombreItem."</div>
            <div style='margin:8px 0;'>".$badgeSistema."</div>
            <div class='separator'></div>

            <div class='card-title'>Ubicación</div>
            <div class='card-text'>".$ubicacionItem."</div>

            <div class='separator'></div>

            <div class='card-title'>Tipo</div>
            <div class='card-text'>".$tipoItem."</div>

            <div class='card-title'>Subtipo</div>
            <div class='card-text'>".$subtipoItem."</div>

            <div class='card-title'>Genera alerta</div>
            <div class='card-text'>".($alertaItem == 1 ? "Sí" : "No")."</div>

            <div class='card-buttons'>
                ".$acciones."
            </div>

            <div class='clear'></div>
        </div>
    </div>";
}

if ($content == ""){
    $content .= "
    <div class='x-1'>
        <div class='card'>
            <div class='card-title'>¡Ups!</div>
            <div class='card-subtitle'>No hay almacenes registrados actualmente.</div>
        </div>
    </div>";
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv='content-type' content='text/html' charset='utf-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
    <title><?php echo $appTitle; ?></title>
    <link rel='stylesheet' href='../../view/css/main.css' />
    <style>
        .btn-main {
            display:inline-block;
            padding:10px 18px;
            background:#233251;
            color:white;
            border:none;
            border-radius:6px;
            cursor:pointer;
            text-decoration:none;
            font-size:12px;
        }
        .btn-light {
            display:inline-block;
            padding:10px 18px;
            background:#eee;
            color:#333;
            border:none;
            border-radius:6px;
            cursor:pointer;
            text-decoration:none;
            font-size:12px;
        }
        .btn-action {
            display:inline-block;
            padding:8px 12px;
            background:#233251;
            color:white;
            border-radius:6px;
            text-decoration:none;
            font-size:11px;
            margin-right:5px;
            margin-top:10px;
        }
        .btn-danger {
            background:#d9534f;
        }
        .input-control, .select-control {
            width:100%;
            padding:10px;
            border:1px solid #ddd;
            border-radius:6px;
            margin-top:5px;
            margin-bottom:10px;
            box-sizing:border-box;
        }
        .label-control {
            font-size:12px;
            color:#555;
            font-weight:bold;
        }
    </style>
</head>

<body>
<div class='ctn-form'>
    <div class='form-header'>
        <div class='ctn-icon'><div class='icon'></div></div>
        <div class='form-title'><?php echo $appTitle; ?></div>
        <div class='form-subtitle'><?php echo $nomUsuario ?></div>
        <div class='bar'><div class='step'></div></div>        
        <a href='../c-panel.php'><div class='btn-back'></div></a>
    </div>

    <div class='form-content'>
        <div class='title'><?php echo $title ?></div>

        <div class='x-1'>
            <div class='card'>
                <div class='card-title'><?php echo ($modo == "edit") ? "Editar Almacén" : "Nuevo Almacén"; ?></div>
                <div class='separator'></div>

                <form method='post' action='<?php echo $formAction; ?>'>
                    <input type='hidden' name='hashAlmacen' value='<?php echo $hashAlmacen; ?>' />

                    <div class='label-control'>Nombre</div>
                    <input type='text' name='nombre' class='input-control' value='<?php echo htmlspecialchars($nombre); ?>' required />

                    <div class='label-control'>Ubicación</div>
                    <input type='text' name='ubicacion' class='input-control' value='<?php echo htmlspecialchars($ubicacion); ?>' required />

                    <div class='label-control'>Genera alerta</div>
                    <select name='genera_alerta' class='select-control'>
                        <option value='1' <?php echo ($genera_alerta == 1 ? "selected" : ""); ?>>Sí</option>
                        <option value='0' <?php echo ($genera_alerta == 0 ? "selected" : ""); ?>>No</option>
                    </select>

                    <input type='hidden' name='tipo_almacen' value='REAL' />
                    <input type='hidden' name='subtipo_almacen' value='NORMAL' />

                    <button type='submit' class='btn-main'><?php echo $txtBoton; ?></button>
                    <a href='c-almacen-gestion.php' class='btn-light'>Limpiar</a>
                </form>
            </div>
        </div>

        <div class='title'><?php echo $titleList ?></div>

        <?php echo $content; ?>

        <div class='clear'></div>
    </div>
</div>
</body>
</html>