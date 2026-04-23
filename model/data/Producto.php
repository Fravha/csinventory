<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

class Producto{
    public $idProducto;
    public $hashProducto;
    public $nombre;
    public $sku;
    public $unidad_medida;
    public $tipo;
    public $estado;

    function __construct($_idProducto, $_hashProducto, $_nombre, $_sku, $_unidad_medida, $_tipo, $_estado)
    {
        $this->idProducto = $_idProducto;
        $this->hashProducto = $_hashProducto;
        $this->nombre = $_nombre;
        $this->sku = $_sku;
        $this->unidad_medida = $_unidad_medida;
        $this->tipo = $_tipo;
        $this->estado = $_estado;
    }
}

?>