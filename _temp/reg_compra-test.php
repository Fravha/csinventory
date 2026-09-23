<?php

require_once "../model/RN_Compra.php";
require_once "../model/RN_Almacen.php";
require_once "../model/RN_Producto.php";
require_once "../model/data/Compra.php";
require_once "../model/data/CompraDetalle.php";

$oRN_Compra = new RN_Compra();
$oRN_Almacen = new RN_Almacen();
$oRN_Producto = new RN_Producto();

$listaAlmacenes = $oRN_Almacen->GetList();
$listaProductos = $oRN_Producto->GetList();

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $numero_compra = isset($_POST["numero_compra"]) ? trim($_POST["numero_compra"]) : "";
        $proveedor_nombre = isset($_POST["proveedor_nombre"]) ? trim($_POST["proveedor_nombre"]) : "";
        $idAlmacen = isset($_POST["idAlmacen"]) ? (int)$_POST["idAlmacen"] : 0;
        $fecha_compra = isset($_POST["fecha_compra"]) ? trim($_POST["fecha_compra"]) : date("Y-m-d H:i:s");
        $observacion = isset($_POST["observacion"]) ? trim($_POST["observacion"]) : "";
        $estado = "CONFIRMADA";

        $idProducto = isset($_POST["idProducto"]) ? $_POST["idProducto"] : array();
        $lote = isset($_POST["lote"]) ? $_POST["lote"] : array();
        $cantidad = isset($_POST["cantidad"]) ? $_POST["cantidad"] : array();
        $precio = isset($_POST["precio"]) ? $_POST["precio"] : array();

        $listaDetalle = array();
        $subtotalCompra = 0;

        for ($i = 0; $i < count($idProducto); $i++) {
            $idProd = (int)$idProducto[$i];
            $lot = trim($lote[$i]);
            $cant = (float)$cantidad[$i];
            $prec = (float)$precio[$i];

            if ($idProd > 0 && $cant > 0 && $prec >= 0) {
                $subtotalDetalle = $cant * $prec;
                $subtotalCompra += $subtotalDetalle;

                $listaDetalle[] = new CompraDetalle(
                    0,
                    "",
                    0,
                    $idProd,
                    $lot,
                    $cant,
                    $prec,
                    $subtotalDetalle,
                    null,
                    null
                );
            }
        }

        if ($proveedor_nombre == "") {
            throw new Exception("Debes ingresar el nombre del proveedor.");
        }

        if ($idAlmacen <= 0) {
            throw new Exception("Debes seleccionar un almacén.");
        }

        if (count($listaDetalle) == 0) {
            throw new Exception("Debes agregar al menos un producto válido.");
        }

        $oCompra = new Compra(
            0,
            "",
            $numero_compra,
            $proveedor_nombre,
            $idAlmacen,
            $fecha_compra,
            $observacion,
            $estado,
            $subtotalCompra,
            $subtotalCompra,
            null
        );

        $res = $oRN_Compra->Save($oCompra, $listaDetalle);

        if ($res) {
            $mensaje = "Compra registrada correctamente.";
        } else {
            $error = "No se pudo registrar la compra.";
        }

    } catch (Exception $ex) {
        $error = $ex->getMessage();
    }
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Compra de Prueba</title>
    <link rel="stylesheet" href="../../view/css/main.css" />
    <style>
        .input-control, .select-control, .textarea-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-top: 5px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
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
        .msg-ok {
            padding:10px;
            background:#dff0d8;
            color:#3c763d;
            border-radius:6px;
            margin-bottom:15px;
        }
        .msg-error {
            padding:10px;
            background:#f2dede;
            color:#a94442;
            border-radius:6px;
            margin-bottom:15px;
        }
        .detalle-row {
            border: 1px solid #eee;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="ctn-form">
    <div class="form-header">
        <div class="ctn-icon"><div class="icon"></div></div>
        <div class="form-title">Compra de Prueba</div>
        <div class="form-subtitle">Registro manual</div>
        <a href="../c-panel.php"><div class="btn-back"></div></a>
    </div>

    <div class="form-content">
        <div class="title">Registrar Compra</div>

        <?php if ($mensaje != "") { ?>
            <div class="msg-ok"><?php echo $mensaje; ?></div>
        <?php } ?>

        <?php if ($error != "") { ?>
            <div class="msg-error"><?php echo $error; ?></div>
        <?php } ?>

        <form method="post">
            <div class="x-2">
                <div>Número Compra</div>
                <input type="text" name="numero_compra" class="input-control" />
            </div>

            <div class="x-2">
                <div>Proveedor</div>
                <input type="text" name="proveedor_nombre" class="input-control" required />
            </div>

            <div class="x-2">
                <div>Almacén</div>
                <select name="idAlmacen" class="select-control" required>
                    <option value="">Seleccionar</option>
                    <?php foreach($listaAlmacenes as $alm){ ?>
                        <option value="<?php echo $alm->idAlmacen; ?>">
                            <?php echo $alm->nombre; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="x-2">
                <div>Fecha Compra</div>
                <input type="datetime-local" name="fecha_compra" class="input-control" value="<?php echo date('Y-m-d\TH:i'); ?>" />
            </div>

            <div class="x-1">
                <div>Observación</div>
                <textarea name="observacion" class="textarea-control"></textarea>
            </div>

            <div class="clear"></div>

            <div class="title">Detalle</div>

            <?php for($i = 0; $i < 3; $i++){ ?>
                <div class="detalle-row">
                    <div class="x-3">
                        <div>Producto</div>
                        <select name="idProducto[]" class="select-control">
                            <option value="">Seleccionar</option>
                            <?php foreach($listaProductos as $prod){ ?>
                                <option value="<?php echo $prod->idProducto; ?>">
                                    <?php echo $prod->nombre; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="x-3">
                        <div>Lote</div>
                        <input type="text" name="lote[]" class="input-control" />
                    </div>

                    <div class="x-3">
                        <div>Cantidad</div>
                        <input type="number" step="0.01" name="cantidad[]" class="input-control" />
                    </div>

                    <div class="x-3">
                        <div>Precio Unitario</div>
                        <input type="number" step="0.01" name="precio[]" class="input-control" />
                    </div>

                    <div class="clear"></div>
                </div>
            <?php } ?>

            <button type="submit" class="btn-main">Guardar Compra</button>
        </form>
    </div>
</div>

</body>
</html>