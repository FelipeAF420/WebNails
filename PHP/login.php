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
$stmt->bind_param('ss', $correo, $num_celular);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // El correo o el número ya están registrados
    echo json_encode(['success' => false, 'message' => 'El correo electrónico o el número de teléfono ya están registrados.']);
} else {
    // Insertar el nuevo cliente en la base de datos
    $hashedPassword = password_hash($contraseña, PASSWORD_BCRYPT);

    $sql = "INSERT INTO Cliente (correo, nombre, apellidoP, apellidoM, num_celular, contraseña) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssss', $correo, nombre, $apellidoP, $apellidoM, num_celular, $hashedPassword);

    if ($stmt->execute()) {
        // Registro exitoso
        // Generar token de verificación
        $token = bin2hex(random_bytes(16));

        // Opcional: Almacenar el token en la base de datos
        // $sql_token = "INSERT INTO Verificacion (correo, token) VALUES (?, ?)";
        // $stmt_token = $conn->prepare($sql_token);
        // $stmt_token->bind_param('ss', $correo, $token);
        // $stmt_token->execute();

        // Enviar correo electrónico de verificación
        $subject = "Verificación de correo electrónico";
        $message = "Hola $nombre,\n\nPor favor verifica tu correo haciendo clic en el siguiente enlace:\n\nhttps://tu-sitio.com/verificar.php?correo=$correo&token=$token";
        $headers = "From: noreply@tu-sitio.com";

        if (mail($correo, $subject, $message, $headers)) {
            echo json_encode(['success' => true, 'message' => 'Registro exitoso. Por favor verifica tu correo electrónico.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo enviar el correo de verificación.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error en el registro: ' . $stmt->error]);
    }
}

// Cerrar la conexión y las declaraciones preparadas
$stmt->close();
$conn->close();
?>
