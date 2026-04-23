<?php

require_once "data/Movimiento.php";
require_once "data/DB.php";

class RN_Movimiento extends DataBase {

    function __construct()
    {
        parent::Open();
    }

    /**
     * Obtiene el historial de movimientos cruzando datos con Productos y Almacenes
     */
    function GetList(){
        // Usamos INNER JOIN para obtener nombres en lugar de solo IDs
        $sql = "SELECT m.*, p.nombre as nomProducto, a.nombre as nomAlmacen 
                FROM movimientos m
                INNER JOIN productos p ON m.idProducto = p.idProducto
                INNER JOIN almacenes a ON m.idAlmacen = a.idAlmacen
                ORDER BY m.fecha DESC"; // Los más recientes primero
                
        $res = $this->Execute($sql);
        
        $list = array();

        if ($this->ContainsData($res)){
            $data = $this->DataListStructure($res);

            foreach ($data as $item) {
                // Creamos el objeto Movimiento
                $oMov = new Movimiento(
                    $item["idProducto"],
                    $item["idAlmacen"],
                    $item["tipo"],
                    $item["cantidad"],
                    $item["motivo"],
                    $item["fecha"]
                );

                // Agregamos propiedades virtuales para la vista (nombres)
                $oMov->nomProducto = $item["nomProducto"];
                $oMov->nomAlmacen = $item["nomAlmacen"];
                $oMov->idMovimiento = $item["idMovimiento"]; // Para mostrar el correlativo

                $list[] = $oMov;
            }
        }

        return $list;
    }

    /**
     * Obtiene los movimientos de un solo producto (Útil para ver el historial de un insumo)
     */
    function GetListByProducto($idProducto){
        $sql = "SELECT m.*, p.nombre as nomProducto, a.nombre as nomAlmacen 
                FROM movimientos m
                INNER JOIN productos p ON m.idProducto = p.idProducto
                INNER JOIN almacenes a ON m.idAlmacen = a.idAlmacen
                WHERE m.idProducto = $idProducto
                ORDER BY m.fecha DESC";
        
        $res = $this->Execute($sql);
        // ... misma lógica de llenado de lista ...
        return $this->ProcesarLista($res); 
    }
}
?>