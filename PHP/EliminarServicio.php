<?php
require_once('Conection.php');

// Verificar si se recibió el ID del servicio a eliminar
if (isset($_POST['idServicio'])) {
    $idServicio = $_POST['idServicio'];

    // Ejecutar la consulta para eliminar el servicio
    $sql = "DELETE FROM Servicio WHERE id_servicio = :id";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id', $idServicio, PDO::PARAM_INT);

    // Manejar el resultado de la ejecución de la consulta
    if ($stmt->execute()) {
        // Si la eliminación fue exitosa, devolver un mensaje de éxito
        echo json_encode(array('success' => true));
    } else {
        // Si hubo un error en la eliminación, devolver un mensaje de error
        echo json_encode(array('success' => false, 'message' => 'Error al eliminar el servicio.'));
    }
} else {
    // Si no se recibió el ID del servicio, devolver un mensaje de error
    echo json_encode(array('success' => false, 'message' => 'No se proporcionó el ID del servicio.'));
}
?>
