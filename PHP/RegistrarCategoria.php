<?php
require_once('Conection.php');
require_once('Clases/Categoria.php');

// Manejo del formulario de registro y actualización de categorías
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Registro o actualización de categorías
    if(isset($_POST['nombre_categoria'])) {
        $nombreCategoria = $_POST['nombre_categoria'];
        $nombreImagen = $_FILES['imagen_categoria']['name'];
        $directorioImagenes = "../imagenes/";
        $rutaImagen = $directorioImagenes . basename($nombreImagen);
        $editCategoriaId = isset($_POST['edit_categoria_id']) ? $_POST['edit_categoria_id'] : null; // Obtener el ID de la categoría si se está realizando una actualización

        if (move_uploaded_file($_FILES['imagen_categoria']['tmp_name'], $rutaImagen)) {
            if ($editCategoriaId) {
                // Actualizar la categoría existente
                $categoria = new Categoria($nombreCategoria, $rutaImagen);
                $categoria->setId($editCategoriaId);
                if (actualizarCategoria($categoria)) {
                    echo "<script>alert('Categoría actualizada correctamente.');</script>";
                } else {
                    echo "<script>alert('Error al actualizar la categoría.');</script>";
                }
            } else {
                // Insertar una nueva categoría
                if (categoriaExiste($nombreCategoria)) {
                    echo "<script>alert('La categoría ya existe en la base de datos.');</script>";
                } else {
                    $categoria = new Categoria($nombreCategoria, $rutaImagen);
                    if (agregarCategoria($categoria)) {
                        echo "<script>alert('Categoría agregada correctamente.');</script>";
                    } else {
                        echo "<script>alert('Error al agregar la categoría.');</script>";
                    }
                }
            }
        } else {
            echo "<script>alert('Error al subir la imagen.');</script>";
        }
    }
    // Eliminación de categorías
    elseif(isset($_POST['idCategoria'])) {
        $idCategoria = $_POST['idCategoria'];

        if(eliminarCategoria($idCategoria)) {
            echo json_encode(array('success' => true));
            exit;
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error al eliminar la categoría.'));
            exit;
        }
    } else {
        echo "<script>alert('Error: No se recibieron datos del formulario.');</script>";
    }
} else {
    echo "<script>alert('Error: No se recibieron datos del formulario.');</script>";
}

// Funciones para manejar las operaciones con la base de datos
function categoriaExiste($nombreCategoria) {
    global $conexion;
    $sql = "SELECT COUNT(*) FROM Categoria WHERE nombre = :nombre";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombreCategoria, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result > 0;
}

function agregarCategoria($categoria) {
    global $conexion;
    $sql = "INSERT INTO Categoria (nombre, imagen) VALUES (:nombre, :imagen)";
    try {
        $stmt = $conexion->prepare($sql);
        $nombre = $categoria->getNombre();
        $imagen = $categoria->getImagen();
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        return $stmt->execute(); // Devolver true si la inserción fue exitosa, false en caso contrario
    } catch (PDOException $e) {
        return false;
    }
}

function actualizarCategoria($categoria) {
    global $conexion;
    $sql = "UPDATE Categoria SET nombre = :nombre, imagen = :imagen WHERE id_categoria = :id";
    try {
        $stmt = $conexion->prepare($sql);
        $nombre = $categoria->getNombre();
        $imagen = $categoria->getImagen();
        $id = $categoria->getId();
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // Devolver true si la actualización fue exitosa, false en caso contrario
    } catch (PDOException $e) {
        return false;
    }
}

function eliminarCategoria($idCategoria) {
    global $conexion;
    $sql = "DELETE FROM Categoria WHERE id_categoria = :id";
    try {
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $idCategoria, PDO::PARAM_INT);
        return $stmt->execute(); // Devuelve true si la eliminación fue exitosa, false en caso contrario
    } catch (PDOException $e) {
        return false;
    }
}
?>
