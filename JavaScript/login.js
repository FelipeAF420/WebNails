// Mostrar u ocultar la contraseña
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('passwordInput');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
});

// Manejar el formulario de registro
document.getElementById('registrationForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Obtener los datos del formulario
    const form = event.target;
    const formData = new FormData(form);

    // Enviar una solicitud POST al servidor con los datos del formulario y la imagen (si se proporciona)
    fetch('../PHP/login.php', {
        method: 'POST',
        body: formData // Usar FormData para incluir la imagen y los datos del formulario
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
        // Verifica si data incluye el mensaje de éxito o redirección
        if (data.includes("Registro exitoso")) {
            alert('Registro exitoso. Redirigiendo al perfil.');
            // Redirigir a la página de perfil
            window.location.href = 'perfil.html';
        } else {
            // Maneja otros mensajes de respuesta (por ejemplo, errores)
            alert('Ocurrió un error: ' + data);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error');
    });
});
