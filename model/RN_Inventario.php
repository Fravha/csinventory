<?php

require_once "data/Inventario.php";
require_once "data/DB.php";

class RN_Inventario extends DataBase {

    function __construct()
    {
        parent::Open();
    }

    /**
     * Obtiene el stock consolidado: une productos y almacenes para mostrar nombres
     */
    function GetList(){
        // Añadimos p.unidad_medida al SELECT
        $sql = "SELECT i.*, p.nombre as nomProducto, p.unidad_medida, a.nombre as nomAlmacen 
                FROM inventarios i
                INNER JOIN productos p ON i.idProducto = p.idProducto
                INNER JOIN almacenes a ON i.idAlmacen = a.idAlmacen
                WHERE p.deleted_at IS NULL AND a.deleted_at IS NULL";
                
        $res = $this->Execute($sql);
        $list = array();

        if ($this->ContainsData($res)){
            $data = $this->DataListStructure($res);
            foreach ($data as $item) {
                $list[] = new Inventario(
                    $item["idProducto"],
                    $item["idAlmacen"],
                    $item["stock_actual"],
                    $item["punto_critico"],
                    $item["nomProducto"], 
                    $item["nomAlmacen"],
                    $item["unidad_medida"] // Enviamos la unidad al constructor
                );
            }
        }
        return $list;
    }

    /**
     * Obtiene el stock de un producto específico en un almacén específico
     */
    function GetDataByKeys($idProducto, $idAlmacen){
        $sql = "SELECT * FROM inventarios WHERE idProducto = $idProducto AND idAlmacen = $idAlmacen";
        $res = $this->Execute($sql);
        
        $oInventario = null;

        if ($this->ContainsData($res)){
            $row = $this->FetchArray($res);
            $oInventario = new Inventario(
                    $row["idProducto"],
                    $row["idAlmacen"],
                    $row["stock_actual"],
                    $row["punto_critico"]
                );
        }

        return $oInventario;
    }

    /**
     * Actualiza el stock (útil cuando se procesa una Compra o una Venta)
     */
    function UpdateStock($idProducto, $idAlmacen, $nuevaCantidad, $operacion = 'SUMA'){
        // Esta lógica permite sumar stock (compras) o restar (ventas/producción)
        $signo = ($operacion == 'SUMA') ? "+" : "-";
        
        $sql = "UPDATE inventarios SET 
                stock_actual = stock_actual $signo $nuevaCantidad 
                WHERE idProducto = $idProducto AND idAlmacen = $idAlmacen";
        
        return $this->Execute($sql);
    }

    /**
     * Guarda una nueva relación producto-almacén (inicialización)
     */
    function Save($oInventario){
        $sql = "INSERT INTO inventarios (idProducto, idAlmacen, stock_actual, punto_critico) 
                VALUES (
                " . $oInventario->idProducto . ",
                " . $oInventario->idAlmacen . ",
                " . $oInventario->stock_actual . ",
                " . $oInventario->punto_critico . ")";

        return $this->Execute($sql);
    }

    /**
     * Registra un movimiento y actualiza automáticamente el stock en la tabla inventarios
     */
    function RegistrarMovimiento($idProducto, $idAlmacen, $tipo, $cantidad, $motivo) {
        // 1. Insertar el registro de auditoría en la tabla movimientos 
        $sqlMov = "INSERT INTO movimientos (idProducto, idAlmacen, tipo, cantidad, motivo) 
                   VALUES ($idProducto, $idAlmacen, '$tipo', $cantidad, '$motivo')";
        
        $resMov = $this->Execute($sqlMov);

        if($resMov) {
            // 2. Determinar si sumamos o restamos al inventario
            // Entradas suman, Salidas y Ajustes (Bajas) restan por defecto
            $operador = ($tipo == 'Entrada') ? "+" : "-";

            $sqlInv = "UPDATE inventarios SET 
                       stock_actual = stock_actual $operador $cantidad 
                       WHERE idProducto = $idProducto AND idAlmacen = $idAlmacen";
            
            return $this->Execute($sqlInv);
        }
        
        return false;
    }
}
?>