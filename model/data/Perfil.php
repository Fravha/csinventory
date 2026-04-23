<?php

class Perfil{
    public $idPerfil;
    public $hashPerfil;
    public $nombre;
    public $descripcion;
    public $estado;

    function __construct($idPerfil, $hashPerfil, $nombre, $descripcion, $estado)
    {
        $this->idPerfil = $idPerfil;
        $this->hashPerfil = $hashPerfil;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
    }
}

?>