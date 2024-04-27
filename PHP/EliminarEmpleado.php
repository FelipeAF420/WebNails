<?php
// Incluir el archivo de conexión a la base de datos
require_once 'Conection.php';

// Verificar si se recibió el ID del empleado por POST
if (isset($_POST['id_empleado'])) {
    // Obtener el ID del empleado a eliminar
    $empleadoId = $_POST['id_empleado'];
    
    // Eliminar los servicios relacionados al empleado
    $query = "DELETE FROM EmpleadoServicio WHERE id_empleado = ?";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$empleadoId]);

    // Eliminar al empleado
    $query = "DELETE FROM Empleado WHERE id_empleado = ?";
    $stmt = $conexion->prepare($query);
    $stmt->execute([$empleadoId]);

    // Mostrar mensaje de éxito
    echo "Empleado y servicios relacionados eliminados correctamente.";
} else {
    // Si no se recibió el ID del empleado, mostrar un mensaje de error
    echo "Error: No se proporcionó el ID del empleado.";
}
?>
