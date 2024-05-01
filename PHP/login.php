<?php

require_once 'Conection.php';

// Verifica si la conexión a la base de datos está definida
if (!isset($conn) || $conn === null) {
    // Manejar el error si la conexión no está definida o es nula
    die("<script>alert('Error de conexión a la base de datos.'); window.history.back();</script>");
}

// Verificar si se ha recibido una solicitud POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener datos del formulario
    $correo = $_POST['correo'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellidoP = $_POST['apellidoP'] ?? '';
    $apellidoM = $_POST['apellidoM'] ?? '';
    $num_celular = $_POST['num_celular'] ?? '';
    $contraseña = $_POST['contraseña'] ?? '';

    // Verificar si el correo o el número ya están registrados
    $sql = "SELECT * FROM Cliente WHERE correo = ? OR num_celular = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("<script>alert('Error en la preparación de la consulta.'); window.history.back();</script>");
    }
    $stmt->bind_param('ss', $correo, $num_celular);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El correo o el número ya están registrados
        echo "<script>alert('El correo electrónico o el número de teléfono ya están registrados.'); window.history.back();</script>";
    } else {
        // Insertar el nuevo cliente en la base de datos
        $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT);

        // Preparar la consulta para insertar el nuevo cliente
        $sql = "INSERT INTO Cliente (correo, nombre, apellidoP, apellidoM, num_celular, contraseña) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("<script>alert('Error en la preparación de la consulta de inserción.'); window.history.back();</script>");
        }
        $stmt->bind_param('ssssss', $correo, $nombre, $apellidoP, $apellidoM, $num_celular, $hashedPassword);

        if ($stmt->execute()) {
            // Registro exitoso
            echo "<script>alert('Registro exitoso.'); window.location.href = 'perfil.html';</script>";
        } else {
            echo "<script>alert('Error en el registro: " . $stmt->error . "'); window.history.back();</script>";
        }
    }

    // Cerrar la declaración preparada
    $stmt->close();
}

// Cerrar la conexión con la base de datos
$conn->close();

?>
