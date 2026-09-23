<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

class UnidadMedida
{
    public $idUnidad;
    public $nombre;
    public $abreviatura;
    public $tipo;
    public $factor_base;
    public $unidad_base;
    public $activo;

    /**
     * Constructor de la clase UnidadMedida
     */
    public function __construct(
        $idUnidad,
        $nombre,
        $abreviatura,
        $tipo,
        $factor_base,
        $unidad_base,
        $activo
    ) {
        $this->idUnidad = $idUnidad;
        $this->nombre = $nombre;
        $this->abreviatura = $abreviatura;
        $this->tipo = $tipo;
        $this->factor_base = $factor_base;
        $this->unidad_base = $unidad_base;
        $this->activo = $activo;
    }
}

?>