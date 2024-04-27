<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Empleado y Asignación de Servicios</title>
    <link rel="stylesheet" href="../CSS/RegistroEmpleado.css">
</head>
<body>
    <div class="containerEmpleado">
        <h2>Registro de Empleado y Asignación de Servicios</h2>
        <form action="RegistrarEmpleado.php" method="POST" enctype="multipart/form-data">
            <!-- Columna izquierda para nombre, apellidos e imagen -->
            <div class="form-column">
                <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required>
                </div>
                <div class="form-group">
                    <label for="apellidoP">Apellido Paterno:</label>
                    <input type="text" name="apellidoP" id="apellidoP" required>
                </div>
                <div class="form-group">
                    <label for="apellidoM">Apellido Materno:</label>
                    <input type="text" name="apellidoM" id="apellidoM" required>
                </div>
                <div class="form-group">
                    <label for="imagen">Imagen (URL):</label>
                    <input type="file" name="imagen" id="imagen">
                </div>
                <div class="form-group">
                    <img id="imagen-preview" class="preview-imagen" src="#" alt="Vista previa de la imagen">
                </div>
            </div>
            <!-- Columna derecha para la selección de servicios -->
            <div class="form-column">
                <div class="form-group">
                    <label for="categoria">Categoría:</label>
                    <div class="filtro-categoria">
                        <select name="categoria" id="categoria">
                            <!-- Las opciones de categoría se cargarán dinámicamente aquí -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="subcategoria">Subcategoría:</label>
                    <div class="filtro-subcategoria">
                        <select name="subcategoria" id="subcategoria">
                            <!-- Las opciones de subcategoría se cargarán dinámicamente aquí -->
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Servicios:</label>
                    <div id="serviciosList" class="servicios-list">
                        <!-- Los servicios se cargarán aquí mediante JavaScript -->
                    </div>
                </div>
            </div>
            <input type="submit" value="Registrar">
        </form>
    </div>
    <script>
        // Función para cargar los servicios dinámicamente desde el servidor
        function cargarServicios() {
            fetch('../PHP/RecuperarServicios.php')
            .then(response => response.json())
            .then(servicios => {
                const serviciosList = document.getElementById('serviciosList');
                servicios.forEach(servicio => {
                    const card = document.createElement('div');
                    card.classList.add('servicio-card');

                    const img = document.createElement('img');
                    img.src = servicio.imagen;
                    img.alt = servicio.nombre;
                    card.appendChild(img);

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.name = 'servicios[]';
                    checkbox.value = servicio.id_servicio;
                    card.appendChild(checkbox);

                    const nombreServicio = document.createElement('span');
                    nombreServicio.textContent = servicio.nombre;
                    card.appendChild(nombreServicio);

                    serviciosList.appendChild(card);
                });
            })
            .catch(error => console.error('Error al cargar los servicios:', error));
        }

        // Llamar a la función cargarServicios cuando la página se cargue completamente
        document.addEventListener('DOMContentLoaded', cargarServicios);
    </script>
</body>
</html>
