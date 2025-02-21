var citasRecords; // Definida en un ámbito global

$(document).ready(function () {
    // Inicializar DataTable
    citasRecords = $('#citasTable').DataTable({
        "ajax": {
            "url": "../controller/citas_action.php",
            "type": "POST",
            "data": { action: 'listCitas' },
            "dataType": "json",
            "dataSrc": '',
            "error": function (xhr, error, code) {
                console.log("Error: " + error);
                console.log("Code: " + code);
                console.log(xhr.responseText);
                alert("Ocurrió un error al cargar los datos.");
            }
        },
        "columns": [
            { "data": "paciente" },
            { "data": "doctor" },
            { "data": "fecha" },
            {
                "data": "hora",
                "render": function (data, type, row) {
                    // Convertir la hora en formato adecuado (hh:mm)
                    var formattedTime = data.substring(0, 5); // Extrae solo la parte de la hora y minutos
                    return formattedTime;
                }
            },  
            {
                "data": null,
                "render": function (data, type, row) {
                    return '<div class="d-flex gap-2">' + // Contenedor flex con espacio entre los botones
                        '<button class="btn btn-danger btn-delete" data-id="' + row.id + '">Eliminar</button>' +
                        '<button class="btn btn-primary btn-edit" data-id="' + row.id + '">Modificar</button>' +
                        '</div>';
                }
            }
        ],
        "lengthChange": false,
        responsive: true
    });
});

// Manejar el evento de clic en el botón de modificar
$('#citasTable tbody').on('click', '.btn-edit', function () {
    var citaId = $(this).data('id');

    // Obtener la lista de doctores primero
    $.ajax({
        url: '../controller/citas_action.php',
        type: 'POST',
        data: { action: 'getDoctores' },
        dataType: 'json',
        success: function (doctores) {
            var doctorSelect = $('#editDoctor');
            doctorSelect.empty(); // Limpiar opciones previas
            $.each(doctores, function (index, doctor) {
                doctorSelect.append('<option value="' + doctor.nombre + '">' + doctor.nombre + '</option>');
            });

            // Ahora obtener la cita seleccionada
            $.ajax({
                url: '../controller/citas_action.php',
                type: 'POST',
                data: { action: 'getCita', id: citaId },
                dataType: 'json',
                success: function (data) {
                    $('#editCitaId').val(data.id);
                    $('#editPaciente').val(data.paciente);
                    $('#editFecha').val(data.fecha);
                    $('#editHora').val(data.hora);
                    
                    // Seleccionar el doctor correspondiente en el dropdown
                    $('#editDoctor').val(data.doctor);

                    // Mostrar el modal después de cargar la cita
                    $('#editCitaModal').modal('show');
                },
                error: function (xhr, error, code) {
                    console.log("Error al obtener la cita: " + error);
                }
            });

        },
        error: function (xhr, error, code) {
            console.log("Error al obtener doctores: " + error);
        }
    });
});

// Evento para la edición de la cita con SweetAlert
$('#editCitaForm').on('submit', function (e) {
    e.preventDefault(); // Prevenir el envío del formulario por defecto

    $.ajax({
        url: '../controller/citas_action.php',
        type: 'POST',
        data: $(this).serialize() + '&action=updateCita', // Enviar los datos del formulario
        success: function (response) {
            var result = JSON.parse(response);
            if (result.status === 'success') {
                citasRecords.ajax.reload();
                $('#editCitaModal').modal('hide');
                // Notificación de éxito con SweetAlert
                Swal.fire('¡Éxito!', result.message, 'success');
            } else {
                // Notificación de error con SweetAlert
                Swal.fire('Error', result.message, 'error');
            }
        },
        error: function (xhr, error, code) {
            console.log("Error: " + error);
            // Notificación de error con SweetAlert
            Swal.fire('Error', 'Ocurrió un error al actualizar la cita.', 'error');
        }
    });
});

// Evento para la eliminación con SweetAlert
$('#citasTable tbody').on('click', '.btn-delete', function () {
    var citaId = $(this).data('id');

    // Mostrar SweetAlert2 de confirmación
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esta acción.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, proceder a eliminar
            $.ajax({
                url: '../controller/citas_action.php',
                type: 'POST',
                data: { action: 'deleteCita', id: citaId },
                success: function (response) {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        citasRecords.ajax.reload();
                        Swal.fire('¡Eliminado!', result.message, 'success'); // Notificación de éxito
                    } else {
                        Swal.fire('Error', result.message, 'error'); // Notificación de error
                    }
                },
                error: function (xhr, error, code) {
                    console.log("Error: " + error);
                    Swal.fire('Error', 'Ocurrió un error al eliminar la cita.', 'error');
                }
            });
        }
    });
});

// Evento para agregar cita con SweetAlert
$('#addCitaForm').on('submit', function (e) {
    e.preventDefault(); // Prevenir el envío del formulario por defecto

    $.ajax({
        url: '../controller/citas_action.php',
        type: 'POST',
        data: $(this).serialize() + '&action=addCita', // Enviar los datos del formulario
        success: function (response) {
            var result = JSON.parse(response);
            if (result.status === 'success') {
                citasRecords.ajax.reload();
                $('#addCitaModal').modal('hide');
                Swal.fire('¡Éxito!', result.message, 'success'); // Notificación de éxito
            } else {
                Swal.fire('Error', result.message, 'error'); // Notificación de error
            }
        },
        error: function (xhr, error, code) {
            console.log("Error: " + error);
            Swal.fire('Error', 'Ocurrió un error al agregar la cita.', 'error');
        }
    });
});
$(document).ready(function() {
    $('#addCitaModal').on('show.bs.modal', function() {
        $.ajax({
            url: '../controller/citas_action.php',
            type: 'POST',
            data: { action: 'getDoctores' },
            dataType: 'json',
            success: function(data) {
                var doctorSelect = $('#addDoctor');
                doctorSelect.empty(); // Limpiar las opciones existentes
                doctorSelect.append('<option value="">Seleccione un doctor</option>'); // Opción por defecto
                $.each(data, function(index, doctor) {
                    doctorSelect.append('<option value="' + doctor.nombre + '">' + doctor.nombre + '</option>');
                });
            },
            error: function(xhr, status, error) {
                console.log("Error al obtener doctores: " + error);
            }
        });
    });
});
