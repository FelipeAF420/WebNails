<?php
// Incluir el archivo de conexión
require_once('Conection.php');

// Verificar si se recibió el nombre de la categoría
if(isset($_POST['categoria'])) {
    $categoriaSeleccionada = $_POST['categoria'];

    // Consulta para obtener las subcategorías asociadas a la categoría seleccionada
    $sql = "SELECT id_subcategoria, nombre, imagen, id_categoria FROM Subcategoria WHERE id_categoria IN (SELECT id_categoria FROM Categoria WHERE nombre = :categoria)";

    try {
        // Preparar y ejecutar la consulta
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':categoria', $categoriaSeleccionada, PDO::PARAM_STR);
        $stmt->execute();

        // Mostrar el título "Subcategorías"
        echo '<h2>Subcategorías</h2>';

        // Generar las tarjetas de subcategorías
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $idSubcategoria = $row['id_subcategoria'];
            $nombreSubcategoria = $row['nombre'];
            $imagenSubcategoria = $row['imagen'];
            $categoriaId = $row['id_categoria'];

            // Generar la tarjeta de subcategoría
            echo '<div class="card">';
            echo '<input type="hidden" class="categoria-id" value="'.$categoriaId.'">'; // Campo oculto para almacenar el ID de la categoría
            echo '<img src="' . $imagenSubcategoria . '" class="card-img-top" alt="' . $nombreSubcategoria . '">';
            echo '<div class="card-body">';
            echo '<h3 class="card-title">' . $nombreSubcategoria . '</h3>';
            echo '<button class="dropdown-btn"></button>'; // Botón del menú desplegable
            echo '<div class="dropdown-menu">';
            echo '<button class="editar-subcategoria-btn" data-id="' . $idSubcategoria . '">Editar</button>';
            echo '<button class="eliminar-subcategoria-btn" data-id="' . $idSubcategoria . '">Eliminar</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }

    } catch (PDOException $e) {
        // En caso de error, imprimir el mensaje de error
        echo 'Error al obtener las subcategorías: ' . $e->getMessage();
    }
} else {
    // Si no se recibió el nombre de la categoría, mostrar un mensaje de error
    echo 'Error: No se recibió el nombre de la categoría.';
}
?>
