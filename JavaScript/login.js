document.getElementById('registrationForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Obtener los datos del formulario
    const form = event.target;
    const correo = form.elements['correo'].value;
    const nombre = form.elements['nombre'].value;
    const apellidoP = form.elements['apellidoP'].value;
    const apellidoM = form.elements['apellidoM'].value;
    const num_celular = form.elements['num_celular'].value;
    const contraseña = form.elements['contraseña'].value;
    const confirmarContraseña = form.elements['confirmarContraseña'].value;

    // Validar que las contraseñas coincidan
    if (contraseña !== confirmarContraseña) {
        alert('Las contraseñas no coinciden.');
        return;
    }

    // Enviar una solicitud POST al servidor
    fetch('login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            correo,
            nombre,
            apellidoP,
            apellidoM,
            num_celular,
            contraseña
        })
    })
    .then(response => response.json())
    .then(data => {
        // Manejar la respuesta del servidor
        if (data.success) {
            alert('Registro exitoso. Por favor verifica tu correo electrónico.');
            // Puedes redirigir al usuario a otra página después del registro exitoso
            // window.location.href = 'perfil.html';
        } else {
            alert('Error en el registro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error');
    });
});
