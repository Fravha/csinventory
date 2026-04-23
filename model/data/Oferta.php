<?php

/**
 * @author		Miguel Angel Macias Burgos
 * @company 	WBT
 * @copyright 	2026
 * @version     1.0
 */

class Oferta{
    public $idOferta;
    public $hashOferta;
    public $fechaInicial;
    public $fechaFinal;
    public $porcentajeDescuento;
    public $idProducto;
    public $estado;

    function __construct($_idOferta, $_hashOferta, $_fechaInicial, $_fechaFinal, $_porcentajeDescuento, $_idProducto, $_estado)
    {
        $this->idOferta = $_idOferta;
        $this->hashOferta = $_hashOferta;
        $this->fechaInicial = $_fechaInicial;
        $this->fechaFinal = $_fechaFinal;
        $this->porcentajeDescuento = $_porcentajeDescuento;
        $this->idProducto = $_idProducto;
        $this->estado = $_estado;
    }
}

?>