var serviciosSeleccionados = [];
var empleadosSeleccionados = [];
var servicioCounter = 1;

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

            // Mostrar los detalles del servicio seleccionado en el div de detalle de la cita
            mostrarDetalleCita(servicioId, servicioCounter);

            // Incrementar el contador de servicios
            servicioCounter++;
        } else {
            // Si no está seleccionado, eliminar el ID del servicio de la lista de servicios seleccionados
            var index = serviciosSeleccionados.indexOf(servicioId);
            if (index !== -1) {
                serviciosSeleccionados.splice(index, 1);
                actualizarCampoServicios();

                // Eliminar los detalles del servicio del div de detalle de la cita
                $('#detalle-cita').find(`#servicio-${servicioCounter - 1}`).remove();

                // Decrementar el contador de servicios
                servicioCounter--;
            }
        }

        // Actualizar el campo oculto con la lista de servicios seleccionados
        actualizarCampoServicios();
        cargarEmpleados(serviciosSeleccionados);
        console.log('Servicios seleccionados:', serviciosSeleccionados);
    });

    // Evento de clic para los cards de empleado utilizando delegación de eventos
    $(document).on('click', '.card-empleado', function() {
        // Obtener el ID del empleado seleccionado
        var empleadoId = $(this).data('id');

        // Alternar la clase de selección del card de empleado
        $(this).toggleClass('seleccionadoEmpleado');

        // Si el card de empleado está seleccionado, agregarlo a la lista de empleados seleccionados
        if ($(this).hasClass('seleccionadoEmpleado')) {
            empleadosSeleccionados.push(empleadoId);
        } else {
            // Si el card de empleado no está seleccionado, eliminarlo de la lista de empleados seleccionados
            var index = empleadosSeleccionados.indexOf(empleadoId);
            if (index !== -1) {
                empleadosSeleccionados.splice(index, 1);
               
            }
        }
        console.log('Empleados seleccionados:', empleadosSeleccionados);
    });
});

// Función para mostrar los detalles del servicio en el div de detalle de la cita
function mostrarDetalleCita(servicioId, counter) {
    // Realizar una solicitud AJAX para obtener los detalles del servicio
    $.ajax({
        url: '../PHP/ObtenerDetallesServicio.php',
        method: 'GET',
        data: { servicioId: servicioId },
        dataType: 'json',
        success: function(detalle) {
            // Construir el HTML para mostrar los detalles del servicio
            var detalleHtml = `
                <div id="servicio-${counter}">
                    <h3>Servicio ${counter}</h3>
                    <p>Servicio: ${detalle.nombre}</p>
                    <p>Descripción: ${detalle.descripcion}</p>
                    <p>Precio: ${detalle.precio}</p>
                </div>
            `;

            // Agregar los detalles del servicio al div de detalle de la cita
            $('#detalle-cita').append(detalleHtml);
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener los detalles del servicio:', error);
        }
    });
}

// Otras funciones omitidas por brevedad...

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


function cargarEmpleados(serviciosSeleccionados) {
    $.ajax({
        url: '../PHP/RecuperarEmpleadosPorServicio.php',
        type: 'GET',
        data: { servicios: serviciosSeleccionados },
        dataType: 'json',
        success: function(response) {
            // Limpiar el contenedor de empleados
            $('#empleados').empty();
            
            // Iterar sobre los empleados y mostrarlos en la página
            $.each(response, function(index, empleado) {
                // Crear el card del empleado
                var empleadoCard = $(`<div class="card-empleado" data-id="${empleado.id_empleado}"></div>`);


                // Agregar la imagen del empleado al card
                var imagenEmpleado = $('<img class="imagen-empleado">');
                imagenEmpleado.attr('src', empleado.imagen);
                empleadoCard.append(imagenEmpleado);

                // Agregar el nombre y apellidos del empleado al card
                var nombreCompleto = empleado.nombre + ' ' + empleado.apellidoP + ' ' + empleado.apellidoM;
                empleadoCard.append('<h3>' + nombreCompleto + '</h3>');
                $('#empleados').append(empleadoCard);
            });
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar los empleados:', error);
        }
    });
}
