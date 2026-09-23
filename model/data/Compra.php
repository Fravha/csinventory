<?php

/**
 * @author      Francisco Bailaba
 * @company 	Baylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

class Compra {
    public $idCompra;
    public $hashCompra;
    public $numero_compra;
    public $proveedor_nombre;
    public $idAlmacen;
    public $fecha_compra;
    public $observacion;
    public $estado;
    public $subtotal;
    public $total;
    public $deletedAt;

    function __construct(
        $_idCompra,
        $_hashCompra,
        $_numero_compra,
        $_proveedor_nombre,
        $_idAlmacen,
        $_fecha_compra,
        $_observacion,
        $_estado,
        $_subtotal,
        $_total,
        $_deletedAt
    ) {
        $this->idCompra = $_idCompra;
        $this->hashCompra = $_hashCompra;
        $this->numero_compra = $_numero_compra;
        $this->proveedor_nombre = $_proveedor_nombre;
        $this->idAlmacen = $_idAlmacen;
        $this->fecha_compra = $_fecha_compra;
        $this->observacion = $_observacion;
        $this->estado = $_estado;
        $this->subtotal = $_subtotal;
        $this->total = $_total;
        $this->deletedAt = $_deletedAt;
    }
}


?>