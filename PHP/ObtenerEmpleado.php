<?php
// Incluir el archivo de conexión a la base de datos
require_once 'Conection.php';

// Consulta SQL para obtener todos los empleados
$query = "SELECT * FROM Empleado";
$stmt = $conexion->prepare($query);
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver los datos en formato JSON
echo json_encode($resultado);
?>
