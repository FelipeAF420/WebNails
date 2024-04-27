// Obtén una referencia al contenedor de contenido
const content = document.querySelector('.page-content');

// Función para cargar el contenido de una página
// Función para cargar el contenido de una página
function loadPage(url) {
    fetch(url)
        .then(response => response.text())
        .then(data => {
            content.innerHTML = data;

            // Ejecutar JavaScript asociado a la página cargada
            const scriptElements = content.querySelectorAll('script');
            scriptElements.forEach(script => {
                if (script.src) {
                    const newScript = document.createElement('script');
                    newScript.src = script.src;
                    document.body.appendChild(newScript);
                } else {
                    eval(script.innerHTML);
                }
            });
        })
        .catch(error => console.error('Error al cargar la página:', error));
}


// Agrega listeners de eventos a los enlaces del menú lateral
const sidebarLinks = document.querySelectorAll('.sidebar a');
sidebarLinks.forEach(link => {
    link.addEventListener('click', function(event) {
        event.preventDefault(); // Evita el comportamiento predeterminado del enlace
        const page = this.getAttribute('href'); // Obtiene el atributo href del enlace
        loadPage(page); // Carga la página correspondiente
    });
});


function toggleMenu() {
    var sidebar = document.querySelector('.sidebar');
    var menuToggle = document.getElementById('menuToggle');

    // Alternar clase 'active' en el menú lateral
    sidebar.classList.toggle('active');

    // Cambiar el contenido del botón entre ☰ y ✕
    if (menuToggle.innerHTML === '☰') {
        menuToggle.innerHTML = '✕';
    }
    else{
        menuToggle.innerHTML = '☰';
    }
}z