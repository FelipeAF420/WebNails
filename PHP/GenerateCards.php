<?php
// Incluir el archivo de conexión
require_once('Conection.php');

// Consulta para obtener las categorías de la base de datos
$sql = "SELECT id_categoria, nombre, imagen FROM Categoria";

try {
    // Ejecutar la consulta
    $stmt = $conexion->query($sql);

    // Iterar sobre los resultados y generar las tarjetas de categorías
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idCategoria = $row['id_categoria'];
        $nombreCategoria = $row['nombre'];
        $imagenCategoria = $row['imagen'];

        // Generar la tarjeta de categoría
        


        echo '<div class="card">';
        echo '<img src="' . $imagenCategoria . '" alt="' . $nombreCategoria . '">';
        echo '<div class="card-body">';
        echo '<h3 class="card-title">' . $nombreCategoria . '</h3>';
        echo '<button class="dropdown-btn"></button>'; // Botón del menú desplegable
        echo '<div class="dropdown-menu">';
        echo '<button class="editar-btn" data-id="' . $idCategoria . '">Editar</button>'; // Botón para editar con atributo de datos
        echo '<button class="eliminar-btn" data-id="' . $idCategoria . '">Eliminar</button>'; // Agrega el ID como un atributo de datos
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} catch (PDOException $e) {
    // En caso de error, imprimir el mensaje de error
    echo 'Error al obtener las categorías: ' . $e->getMessage();
}
?>
