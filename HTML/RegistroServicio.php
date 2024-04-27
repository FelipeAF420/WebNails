<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Servicio</title>
    <link rel="stylesheet" href="../CSS/styles-cat-subcat-ser.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Servicio</h2>
        <form action="../PHP/RegistrarServicio.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="subcategoria">Subcategoría:</label>
                <select id="subcategoria" name="subcategoria" required>
                <?php
                    // Include the database connection file
                    require_once('../PHP/Conection.php');
                    
                    // Query to fetch categories from the database
                    $sql = "SELECT id_subCategoria, nombre FROM subCategoria";
                    $result = $conexion->query($sql);
                    
                    // Loop through the fetched categories and display them as options
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=\"{$row['id_subCategoria']}\">{$row['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre del Servicio:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción del Servicio:</label>
                <input type="text" id="descripcion" name="descripcion" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio del Servicio:</label>
                <input type="number" id="precio" name="precio" required>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen del Servicio:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>
            </div>
            <button type="submit">Registrar Servicio</button>
        </form>
    </div>
</body>
</html>
