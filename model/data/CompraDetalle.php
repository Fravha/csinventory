<?php

/**
 * @author      Francisco Bailaba
 * @company 	Baylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

class CompraDetalle {
    public $idCompraDetalle;
    public $hashCompraDetalle;
    public $idCompra;
    public $idProducto;
    public $idUnidadMedida;
    public $idPresentacion;
    public $tipoCompra;
    public $tipo_compra;
    public $lote;
    public $cantidad;
    public $cantidad_base;
    public $precio_unitario_compra;
    public $precio_unitario_base;
    public $subtotal;
    public $created_at;
    public $deletedAt;

    function __construct(
        $_idCompraDetalle,
        $_hashCompraDetalle,
        $_idCompra,
        $_idProducto,
        $_lote,
        $_cantidad,
        $_cantidad_base,
        $_precio_unitario_compra,
        $_precio_unitario_base,
        $_subtotal,
        $_created_at,
        $_deletedAt,
        $_idUnidadMedida = null,
        $_idPresentacion = null,
        $_tipoCompra = "UNIDAD"
    ) {
        $this->idCompraDetalle = $_idCompraDetalle;
        $this->hashCompraDetalle = $_hashCompraDetalle;
        $this->idCompra = $_idCompra;
        $this->idProducto = $_idProducto;
        $this->idUnidadMedida = $_idUnidadMedida;
        $this->idPresentacion = $_idPresentacion;
        $this->tipoCompra = $_tipoCompra;
        $this->tipo_compra = $_tipoCompra;
        $this->lote = $_lote;
        $this->cantidad = $_cantidad;
        $this->cantidad_base = $_cantidad_base;
        $this->precio_unitario_compra = $_precio_unitario_compra;
        $this->precio_unitario_base = $_precio_unitario_base;
        $this->subtotal = $_subtotal;
        $this->created_at = $_created_at;
        $this->deletedAt = $_deletedAt;
    }
}

?>
