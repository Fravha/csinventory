<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

class Movimiento{

    public $idProducto;
    public $idAlmacen;
    public $tipo;
    public $Cantidad;
    public $Motivo;
    public $FechaHora;

    function __construct($_idProducto, $_idAlmacen, $_tipo, $_Cantidad, $_Motivo, $_FechaHora)

    {
        $this->idProducto = $_idProducto;
        $this->idAlmacen = $_idAlmacen;
        $this->tipo = $_tipo;
        $this->Cantidad = $_Cantidad;
        $this->Motivo = $_Motivo;
        $this->FechaHora = $_FechaHora;
    }
}
?>