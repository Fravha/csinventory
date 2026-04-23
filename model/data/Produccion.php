<?php

/**
 * @author      Francisco Bailaba
 * @company     Bylaba Projects
 * @copyright   2026
 * @version     1.0
 */

class Produccion {
    public $idProduccion;
    public $hashProduccion;
    public $idReceta;
    public $cantidad_producida;
    public $costo_total_lote;
    public $fecha_produccion;
    public $idUsuario;
    public $estado;
    
    // Propiedades virtuales para la vista (Opcionales)
    public $nomReceta;
    public $nomUsuario;
    public $nomProductoFinal; // Agregamos esta para el historial

    function __construct(
        $_idProduccion, 
        $_hashProduccion, 
        $_idReceta, 
        $_cantidad_producida, 
        $_costo_total_lote, 
        $_fecha_produccion, 
        $_idUsuario, 
        $_estado = 'Finalizado'
    ) {
        $this->idProduccion = $_idProduccion;
        $this->hashProduccion = $_hashProduccion;
        $this->idReceta = $_idReceta;
        $this->cantidad_producida = $_cantidad_producida;
        $this->costo_total_lote = $_costo_total_lote;
        $this->fecha_produccion = $_fecha_produccion;
        $this->idUsuario = $_idUsuario;
        $this->estado = $_estado;
    }
}