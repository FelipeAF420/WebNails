<?php
// Incluir el archivo de conexión
require_once 'Conection.php';

// Obtener el ID del empleado de la solicitud GET
$idEmpleado = $_GET['idEmpleado'];

// Consulta SQL para obtener los servicios asociados con el empleado por su ID
$sql = "SELECT id_servicio FROM EmpleadoServicio WHERE id_empleado = $idEmpleado";

$result = $conn->query($sql);

$servicios = array();

if ($result->num_rows > 0) {
    // Iterar sobre los resultados de la consulta
    while($row = $result->fetch_assoc()) {
        // Agregar el ID del servicio al array de servicios
        $servicios[] = $row['id_servicio'];
    }
}

// Convertir el array de servicios a formato JSON y devolverlo como respuesta
echo json_encode($servicios);

// Cerrar conexión
$conn->close();
?>
