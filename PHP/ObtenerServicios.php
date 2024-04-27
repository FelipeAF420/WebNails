<?php
// Incluir el archivo de conexión
require_once('Conection.php');

// Verificar si se recibió el nombre de la subcategoría
if(isset($_POST['subcategoria'])) {
    $subcategoriaSeleccionada = $_POST['subcategoria'];

    // Consulta para obtener los servicios asociados a la subcategoría seleccionada
    $sql = "SELECT id_servicio, nombre, descripcion, precio, imagen, id_subCategoria FROM Servicio WHERE id_subCategoria IN (SELECT id_subCategoria FROM Subcategoria WHERE nombre = :subcategoria)";

    try {
        // Preparar y ejecutar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':subcategoria', $subcategoriaSeleccionada, PDO::PARAM_STR);
        $stmt->execute();

        echo '<h2>Servicios</h2>';


        // Mostrar los servicios
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $idServicio = $row['id_servicio'];
            $nombreServicio = $row['nombre'];
            $descripcionServicio = $row['descripcion'];
            $precioServicio = $row['precio'];
            $imagenServicio = $row['imagen'];
            $subcategoriaId = $row['id_subCategoria'];

            // Generar la tarjeta de servicio
            echo '<div class="card-servicios">';
            echo '<input type="hidden" class="subcategoria-id" value="'.$subcategoriaId.'">'; // Campo oculto para almacenar el ID de la categoría
            echo '<img src="' . $imagenServicio . '" class="card-img-top" alt="' . $nombreServicio . '">';
            echo '<div class="card-body">';
            echo '<h3 class="card-title">' . $nombreServicio . '</h3>';
            echo '<p class="descripcion-titulo">Descripción:</p>'; // Etiqueta de la descripción
            echo '<div class="description-container"><p>' . $descripcionServicio . '</p></div>'; // Envuelve la descripción en un párrafo
            echo '<p class="precio">Precio: $' . $precioServicio . '</p>';
            echo '<button class="dropdown-btn"></button>'; // Botón del menú desplegable
            echo '<div class="dropdown-menu">';
            echo '<button class="editar-servicio-btn" data-id="' . $idServicio . '">Editar</button>';
            echo '<button class="eliminar-servicio-btn" data-id="' . $idServicio . '">Eliminar</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    } catch (PDOException $e) {
        // En caso de error, imprimir el mensaje de error
        echo 'Error al obtener los servicios: ' . $e->getMessage();
    }
} else {
    // Si no se recibió el nombre de la subcategoría, mostrar un mensaje de error
    echo 'Error: No se recibió el nombre de la subcategoría.';
}
?>
