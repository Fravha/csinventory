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
    public $idUnidadMedida;
    public $idUnidadBase;
    public $unidad_medida;
    public $tipo;
    public $estado;
    public $unidad_nombre;
    public $unidad_abreviatura;


    function __construct($_idProducto, $_hashProducto, $_nombre, $_sku, $_idUnidadMedida, $_tipo, $_estado, $_idUnidadBase = null)
    {
        $this->idProducto = $_idProducto;
        $this->hashProducto = $_hashProducto;
        $this->nombre = $_nombre;
        $this->sku = $_sku;
        $this->idUnidadMedida = $_idUnidadMedida;
        $this->idUnidadBase = $_idUnidadBase ?? $_idUnidadMedida;
        $this->unidad_medida = $_idUnidadMedida;
        $this->tipo = $_tipo;
        $this->estado = $_estado;
    }
}

?>
