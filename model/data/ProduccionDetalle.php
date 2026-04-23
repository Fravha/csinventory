<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

class ProduccionDetalle {
    public $idProdDetalle;
    public $idProduccion;
    public $idProducto;
    public $cantidad_usada;
    public $precio_unitario_momento;
    
    // Propiedades virtuales para reportes
    public $nomInsumo;
    public $subtotal_insumo;

    function __construct(
        $_idProdDetalle, 
        $_idProduccion, 
        $_idProducto, 
        $_cantidad_usada, 
        $_precio_unitario_momento
    ) {
        $this->idProdDetalle = $_idProdDetalle;
        $this->idProduccion = $_idProduccion;
        $this->idProducto = $_idProducto;
        $this->cantidad_usada = $_cantidad_usada;
        $this->precio_unitario_momento = $_precio_unitario_momento;
        
        // Calculamos el subtotal de este insumo en el momento
        $this->subtotal_insumo = $_cantidad_usada * $_precio_unitario_momento;
    }
}