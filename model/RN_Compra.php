<?php

require_once "data/Compra.php";
require_once "data/CompraDetalle.php";
require_once "data/DB.php";

class RN_Compra extends DataBase
{
    function __construct()
    {
        parent::Open();
    }

    function Save($oCompra, $listaDetalle = array())
    {
        try {
            $this->BeginTransaction();

            $idCompra = $this->insertCabecera($oCompra);
            $compraGuardada = $this->fetchCompraById($idCompra);

            if ($compraGuardada === null) {
                throw new RuntimeException("No se pudo recuperar la compra registrada.");
            }

            $detalleGuardado = $this->insertDetalle($idCompra, $listaDetalle);
            $this->applyDetalleEffects($compraGuardada, $detalleGuardado);

            $this->Commit();
            return true;
        } catch (Throwable $e) {
            $this->Rollback();
            return false;
        }
    }

    function GetList()
    {
        $sql = "SELECT *
                FROM compras
                WHERE deleted_at IS NULL
                ORDER BY fecha_compra DESC, idCompra DESC";

        $rows = $this->fetchAll($sql);
        $list = array();

        foreach ($rows as $item) {
            $list[] = $this->mapCompra($item);
        }

        return $list;
    }

    function GetData($_hashCompra)
    {
        $sql = "SELECT *
                FROM compras
                WHERE hashCompra = ?
                  AND deleted_at IS NULL
                LIMIT 1";

        $row = $this->fetchOne($sql, "s", [$_hashCompra]);

        if (!$row) {
            return null;
        }

        return $this->mapCompra($row);
    }

    function GetDetalle($_idCompra)
    {
        return $this->fetchDetalleList((int)$_idCompra, true, true);
    }

    function Update($oCompra, $listaDetalle = array())
    {
        try {
            $this->BeginTransaction();

            $compraActual = $this->GetData($oCompra->hashCompra);
            if ($compraActual === null) {
                throw new RuntimeException("La compra no existe.");
            }

            $detalleActual = $this->fetchDetalleList((int)$compraActual->idCompra, true, false);
            $this->reverseDetalleEffects($compraActual, $detalleActual, "Reversion por actualizacion de compra");

            $okCabecera = $this->executeStatement(
                "UPDATE compras
                 SET proveedor_nombre = ?,
                     idAlmacen = ?,
                     fecha_compra = ?,
                     observacion = ?,
                     estado = ?,
                     subtotal = ?,
                     total = ?
                 WHERE hashCompra = ?
                   AND deleted_at IS NULL",
                "sisssdds",
                [
                    $oCompra->proveedor_nombre,
                    (int)$oCompra->idAlmacen,
                    $oCompra->fecha_compra,
                    $oCompra->observacion,
                    $oCompra->estado,
                    (float)$oCompra->subtotal,
                    (float)$oCompra->total,
                    $oCompra->hashCompra,
                ]
            );

            if (!$okCabecera) {
                throw new RuntimeException("No se pudo actualizar la cabecera.");
            }

            $okDetalle = $this->executeStatement(
                "UPDATE compra_detalle
                 SET deleted_at = NOW()
                 WHERE idCompra = ?
                   AND deleted_at IS NULL",
                "i",
                [(int)$compraActual->idCompra]
            );

            if (!$okDetalle) {
                throw new RuntimeException("No se pudo actualizar el detalle.");
            }

            $compraActualizada = $this->GetData($oCompra->hashCompra);
            if ($compraActualizada === null) {
                throw new RuntimeException("No se pudo recuperar la compra actualizada.");
            }

            $detalleNuevo = $this->insertDetalle((int)$compraActual->idCompra, $listaDetalle);
            $this->applyDetalleEffects($compraActualizada, $detalleNuevo);

            $this->Commit();
            return true;
        } catch (Throwable $e) {
            $this->Rollback();
            return false;
        }
    }

    function Delete($_hashCompra)
    {
        try {
            $this->BeginTransaction();

            $oCompra = $this->GetData($_hashCompra);
            if ($oCompra === null) {
                throw new RuntimeException("La compra no existe.");
            }

            $detalleActual = $this->fetchDetalleList((int)$oCompra->idCompra, true, false);
            $this->reverseDetalleEffects($oCompra, $detalleActual, "Reversion por anulacion de compra");

            $okCabecera = $this->executeStatement(
                "UPDATE compras
                 SET deleted_at = NOW(),
                     estado = 'ANULADA'
                 WHERE hashCompra = ?
                   AND deleted_at IS NULL",
                "s",
                [$_hashCompra]
            );

            if (!$okCabecera) {
                throw new RuntimeException("No se pudo anular la compra.");
            }

            $okDetalle = $this->executeStatement(
                "UPDATE compra_detalle
                 SET deleted_at = NOW()
                 WHERE idCompra = ?
                   AND deleted_at IS NULL",
                "i",
                [(int)$oCompra->idCompra]
            );

            if (!$okDetalle) {
                throw new RuntimeException("No se pudo anular el detalle.");
            }

            $this->Commit();
            return true;
        } catch (Throwable $e) {
            $this->Rollback();
            return false;
        }
    }

    private function mapCompra(array $row)
    {
        return new Compra(
            $row["idCompra"],
            $row["hashCompra"],
            $row["numero_compra"],
            $row["proveedor_nombre"],
            $row["idAlmacen"],
            $row["fecha_compra"],
            $row["observacion"],
            $row["estado"],
            $row["subtotal"],
            $row["total"],
            $row["deleted_at"]
        );
    }

    private function fetchCompraById($idCompra)
    {
        $row = $this->fetchOne(
            "SELECT *
             FROM compras
             WHERE idCompra = ?
             LIMIT 1",
            "i",
            [(int)$idCompra]
        );

        if (!$row) {
            return null;
        }

        return $this->mapCompra($row);
    }

    private function fetchDetalleList($idCompra, $onlyActive = true, $withProductName = true)
    {
        $sql = "SELECT cd.*";
        if ($withProductName) {
            $sql .= ",
                p.nombre AS nomProducto,
                um.nombre AS nomUnidadMedida,
                um.abreviatura AS abrevUnidadMedida,
                ub.nombre AS nomUnidadBase,
                ub.abreviatura AS abrevUnidadBase,
                pp.nombre AS nomPresentacion,
                pp.cantidad_base AS cantidadPresentacion";
        }

        $sql .= " FROM compra_detalle cd";

        if ($withProductName) {
            $sql .= " LEFT JOIN productos p ON cd.idProducto = p.idProducto
                      LEFT JOIN unidades_medida um ON cd.idUnidadMedida = um.idUnidad
                      LEFT JOIN unidades_medida ub ON p.idUnidadBase = ub.idUnidad
                      LEFT JOIN producto_presentaciones pp ON cd.idPresentacion = pp.idPresentacion";
        }

        $sql .= " WHERE cd.idCompra = ?";

        if ($onlyActive) {
            $sql .= " AND cd.deleted_at IS NULL";
        }

        $sql .= " ORDER BY cd.idCompraDetalle ASC";

        $rows = $this->fetchAll($sql, "i", [(int)$idCompra]);
        $list = array();

        foreach ($rows as $item) {
            $detalle = new CompraDetalle(
                $item["idCompraDetalle"],
                $item["hashCompraDetalle"],
                $item["idCompra"],
                $item["idProducto"],
                $item["lote"],
                $item["cantidad"],
                isset($item["cantidad_base"]) ? $item["cantidad_base"] : $item["cantidad"],
                $item["precio_unitario_compra"],
                isset($item["precio_unitario_base"]) ? $item["precio_unitario_base"] : $item["precio_unitario_compra"],
                $item["subtotal"],
                isset($item["created_at"]) ? $item["created_at"] : null,
                isset($item["deleted_at"]) ? $item["deleted_at"] : null,
                isset($item["idUnidadMedida"]) ? $item["idUnidadMedida"] : null,
                isset($item["idPresentacion"]) ? $item["idPresentacion"] : null,
                !empty($item["idPresentacion"]) ? "PRESENTACION" : "UNIDAD"
            );

            if (isset($item["nomProducto"])) {
                $detalle->nomProducto = $item["nomProducto"];
                $detalle->nomUnidadMedida = $item["nomUnidadMedida"];
                $detalle->abrevUnidadMedida = $item["abrevUnidadMedida"];
                $detalle->nomUnidadBase = $item["nomUnidadBase"];
                $detalle->abrevUnidadBase = $item["abrevUnidadBase"];
                $detalle->nomPresentacion = $item["nomPresentacion"];
                $detalle->cantidadPresentacion = $item["cantidadPresentacion"];
            }

            $list[] = $detalle;
        }

        return $list;
    }

    private function insertCabecera($oCompra)
    {
        $ok = $this->executeStatement(
            "INSERT INTO compras (
                hashCompra,
                numero_compra,
                proveedor_nombre,
                idAlmacen,
                fecha_compra,
                observacion,
                estado,
                subtotal,
                total
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            "sssisssdd",
            [
                'temp',
                'temp',
                $oCompra->proveedor_nombre,
                (int)$oCompra->idAlmacen,
                $oCompra->fecha_compra,
                $oCompra->observacion,
                $oCompra->estado,
                (float)$oCompra->subtotal,
                (float)$oCompra->total,
            ]
        );

        if (!$ok) {
            throw new RuntimeException("No se pudo insertar la compra.");
        }

        $idCompra = (int)$this->GetLastIdAutoGenerated();
        if ($idCompra <= 0) {
            throw new RuntimeException("No se pudo obtener el ID de compra.");
        }

        $hashCompra = sha1($idCompra . date("YmdHis"));
        $numeroCompra = "COMP-" . str_pad($idCompra, 6, "0", STR_PAD_LEFT);

        $okHash = $this->executeStatement(
            "UPDATE compras
             SET hashCompra = ?, numero_compra = ?
             WHERE idCompra = ?",
            "ssi",
            [$hashCompra, $numeroCompra, $idCompra]
        );

        if (!$okHash) {
            throw new RuntimeException("No se pudo completar la compra.");
        }

        return $idCompra;
    }

    private function insertDetalle($idCompra, $listaDetalle)
    {
        $detalleInsertado = array();

        foreach ($listaDetalle as $item) {
            $cantidad = (float)$item->cantidad;
            $tipoCompra = isset($item->tipoCompra) ? strtoupper((string)$item->tipoCompra) : "UNIDAD";
            if (isset($item->tipo_compra) && $item->tipo_compra !== "") {
                $tipoCompra = strtoupper((string)$item->tipo_compra);
            }

            $idPresentacion = isset($item->idPresentacion) ? (int)$item->idPresentacion : 0;

            if ($tipoCompra === "PRESENTACION" || $idPresentacion > 0) {
                $tipoCompra = "PRESENTACION";
                $unidadConfig = $this->resolveDetallePresentacionConfig((int)$item->idProducto, $idPresentacion);
                $cantidadBase = round($cantidad * (float)$unidadConfig["cantidad_presentacion"], 4);
            } else {
                $tipoCompra = "UNIDAD";
                $unidadConfig = $this->resolveDetalleUnidadConfig((int)$item->idProducto, (int)$item->idUnidadMedida);
                $cantidadBase = round($cantidad * (float)$unidadConfig["factor_base"], 4);
            }

            $precioUnitario = (float)$item->precio_unitario_compra;
            $subtotalDetalle = (float)$item->subtotal;

            if ($subtotalDetalle <= 0) {
                $subtotalDetalle = $cantidad * $precioUnitario;
            }

            if ($precioUnitario <= 0 && $cantidad > 0 && $subtotalDetalle > 0) {
                $precioUnitario = $subtotalDetalle / $cantidad;
            }

            $subtotalDetalle = round($subtotalDetalle, 2);
            $precioUnitario = round($precioUnitario, 2);
            $precioUnitarioBase = $cantidadBase > 0 ? round($subtotalDetalle / $cantidadBase, 6) : 0;

            $loteSistema = "LOT-" . date("Ymd") . "-" . $idCompra . "-" . (int)$item->idProducto . "-" . substr(time(), -4);

            $okDetalle = $this->executeStatement(
                "INSERT INTO compra_detalle (
                    hashCompraDetalle,
                    idCompra,
                    idProducto,
                    idUnidadMedida,
                    idPresentacion,
                    lote,
                    cantidad,
                    cantidad_base,
                    precio_unitario_compra,
                    precio_unitario_base,
                    subtotal
                ) VALUES (?, ?, ?, ?, NULLIF(?, 0), ?, ?, ?, ?, ?, ?)",
                "siiiisddddd",
                [
                    'temp',
                    (int)$idCompra,
                    (int)$item->idProducto,
                    (int)$unidadConfig["idUnidadMedida"],
                    (int)$unidadConfig["idPresentacion"],
                    $loteSistema,
                    $cantidad,
                    $cantidadBase,
                    $precioUnitario,
                    $precioUnitarioBase,
                    $subtotalDetalle,
                ]
            );

            if (!$okDetalle) {
                throw new RuntimeException("No se pudo insertar el detalle.");
            }

            $idDetalle = (int)$this->GetLastIdAutoGenerated();
            if ($idDetalle <= 0) {
                throw new RuntimeException("No se pudo obtener el ID del detalle.");
            }

            $hashDetalle = sha1($idCompra . "-" . $idDetalle . "-" . date("YmdHis"));

            $okHashDetalle = $this->executeStatement(
                "UPDATE compra_detalle
                 SET hashCompraDetalle = ?
                 WHERE idCompraDetalle = ?",
                "si",
                [$hashDetalle, $idDetalle]
            );

            if (!$okHashDetalle) {
                throw new RuntimeException("No se pudo completar el detalle.");
            }

            $detalle = new CompraDetalle(
                $idDetalle,
                $hashDetalle,
                (int)$idCompra,
                (int)$item->idProducto,
                $loteSistema,
                $cantidad,
                $cantidadBase,
                $precioUnitario,
                $precioUnitarioBase,
                $subtotalDetalle,
                null,
                null,
                (int)$unidadConfig["idUnidadMedida"],
                (int)$unidadConfig["idPresentacion"] > 0 ? (int)$unidadConfig["idPresentacion"] : null,
                $tipoCompra
            );

            $detalle->nomUnidadMedida = $unidadConfig["nombre"];
            $detalle->abrevUnidadMedida = $unidadConfig["abreviatura"];
            $detalle->nomUnidadBase = $unidadConfig["nombre_base"];
            $detalle->abrevUnidadBase = $unidadConfig["abreviatura_base"];
            $detalle->nomPresentacion = isset($unidadConfig["presentacion"]) ? $unidadConfig["presentacion"] : null;
            $detalle->cantidadPresentacion = isset($unidadConfig["cantidad_presentacion"]) ? $unidadConfig["cantidad_presentacion"] : null;

            $detalleInsertado[] = $detalle;
        }

        return $detalleInsertado;
    }

    private function applyDetalleEffects($oCompra, $listaDetalle)
    {
        foreach ($listaDetalle as $detalle) {
            $idProducto = (int)$detalle->idProducto;
            $idAlmacen = (int)$oCompra->idAlmacen;
            $cantidad = (float)$detalle->cantidad_base;

            $this->ensureInventarioRow($idProducto, $idAlmacen);

            $okStock = $this->executeStatement(
                "UPDATE inventarios
                 SET stock_actual = stock_actual + ?
                 WHERE idProducto = ? AND idAlmacen = ?",
                "dii",
                [$cantidad, $idProducto, $idAlmacen]
            );

            if (!$okStock) {
                throw new RuntimeException("No se pudo actualizar el inventario de la compra.");
            }

            $motivo = "Compra " . $oCompra->numero_compra . " Lote: " . $detalle->lote;
            $okMovimiento = $this->executeStatement(
                "INSERT INTO movimientos (idProducto, idAlmacen, tipo, cantidad, motivo)
                 VALUES (?, ?, 'Entrada', ?, ?)",
                "iids",
                [$idProducto, $idAlmacen, $cantidad, $motivo]
            );

            if (!$okMovimiento) {
                throw new RuntimeException("No se pudo registrar el movimiento de compra.");
            }
        }
    }

    private function reverseDetalleEffects($oCompra, $listaDetalle, $motivoBase)
    {
        foreach ($listaDetalle as $detalle) {
            $idProducto = (int)$detalle->idProducto;
            $idAlmacen = (int)$oCompra->idAlmacen;
            $cantidad = (float)$detalle->cantidad_base;

            $inventario = $this->fetchOne(
                "SELECT stock_actual
                 FROM inventarios
                 WHERE idProducto = ? AND idAlmacen = ?
                 LIMIT 1",
                "ii",
                [$idProducto, $idAlmacen]
            );

            if (!$inventario) {
                throw new RuntimeException("No existe inventario para revertir la compra.");
            }

            $stockActual = (float)$inventario["stock_actual"];
            if ($stockActual < $cantidad) {
                throw new RuntimeException("No hay stock suficiente para revertir la compra " . $oCompra->numero_compra . ".");
            }

            $okStock = $this->executeStatement(
                "UPDATE inventarios
                 SET stock_actual = stock_actual - ?
                 WHERE idProducto = ? AND idAlmacen = ?",
                "dii",
                [$cantidad, $idProducto, $idAlmacen]
            );

            if (!$okStock) {
                throw new RuntimeException("No se pudo revertir el inventario de la compra.");
            }

            $motivo = $motivoBase . " " . $oCompra->numero_compra . " Lote: " . $detalle->lote;
            $okMovimiento = $this->executeStatement(
                "INSERT INTO movimientos (idProducto, idAlmacen, tipo, cantidad, motivo)
                 VALUES (?, ?, 'Salida', ?, ?)",
                "iids",
                [$idProducto, $idAlmacen, $cantidad, $motivo]
            );

            if (!$okMovimiento) {
                throw new RuntimeException("No se pudo registrar el movimiento de reversa.");
            }
        }
    }

    private function ensureInventarioRow($idProducto, $idAlmacen)
    {
        $row = $this->fetchOne(
            "SELECT idProducto
             FROM inventarios
             WHERE idProducto = ? AND idAlmacen = ?
             LIMIT 1",
            "ii",
            [(int)$idProducto, (int)$idAlmacen]
        );

        if ($row) {
            return;
        }

        $ok = $this->executeStatement(
            "INSERT INTO inventarios (idProducto, idAlmacen, stock_actual, punto_critico)
             VALUES (?, ?, 0, 0)",
            "ii",
            [(int)$idProducto, (int)$idAlmacen]
        );

        if (!$ok) {
            throw new RuntimeException("No se pudo inicializar el inventario para la compra.");
        }
    }

    private function resolveDetallePresentacionConfig($idProducto, $idPresentacion)
    {
        if ((int)$idPresentacion <= 0) {
            throw new RuntimeException("Debes seleccionar una presentacion para la compra.");
        }

        $presentacion = $this->fetchOne(
            "SELECT
                pp.idPresentacion,
                pp.idProducto,
                pp.nombre AS presentacion,
                pp.cantidad_base,
                pp.idUnidadBase,
                um.nombre AS unidadNombre,
                um.abreviatura AS unidadAbreviatura,
                p.idUnidadBase AS idUnidadBaseProducto,
                ub.nombre AS nombreBase,
                ub.abreviatura AS abreviaturaBase
             FROM producto_presentaciones pp
             INNER JOIN productos p ON pp.idProducto = p.idProducto
             INNER JOIN unidades_medida um ON pp.idUnidadBase = um.idUnidad
             LEFT JOIN unidades_medida ub ON p.idUnidadBase = ub.idUnidad
             WHERE pp.idPresentacion = ?
               AND pp.idProducto = ?
               AND pp.activo = 1
             LIMIT 1",
            "ii",
            [(int)$idPresentacion, (int)$idProducto]
        );

        if (!$presentacion) {
            throw new RuntimeException("La presentacion seleccionada no existe o no pertenece al producto.");
        }

        if ((int)$presentacion["idUnidadBase"] !== (int)$presentacion["idUnidadBaseProducto"]) {
            throw new RuntimeException("La presentacion seleccionada no usa la unidad base del producto.");
        }

        return [
            "idUnidadMedida" => (int)$presentacion["idUnidadBase"],
            "idPresentacion" => (int)$presentacion["idPresentacion"],
            "nombre" => $presentacion["unidadNombre"],
            "abreviatura" => $presentacion["unidadAbreviatura"],
            "factor_base" => 1,
            "cantidad_presentacion" => (float)$presentacion["cantidad_base"],
            "presentacion" => $presentacion["presentacion"],
            "idUnidadBase" => (int)$presentacion["idUnidadBaseProducto"],
            "nombre_base" => $presentacion["nombreBase"],
            "abreviatura_base" => $presentacion["abreviaturaBase"],
        ];
    }

    private function resolveDetalleUnidadConfig($idProducto, $idUnidadMedida)
    {
        $producto = $this->fetchOne(
            "SELECT p.idProducto, p.idUnidadBase, ub.abreviatura AS abreviaturaBase, ub.nombre AS nombreBase
             FROM productos p
             LEFT JOIN unidades_medida ub ON p.idUnidadBase = ub.idUnidad
             WHERE p.idProducto = ?
             LIMIT 1",
            "i",
            [(int)$idProducto]
        );

        if (!$producto) {
            throw new RuntimeException("El producto seleccionado no existe.");
        }

        $unidad = $this->fetchOne(
            "SELECT idUnidad, nombre, abreviatura, factor_base, unidad_base
             FROM unidades_medida
             WHERE idUnidad = ? AND activo = 1
             LIMIT 1",
            "i",
            [(int)$idUnidadMedida]
        );

        if (!$unidad) {
            throw new RuntimeException("La unidad de medida seleccionada no existe o no esta activa.");
        }

        if (strcasecmp((string)$unidad["unidad_base"], (string)$producto["abreviaturaBase"]) !== 0) {
            throw new RuntimeException("La unidad seleccionada no es compatible con la unidad base del producto.");
        }

        return [
            "idUnidadMedida" => (int)$unidad["idUnidad"],
            "idPresentacion" => 0,
            "nombre" => $unidad["nombre"],
            "abreviatura" => $unidad["abreviatura"],
            "factor_base" => (float)$unidad["factor_base"],
            "idUnidadBase" => (int)$producto["idUnidadBase"],
            "nombre_base" => $producto["nombreBase"],
            "abreviatura_base" => $producto["abreviaturaBase"],
        ];
    }
}

?>
