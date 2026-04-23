<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

class Almacen{
    public $idAlmacen;
    public $hashAlmacen;
    public $nombre;
    public $ubicacion;
    public $tipo_almacen;
    public $subtipo_almacen;
    public $genera_alerta;
    public $estado;

    function __construct(
        $_idAlmacen,
        $_hashAlmacen,
        $_nombre,
        $_ubicacion,
        $_tipo_almacen,
        $_subtipo_almacen,
        $_genera_alerta,
        $_estado
    )
    {
        $this->idAlmacen = $_idAlmacen;
        $this->hashAlmacen = $_hashAlmacen;
        $this->nombre = $_nombre;
        $this->ubicacion = $_ubicacion;
        $this->tipo_almacen = $_tipo_almacen;
        $this->subtipo_almacen = $_subtipo_almacen;
        $this->genera_alerta = $_genera_alerta;
        $this->estado = $_estado;
    }
}

?>