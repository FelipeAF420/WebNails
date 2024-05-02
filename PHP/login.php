<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ButFirstNails";

// Crear conexión con la base de datos
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    die(json_encode(['success' => false, 'message' => 'No se recibieron datos del formulario.']));
}

// Variables del formulario
$correo = $data['correo'] ?? '';
$nombre = $data['nombre'] ?? '';
$apellidoP = $data['apellidoP'] ?? '';
$apellidoM = $data['apellidoM'] ?? '';
$num_celular = $data['num_celular'] ?? '';
$contraseña = $data['contraseña'] ?? '';

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

    $sql = "INSERT INTO Cliente (correo, nombre, apellidoP, apellidoM, num_celular, contraseña) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $correo, nombre, $apellidoP, $apellidoM, num_celular, $hashedPassword);

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
