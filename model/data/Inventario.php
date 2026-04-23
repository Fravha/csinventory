<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

class Inventario {
    // Campos de la Tabla
    public $idProducto;
    public $idAlmacen;
    public $stock_actual;
    public $punto_critico;

    // Campos "Virtuales" o de Vista (No están en la tabla inventarios)
    public $nomProducto; 
    public $nomAlmacen;
    public $unidad_medida;

    function __construct($_idP, $_idA, $_stock, $_punto, $_nomP = "", $_nomA = "", $_uMedida = "") {
        $this->idProducto = $_idP;
        $this->idAlmacen = $_idA;
        $this->stock_actual = $_stock;
        $this->punto_critico = $_punto;
        
        // Estos se llenan solo cuando haces el GetList con JOIN
        $this->nomProducto = $_nomP;
        $this->nomAlmacen = $_nomA;
        $this->unidad_medida = $_uMedida;
    }
}

?>