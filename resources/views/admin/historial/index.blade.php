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
                        <div class="row">
                            <div class="col">
                                <h4 id="mititulo">HISTORIAL DE CAMBIOS REALIZADOS:
                                </h4>
                            </div>
                            <div class="col">
                                @can('eliminar-historial')
                                    <button class="btn btn-warning" onclick="limpiarTabla();">
                                        Eliminar Todos los Registros
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped"  id="mitabla" name="mitabla">
                            <thead class="fw-bold text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>USUARIO</th>
                                    <th>FECHA</th>
                                    <th>ACCION</th>
                                    <th>TABLA</th>
                                    <th>ID DEL REGISTRO</th>
                                    <th>NOMBRE / EMPRESA</th>
                                    <th>CLIENTE / PROVEEDOR</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <Tbody id="tbody-mantenimientos">
                            </Tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection
@push('script')
    <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
    <script>
        $(document).ready(function() {

            var tabla = "#mitabla";
            var ruta = "{{ route('historial.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'usuario',
                    name: 'u.name'
                },
                {
                    data: 'fecha',
                    name: 'fecha'
                },

                {
                    data: 'accion',
                    name: 'accion'
                },
                {
                    data: 'tabla',
                    name: 'tabla'
                },
                {
                    data: 'registro_id',
                    name: 'registro_id'
                },
                {
                    data: 'dato1',
                    name: 'dato1'
                },
                {
                    data: 'dato2',
                    name: 'dato2'
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
            var urlregistro = "{{ url('admin/historial') }}";
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

        //limpiar tabla
        function limpiarTabla() {
            Swal.fire({
                title: '¿Esta Seguro de Eliminar Todos los Registros?',
                text: "No lo podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí,Eliminar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var urlregistro = "{{ url('admin/historial/limpiartabla') }}";
                    $.ajax({
                        type: "GET",
                        url: urlregistro,
                        success: function(data1) {
                            if (data1 == "1") {
                                recargartabla();
                                Swal.fire({
                                    icon: "success",
                                    text: "Registros Eliminados",
                                });
                            } else if (data1 == "0") {
                                Swal.fire({
                                    icon: "error",
                                    text: "Registros NO Eliminados",
                                });
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    text: "Registros NO Encontrados",
                                });
                            }
                        }
                    });
                }
            });
        }
    </script>
@endpush
