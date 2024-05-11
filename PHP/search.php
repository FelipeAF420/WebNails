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

// Si la consulta de búsqueda no está vacía, realiza una búsqueda
if (!empty($searchQuery)) {
    // Consulta para empleados
    $stmtEmployees = $conexion->prepare("SELECT nombre, apellidoP, apellidoM FROM Empleado WHERE nombre LIKE :searchQuery OR apellidoP LIKE :searchQuery OR apellidoM LIKE :searchQuery");
    $searchPattern = '%' . $searchQuery . '%';
    $stmtEmployees->bindParam(':searchQuery', $searchPattern, PDO::PARAM_STR);
    $stmtEmployees->execute();

    // Mostrar resultados de empleados
    echo '<h3>Empleados</h3>';
    if ($stmtEmployees->rowCount() > 0) {
        while ($row = $stmtEmployees->fetch(PDO::FETCH_ASSOC)) {
            echo '<p>' . $row['nombre'] . ' ' . $row['apellidoP'] . ' ' . $row['apellidoM'] . '</p>';
        }
    } else {
        echo '<p>No se encontraron empleados.</p>';
    }

    // Consulta para servicios
    $stmtServices = $conexion->prepare("SELECT nombre, descripcion, precio FROM Servicio WHERE nombre LIKE :searchQuery OR descripcion LIKE :searchQuery");
    $stmtServices->bindParam(':searchQuery', $searchPattern, PDO::PARAM_STR);
    $stmtServices->execute();

    // Mostrar resultados de servicios
    echo '<h3>Servicios</h3>';
    if ($stmtServices->rowCount() > 0) {
        while ($row = $stmtServices->fetch(PDO::FETCH_ASSOC)) {
            echo '<p>' . $row['nombre'] . ': ' . $row['descripcion'] . ' - Precio: ' . $row['precio'] . '</p>';
        }
    } else {
        echo '<p>No se encontraron servicios.</p>';
    }
} else {
    echo '<p>Por favor, ingrese un término de búsqueda.</p>';
}

