<?php
// Incluir el archivo de conexión
require_once 'Conection.php';

// Verificar si se proporcionó un ID de empleado válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400); // Bad request
    echo json_encode(array("mensaje" => "ID de empleado no válido."));
    exit;
}

// Recuperar el ID del empleado
$idEmpleado = $_GET['id'];

// Consulta preparada para obtener los datos del empleado por su ID
$sql = "SELECT e.*, es.id_servicio
        FROM empleado e
        LEFT JOIN empleadoServicio es ON e.id_empleado = es.id_empleado
        WHERE e.id_empleado = :id";

// Preparar la consulta
$statement = $conexion->prepare($sql);

if (!$statement) {
    http_response_code(500); // Internal server error
    echo json_encode(array("mensaje" => "Error al preparar la consulta."));
    exit;
}

// Vincular el parámetro
$statement->bindParam(':id', $idEmpleado, PDO::PARAM_INT);

// Ejecutar la consulta
if (!$statement->execute()) {
    http_response_code(500); // Internal server error
    echo json_encode(array("mensaje" => "Error al ejecutar la consulta."));
    exit;
}

// Obtener los datos del empleado y los IDs de los servicios asociados como un array asociativo
$datosEmpleado = array();
while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
    $datosEmpleado['id_empleado'] = $row['id_empleado'];
    $datosEmpleado['nombre'] = $row['nombre'];
    $datosEmpleado['apellidoP'] = $row['apellidoP'];
    $datosEmpleado['apellidoM'] = $row['apellidoM'];
    $datosEmpleado['imagen'] = $row['imagen'];
    $datosEmpleado['servicios'][] = $row['id_servicio'];
}

// Devolver los datos del empleado y los IDs de los servicios asociados como JSON
echo json_encode($datosEmpleado);

// Cerrar el statement
$statement->closeCursor();
?>
