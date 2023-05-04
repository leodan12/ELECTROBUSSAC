@extends('layouts.admin')
@push('css')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
   
  
 <!--<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.css">-->
 
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.js"></script>
  
@endpush
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
                        <a href="{{ url('admin/users/create') }}" class="btn btn-primary float-end">Añadir Usuario</a>
                    </h4>
                </div>
                <div class="card-body">
                    
                    <table class="table table-bordered table-striped dt-responsive nowrap" id="mitabla" name="mitabla"  >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>ROLE</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <Tbody id="tbody-mantenimientos">
                           
                            @forelse ($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    @if ($user->role_as == '0')
                                        <label class="hadge btn-primary">Usuario</label>
                                    @elseif($user->role_as == '1')
                                        <label class="hadge btn-success">Administrador</label>
                                    @else
                                        <label class="badge btn-danger">Ninguno</label>    
                                    @endif
                                <td>
                                    <a href="{{ url('admin/users/'.$user->id.'/edit')}}" class="btn btn-success">
                                        Editar
                                    </a>
                                    <form action="{{ url('admin/users/'.$user->id.'/delete') }}" class="d-inline formulario-eliminar">
                                    <button type="submit" class="btn btn-danger formulario-eliminar">
                                        Eliminar
                                    </button>
                                    </form>
                                    
                        
                                   
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">No hay Usuarios Disponibles</td>
                            </tr>
                            @endforelse
                        </Tbody>
                    </table>
                    <div> 
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>

@push('script')

<script>
     $(document).ready(function () {
        //$('#mitabla').DataTable();
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
    }
  });

    });
     
        
        
    </script>
@endpush

@endsection

@section('js') 
    <script>
        $('.formulario-eliminar').submit(function(e){
            e.preventDefault();
        
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
            this.submit();
        }
        })
    });
    </script>
@endsection