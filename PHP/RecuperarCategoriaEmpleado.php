<?php
// Conexión a la base de datos (ya tienes este código en tu archivo de conexión)
include 'Conection.php';

try {
    // Consulta SQL para obtener las categorías
    $query = "SELECT id_categoria, nombre FROM Categoria";
    $statement = $conexion->prepare($query);
    $statement->execute();

    // Obtener todas las filas como un array asociativo
    $categorias = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Devolver las categorías en formato JSON
    echo json_encode($categorias);
} catch(PDOException $e) {
    // En caso de error, devolver un mensaje de error
    echo json_encode(array('error' => 'Error al obtener categorías: ' . $e->getMessage()));
}
?>
