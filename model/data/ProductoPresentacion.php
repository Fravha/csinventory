<?php

class ProductoPresentacion
{
    public $idPresentacion;
    public $idProducto;
    public $producto;
    public $sku;
    public $presentacion;
    public $cantidad_base;
    public $idUnidadBase;
    public $unidad_base;
    public $unidad_abreviatura;
    public $activo;

    public function __construct(
        $idPresentacion,
        $idProducto,
        $producto,
        $sku,
        $presentacion,
        $cantidad_base,
        $idUnidadBase,
        $unidad_base,
        $unidad_abreviatura,
        $activo
    ) {
        $this->idPresentacion = $idPresentacion;
        $this->idProducto = $idProducto;
        $this->producto = $producto;
        $this->sku = $sku;
        $this->presentacion = $presentacion;
        $this->cantidad_base = $cantidad_base;
        $this->idUnidadBase = $idUnidadBase;
        $this->unidad_base = $unidad_base;
        $this->unidad_abreviatura = $unidad_abreviatura;
        $this->activo = $activo;
    }
}

?>