<?php

/**
 * @author      Francisco Bailaba
 * @company 	Baylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

class Compra{
    public $idCompra;
    public $hashCompra;
    public $idProducto;
    public $idAlmacen;
    public $lote;
    public $cantidad;
    public $precioUnitario;
    public $fechaCompra;
    public $deletedAt;

    function __construct($_idCompra, $_hashCompra, $_idProducto, $_idAlmacen, $_lote, $_cantidad, $_precioUnitario, $_fechaCompra, $_deletedAt)
    {
        $this->idCompra = $_idCompra;
        $this->hashCompra = $_hashCompra;
        $this->idProducto = $_idProducto;
        $this->idAlmacen = $_idAlmacen;
        $this->lote = $_lote;
        $this->cantidad = $_cantidad;
        $this->precioUnitario = $_precioUnitario;
        $this->fechaCompra = $_fechaCompra;
        $this->deletedAt = $_deletedAt;
    }
}

?>