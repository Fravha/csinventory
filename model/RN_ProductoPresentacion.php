<?php

require_once "data/ProductoPresentacion.php";
require_once "data/DB.php";

class RN_ProductoPresentacion extends DataBase
{
    public function __construct()
    {
        parent::Open();
    }

    public function GetList()
    {
        $sql = "
            SELECT 
                pp.idPresentacion,
                pp.idProducto,
                p.nombre AS producto,
                p.sku,
                pp.nombre AS presentacion,
                pp.cantidad_base,
                pp.idUnidadBase,
                um.nombre AS unidad_base,
                um.abreviatura AS unidad_abreviatura,
                pp.activo
            FROM producto_presentaciones pp
            INNER JOIN productos p 
                ON pp.idProducto = p.idProducto
            INNER JOIN unidades_medida um 
                ON pp.idUnidadBase = um.idUnidad
            WHERE pp.activo = 1
            ORDER BY p.nombre ASC, pp.nombre ASC
        ";

        return $this->BuildList($this->Execute($sql));
    }

    public function GetListByProducto($idProducto)
    {
        $idProducto = intval($idProducto);

        $sql = "
            SELECT 
                pp.idPresentacion,
                pp.idProducto,
                p.nombre AS producto,
                p.sku,
                pp.nombre AS presentacion,
                pp.cantidad_base,
                pp.idUnidadBase,
                um.nombre AS unidad_base,
                um.abreviatura AS unidad_abreviatura,
                pp.activo
            FROM producto_presentaciones pp
            INNER JOIN productos p 
                ON pp.idProducto = p.idProducto
            INNER JOIN unidades_medida um 
                ON pp.idUnidadBase = um.idUnidad
            WHERE pp.activo = 1
            AND pp.idProducto = $idProducto
            ORDER BY pp.nombre ASC
        ";

        return $this->BuildList($this->Execute($sql));
    }

    public function GetById($idPresentacion)
    {
        $idPresentacion = intval($idPresentacion);

        $sql = "
            SELECT 
                pp.idPresentacion,
                pp.idProducto,
                p.nombre AS producto,
                p.sku,
                pp.nombre AS presentacion,
                pp.cantidad_base,
                pp.idUnidadBase,
                um.nombre AS unidad_base,
                um.abreviatura AS unidad_abreviatura,
                pp.activo
            FROM producto_presentaciones pp
            INNER JOIN productos p 
                ON pp.idProducto = p.idProducto
            INNER JOIN unidades_medida um 
                ON pp.idUnidadBase = um.idUnidad
            WHERE pp.idPresentacion = $idPresentacion
            LIMIT 1
        ";

        $res = $this->Execute($sql);
        $list = $this->BuildList($res);

        return count($list) > 0 ? $list[0] : null;
    }

    public function Save($idProducto, $nombre, $cantidadBase, $idUnidadBase)
    {
        $idProducto = intval($idProducto);
        $nombre = addslashes(trim($nombre));
        $cantidadBase = floatval($cantidadBase);
        $idUnidadBase = intval($idUnidadBase);

        $this->assertUnidadBaseProducto($idProducto, $idUnidadBase);

        $sql = "
            INSERT INTO producto_presentaciones
            (
                idProducto,
                nombre,
                cantidad_base,
                idUnidadBase,
                activo
            )
            VALUES
            (
                $idProducto,
                '$nombre',
                $cantidadBase,
                $idUnidadBase,
                1
            )
        ";

        return $this->Execute($sql);
    }

    public function Update($idPresentacion, $idProducto, $nombre, $cantidadBase, $idUnidadBase)
    {
        $idPresentacion = intval($idPresentacion);
        $idProducto = intval($idProducto);
        $nombre = addslashes(trim($nombre));
        $cantidadBase = floatval($cantidadBase);
        $idUnidadBase = intval($idUnidadBase);

        $this->assertUnidadBaseProducto($idProducto, $idUnidadBase);

        $sql = "
            UPDATE producto_presentaciones
            SET
                idProducto = $idProducto,
                nombre = '$nombre',
                cantidad_base = $cantidadBase,
                idUnidadBase = $idUnidadBase
            WHERE idPresentacion = $idPresentacion
        ";

        return $this->Execute($sql);
    }

    public function SoftDelete($idPresentacion)
    {
        $idPresentacion = intval($idPresentacion);

        $sql = "
            UPDATE producto_presentaciones
            SET activo = 0
            WHERE idPresentacion = $idPresentacion
        ";

        return $this->Execute($sql);
    }

    private function assertUnidadBaseProducto($idProducto, $idUnidadBase)
    {
        $producto = $this->fetchOne(
            "SELECT idUnidadBase
             FROM productos
             WHERE idProducto = ?
             LIMIT 1",
            "i",
            [(int)$idProducto]
        );

        if (!$producto) {
            throw new RuntimeException("El producto seleccionado no existe.");
        }

        if ((int)$producto["idUnidadBase"] !== (int)$idUnidadBase) {
            throw new RuntimeException("La presentacion debe usar la unidad base del producto.");
        }
    }

    private function BuildList($res)
    {
        $list = array();

        if ($this->ContainsData($res)) {
            $data = $this->DataListStructure($res);

            foreach ($data as $item) {
                $list[] = new ProductoPresentacion(
                    $item["idPresentacion"],
                    $item["idProducto"],
                    $item["producto"],
                    $item["sku"],
                    $item["presentacion"],
                    $item["cantidad_base"],
                    $item["idUnidadBase"],
                    $item["unidad_base"],
                    $item["unidad_abreviatura"],
                    $item["activo"]
                );
            }
        }

        return $list;
    }
}

?>
