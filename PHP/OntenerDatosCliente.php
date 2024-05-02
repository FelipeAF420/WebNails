<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['correo'])) {
    // Si el usuario no ha iniciado sesión, redirigir al formulario de inicio de sesión
    header("Location: ../HTML/loginMain.html");
    exit();
}

// Incluir el archivo de conexión
include_once "Conection.php";

try {
    // Consulta SQL para obtener los datos del cliente
    $correo = $_SESSION['correo'];
    $consulta = "SELECT * FROM Cliente WHERE correo = :correo";
    $stmt = $conexion->prepare($consulta);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();

    // Obtener los datos del cliente
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    // Manejar errores de conexión a la base de datos
    echo "Error al obtener datos del cliente: " . $e->getMessage();
}
?>
