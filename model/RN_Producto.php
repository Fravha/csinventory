<?php

require_once "data/Producto.php";
require_once "data/DB.php";

class RN_Producto extends DataBase {

    function __construct()
    {
        parent::Open();
    }

    /**
     * Obtiene la lista de insumos/productos activos
     */
    function GetList()
    {
        // Traemos productos activos con la unidad seleccionada y su unidad base.
        $sql = "
            SELECT 
                p.*,
                um.nombre AS unidad_nombre,
                um.abreviatura AS unidad_abreviatura,
                ub.nombre AS unidad_base_nombre,
                ub.abreviatura AS unidad_base_abreviatura
            FROM productos p
            LEFT JOIN unidades_medida um 
                ON p.idUnidadMedida = um.idUnidad
            LEFT JOIN unidades_medida ub
                ON p.idUnidadBase = ub.idUnidad
            WHERE p.estado = 1
            AND p.deleted_at IS NULL
        ";

        $res = $this->Execute($sql);
        $list = array();

        if ($this->ContainsData($res)) {
            $data = $this->DataListStructure($res);

            foreach ($data as $item) {

                $producto = new Producto(
                    $item["idProducto"],
                    $item["hashProducto"],
                    $item["nombre"],
                    $item["sku"],
                    $item["idUnidadMedida"] ?? $item["idUnidadBase"],
                    $item["tipo"],
                    $item["estado"],
                    $item["idUnidadBase"]
                );
                
                $producto->unidad_nombre = $item["unidad_nombre"];
                $producto->unidad_abreviatura = $item["unidad_abreviatura"];
                $producto->unidad_medida = $item["unidad_abreviatura"] ?: $item["unidad_nombre"];
                $producto->unidad_base_nombre = $item["unidad_base_nombre"];
                $producto->unidad_base_abreviatura = $item["unidad_base_abreviatura"];

                $list[] = $producto;
            }
        }

        return $list;
    }

    /**
     * Obtiene la lista de insumos activos
     */
    function GetListInsumo(){
        $sql = "
            SELECT 
                p.*,
                um.nombre AS unidad_nombre,
                um.abreviatura AS unidad_abreviatura
            FROM productos p
            LEFT JOIN unidades_medida um
                ON p.idUnidadMedida = um.idUnidad
            WHERE p.estado = 1
                AND p.deleted_at IS NULL
                AND p.tipo = 'Insumo'
        ";
        $res = $this->Execute($sql);
        
        $list = array();

        if ($this->ContainsData($res)){
            $data = $this->DataListStructure($res);

            foreach ($data as $item) {
                $producto = new Producto(
                    $item["idProducto"],
                    $item["hashProducto"],
                    $item["nombre"],
                    $item["sku"],
                    $item["idUnidadMedida"] ?? $item["idUnidadBase"],
                    $item["tipo"],
                    $item["estado"],
                    $item["idUnidadBase"]
                );

                $producto->unidad_nombre = $item["unidad_nombre"];
                $producto->unidad_abreviatura = $item["unidad_abreviatura"];
                $producto->unidad_medida = $item["unidad_abreviatura"] ?: $item["unidad_nombre"];

                $list[] = $producto;
            }
        }

        return $list;
    }

    /**
     * Obtiene un producto específico mediante su hash
     */
    function GetData($_hashProducto){
        $sql = "
            SELECT 
                p.*,
                um.nombre AS unidad_nombre,
                um.abreviatura AS unidad_abreviatura
            FROM productos p
            LEFT JOIN unidades_medida um
                ON p.idUnidadMedida = um.idUnidad
            WHERE p.hashProducto = '" . $_hashProducto . "'
        ";
        $res = $this->Execute($sql);
        
        $oProducto = null;

        if ($this->ContainsData($res)){
            $row = $this->FetchArray($res);

            $oProducto = new Producto(
                    $row["idProducto"],
                    $row["hashProducto"],
                    $row["nombre"],
                    $row["sku"],
                    $row["idUnidadMedida"] ?? $row["idUnidadBase"],
                    $row["tipo"],
                    $row["estado"],
                    $row["idUnidadBase"]
                );

            $oProducto->unidad_nombre = $row["unidad_nombre"];
            $oProducto->unidad_abreviatura = $row["unidad_abreviatura"];
            $oProducto->unidad_medida = $row["unidad_abreviatura"] ?: $row["unidad_nombre"];
        }

        return $oProducto;
    }

    /**
     * Registra un nuevo producto e insumo
     */
    function Save($oProducto){
        $unidad = $this->resolveUnidadConfig((int) $oProducto->idUnidadMedida);

        // Guardamos la unidad elegida por el usuario y la unidad base para consistencia del inventario.
        $sql = "INSERT INTO productos (hashProducto, nombre, sku, idUnidadMedida, idUnidadBase, unidad_medida, tipo, estado) 
                VALUES (
                'temp',
                '" . $oProducto->nombre . "',
                '" . $oProducto->sku . "',
                '" . $unidad["idUnidadMedida"] . "',
                '" . $unidad["idUnidadBase"] . "',
                '" . $unidad["abreviatura_base"] . "',
                '" . $oProducto->tipo . "',
                1)";
    
        $res = $this->Execute($sql);
        $id = $this->GetLastIdAutoGenerated();

        // Generación del hash tras la inserción para asegurar unicidad
        $sql2 = "UPDATE productos SET hashProducto = '" . sha1($id) . "' WHERE idProducto = " . $id;

        $this->Execute($sql2);
        return $res;
    }

    /**
     * Actualiza la información del producto
     */
    function Update($oProducto){
        $idUnidadMedida = (int) ($oProducto->idUnidadMedida ?: $oProducto->idUnidadBase);
        $unidad = $this->resolveUnidadConfig($idUnidadMedida);

        $sql = "UPDATE productos SET 
                nombre = '" . $oProducto->nombre . "',
                sku = '" . $oProducto->sku . "',
                idUnidadMedida = '" . $unidad["idUnidadMedida"] . "',
                idUnidadBase = '" . $unidad["idUnidadBase"] . "',
                unidad_medida = '" . $unidad["abreviatura_base"] . "',
                tipo = '" . $oProducto->tipo . "'
                WHERE hashProducto = '" . $oProducto->hashProducto . "'";
        
        $res = $this->Execute($sql);
        return $res;
    }

    private function resolveUnidadConfig($idUnidadMedida)
    {
        $idUnidadMedida = (int) $idUnidadMedida;

        $unidadSeleccionada = $this->fetchOne(
            "SELECT idUnidad, abreviatura, unidad_base FROM unidades_medida WHERE idUnidad = ? AND activo = 1",
            "i",
            [$idUnidadMedida]
        );

        if (!$unidadSeleccionada) {
            throw new Exception("La unidad de medida seleccionada no existe o no está activa.");
        }

        $abreviaturaBase = $unidadSeleccionada["unidad_base"];

        $unidadBase = $this->fetchOne(
            "SELECT idUnidad FROM unidades_medida WHERE abreviatura = ? AND activo = 1 ORDER BY idUnidad ASC LIMIT 1",
            "s",
            [$abreviaturaBase]
        );

        if (!$unidadBase) {
            throw new Exception("No existe una unidad base activa para la abreviatura " . $abreviaturaBase . ".");
        }

        return [
            "idUnidadMedida" => (int) $unidadSeleccionada["idUnidad"],
            "idUnidadBase" => (int) $unidadBase["idUnidad"],
            "abreviatura" => $unidadSeleccionada["abreviatura"],
            "abreviatura_base" => $abreviaturaBase,
        ];
    }

    /**
     * Borrado lógico (Soft Delete)
     */
    function Delete($_hashProducto){
        // Cambiamos estado a 0 y registramos la fecha de eliminación
        $sql = "UPDATE productos SET 
                estado = 0, 
                deleted_at = NOW() 
                WHERE hashProducto = '" . $_hashProducto . "'";
        
        $res = $this->Execute($sql);
        return $res;
    }
}

?>
