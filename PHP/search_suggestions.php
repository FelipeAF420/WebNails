<?php
// Configuración de la conexión a la base de datos usando PDO
$servername = "localhost";
$username = "root";
$password = "";
$database = "butfirstnails";

try {
    // Crear una nueva instancia de PDO para la conexión a la base de datos
    $conexion = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);
    // Establecer los atributos de la conexión para manejar errores y excepciones
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Obtener la consulta de búsqueda del formulario
$searchQuery = isset($_GET['q']) ? $_GET['q'] : '';

// Inicializar una matriz para almacenar los resultados
$results = [];

// Si la consulta de búsqueda no está vacía, realiza una búsqueda
if (!empty($searchQuery)) {
    // Consulta para empleados
    $stmtEmployees = $conexion->prepare("SELECT nombre, apellidoP, apellidoM, 'Empleado' AS tipo FROM Empleado WHERE nombre LIKE :searchQuery OR apellidoP LIKE :searchQuery OR apellidoM LIKE :searchQuery LIMIT 5");
    $searchPattern = '%' . $searchQuery . '%';
    $stmtEmployees->bindParam(':searchQuery', $searchPattern, PDO::PARAM_STR);
    $stmtEmployees->execute();

    // Añadir resultados de empleados a la matriz
    while ($row = $stmtEmployees->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $row;
    }

    // Consulta para servicios
    $stmtServices = $conexion->prepare("SELECT nombre, descripcion, 'Servicio' AS tipo FROM Servicio WHERE nombre LIKE :searchQuery OR descripcion LIKE :searchQuery LIMIT 5");
    $stmtServices->bindParam(':searchQuery', $searchPattern, PDO::PARAM_STR);
    $stmtServices->execute();

    // Añadir resultados de servicios a la matriz
    while ($row = $stmtServices->fetch(PDO::FETCH_ASSOC)) {
        $results[] = $row;
    }
}

// Enviar resultados como JSON
header('Content-Type: application/json');
echo json_encode($results);
?>
