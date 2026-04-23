<?php

require_once "../model/RN_Usuario.php";
require_once "../model/data/Usuario.php";

$oRN_Usuario = new RN_Usuario();

$oUsuario = new Usuario();

// Datos de prueba
$oUsuario->hashUsuario = sha1("Operador"); // luego se actualizará
$oUsuario->nombre = "Operador";
$oUsuario->username = "oper";
$oUsuario->pswd = "12345";
$oUsuario->idPerfil = 2;
$oUsuario->estado = "Activo";

// Guardar
$res = $oRN_Usuario->Save($oUsuario);

if ($res){
    echo "Usuario guardado correctamente.";
} else {
    echo "Error al guardar usuario.";
}

?>