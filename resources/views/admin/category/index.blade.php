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
                                <h4 id="mititulo">CATEGORIAS:
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    @can('crear-categoria')
                                        <a href="{{ url('admin/category/create') }}" class="btn btn-primary float-end">Añadir
                                            Categoria</a>
                                    @endcan
                                </h4>
                            </div>
                        </div> 
                    </div> 
                    <div class="card-body"> 
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="mitabla" style="width: 100%;"
                                name="mitabla">
                                <thead class="fw-bold text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>NOMBRE</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mantenimientos">

                                </tbody>
                            </table>
                        </div>


                        <div class="modal fade " id="modalkits" tabindex="-1" aria-labelledby="modalkits"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="mimodalLabel">Lista de Categorias Eliminadas</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="table-responsive">
                                            <table class="table table-row-bordered gy-5 gs-5" style="width: 100%"
                                                id="mitabla1" name="mitabla1">
                                                <thead class="fw-bold text-primary">
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>NOMBRE</th>
                                                        <th>ACCION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>

                                    </div>
                                </div>
                            </div>
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
            var ruta = "{{ route('categorias.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'nombre',
                    name: 'nombre'
                },
                {
                    data: 'accion',
                    name: 'accion',
                    searchable: false,
                    orderable: false,
                },
            ];
            numeroeliminados = @json($datoseliminados);
            mostrarmensaje(numeroeliminados);
            var btns = 'lfrtip';
            iniciarTablaIndex(tabla, ruta, columnas, btns);

        });
        //para borrar un registro de la tabla
        $(document).on('click', '.btnborrar', function(event) {
            const idregistro = event.target.dataset.idregistro;
            var urlregistro = "{{ url('admin/category') }}";
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
                                numeroeliminados++;
                                mostrarmensaje(numeroeliminados);
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

        var inicializartabla = 0;
        const modalkits = document.getElementById('modalkits');
        modalkits.addEventListener('show.bs.modal', event => {
            var urlinventario = "{{ url('admin/category/showcategoryrestore') }}";
            $.get(urlinventario, function(data) {
                var btns = 'lfrtip';
                var tabla = '#mitabla1';
                if (inicializartabla > 0) {
                    $("#mitabla1").dataTable().fnDestroy(); //eliminar las filas de la tabla  
                }
                $('#mitabla1 tbody tr').slice().remove();
                for (var i = 0; i < data.length; i++) {
                    filaDetalle = '<tr id="fila' + i +
                        '"><td>' + data[i].id +
                        '</td><td>' + data[i].nombre +
                        '</td><td><button type="button" class="btn btn-info"  ' +
                        ' onclick="RestaurarRegistro(' + data[i].id + ')" >Restaurar</button></td>  ' +
                        '</tr>';
                    $("#mitabla1>tbody").append(filaDetalle);
                }
                inicializartabladatos(btns, tabla, "");
                inicializartabla++;
            });
        });

        function RestaurarRegistro(idregistro) {
            var urlregistro = "{{ url('admin/category/restaurar') }}";
            Swal.fire({
                title: '¿Desea Restaurar El Registro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí,Restaurar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "GET",
                        url: urlregistro + '/' + idregistro,
                        success: function(data1) {
                            if (data1 == "1") {
                                recargartabla();
                                $('#modalkits').modal('hide');
                                numeroeliminados--;
                                mostrarmensaje(numeroeliminados);
                                Swal.fire({
                                    icon: "success",
                                    text: "Registro Restaurado",
                                });
                            } else if (data1 == "0") {
                                Swal.fire({
                                    icon: "error",
                                    text: "Registro NO Restaurado",
                                });
                            } else if (data1 == "2") {
                                Swal.fire({
                                    icon: "error",
                                    text: "Registro NO Encontrado",
                                });
                            }
                        }
                    });
                }
            });
        }

        function mostrarmensaje(numeliminados) {
            var registro = "CATEGORIAS: ";
            var boton =
                ' @can('recuperar-categoria') <button id="btnrestore" class="btn btn-info btn-sm" data-bs-toggle="modal"  data-bs-target="#modalkits"> Restaurar Eliminados </button> @endcan ';
            if (numeliminados > 0) {
                document.getElementById('mititulo').innerHTML = registro + boton;
            } else {
                document.getElementById('mititulo').innerHTML = registro;
            }
        }
    </script>
@endpush
