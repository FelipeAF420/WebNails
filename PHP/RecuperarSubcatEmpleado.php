<?php
// Conexión a la base de datos (ya tienes este código en tu archivo de conexión)
include 'Conection.php';

try {
    // Verificar si se recibió el parámetro 'categoria' en la solicitud GET
    if(isset($_GET['categoria'])) {
        $categoriaId = $_GET['categoria'];

        // Consulta SQL para obtener las subcategorías relacionadas con la categoría seleccionada
        $query = "SELECT id_subCategoria, nombre FROM Subcategoria WHERE id_categoria = ?";
        $statement = $conexion->prepare($query);
        $statement->execute([$categoriaId]);

        // Obtener todas las filas como un array asociativo
        $subcategorias = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Devolver las subcategorías en formato JSON
        echo json_encode($subcategorias);
    } else {
        // Si no se recibió el parámetro 'categoria', devolver un mensaje de error
        echo json_encode(array('error' => 'No se recibió la categoría.'));
    }
} catch(PDOException $e) {
    // En caso de error, devolver un mensaje de error
    echo json_encode(array('error' => 'Error al obtener subcategorías: ' . $e->getMessage()));
}
?>
