var mitabla;
function iniciarTablaIndex(tabla, ruta, columnas, btns) {
    //para agregar un input de busqueda en cada columna
    $(tabla + ' thead th').each(function () {
        var title = $(this).text();
        if (title != "ACCIONES") {
            $(this).html(title + ' <br>  <input style="width:100%;"   type="text"/>');
        }
    });

    //se inicia la tabla como datatables 
    mitabla = $(tabla).DataTable({
        processing: true,
        serverSide: true,
        ajax: ruta,
        dataType: 'json',
        type: "POST",
        columns: columnas,
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "( filtrado de un total de _MAX_ registros )",
            "sInfoPostFix": "",
            "sSearch": "Buscar Registro:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "order": [
            [0, "desc"]
        ],
        "sScrollX": "100%",
        scrollX: true,
        "pageLength": 10,
        autoFill: true,
        dom: btns,
        buttons: [{
            extend: 'excel',
            className: 'btn-success',
            text: 'Descargar Excel',
            exportOptions: {
                columns: ':visible'
            }
        }],
    });

    //para hacer la busqueda al cambiar el valor de un input de una columna
    mitabla.columns().every(function () {
        var table = this;
        $('input', this.header()).on('keyup change', function () {
            if (table.search() !== this.value) { 
                    table.search(this.value).draw(); 
            }
        });
    });
}

//para recargar la tabla despues de hacer un cambio
function recargartabla() {
    mitabla.ajax.reload(null, false);
}
//iniciar la tabla 2
function inicializartabla1(inicializart) {
    if (inicializart == 0) {
        $('#mitabla1').DataTable({
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "( filtrado de un total de _MAX_ registros )",
                "sInfoPostFix": "",
                "sSearch": "Buscar Registro:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            },
            "order": [[0, "desc"]],
            "pageLength": 5,
            "aLengthMenu": [[5, 10, 25, 50], [5, 10, 25, 50]],
            scrollX: true,
        });
    }
}
//iniciar la tabla de reportes con botones
function inicializartabladatos(btns, tabla, titulo) {
    mitabladatos = $(tabla).DataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "( filtrado de un total de _MAX_ registros )",
            "sInfoPostFix": "",
            "sSearch": "Buscar Registro:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        "order": [[0, "desc"]],
        "pageLength": 10,
        scrollX: true,
        autoFill: true,
        dom: btns,
        buttons: [{
            extend: 'excel',
            className: 'btn-success',
            text: 'Descargar Excel',
            title: titulo,
            exportOptions: {
                columns: ':visible'
            }
        }],
    });

}
