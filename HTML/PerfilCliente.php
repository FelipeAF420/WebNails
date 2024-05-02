<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Cliente</title>
    <link rel="stylesheet" href="../CSS/PerfilCliente.css">
</head>
<body>
<?php include_once "../PHP/OntenerDatosCliente.php"; ?>

<div class="container">
    <div class="profile-header">
        <h2>Perfil del Cliente</h2>
        <!-- Aquí puedes agregar una imagen de perfil del cliente -->
        <div class="profile-avatar">
            <img src="../imagenes/<?php echo $cliente['imagen']; ?>" alt="Imagen de perfil del cliente">
        </div>
    </div>


    <div class="profile-details">
        <h3>Información Personal</h3>
        <p><strong>Nombre:</strong> <?php echo $cliente['nombre'] . ' ' . $cliente['apellidoP'] . ' ' . $cliente['apellidoM']; ?></p>
        <p><strong>Correo Electrónico:</strong> <?php echo $cliente['correo']; ?></p>
        <p><strong>Número de Teléfono:</strong> <?php echo $cliente['num_celular']; ?></p>
        <!-- Aquí puedes agregar botones para editar la información del cliente -->
        <button id="editar-informacion">Editar Información</button>
    </div>
    <div class="profile-appointments">
        <h3>Historial de Citas</h3>
        <!-- Aquí puedes mostrar el historial de citas del cliente -->
    </div>
    <form action="../PHP/CerrarSesion.php" method="post">
    <button type="submit" name="CerrarSesion">Cerrar Sesión</button>
</form>
</div>

</body>
</html>
