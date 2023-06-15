@extends('layouts.admin')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">

                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>USUARIOS
                            @can('crear-usuario')
                                <a href="{{ url('admin/users/create') }}" class="btn btn-primary float-end">Añadir Usuario</a>
                            @endcan
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <div id="scroll" class="table-responsive">
                            <table class="table table-bordered table-striped nowrap scroll" id="mitabla"
                                name="mitabla" style="width: 100%;">
                                <thead class="fw-bold text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>NOMBRE</th>
                                        <th>EMAIL</th>
                                        <th>ROL</th>
                                        <th>ESTADO</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mantenimientos">
                                    
                                </tbody>
                            </table>

                        </div>

                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    
     <script src="{{ asset('admin/midatatable.js') }}"></script>
     <script>
         var numeroeliminados = 0;
         $(document).ready(function() {
 
             var tabla = "#mitabla";
             var ruta = "{{ route('usuario.index') }}"; //darle un nombre a la ruta index
             var columnas = [{
                     data: 'id',
                     name: 'id'
                 },
                 {
                     data: 'name',
                     name: 'name'
                 },
                 {
                     data: 'email',
                     name: 'email'
                 },
                 {
                     data: 'rol',
                     name: 'r.name'
                 },
                 {  
                     data: 'status',
                     name: 'status'
                 },
                 {
                     data: 'acciones',
                     name: 'acciones',
                     searchable: false,
                     orderable: false,
                 },
             ];
             
             var btns = 'lfrtip';
             iniciarTablaIndex(tabla, ruta, columnas, btns);
 
         });
         //para borrar un registro de la tabla
         $(document).on('click', '.btnborrar', function(event) {
             const idregistro = event.target.dataset.idregistro;
             var urlregistro = "{{ url('admin/users') }}";
             Swal.fire({
                 title: '¿Esta seguro de Eliminar?',
                 text: "No lo podra revertir!",
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: 'Sí,Eliminar!'
             }).then((result) => {
                 if (result.isConfirmed) {
                     $.ajax({
                         type: "GET",
                         url: urlregistro + '/' + idregistro + '/delete',
                         success: function(data1) {
                             if (data1 == "1") {
                                 recargartabla();
                                 $(event.target).closest('tr').remove(); 
                                 Swal.fire({
                                     icon: "success",
                                     text: "Registro Eliminado",
                                 });
                             } else if (data1 == "0") {
                                 Swal.fire({
                                     icon: "error",
                                     text: "Registro No Eliminado",
                                 });
                             } else if (data1 == "2") {
                                 Swal.fire({
                                     icon: "error",
                                     text: "Registro No Encontrado",
                                 });
                             }
                         }
                     });
                 }
             });
         });
 
       
         
          
     </script>
@endpush
