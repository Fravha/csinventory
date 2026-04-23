<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

class Receta{
    public $idReceta;
    public $hashReceta;
    public $nombre_servicio;
    public $costo_operativo_sugerido;
    public $estado;
    public $deleted_at;
    public $idProductoFinal;

    function __construct($_idReceta, $_hashReceta, $_nombre_servicio, $_costo_operativo_sugerido, $_estado, $_deleted_at, $_idProductoFinal)
    {
        $this->idReceta = $_idReceta;
        $this->hashReceta = $_hashReceta;
        $this->nombre_servicio = $_nombre_servicio;
        $this->costo_operativo_sugerido = $_costo_operativo_sugerido;
        $this->estado = $_estado;
        $this->deleted_at = $_deleted_at;
        $this->idProductoFinal = $_idProductoFinal;
    }
}

?>