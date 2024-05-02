<?php
require_once 'Conection.php';
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['cliente_id'])) {
    // Redirige al usuario a la página de inicio de sesión si no ha iniciado sesión
    header("Location: login.html");
    exit();
}

// Recupera los datos del usuario de la base de datos
$cliente_id = $_SESSION['cliente_id'];
$sql = "SELECT * FROM Cliente WHERE id_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $cliente_id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();

// Cierra la declaración preparada
$stmt->close();

// Cierra la conexión con la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Cliente</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <div class="container">
        <h2>Perfil del Cliente</h2>
        <p><strong>Nombre:</strong> <?php echo $cliente['nombre'] . ' ' . $cliente['apellidoP'] . ' ' . $cliente['apellidoM']; ?></p>
        <p><strong>Correo electrónico:</strong> <?php echo $cliente['correo']; ?></p>
        <p><strong>Número de teléfono:</strong> <?php echo $cliente['num_celular']; ?></p>
        <p><strong>Asistencias:</strong> <?php echo $cliente['asistencias']; ?></p>
        <p><strong>Faltas:</strong> <?php echo $cliente['faltas']; ?></p>
        <p><strong>Cancelaciones:</strong> <?php echo $cliente['cancelaciones']; ?></p>

        <!-- Si quieres mostrar la imagen -->
        <?php if (!empty($cliente['imagen'])): ?>
            <p><strong>Imagen:</strong></p>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($cliente['imagen']); ?>" alt="Imagen del cliente">
        <?php endif; ?>

    </div>
</body>

</html>
