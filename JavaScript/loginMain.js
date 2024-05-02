// Manejar el formulario de inicio de sesión
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Obtener los datos del formulario
    const form = event.target;
    const correo = form.elements['correo'].value;
    const contraseña = form.elements['contraseña'].value;

    // Enviar una solicitud POST al servidor con los datos del formulario
    fetch('../PHP/loginMain.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            correo,
            contraseña
        })
    })
    .then(response => {
        // Verifica si la respuesta fue exitosa
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor: ' + response.statusText);
        }
        return response.text(); // Leer la respuesta como texto
    })
    .then(data => {
        // Manejar la respuesta del servidor
        console.log('Respuesta del servidor:', data);
        // Si la respuesta contiene la redirección, redirige al perfil
        if (data.includes("Location: perfil.php")) {
            window.location.href = 'perfil.php';
        } else {
            // Muestra un mensaje de error si el inicio de sesión falló
            alert('Inicio de sesión fallido: ' + data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al iniciar sesión.');
    });
});
