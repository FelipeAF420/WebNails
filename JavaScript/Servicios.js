$(document).ready(function(){
    // Mostrar la ventana emergente de categoría al presionar el botón correspondiente
    $("#btnAgregarCategoria").click(function(event){
        event.stopPropagation(); // Detener la propagación del evento para evitar conflictos
        $(".ventana-emergente-categoria").fadeIn(); // Muestra la ventana emergente de categoría
        $("#tituloCategoria").text("Registrar Categoría"); // Cambia el texto del título del formulario
        $("#btnSubmitCategoria").text("Registrar"); // Cambia el texto del botón del formulario
        limpiarFormulario();
    });

    // Mostrar la ventana emergente de subcategoría al presionar el botón correspondiente
    $("#btnAgregarSubcategoria").click(function(event){
        event.stopPropagation(); // Detener la propagación del evento para evitar conflictos
        $(".ventana-emergente-subcategoria").fadeIn(); // Muestra la ventana emergente de subcategoría
        $("#tituloSubcategoria").text("Registrar Subcategoría"); // Cambia el texto del título del formulario
        $("#btnSubmitSubcategoria").text("Registrar"); // Cambia el texto del botón del formulario
        limpiarFormulario();
    });

    // Mostrar la ventana emergente de servicio al presionar el botón correspondiente
    $("#btnAgregarServicio").click(function(event){
        event.stopPropagation(); // Detener la propagación del evento para evitar conflictos
        $(".ventana-emergente-servicio").fadeIn(); // Muestra la ventana emergente de servicio
        $("#tituloServicio").text("Registrar Servicio"); // Cambia el texto del título del formulario
        $("#btnSubmitServicio").text("Registrar"); // Cambia el texto del botón del formulario
        limpiarFormulario();
    });

        // Manejar la eliminación de categorías al hacer clic en el botón "Eliminar" en el menú desplegable
    $(".dropdown-menu").on('click', '.eliminar-btn', function(event){
        event.stopPropagation(); // Detener la propagación del evento para evitar conflictos

        // Obtener el ID de la categoría a eliminar
        var idCategoria = $(this).data('id'); // Obtener el ID de la categoría del atributo de datos

        // Obtener el nombre de la categoría a eliminar
        var card = $(this).closest('.card'); // Encuentra el card más cercano
        var categoria = card.find('.card-title').text().trim(); // Obtiene el nombre de la categoría del card

        // Mostrar mensaje de confirmación
        if(confirm("¿Está seguro de que desea eliminar la categoría '" + categoria + "'?")) {
            // Enviar solicitud para eliminar la categoría
            $.ajax({
                url: '../PHP/RegistrarCategoria.php',
                method: 'POST',
                data: { idCategoria: idCategoria }, // Enviar el ID de la categoría al servidor
                success: function(response) {
                    alert('Categoría eliminada correctamente.');
                    reloadPage();
                },
                error: function(xhr, status, error) {
                    console.error('Error al eliminar la categoría:', error);
                    alert('Error al eliminar la categoría. Por favor, inténtelo de nuevo.');
                }
            });
        }
    });
});

$(document).on('click', '.editar-btn', function(event){
    event.preventDefault(); // Prevenir el comportamiento predeterminado del botón
    event.stopPropagation();
    // Obtener los datos del card
    var categoriaId = $(this).data('id');

        // Obtener los datos del card
    var card = $(this).closest('.card'); // Encuentra el card más cercano
    var categoria = card.find('.card-title').text().trim(); // Obtiene el nombre de la categoría del card
    var imagen = card.find('img').attr('src'); // Obtiene la URL de la imagen del card
        // Colocar los datos del card en el formulario de actualización
    $("#edit_categoria_id").val(categoriaId); // Establece el valor del ID de categoría en el campo oculto del formulario
    $("#nombre_categoria").val(categoria); // Establece el nombre de la categoría en el campo del formulario
    $("#imagen_categoria").attr('src', imagen);
    $("#imagen_preview").attr('src', imagen).show(); // Establece la URL de la imagen en la vista previa y la muestra

        // Mostrar la ventana emergente de actualización de categoría
    $("#ventanaAgregarCategoria").fadeIn();
    $("#tituloCategoria").text("Actualizar Categoría"); // Cambia el texto del título del formulario
    $("#btnSubmitSCategoria").text("Actualizar"); // Cambia el texto del botón del formulario


});

    // Editar subcategoría
$(document).on('click', '.editar-subcategoria-btn', function(event){
    event.preventDefault(); // Prevenir el comportamiento predeterminado del botón
    event.stopPropagation();
    // Obtener los datos del card
    var card = $(this).closest('.card'); // Encuentra el card más cercano
    var subcategoriaId = $(this).data('id'); // Obtiene el ID de la subcategoría del botón de edición
    var nombreSubcategoria = card.find('.card-title').text().trim(); // Obtiene el nombre de la subcategoría del card
    var categoriaId = card.find('.categoria-id').val(); // Obtiene el ID de la categoría desde el campo oculto del card
    var imagenSubcategoria = card.find('img').attr('src'); // Obtiene la URL de la imagen del card

    // Llenar el formulario con los datos del card
    $("#edit_subcategoria_id").val(subcategoriaId); // Establece el valor del ID de subcategoría en el campo oculto del formulario
    $("#nombre").val(nombreSubcategoria); // Establece el nombre de la subcategoría en el campo del formulario
    $("#categoria").val(categoriaId); // Establece el ID de la categoría asociada en el campo del formulario
    $("#imagen_previewsub").attr('src', imagenSubcategoria).show(); // Establece la URL de la imagen en la vista previa y la muestra

    // Mostrar la ventana emergente de edición de subcategoría
    $("#ventanaAgregarSubcategoria").fadeIn();
    $("#tituloSubcategoria").text("Actualizar Subcategoría"); // Cambia el texto del título del formulario
    $("#btnSubmitSubcategoria").text("Actualizar Subcategoría"); // Cambia el texto del botón del formulario
});


// Editar servicio
$(document).on('click', '.editar-servicio-btn', function(event){
    event.preventDefault(); // Prevenir el comportamiento predeterminado del botón
    event.stopPropagation();
    
    // Obtener los datos del card
    var card = $(this).closest('.card-servicios'); // Encuentra el card más cercano
    var servicioId = $(this).data('id'); // Obtiene el ID del servicio del botón de edición
    var nombreServicio = card.find('.card-title').text().trim(); // Obtiene el nombre del servicio del card
    var descripcionServicio = card.find('.description-container p').text().trim(); // Obtiene la descripción del servicio del card
    var precioServicioText = card.find('.precio').text().trim(); // Obtiene el texto que contiene el precio del servicio del card
    // Extraer el precio del texto
    var precioServicio = parseFloat(precioServicioText.split('$')[1].trim()); // Obtiene el precio del servicio del card
    var subcategoriaId = card.find('.subcategoria-id').val(); // Obtiene el ID de la subcategoría desde el campo oculto del card
    var imagenServicio = card.find('.card-img-top').attr('src'); // Obtiene la URL de la imagen del card

    // Llenar el formulario con los datos del card
    $("#edit_servicio_id").val(servicioId); // Establece el valor del ID de servicio en el campo oculto del formulario
    $("#nombre_servicio").val(nombreServicio); // Establece el nombre del servicio en el campo del formulario
    $("#descripcion").val(descripcionServicio); // Establece la descripción del servicio en el campo del formulario
    $("#precio").val(precioServicio); // Establece el precio del servicio en el campo del formulario
    $("#subcategoria").val(subcategoriaId); // Establece el ID de la subcategoría asociada en el campo del formulario
    $("#imagen_previewser").attr('src', imagenServicio).show(); // Establece la URL de la imagen en la vista previa y la muestra

    // Mostrar la ventana emergente de edición de servicio
    $("#ventanaAgregarServicio").fadeIn();
    $("#tituloServicio").text("Actualizar Servicio"); // Cambia el texto del título del formulario
    $("#btnSubmitSubcategoria").text("Actualizar Servicio"); // Cambia el texto del botón del formulario
});








//eliminar subcategoria
$(document).on('click', '.eliminar-subcategoria-btn', function(event){
    event.stopPropagation(); // Detener la propagación del evento para evitar conflictos

    // Obtener el ID de la subcategoría a eliminar
    var idSubcategoria = $(this).data('id'); // Obtener el ID de la subcategoría del atributo de datos
    console.log("ID del servicio a eliminar: " + idSubcategoria);
    // Obtener el nombre de la subcategoría a eliminar
    var card = $(this).closest('.card'); // Encuentra el card más cercano
    var subcategoria = card.find('.card-title').text().trim(); // Obtiene el nombre de la subcategoría del card

    // Mostrar mensaje de confirmación
    if(confirm("¿Está seguro de que desea eliminar la subcategoría '" + subcategoria + "'?")) {
        // Enviar solicitud para eliminar la subcategoría
        $.ajax({
            url: '../PHP/RegistrarSubcat.php',
            method: 'POST',
            data: { idSubcategoria: idSubcategoria }, // Enviar el ID de la subcategoría al servidor
            success: function(response) {
                alert('Subcategoría eliminada correctamente.');
                reloadPage(); // Recargar la página para mostrar los cambios
            },
            error: function(xhr, status, error) {
                console.error('Error al eliminar la subcategoría:', error);
                alert('Error al eliminar la subcategoría. Por favor, inténtelo de nuevo.');
            }
        });
    }
});





//eliminar servicio
$(document).on('click', '.eliminar-servicio-btn', function(event){
    event.preventDefault(); // Prevenir el comportamiento predeterminado del botón
    event.stopPropagation();
    
    // Obtener el ID del servicio a eliminar
    var idServicio = $(this).data('id'); // Obtener el ID del servicio del atributo de datos
    console.log("ID del servicio a eliminar: " + idServicio);
    // Obtener el nombre del servicio a eliminar
    var card = $(this).closest('.card-servicios'); // Encuentra el card más cercano
    var servicio = card.find('.card-title').text().trim(); // Obtiene el nombre del servicio del card

    // Mostrar mensaje de confirmación
    if(confirm("¿Está seguro de que desea eliminar el servicio '" + servicio + "'?")) {
        // Enviar solicitud para eliminar el servicio
        $.ajax({
            url: '../PHP/RegistrarServicio.php',
            method: 'POST',
            data: { idServicio: idServicio }, // Enviar el ID del servicio al servidor
            success: function(response) {
                alert('Servicio eliminado correctamente.');
                reloadPage(); // Recargar la página para reflejar los cambios
            },
            error: function(xhr, status, error) {
                console.error('Error al eliminar el servicio:', error);
                alert('Error al eliminar el servicio. Por favor, inténtelo de nuevo.');
            }
        });
    }

});






function limpiarFormulario() {
    $("#edit_categoria_id").val(''); // Borra el valor del ID de categoría
    $("#nombre_categoria").val(''); // Borra el nombre de la categoría
    $("#nombre_servicio").val('');
    $("#subcategoria").val('');
    $("#nombre").val(''); // Borra el nombre de la categoría
    $("#imagen").val('');
    $("#precio").val('');
    $("#descripcion").val('');
    $("#imagen_categoria").val(''); // Borra la URL de la imagen en la vista previa
    $("#imagen_preview").attr('src', ''); // Borra la URL de la imagen en la vista previa
    $("#imagen_previewsub").attr('src', '');
    $("#imagen_previewser").attr('src', '');

    $("#categoria").val('');
}

// Ocultar las ventanas emergentes al hacer clic fuera de ellas
$(document).click(function(event) {
    if (!$(event.target).closest('.ventana-emergente').length) {
        $(".ventana-emergente").fadeOut(); // Oculta todas las ventanas emergentes
        //limpiarFormulario(); // Limpia los campos del formulario
    }
});



// Recargar la página después de agregar, actualizar o eliminar una categoría
function reloadPage() {
    location.reload();
}








// Evento para manejar la selección de una subcategoría
document.getElementById('subcategorias').addEventListener('click', function(event) {
    // Verificar si se hizo clic en una tarjeta de subcategoría
    var targetCard = event.target.closest('.card');
    if (targetCard) {
        // Obtener el nombre de la subcategoría seleccionada
        var nombreSubcategoria = targetCard.querySelector('.card-title').innerText;

        // Enviar una solicitud AJAX al servidor para obtener los servicios relacionados
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../PHP/ObtenerServicios.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Actualizar el contenido del contenedor de servicios con la respuesta del servidor
                document.getElementById('servicios').innerHTML = xhr.responseText;
            } else {
                console.error('Error al obtener los servicios:', xhr.statusText);
            }
        };
        xhr.onerror = function() {
            console.error('Error de red al obtener los servicios');
        };
        xhr.send('subcategoria=' + encodeURIComponent(nombreSubcategoria));
    }
});


//mostrar subcategorias
$(document).ready(function() {
    $('.card').click(function() {
        var categoriaSeleccionada = $(this).find('.card-title').text();
        obtenerSubcategorias(categoriaSeleccionada);
        limpiarServicios();
    });
});

function obtenerSubcategorias(categoria) {
    $.ajax({
        type: 'POST',
        url: '../PHP/ObtenerSubCat.php',
        data: { categoria: categoria },
        success: function(response) {
            $('#subcategorias').html(response);
        },
        error: function() {
            alert('Error al cargar las subcategorías');
        }
    });
}

function limpiarServicios() {
    $('#servicios').empty(); // Elimina los servicios anteriores
}




$(document).ready(function(){
    // Abrir y cerrar el menú desplegable al hacer clic en el botón
    $(document).on('click', '.dropdown-btn', function(){
        $(this).next('.dropdown-menu').toggleClass('show');
    });

    // Cerrar el menú al hacer clic fuera de él
    $(document).click(function(event) {
        if (!$(event.target).closest('.dropdown-btn').length && !$(event.target).closest('.dropdown-menu').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });
});

// Controlador de eventos para el cambio de archivo en el input de imagen de categoría
$('#imagen_categoria').on('change', function() {
    var file = $(this).prop('files')[0]; // Obtener el archivo seleccionado

    // Verificar si se seleccionó un archivo
    if (file) {
        var reader = new FileReader(); // Crear un objeto FileReader

        // Controlador de eventos para cuando se carga el archivo
        reader.onload = function(e) {
            $('#imagen_preview').attr('src', e.target.result).show(); // Actualizar la vista previa de la imagen
        };

        reader.readAsDataURL(file); // Leer el archivo como una URL de datos
    }
});


$(document).on('change','#imagen', '#imagen_servicio', function(event) {
    var input = event.target;
    var reader = new FileReader();

    reader.onload = function() {
        var dataURL = reader.result;

        $('#imagen_previewsub').attr('src', dataURL); // Actualizar la vista previa de la imagen
        $('#imagen_previewser').attr('src', dataURL); // Actualizar la vista previa de la imagen
      
    };

    reader.readAsDataURL(input.files[0]);
});
