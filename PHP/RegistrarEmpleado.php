<?php
require_once 'Conection.php';
require_once 'Clases/Empleado.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario para el empleado
    $nombre = $_POST["nombre"];
    $apellidoPaterno = $_POST["apellidoP"];
    $apellidoMaterno = $_POST["apellidoM"];
    $imagen = $_FILES["imagen"];

    // Crear un objeto Empleado con los datos del formulario
    $empleado = new Empleado($nombre, $apellidoPaterno, $apellidoMaterno, $imagen);

    try {
        // Iniciar una transacción
        $conexion->beginTransaction();

        // Mover la imagen al directorio de almacenamiento
        $imagenRuta = guardarImagen($imagen);

        // Verificar si el empleado ya existe en la base de datos
        $query = "SELECT id_empleado FROM Empleado WHERE nombre = ? AND apellidoP = ? AND apellidoM = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$nombre, $apellidoPaterno, $apellidoMaterno]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            // Si el empleado ya existe, actualizar la imagen
            $idEmpleado = $resultado['id_empleado'];
            $query = "UPDATE Empleado SET imagen = ? WHERE id_empleado = ?";
            $stmt = $conexion->prepare($query);
            $stmt->execute([$imagenRuta, $idEmpleado]);
            actualizarServicios($conexion, $idEmpleado);
        } else {
            // Si el empleado no existe, insertarlo en la base de datos
            $query = "INSERT INTO Empleado (nombre, apellidoP, apellidoM, imagen) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->execute([$nombre, $apellidoPaterno, $apellidoMaterno, $imagenRuta]);
            $idEmpleado = $conexion->lastInsertId(); // Obtener el ID del empleado recién insertado
            actualizarServicios($conexion, $idEmpleado);
        }

        // Procesar los horarios de trabajo del empleado
        $horariosJSON = $_POST["horariosSeleccionados"];
        $horarios = json_decode($horariosJSON, true);
        procesarHorarios($conexion, $idEmpleado, $horarios);

        // Confirmar la transacción
        $conexion->commit();

        echo "Empleado registrado o actualizado correctamente.";
    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $conexion->rollBack();

        echo "Error al registrar o actualizar el empleado: " . $e->getMessage();
    } finally {
        // Cerrar la conexión
        $conexion = null;
    }
}

function procesarHorarios($conexion, $idEmpleado, $horarios) {
    foreach ($horarios as $diaSemana => $horario) {
        $query = "INSERT INTO HorarioEmpleado (id_empleado, dia_semana, hora_inicio, hora_fin, hora_descanso_inicio, hora_descanso_fin) 
                  VALUES (?, ?, ?, ?, ?, ?) 
                  ON DUPLICATE KEY UPDATE 
                  hora_inicio = VALUES(hora_inicio), 
                  hora_fin = VALUES(hora_fin), 
                  hora_descanso_inicio = VALUES(hora_descanso_inicio), 
                  hora_descanso_fin = VALUES(hora_descanso_fin)";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$idEmpleado, $diaSemana, $horario["horaInicio"], $horario["horaFin"], $horario["horaInicioDescanso"], $horario["horaFinDescanso"]]);
    }
}

// Función para guardar la imagen en el servidor
function guardarImagen($imagen) {
    $targetDir = "../imagenes/";
    $targetFilePath = $targetDir . basename($imagen["name"]);

    // Mover archivo al directorio de almacenamiento
    if (!move_uploaded_file($imagen["tmp_name"], $targetFilePath)) {
        throw new Exception("Error al cargar la imagen.");
    }

    return $targetFilePath;
}

function actualizarServicios($conexion, $idEmpleado) {
    if(isset($_POST['serviciosSeleccionados']) && !empty($_POST['serviciosSeleccionados'])) {
        // Convertir la cadena JSON de los servicios seleccionados a un array PHP
        $serviciosSeleccionados = json_decode($_POST['serviciosSeleccionados']);

        // Eliminar los servicios existentes del empleado
        $query = "DELETE FROM EmpleadoServicio WHERE id_empleado = ?";
        $stmt = $conexion->prepare($query);
        $stmt->execute([$idEmpleado]);

        // Insertar los nuevos servicios seleccionados del empleado
        foreach ($serviciosSeleccionados as $servicioId) {
            $query = "INSERT INTO EmpleadoServicio (id_empleado, id_servicio) VALUES (?, ?)";
            $stmt = $conexion->prepare($query);
            $stmt->execute([$idEmpleado, $servicioId]);
        }
    }
}
?>
