$(document).ready(function(){
    // Mostrar la ventana emergente de categoría al presionar el botón correspondiente
    $("#btnAgregarEmpleado").click(function(event){
        event.stopPropagation(); // Detener la propagación del evento para evitar conflictos
        $(".ventana-emergente-empleado").fadeIn(); // Muestra la ventana emergente de categoría

    });
});

$(document).click(function(event) {
    if (!$(event.target).closest('.ventana-emergente').length) {
        $(".ventana-emergente").fadeOut(); // Oculta todas las ventanas emergentes
        //limpiarFormulario(); // Limpia los campos del formulario
    }
});


// Cuando la página se cargue, cargar las categorías
$(document).ready(function() {
    cargarCategorias();
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

$(document).ready(function() {
    // Lista para almacenar los IDs de los servicios seleccionados
    var serviciosSeleccionados = [];

    $(document).on('click', '.editar-empleado', function(event) {
        event.preventDefault(); // Evitar que el enlace se comporte como un enlace normal

        // Obtener el ID del empleado del atributo data
        var idEmpleado = $(this).data('id');
        $('#empleadoId').val(empleadoId);
        // Realizar una solicitud AJAX para obtener los datos del empleado por su ID
        $.ajax({
            url: '../PHP/ObtenerEmpleadoPorId.php',
            method: 'GET',
            dataType: 'json',
            data: { id: idEmpleado },
            success: function(data) {
                // Llenar automáticamente los campos del formulario con los datos del empleado
                $('#nombre').val(data.nombre);
                $('#apellidoP').val(data.apellidoP);
                $('#apellidoM').val(data.apellidoM);
                $('#imagen-preview').attr('src', data.imagen);

                // Almacenar los IDs de los servicios asociados al empleado
                serviciosSeleccionados = data.servicios;

                // Seleccionar automáticamente los servicios asociados al empleado
                $('.servicio-card').removeClass('seleccionado'); // Deseleccionar todos los servicios
                serviciosSeleccionados.forEach(function(servicioId) {
                    $('.servicio-card[data-id="' + servicioId + '"]').addClass('seleccionado');
                });

                // Mostrar la ventana emergente de edición de empleado
                $(".ventana-emergente-empleado").fadeIn();
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener los datos del empleado:', error);
                // Aquí puedes mostrar un mensaje de error al usuario si lo deseas
            }
        });
    });



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

    // Función para cargar todos los servicios
    function cargarTodosLosServicios() {
        // Realizar la solicitud AJAX al script PHP para obtener todos los servicios
        $.ajax({
            url: '../PHP/RecuperarAllServices.php', // Ruta del script PHP para obtener todos los servicios
            type: 'GET',
            dataType: 'json', // Tipo de datos esperado en la respuesta
            success: function(response) {
                // Limpiar el contenido actual de los servicios
                $('#serviciosList').empty();
                
                // Verificar si se recibieron servicios
                if (response && response.length > 0) {
                    // Iterar sobre cada servicio y agregarlo al contenedor de servicios
                    $.each(response, function(index, servicio) {
                        // Crear un nuevo card de servicio y agregarlo al contenedor
                        var servicioCard = $(`<div class="servicio-card" data-id="${servicio.id_servicio}">
                            <img src="${servicio.imagen}" alt="${servicio.nombre}">
                            <h3>${servicio.nombre}</h3>
                        </div>`);
                        // Verificar si el servicio está seleccionado y agregar la clase 'seleccionado' en consecuencia
                        if (serviciosSeleccionados.includes(servicio.id_servicio)) {
                            servicioCard.addClass('seleccionado');
                        }
                        $('#serviciosList').append(servicioCard);
                    });

                } else {
                    // Mostrar un mensaje si no se encontraron servicios
                    $('#serviciosList').html('<p>No se encontraron servicios</p>');
                }
            },
            error: function(xhr, status, error) {
                // Mostrar un mensaje de error si la solicitud AJAX falla
                console.error('Error al cargar los servicios:', error);
            }
        });
    }

    // Función para cargar las subcategorías
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


    // Función para cargar los servicios
    function cargarServicios(subcategoriaId) {
        // Realizar la solicitud AJAX al script PHP para obtener los servicios
        $.ajax({
            url: '../PHP/RecuperarServicios.php', // Ruta del script PHP
            type: 'GET',
            data: { subcategoria: subcategoriaId }, // Enviar el ID de la subcategoría como parámetro
            dataType: 'json', // Tipo de datos esperado en la respuesta
            success: function(response) {
                // Limpiar el contenido actual de los servicios
                $('#serviciosList').empty();
                
                // Verificar si se recibieron servicios
                if (response && response.length > 0) {
                    // Iterar sobre cada servicio y agregarlo al contenedor de servicios
                    $.each(response, function(index, servicio) {
                        // Crear un nuevo card de servicio y agregarlo al contenedor
                        var servicioCard = $(`<div class="servicio-card" data-id="${servicio.id_servicio}">
                            <img src="${servicio.imagen}" alt="${servicio.nombre}">
                            <h3>${servicio.nombre}</h3>
                        </div>`);
                        // Verificar si el servicio está seleccionado y agregar la clase 'seleccionado' en consecuencia
                        if (serviciosSeleccionados.includes(servicio.id_servicio)) {
                            servicioCard.addClass('seleccionado');
                        }
                        $('#serviciosList').append(servicioCard);
                    });

                } else {
                    // Mostrar un mensaje si no se encontraron servicios
                    $('#serviciosList').html('<p>No se encontraron servicios</p>');
                }
            },
            error: function(xhr, status, error) {
                // Mostrar un mensaje de error si la solicitud AJAX falla
                console.error('Error al cargar los servicios:', error);
            }
        });
    }

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

    $(document).ready(function() {
        // Manejar el envío del formulario
        $('#formularioRegistroEmpleado').submit(function(event) {
            // Verificar si no se han seleccionado servicios
            enviarHorario();
            if (serviciosSeleccionados.length === 0) {
                // Mostrar un mensaje de error al usuario
                alert("Debes seleccionar al menos un servicio.");
                // Evitar que se envíen los datos del formulario
                event.preventDefault();
            }
            if (Object.keys(horariosSemanales).length === 0) {
                // Mostrar un mensaje de error al usuario
                alert("Debes agregar al menos un horario.");
                // Evitar que se envíen los datos del formulario
                event.preventDefault();
                return;
            }
    
            // Si se han seleccionado servicios y se han definido horarios, continuar con el envío del formulario

        });
    });
    
});


// Función para cargar y mostrar los empleados en cards
$(document).ready(function() {
    // Función para cargar los empleados
    function cargarEmpleados() {
        $.ajax({
            url: '../PHP/ObtenerEmpleado.php',
            method: 'GET',
            dataType: 'json',
            success: function(empleados) {
                // Limpiar el contenedor de empleados antes de agregar nuevos empleados
                $('.scroll-containerEmpleados').empty();
    
                // Iterar sobre los empleados recibidos
                $.each(empleados, function(index, empleado) {
                    // Crear el card del empleado
                    var empleadoCard = $('<div class="card-empleado"></div>');
    
                    // Agregar la imagen del empleado al card
                    var imagenEmpleado = $('<img class="imagen-empleado">');
                    imagenEmpleado.attr('src', empleado.imagen);
                    empleadoCard.append(imagenEmpleado);
    
                    // Agregar el nombre y apellidos del empleado al card
                    var nombreCompleto = empleado.nombre + ' ' + empleado.apellidoP + ' ' + empleado.apellidoM;
                    empleadoCard.append('<h3>' + nombreCompleto + '</h3>');
    
                    // Crear un contenedor para los servicios del empleado
                    var serviciosContainer = $('<div class="scroll-containerServicios"></div>');
    
                    // Realizar una solicitud AJAX para obtener los servicios del empleado
                    $.ajax({
                        url: '../PHP/ObtenerServicioEmpleado.php?idEmpleado=' + empleado.id_empleado,
                        method: 'GET',
                        dataType: 'json',
                        success: function(servicios) {
                            // Agregar cada servicio al contenedor de servicios del empleado
                            $.each(servicios, function(index, servicio) {
                                // Crear el card del servicio
                                var servicioCard = $('<div class="card-servicioEmpleado"></div>');
    
                                // Agregar la imagen del servicio al card
                                var imagenServicio = $('<img class="imagen-servicio">');
                                imagenServicio.attr('src', servicio.imagen);
                                servicioCard.append(imagenServicio);
    
                                // Agregar el nombre del servicio al card
                                servicioCard.append('<h4>' + servicio.nombre + '</h4>');
    
                                // Agregar el card del servicio al contenedor de servicios del empleado
                                serviciosContainer.append(servicioCard);
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al obtener los servicios del empleado:', error);
                        }
                    });
    
                    // Agregar el contenedor de servicios al card del empleado
                    empleadoCard.append(serviciosContainer);
    
                    // Crear el botón de menú desplegable
                    var dropdownBtn = $('<div class="dropdown-btn">⋮</div>');
    
                    // Crear el contenido del menú desplegable
                    // Agregar el contenido del menú desplegable al botón con un ID que almacene el ID del empleado
                    var dropdownContent = $('<div class="dropdown-content">' +
                    '<a href="#" class="editar-empleado" data-id="' + empleado.id_empleado + '">Editar</a>' +
                    '<a href="#" class="eliminar-empleado" data-id="' + empleado.id_empleado + '">Eliminar</a>' +
                    '</div>');

    
                    // Agregar el contenido del menú desplegable al botón
                    dropdownBtn.append(dropdownContent);
    
                    // Agregar el botón de menú desplegable al card del empleado
                    empleadoCard.append(dropdownBtn);
    
                    // Agregar el card del empleado al contenedor de empleados
                    $('.scroll-containerEmpleados').append(empleadoCard);
    
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar los empleados:', error);
            }
        });
    }
    

    // Cargar los empleados al cargar la página
    cargarEmpleados();
});



$(document).ready(function() {
    // Evento de clic para el botón de eliminar empleado utilizando delegación de eventos
    $(document).on('click', '.eliminar-empleado', function() {
        // Obtener el ID del empleado a eliminar
        var empleadoId = $(this).data('id');
        
        // Confirmar la eliminación
        if (confirm('¿Estás seguro de que quieres eliminar este empleado y sus servicios relacionados?')) {
            // Realizar una solicitud AJAX para eliminar el empleado y sus servicios
            $.ajax({
                url: '../PHP/EliminarEmpleado.php',
                method: 'POST',
                data: { id_empleado: empleadoId },
                success: function(response) {
                    // Mostrar mensaje de éxito
                    alert(response);
                    // Eliminar el elemento del DOM correspondiente al empleado eliminado
                    $(this).closest('.card-empleado').remove();
                },
                error: function(xhr, status, error) {
                    // Mostrar mensaje de error si la solicitud falla
                    console.error('Error al eliminar empleado:', error);
                }
            });
        }
    });
});



function clearTime() {
    document.getElementById("horaInicio").value = "";
    document.getElementById("horaFin").value = "";
    document.getElementById("horaIniciodescanso").value = "";
    document.getElementById("horaFindescanso").value = "";

    // Obtener el valor del día seleccionado
    var diaSemana = document.getElementById("diaSemana").value;

    // Verificar si todos los campos de tiempo están vacíos
    var todosVacios = !document.getElementById("horaInicio").value &&
                      !document.getElementById("horaFin").value &&
                      !document.getElementById("horaIniciodescanso").value &&
                      !document.getElementById("horaFindescanso").value;

    // Si todos los campos están vacíos, eliminar el horario del día del arreglo
    if (todosVacios) {
        eliminarHorarioDelArreglo(diaSemana);
    }
}

// Función para eliminar el horario del día correspondiente del arreglo
function eliminarHorarioDelArreglo(diaSemana) {
    // Verificar si existe un horario para el día en el arreglo
    if (horariosSemanales.hasOwnProperty(diaSemana)) {
        delete horariosSemanales[diaSemana];
    }
}

// Arreglo para almacenar los horarios de trabajo
var horariosSemanales = {};


// Función para capturar los horarios de trabajo ingresados
function enviarHorario() {
   
    var diaSemana = document.getElementById("diaSemana").value;
    var horaInicio = document.getElementById("horaInicio").value;
    var horaFin = document.getElementById("horaFin").value;
    var horaInicioDescanso = document.getElementById("horaIniciodescanso").value;
    var horaFinDescanso = document.getElementById("horaFindescanso").value;

    if (horaInicio && !horaFin) {
        alert("Horario incompleto.");
        return false;
    }
    if (!horaInicio && horaFin) {
        alert("Horario incompleto.");
        return false;
    }
    
    if (horaInicio && horaFin && horaInicio >= horaFin) {
        alert("La hora de inicio debe ser menor que la hora de fin.");
        return false;
    }
    if ((horaInicioDescanso || horaFinDescanso) && !(horaInicio && horaFin)) {
        alert("Debes establecer un horario antes de definir un horario de descanso.");
        return false;
    }
    if (horaInicioDescanso && !horaFinDescanso) {
        alert("Horario incompleto.");
        return false;
    }
    if (!horaInicioDescanso && horaFinDescanso) {
        alert("Horario incompleto.");
        return false;
    }
    if (horaInicioDescanso && horaFinDescanso) {
        if (horaInicioDescanso < horaInicio || horaFinDescanso > horaFin) {
            alert("Las horas de descanso deben estar dentro del rango de la hora de inicio y la hora de fin.");
            return false;
        }
    }
    var todosVacios = !document.getElementById("horaInicio").value &&
    !document.getElementById("horaFin").value &&
    !document.getElementById("horaIniciodescanso").value &&
    !document.getElementById("horaFindescanso").value;

// Si todos los campos están vacíos, eliminar el horario del día del arreglo
    if (todosVacios) {
    return false
    }


    var horarios = {
        "diaSemana": diaSemana,
        "horaInicio": horaInicio,
        "horaFin": horaFin,
        "horaInicioDescanso": horaInicioDescanso,
        "horaFinDescanso": horaFinDescanso
    };

    // Agregar los horarios al arreglo utilizando el día de la semana como clave
    horariosSemanales[diaSemana] = horarios;
    
    console.log("Horarios enviados:", JSON.stringify(horariosSemanales));

    document.getElementById("horariosSeleccionados").value = JSON.stringify(horariosSemanales);


    // Si todas las validaciones pasan, devuelve true para enviar el formulario
    return true;
}


// Evento change para el select de día de la semana
$('#diaSemana').change(function() {
    // Llamar a la función enviarHorario() para capturar los horarios de trabajo al cambiar la opción
   
    
    var diaSeleccionado = this.value;
    var horarios = horariosSemanales[diaSeleccionado];
    if (horarios) {
        // Si existen horarios definidos para el día seleccionado, actualizar los campos de entrada de tiempo
        $('#horaInicio').val(horarios.horaInicio);
        $('#horaFin').val(horarios.horaFin);
        $('#horaIniciodescanso').val(horarios.horaInicioDescanso);
        $('#horaFindescanso').val(horarios.horaFinDescanso);
    } else {
        // Si no existen horarios definidos para el día seleccionado, borrar los campos de entrada de tiempo
        $('#horaInicio').val('');
        $('#horaFin').val('');
        $('#horaIniciodescanso').val('');
        $('#horaFindescanso').val('');
    }
    enviarHorario();
});


