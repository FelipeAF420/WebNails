<?php
// Iniciar sesión
session_start();

// Verificar si se enviaron datos por el formulario de inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    // Incluir el archivo de conexión
    include_once "Conection.php";

    try {
        // Consulta SQL para verificar las credenciales del usuario
        $consulta = "SELECT * FROM Cliente WHERE correo = :correo AND contrasena = :contrasena";
        $stmt = $conexion->prepare($consulta);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':contrasena', $contraseña);
        $stmt->execute();

        // Verificar si se encontraron resultados
        if ($stmt->rowCount() == 1) {
            // Iniciar sesión y redirigir al perfil del cliente
            $_SESSION['correo'] = $correo;
            header("Location: ../HTML/PerfilCliente.php");
            exit();
        } else {
            // Si las credenciales son incorrectas, mostrar un mensaje de error
            $error = "Correo electrónico o contraseña incorrectos.";
        }
    } catch(PDOException $e) {
        // Manejar errores de conexión a la base de datos
        $error = "Error de conexión: " . $e->getMessage();
    }
}
?>

