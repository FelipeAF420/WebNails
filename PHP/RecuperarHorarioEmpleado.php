<?php
// Obtener la conexión a la base de datos (debes configurar esto según tu entorno)
include 'Conection.php';

// Verificar si se proporcionó el ID del empleado y el día de la semana en la solicitud GET
if(isset($_GET['idEmpleado']) && isset($_GET['diaSemana'])) {
    // Obtener el ID del empleado y el día de la semana de la solicitud GET
    $idEmpleado = $_GET['idEmpleado'];
    $diaSemana = $_GET['diaSemana'];

    // Consultar la base de datos para obtener el horario del día seleccionado del empleado
    $query = "SELECT * FROM HorarioEmpleado WHERE id_empleado = :idEmpleado AND dia_semana = :diaSemana";
    $statement = $conexion->prepare($query);
    $statement->bindParam(':idEmpleado', $idEmpleado);
    $statement->bindParam(':diaSemana', $diaSemana);
    $statement->execute();

    // Obtener el resultado de la consulta como un array asociativo
    $horario = $statement->fetch(PDO::FETCH_ASSOC);

    // Devolver el horario del día seleccionado como respuesta en formato JSON
    echo json_encode($horario);
} else {
    // Si no se proporcionó el ID del empleado o el día de la semana, devolver un mensaje de error
    echo json_encode(array('mensaje' => 'ID de empleado o día de la semana no proporcionados'));
}
?>
