<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Subcategoría</title>
    <link rel="stylesheet" href="../CSS/styles-cat-subcat-ser.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Subcategoría</h2>
        <form action="../PHP/RegistrarSubcat.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria" required>
                    <?php
                    // Include the database connection file
                    require_once('../PHP/Conection.php');
                    
                    // Query to fetch categories from the database
                    $sql = "SELECT id_categoria, nombre FROM Categoria";
                    $result = $conexion->query($sql);
                    
                    // Loop through the fetched categories and display them as options
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value=\"{$row['id_categoria']}\">{$row['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre de la Subcategoría:</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen de la Subcategoría:</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>
            </div>
            <button type="submit">Registrar Subcategoría</button>
        </form>
    </div>
</body>
</html>
