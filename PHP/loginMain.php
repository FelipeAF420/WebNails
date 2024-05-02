<?php
require_once 'Conection.php';
require_once 'Clases/Cliente.php';

// Verificar si se ha recibido una solicitud POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener datos del formulario
    $correo = $_POST['correo'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';

    // Verificar si el correo electrónico y la contraseña coinciden en la base de datos
    $sql = "SELECT * FROM Cliente WHERE correo = ?;";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('s', $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verificar la contraseña
        if (password_verify($contraseña, $row['contraseña'])) {
            // Inicio de sesión exitoso, redirigir a perfil.php
            session_start();
            $_SESSION['cliente_id'] = $row['id_cliente'];
            header("Location: perfil.php");
            exit();
        } else {
            // Contraseña incorrecta
            echo "<script>alert('Contraseña incorrecta.'); window.history.back();</script>";
        }
    } else {
        // Correo no encontrado
        echo "<script>alert('Correo electrónico no encontrado.'); window.history.back();</script>";
    }

    // Cerrar declaraciones preparadas
    $stmt->close();
}

// Cerrar la conexión con la base de datos
$conexion->close();
?>

