<?php
// Verificar si se recibió el ID de la subcategoría
if(isset($_GET['subcategoria'])) {
    // Obtener el ID de la subcategoría desde la solicitud AJAX
    $subcategoriaId = $_GET['subcategoria'];

    // Incluir el archivo de conexión a la base de datos
    include 'Conection.php';

    try {
        // Preparar la consulta SQL para obtener los servicios de la subcategoría
        $query = "SELECT * FROM Servicio WHERE id_subCategoria = :subcategoriaId";
        $statement = $conexion->prepare($query);
        $statement->bindParam(':subcategoriaId', $subcategoriaId, PDO::PARAM_INT);
        $statement->execute();

        // Obtener los resultados de la consulta como un array asociativo
        $servicios = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Devolver los servicios en formato JSON
        echo json_encode($servicios);
    } catch(PDOException $e) {
        // En caso de error, devolver un mensaje de error
        echo json_encode(array('error' => 'Error al obtener los servicios: ' . $e->getMessage()));
    }
} else {
    // Si no se recibió el ID de la subcategoría, devolver un mensaje de error
    echo json_encode(array('error' => 'No se recibió el ID de la subcategoría.'));
}
?>
