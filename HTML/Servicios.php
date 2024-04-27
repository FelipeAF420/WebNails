<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías</title>
    <link rel="stylesheet" href="../CSS/styles-servicios.css">
</head>
<body>
<!-- Botones de agregar -->
<div class="contenedor-botones">
    <button id="btnAgregarCategoria" class="btn-agregar">Agregar Categoría</button>
    <button id="btnAgregarSubcategoria" class="btn-agregar">Agregar Subcategoría</button>
    <button id="btnAgregarServicio" class="btn-agregar">Agregar Servicio</button>
</div>


    <!-- Ventanas emergentes -->
    <div id="ventanaAgregarCategoria" class="ventana-emergente ventana-emergente-categoria">
    <div class="contenedor">
        <h2 id="tituloCategoria">Registrar Categoría</h2>
        <form id="formActualizarCategoria" action="../PHP/RegistrarCategoria.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" id="edit_categoria_id" name="edit_categoria_id"> <!-- Campo oculto para almacenar el ID de la categoría -->
        
            <div class="form-group">
                <label for="nombre_categoria">Nombre de la Categoría:</label>
                <input type="text" id="nombre_categoria" name="nombre_categoria" required>
            </div>
            <div class="form-group">
                <label for="imagen_categoria">Imagen de la Categoría:</label>
                <input type="file" id="imagen_categoria" name="imagen_categoria" accept="image/*" required>
                <img id="imagen_preview" src="#" alt="Vista previa de la imagen" style="max-width: 100%; max-height: 200px;">
            </div>
            <button id="btnSubmitCategoria" type="submit">Registrar Categoría</button>
          
        </form>
    </div>
    </div>


    <div id="ventanaAgregarSubcategoria" class="ventana-emergente ventana-emergente-subcategoria">
    <div class="contenedor">
        <h2 id="tituloSubcategoria">Registro de Subcategoría</h2>
        <form id="RegistrarSubcategoria" action="../PHP/RegistrarSubcat.php" method="POST" enctype="multipart/form-data">
            <!-- Campo oculto para almacenar el ID de la subcategoría en caso de actualización -->
            <input type="hidden" id="edit_subcategoria_id" name="edit_subcategoria_id">
            <div class="form-group">
                <label for="categoria">Categoría:</label>
                <select id="categoria" name="categoria" required>
                    <?php
                    require_once('../PHP/Conection.php');
                    
                    $sql = "SELECT id_categoria, nombre FROM Categoria";
                    $result = $conexion->query($sql);
                    
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
                <img id="imagen_previewsub" src="#" alt="Vista previa de la imagen" style="max-width: 100%; max-height: 200px;">
            </div>
            <button id="btnSubmitSubcategoria" type="submit">Registrar Subcategoría</button> <!-- Cambiamos el ID para poder modificarlo dinámicamente -->
        </form>
    </div>
    </div>

    <div id="ventanaAgregarServicio" class="ventana-emergente ventana-emergente-servicio">
    <div class="contenedor">
        <h2 id="tituloServicio"> Registro de Servicio</h2>
        <form id="RegistrarServicio" action="../PHP/RegistrarServicio.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" id="edit_servicio_id" name="edit_servicio_id">

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
                <label for="nombre_servicio">Nombre del Servicio:</label>
                <input type="text" id="nombre_servicio" name="nombre" required>
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
                <img id="imagen_previewser" src="#" alt="Vista previa de la imagen" style="max-width: 100%; max-height: 200px;">
            </div>
            </div>
            <button id="btnSubmitServicio" type="submit">Registrar Servicio</button>
        </form>
    </div>
    </div>
    <h2>Categorías</h2>
    <div class="scroll-container">
        <div class="cards" id="categorias">
            <?php include '../PHP/GenerateCards.php'; ?>
        </div>
    </div>
    <div class="scroll-container">
        <div class="cards" id="subcategorias">
        </div>
    </div>
    <div class="scroll-container">
        <div class="cards" id="servicios">
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 

    <script src="../javaScript/Servicios.js"></script>
    
</body>
</html>
