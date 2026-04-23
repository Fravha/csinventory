<?php
/**
 * @author		Francisco Bailaba
 * @company 	Bylaba Projects
 * @copyright 	2026
 * @version     1.0
 */

class Usuario{
    public $idUsuario;
    public $hashUsuario;
    public $nombre;
    public $username;
    public $pswd;
    public $idPerfil;
    public $estado;

    function __construct($idUsuario, $hashUsuario, $nombre, $username, $pswd, $idPerfil, $estado)
    {
        $this->idUsuario = $idUsuario;
        $this->hashUsuario = $hashUsuario;
        $this->nombre = $nombre;
        $this->username = $username;
        $this->pswd = $pswd;
        $this->idPerfil = $idPerfil;
        $this->estado = $estado;
    }
}

?>