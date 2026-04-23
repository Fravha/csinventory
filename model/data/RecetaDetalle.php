<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

class RecetaDetalle{
    public $idDetalle;
    public $idReceta;
    public $idProducto;
    public $cantidadNecesaria;

    function __construct($_idDetalle, $_idReceta, $_idProducto, $_cantidadNecesaria)
    {
        $this->idDetalle = $_idDetalle;
        $this->idReceta = $_idReceta;
        $this->idProducto = $_idProducto;
        $this->cantidadNecesaria = $_cantidadNecesaria;
    }
}

?>