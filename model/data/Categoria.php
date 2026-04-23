<?php

/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */
 
class Categoria{
    public $idCategoria;
    public $hashCategoria;
    public $nombre;
    public $estado;
    
    function __construct($_idCategoria, $_hashCategoria, $_nombre, $_estado)
    {
        $this->idCategoria = $_idCategoria;
        $this->hashCategoria = $_hashCategoria;
        $this->nombre = $_nombre;
        $this->estado = $estado;
    }
}

?>