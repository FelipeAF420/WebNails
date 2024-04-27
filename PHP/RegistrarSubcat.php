<?php
require_once('Conection.php');
require_once('Clases/Subcategoria.php');

// Manejo del formulario de registro y actualización de subcategorías
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Registro o actualización de subcategorías
    if(isset($_POST['nombre'])) {
        $nombreSubcategoria = $_POST['nombre'];
        $nombreImagen = $_FILES['imagen']['name'];
        $directorioImagenes = "../imagenes/";
        $rutaImagen = $directorioImagenes . basename($nombreImagen);
        $editSubcategoriaId = isset($_POST['edit_subcategoria_id']) ? $_POST['edit_subcategoria_id'] : null; // Obtener el ID de la subcategoría si se está realizando una actualización
        $idCategoria = $_POST['categoria']; // Obtener el ID de la categoría asociada

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            if ($editSubcategoriaId) {
                // Actualizar la subcategoría existente
                $subcategoria = new Subcategoria($idCategoria, $nombreSubcategoria, $rutaImagen);
                $subcategoria->setId($editSubcategoriaId);
                if (actualizarSubcategoria($subcategoria)) {
                    echo "<script>alert('Subcategoría actualizada correctamente.');</script>";
                } else {
                    echo "<script>alert('Error al actualizar la subcategoría.');</script>";
                }
            } else {
                // Insertar una nueva subcategoría
                if (subcategoriaExiste($nombreSubcategoria)) {
                    echo "<script>alert('La subcategoría ya existe en la base de datos.');</script>";
                } else {
                    $subcategoria = new Subcategoria($idCategoria, $nombreSubcategoria, $rutaImagen);
                    if (agregarSubcategoria($subcategoria)) {
                        echo "<script>alert('Subcategoría agregada correctamente.');</script>";
                    } else {
                        echo "<script>alert('Error al agregar la subcategoría.');</script>";
                    }
                }
            }
        } else {
            echo "<script>alert('Error al subir la imagen.');</script>";
        }
    }
    // Eliminación de subcategorías
    elseif(isset($_POST['idSubcategoria'])) {
        $idSubcategoria = $_POST['idSubcategoria'];

        if(eliminarSubcategoria($idSubcategoria)) {
            echo json_encode(array('success' => true));
            exit;
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error al eliminar la subcategoría.'));
            exit;
        }
    } else {
        echo "<script>alert('Error: No se recibieron datos del formulario.');</script>";
    }
} else {
    echo "<script>alert('Error: No se recibieron datos del formulario.');</script>";
}

// Funciones para manejar las operaciones con la base de datos
function subcategoriaExiste($nombreSubcategoria) {
    global $conexion;
    $sql = "SELECT COUNT(*) FROM Subcategoria WHERE nombre = :nombre";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombreSubcategoria, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetchColumn();
    return $result > 0;
}

function agregarSubcategoria($subcategoria) {
    global $conexion;
    $sql = "INSERT INTO Subcategoria (id_categoria, nombre, imagen) VALUES (:idCategoria, :nombre, :imagen)";
    try {
        $stmt = $conexion->prepare($sql);
        $idCategoria = $subcategoria->getIdCategoria();
        $nombre = $subcategoria->getNombre();
        $imagen = $subcategoria->getImagen();
        $stmt->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        return $stmt->execute(); // Devolver true si la inserción fue exitosa, false en caso contrario
    } catch (PDOException $e) {
        return false;
    }
}

function actualizarSubcategoria($subcategoria) {
    global $conexion;
    $sql = "UPDATE Subcategoria SET id_categoria = :idCategoria, nombre = :nombre, imagen = :imagen WHERE id_subcategoria = :id";
    try {
        $stmt = $conexion->prepare($sql);
        $idCategoria = $subcategoria->getIdCategoria();
        $nombre = $subcategoria->getNombre();
        $imagen = $subcategoria->getImagen();
        $id = $subcategoria->getId();
        $stmt->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // Devolver true si la actualización fue exitosa, false en caso contrario
    } catch (PDOException $e) {
        return false;
    }
}

function eliminarSubcategoria($idSubcategoria) {
    global $conexion;
    $sql = "DELETE FROM Subcategoria WHERE id_subcategoria = :id";
    try {
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $idSubcategoria, PDO::PARAM_INT);
        return $stmt->execute(); // Devuelve true si la eliminación fue exitosa, false en caso contrario
    } catch (PDOException $e) {
        return false;
    }
}
?>
