<?php
require_once('Conection.php');
require_once('Clases/Servicio.php');

// Manejo del formulario de registro y actualización de servicios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Registro o actualización de servicios
    if(isset($_POST['nombre'])) {
        $nombreServicio = $_POST['nombre'];
        $descripcionServicio = $_POST['descripcion'];
        $precioServicio = $_POST['precio'];
        $imagenServicio = $_FILES['imagen']['name'];
        $subcategoriaId = $_POST['subcategoria'];
        $directorioImagenes = "../imagenes/";
        $rutaImagen = $directorioImagenes . basename($imagenServicio);
        $editServicioId = isset($_POST['edit_servicio_id']) ? $_POST['edit_servicio_id'] : null; // Obtener el ID del servicio si se está realizando una actualización

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaImagen)) {
            if ($editServicioId) {
                // Actualizar el servicio existente
                $servicio = new Servicio($subcategoriaId, $nombreServicio, $descripcionServicio, $precioServicio, $rutaImagen);
                $servicio->setId($editServicioId);
                if (actualizarServicio($servicio)) {
                    echo "<script>alert('Servicio actualizado correctamente.');</script>";
                } else {
                    echo "<script>alert('Error al actualizar el servicio.');</script>";
                }
            } else {
                // Insertar un nuevo servicio
                $servicio = new Servicio($subcategoriaId, $nombreServicio, $descripcionServicio, $precioServicio, $rutaImagen);
                if (agregarServicio($servicio)) {
                    echo "<script>alert('Servicio agregado correctamente.');</script>";
                } else {
                    echo "<script>alert('Error al agregar el servicio.');</script>";
                }
            }
        } else {
            echo "<script>alert('Error al subir la imagen.');</script>";
        }
    }
    // Eliminación de servicios
    elseif(isset($_POST['idServicio'])) {
        $idServicio = $_POST['idServicio'];

        if(eliminarServicio($idServicio)) {
            echo json_encode(array('success' => true));
            exit;
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error al eliminar el servicio.'));
            exit;
        }
    } else {
        echo "<script>alert('Error: No se recibieron datos del formulario.');</script>";
    }
} else {
    echo "<script>alert('Error: No se recibieron datos del formulario.');</script>";
}

// Funciones para manejar las operaciones con la base de datos
function agregarServicio($servicio) {
    global $conexion;
    $sql = "INSERT INTO Servicio (id_subCategoria, nombre, descripcion, precio, imagen) VALUES (:idSubcategoria, :nombre, :descripcion, :precio, :imagen)";
    try {
        $stmt = $conexion->prepare($sql);
        $idSubcategoria = $servicio->getIdSubcategoria();
        $nombre = $servicio->getNombre();
        $descripcion = $servicio->getDescripcion();
        $precio = $servicio->getPrecio();
        $imagen = $servicio->getImagen();
        $stmt->bindParam(':idSubcategoria', $idSubcategoria, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        return $stmt->execute(); // Devolver true si la inserción fue exitosa, false en caso contrario
    } catch (PDOException $e) {
        return false;
    }
}

function actualizarServicio($servicio) {
    global $conexion;
    $sql = "UPDATE Servicio SET id_subCategoria = :idSubcategoria, nombre = :nombre, descripcion = :descripcion, precio = :precio, imagen = :imagen WHERE id_servicio = :id";
    try {
        $stmt = $conexion->prepare($sql);
        $idSubcategoria = $servicio->getIdSubcategoria();
        $nombre = $servicio->getNombre();
        $descripcion = $servicio->getDescripcion();
        $precio = $servicio->getPrecio();
        $imagen = $servicio->getImagen();
        $id = $servicio->getId();
        $stmt->bindParam(':idSubcategoria', $idSubcategoria, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
        $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute(); // Devolver true si la actualización fue exitosa, false en caso contrario
    } catch (PDOException $e) {
        return false;
    }
}

// Eliminar registros relacionados en la tabla EmpleadoServicio
function eliminarRegistrosEmpleadoServicio($idServicio) {
    global $conexion;
    $sql = "DELETE FROM EmpleadoServicio WHERE id_servicio = :idServicio";
    try {
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':idServicio', $idServicio, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        return false;
    }
}

// Eliminar servicio de la tabla Servicio
function eliminarServicio($idServicio) {
    global $conexion;
    // Primero eliminar registros relacionados en la tabla EmpleadoServicio
    if(eliminarRegistrosEmpleadoServicio($idServicio)) {
        // Luego eliminar el servicio de la tabla Servicio
        $sql = "DELETE FROM Servicio WHERE id_servicio = :idServicio";
        try {
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(':idServicio', $idServicio, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    } else {
        return false;
    }
}

?>
