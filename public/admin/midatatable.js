$('#mitabla').DataTable({
    "language": {
   "sProcessing":     "Procesando...",
   "sLengthMenu":     "Mostrar _MENU_ registros",
   "sZeroRecords":    "No se encontraron resultados",
   "sEmptyTable":     "Ningún dato disponible en esta tabla",
   "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
   "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
   "sInfoFiltered":   "( filtrado de un total de _MAX_ registros )",
   "sInfoPostFix":    "",
   "sSearch":         "Buscar Registro:",
   "sUrl":            "",
   "sInfoThousands":  ",",
   "sLoadingRecords": "Cargando...",
   "loadingRecords": "Cargando...",
   "processing": "Procesando...",
   "oPaginate": {
       "sFirst":    "Primero",
       "sLast":     "Último",
       "sNext":     "Siguiente",
       "sPrevious": "Anterior"
   },
   "oAria": {
       "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
       "sSortDescending": ": Activar para ordenar la columna de manera descendente"
   }
},  "order": [[ 0, "desc" ]],  
"sScrollX": "100%",
scrollX: true, 
autoFill: true,
});


function inicializartabla1(inicializart){
    if(inicializart==0){
    $('#mitabla1').DataTable({
    "language": {
   "sProcessing":     "Procesando...",
   "sLengthMenu":     "Mostrar _MENU_ registros",
   "sZeroRecords":    "No se encontraron resultados",
   "sEmptyTable":     "Ningún dato disponible en esta tabla",
   "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
   "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
   "sInfoFiltered":   "( filtrado de un total de _MAX_ registros )",
   "sInfoPostFix":    "",
   "sSearch":         "Buscar Registro:",
   "sUrl":            "",
   "sInfoThousands":  ",",
   "sLoadingRecords": "Cargando...",
   "loadingRecords": "Cargando...",
    "processing": "Procesando...",
   "oPaginate": {
       "sFirst":    "Primero",
       "sLast":     "Último",
       "sNext":     "Siguiente",
       "sPrevious": "Anterior"
   },
   "oAria": {
       "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
       "sSortDescending": ": Activar para ordenar la columna de manera descendente"
   }
},  "order": [[ 0, "desc" ]], 
scrollX: true,
});} 
}
