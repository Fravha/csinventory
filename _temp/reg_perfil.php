<?php
/**
 * @author       Francisco Bailaba
 * @company      Bylaba Projects
 * @version      1.1
 */

// Si RN_Perfil maneja la conexión, asegúrate de que esté instanciado correctamente.
// Aquí asumo que tienes una variable $pdo disponible.
require_once "../model/RN_Perfil.php";

$nombre      = "Operador";
$descripcion = NULL;
$estado      = "Activo";

// Generación del Hash SHA-1 (40 caracteres)
$semilla     = "2";
$hashPerfil  = sha1($semilla); 

echo "<h2>Verificación de Datos:</h2>";
echo "Hash generado: <code>$hashPerfil</code> (" . strlen($hashPerfil) . " caracteres)<br>";

try {
    // CORRECCIÓN: La sintaxis que tenías mezclaba instanciación con SQL.
    // Lo ideal es usar el objeto de Regla de Negocio (RN) o PDO directamente.
    
    /* Si usas PDO directo: */
    $sql = "INSERT INTO perfiles (hashPerfil, nombre, descripcion, estado) 
            VALUES (:hash, :nombre, :desc, :estado)";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':hash'   => $hashPerfil,
        ':nombre' => $nombre,
        ':desc'   => $descripcion,
        ':estado' => $estado
    ]);

    echo "<p style='color:green;'>¡Perfil '$nombre' creado con éxito!</p>";

} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
?>