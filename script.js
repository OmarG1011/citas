
var citasRecords; // Definida en un ámbito global

$(document).ready(function() {
    // Inicializar DataTable
    citasRecords = $('#citasTable').DataTable({
        "ajax": {
            "url": "../controller/citas_action.php",
            "type": "POST",
            "data": { action: 'listCitas' },
            "dataType": "json",
            "dataSrc": '',
            "error": function(xhr, error, code) {
                console.log("Error: " + error);
                console.log("Code: " + code);
                console.log(xhr.responseText);
                alert("Ocurrió un error al cargar los datos.");
            }
        },
        "columns": [
            { "data": "id" },
            { "data": "paciente" },
            { "data": "doctor" },
            { "data": "fecha" },
            { "data": "hora" },
            {
                "data": null,
                "render": function(data, type, row) {
                    return '<button class="btn btn-danger btn-delete" data-id="' + row.id + '">Eliminar</button>' + 
                           '<button class="btn btn-primary btn-edit" data-id="' + row.id + '">Modificar</button>';
                }
            }
        ]
    });
});

// Manejar el evento de clic en el botón de modificar
$('#citasTable tbody').on('click', '.btn-edit', function() {
    var citaId = $(this).data('id');
    
    // Obtener los datos de la cita usando AJAX
    $.ajax({
        url: '../controller/citas_action.php',
        type: 'POST',
        data: { action: 'getCita', id: citaId },
        dataType: 'json',
        success: function(data) {
            // Llenar el formulario con los datos de la cita
            $('#editCitaId').val(data.id);
            $('#editPaciente').val(data.paciente);
            $('#editDoctor').val(data.doctor);
            $('#editFecha').val(data.fecha);
            $('#editHora').val(data.hora);
            $('#editCitaModal').modal('show'); // Mostrar el modal
        },
        error: function(xhr, error, code) {
            console.log("Error al obtener la cita: " + error);
        }
    });
});
$('#editCitaForm').on('submit', function(e) {
    e.preventDefault(); // Prevenir el envío del formulario por defecto

    $.ajax({
        url: '../controller/citas_action.php',
        type: 'POST',
        data: $(this).serialize() + '&action=updateCita', // Enviar los datos del formulario
        success: function(response) {
            var result = JSON.parse(response);
            if (result.status === 'success') {
                // Recargar la DataTable
                citasRecords.ajax.reload();
                $('#editCitaModal').modal('hide'); // Ocultar el modal
                alert(result.message); // Mostrar mensaje de éxito
            } else {
                alert(result.message); // Mostrar mensaje de error
            }
        },
        error: function(xhr, error, code) {
            console.log("Error: " + error);
            alert("Ocurrió un error al actualizar la cita.");
        }
    });
});

// Manejar el evento de clic en el botón de eliminar
$('#citasTable tbody').on('click', '.btn-delete', function() {
    var citaId = $(this).data('id');
    
    if (confirm('¿Estás seguro de que deseas eliminar esta cita?')) {
        $.ajax({
            url: '../controller/citas_action.php',
            type: 'POST',
            data: { action: 'deleteCita', id: citaId },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    // Recargar la DataTable
                    citasRecords.ajax.reload();
                    alert(result.message); // Mostrar mensaje de éxito
                } else {
                    alert(result.message); // Mostrar mensaje de error
                }
            },
            error: function(xhr, error, code) {
                console.log("Error: " + error);
                alert("Ocurrió un error al eliminar la cita.");
            }
        });
    }
});

$('#addCitaForm').on('submit', function(e) {
    e.preventDefault(); // Prevenir el envío del formulario por defecto

    $.ajax({
        url: '../controller/citas_action.php',
        type: 'POST',
        data: $(this).serialize() + '&action=addCita', // Enviar los datos del formulario
        success: function(response) {
            var result = JSON.parse(response);
            if (result.status === 'success') {
                // Recargar la DataTable
                citasRecords.ajax.reload();
                $('#addCitaModal').modal('hide'); // Ocultar el modal
                alert(result.message); // Mostrar mensaje de éxito
            } else {
                alert(result.message); // Mostrar mensaje de error
            }
        },
        error: function(xhr, error, code) {
            console.log("Error: " + error);
            alert("Ocurrió un error al agregar la cita.");
        }
    });
});




  