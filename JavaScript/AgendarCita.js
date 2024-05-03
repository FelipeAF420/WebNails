var serviciosSeleccionados = [];
 $(document).ready(function() {
   
    function actualizarCampoServicios() {
        $('#serviciosSeleccionados').val(JSON.stringify(serviciosSeleccionados));
    }

        // Al cargar la página, seleccionar automáticamente la opción "All services"
    $('#categoria').append('<option value="all" selected>All services</option>');
    cargarTodosLosServicios();
    $('#subcategoria').prop('disabled', true).val(''); // Deshabilitar y limpiar la selección

    // Función para cargar las subcategorías al seleccionar una categoría
    $('#categoria').change(function() {
        var categoriaId = $(this).val(); // Obtener el ID de la categoría seleccionada
        if (categoriaId == 'all') {
            // Si se selecciona "All services", deshabilitar el select de subcategorías
            $('#subcategoria').prop('disabled', true).val(''); // Deshabilitar y limpiar la selección
            cargarTodosLosServicios(); // Cargar todos los servicios
        } else {
            // Si se selecciona una categoría normal, habilitar el select de subcategorías
            $('#subcategoria').prop('disabled', false);
            cargarSubcategorias(categoriaId); // Cargar las subcategorías normales
        }
    });
    cargarCategorias();



// Evento de clic para los cards de servicio utilizando delegación de eventos
     $(document).on('click', '.servicio-card', function() {
        // Obtener el ID del servicio del atributo data
        var servicioId = $(this).data('id');

        // Alternar la clase de selección del card de servicio
        $(this).toggleClass('seleccionado');

        // Verificar si el card de servicio está seleccionado
        if ($(this).hasClass('seleccionado')) {
            // Si está seleccionado, agregar el ID del servicio a la lista de servicios seleccionados
            serviciosSeleccionados.push(servicioId);
            actualizarCampoServicios();
        } else {
            
            // Si no está seleccionado, eliminar el ID del servicio de la lista de servicios seleccionados
            var index = serviciosSeleccionados.indexOf(servicioId);
            if (index !== -1) {
                serviciosSeleccionados.splice(index, 1);
                actualizarCampoServicios();
            }
        }

        // Actualizar el campo oculto con la lista de servicios seleccionados
        actualizarCampoServicios();

        // Imprimir la lista de servicios seleccionados en la consola (para propósitos de prueba)
        console.log('Servicios seleccionados:', serviciosSeleccionados);
    });
    
});

 // Función para cargar las categorías
function cargarCategorias() {
    // Realizar una solicitud AJAX al archivo obtener_categorias.php
    $.ajax({
        url: '../PHP/RecuperarCategoriaEmpleado.php',
        method: 'GET',
        dataType: 'json',
        success: function(categorias) {
            // Limpiar el primer select antes de agregar nuevas opciones
            $('#categoria').empty();
            
            // Agregar la opción "Todos" al primer select
            $('#categoria').append('<option  value="all" selected>All services</option>');
            
            // Iterar sobre las categorías recibidas y agregarlas al primer select
            $.each(categorias, function(index, categoria) {
                $('#categoria').append('<option value="' + categoria.id_categoria + '">' + categoria.nombre + '</option>');
            });
        },
        error: function(xhr, status, error) {
            // En caso de error, mostrar un mensaje de error en la consola del navegador
            console.error('Error al cargar las categorías:', error);
        }
    });
}

function cargarSubcategorias(categoriaId) {
    // Realizar la solicitud AJAX al script PHP para obtener las subcategorías
    $.ajax({
        url: '../PHP/RecuperarSubcatEmpleado.php', // Ruta del script PHP
        type: 'GET',
        data: { categoria: categoriaId }, // Enviar el ID de la categoría como parámetro
        dataType: 'json', // Tipo de datos esperado en la respuesta
        success: function(response) {
            // Limpiar el contenido actual del select de subcategorías
            $('#subcategoria').empty();
            
            // Verificar si se recibieron subcategorías
            if (response && response.length > 0) {
                // Iterar sobre cada subcategoría y agregarla al select
                $.each(response, function(index, subcategoria) {
                    $('#subcategoria').append(`<option value="${subcategoria.id_subCategoria}">${subcategoria.nombre}</option>`);
                });
                // Seleccionar automáticamente la primera opción del select de subcategorías
                $('#subcategoria option:first-child').prop('selected', true);
                // Llamar a la función para cargar los servicios de la subcategoría seleccionada automáticamente
                cargarServicios($('#subcategoria').val());
            } else {
                // Mostrar un mensaje si no se encontraron subcategorías
                $('#subcategoria').html('<option value="">No se encontraron subcategorías</option>');
            }
        },
        error: function(xhr, status, error) {
            // Mostrar un mensaje de error si la solicitud AJAX falla
            console.error('Error al cargar las subcategorías:', error);
        }
    });
}
   $('#subcategoria').change(function() {
    var subcategoriaId = $(this).val(); // Obtener el ID de la subcategoría seleccionada
    cargarServicios(subcategoriaId); // Llamar a la función para cargar los servicios
});


function cargarServicios(subcategoriaId) {
    // Realizar la solicitud AJAX al script PHP para obtener los servicios
    $.ajax({
        url: '../PHP/RecuperarServicios.php', // Ruta del script PHP
        type: 'GET',
        data: { subcategoria: subcategoriaId }, // Enviar el ID de la subcategoría como parámetro
        dataType: 'json', // Tipo de datos esperado en la respuesta
        success: function(response) {
            // Limpiar el contenido actual de los servicios
            $('#containerServicios').empty();
            
            // Verificar si se recibieron servicios
            if (response && response.length > 0) {
                // Iterar sobre cada servicio y agregarlo al contenedor de servicios
                $.each(response, function(index, servicio) {
                    // Crear un nuevo card de servicio y agregarlo al contenedor
                    var servicioCard = $(`<div class="servicio-card" data-id="${servicio.id_servicio}">
                        <img src="${servicio.imagen}" alt="${servicio.nombre}">
                        <h3>${servicio.nombre}</h3>
                    </div>`);
                    if (serviciosSeleccionados.includes(servicio.id_servicio)) {
                        servicioCard.addClass('seleccionado');
                    }
                    $('#containerServicios').append(servicioCard);
                });

            } else {
                // Mostrar un mensaje si no se encontraron servicios
                $('#containerServicios').html('<p>No se encontraron servicios</p>');
            }
        },
        error: function(xhr, status, error) {
            // Mostrar un mensaje de error si la solicitud AJAX falla
            console.error('Error al cargar los servicios:', error);
        }
    });
}


function cargarTodosLosServicios() {
    // Realizar la solicitud AJAX al script PHP para obtener todos los servicios
    $.ajax({
        url: '../PHP/RecuperarAllServices.php', // Ruta del script PHP para obtener todos los servicios
        type: 'GET',
        dataType: 'json', // Tipo de datos esperado en la respuesta
        success: function(response) {
            // Limpiar el contenido actual de los servicios
            $('#containerServicios').empty();
            
            // Verificar si se recibieron servicios
            if (response && response.length > 0) {
                // Iterar sobre cada servicio y agregarlo al contenedor de servicios
                $.each(response, function(index, servicio) {
                    // Crear un nuevo card de servicio y agregarlo al contenedor
                    var servicioCard = $(`<div class="servicio-card" data-id="${servicio.id_servicio}">
                        <img src="${servicio.imagen}" alt="${servicio.nombre}">
                        <h3>${servicio.nombre}</h3>
                    </div>`);
                    if (serviciosSeleccionados.includes(servicio.id_servicio)) {
                        servicioCard.addClass('seleccionado');
                    }
                    $('#containerServicios').append(servicioCard);
                });

            } else {
                // Mostrar un mensaje si no se encontraron servicios
                $('#containerServicios').html('<p>No se encontraron servicios</p>');
            }
        },
        error: function(xhr, status, error) {
            // Mostrar un mensaje de error si la solicitud AJAX falla
            console.error('Error al cargar los servicios:', error);
        }
    });
}
