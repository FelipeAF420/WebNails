<?php
// Incluir la clase Cliente y la conexión a la base de datos
require_once 'Clases/Cliente.php';
require_once 'Conection.php'; // Asegúrate de tener este archivo con la conexión a tu base de datos

// Verificar si se enviaron datos desde el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $apellidoPaterno = $_POST["apellidoPaterno"];
    $apellidoMaterno = $_POST["apellidoMaterno"];
    $correo = $_POST["correo"];
    $contrasena = $_POST["contraseña"];
    $numCelular = $_POST["celular"];

    // Crear un nuevo objeto Cliente con los datos del formulario
    $cliente = new Cliente($nombre, $apellidoPaterno, $apellidoMaterno, $correo, $contrasena, $numCelular, '', 0, 0, 0);

    // Insertar el cliente en la base de datos
    $query = "INSERT INTO Cliente (nombre, apellidoP, apellidoM, correo, contrasena, num_celular) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $nombre = $cliente->getNombre();
    $apellidoPaterno = $cliente->getApellidoPaterno();
    $apellidoMaterno = $cliente->getApellidoMaterno();
    $correo = $cliente->getCorreo();
    $contrasenaHash = $cliente->getContrasenaHash();
    $numCelular = $cliente->getNumCelular();
    $stmt->bindParam(1, $nombre);
    $stmt->bindParam(2, $apellidoPaterno);
    $stmt->bindParam(3, $apellidoMaterno);
    $stmt->bindParam(4, $correo);
    $stmt->bindParam(5, $contrasenaHash);
    $stmt->bindParam(6, $numCelular);


    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Registro exitoso
        echo "¡Registro exitoso!";
    } else {
        // Error al registrar
        $errorInfo = $stmt->errorInfo();
        echo "Error al registrar el cliente: " . $errorInfo[2];
    }
}
?>

