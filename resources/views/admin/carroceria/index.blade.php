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
                                <h4 id="mititulo">CARROCERIAS:
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    @can('crear-carroceria')
                                        <a href="{{ url('admin/carroceria/create') }}" class="btn btn-primary float-end">Añadir
                                            Carroceria</a>
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
                                        <th>CARROCERIA</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mantenimientos">

                                </tbody>
                            </table>
                        </div>

                        <div class="modal fade" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="mimodalLabel">Ver Kit de Productos</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form>
                                            <div class="row">
                                                <div class="col-sm-12  mb-3">
                                                    <label for="vercategoria" class="col-form-label">CARROCERIA:</label>
                                                    <input type="text" class="form-control" id="vercarroceria" readonly>
                                                </div>

                                            </div>
                                        </form>
                                        <div class="table-responsive">
                                            <table class="table table-striped table-row-bordered gy-5 gs-5" id="kits">
                                                <thead class="fw-bold text-primary">
                                                    <tr>
                                                        <th>Cantidad</th>
                                                        <th>Unidad</th>
                                                        <th>Producto</th>
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
                                            data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade " id="modalkits" tabindex="-1" aria-labelledby="modalkits"
                            aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="mimodalLabel">Lista de Carrocerias Eliminadas
                                        </h1>
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
                                                        <th>CARROCERIA</th>
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
    <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
    <script>
        var numeroeliminados = 0;
        $(document).ready(function() {
            var tabla = "#mitabla";
            var ruta = "{{ route('carrocerias.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'tipocarroceria',
                    name: 'tipocarroceria'
                },
                {
                    data: 'acciones',
                    name: 'acciones',
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
            var urlregistro = "{{ url('admin/carroceria') }}";
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
            
            var urlinventario = "{{ url('admin/carroceria/showcarroceriarestore') }}";
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
                        '</td><td>' + data[i].tipocarroceria +
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
            var urlregistro = "{{ url('admin/carroceria/restaurar') }}";
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
            var registro = "CARROCERIAS: ";
            var boton =
                ' @can('recuperar-carroceria') <button id="btnrestore" class="btn btn-info btn-sm" data-bs-toggle="modal"  data-bs-target="#modalkits"> Restaurar Eliminados </button> @endcan ';
            if (numeliminados > 0) { 
                document.getElementById('mititulo').innerHTML = registro + boton;
            } else {
                document.getElementById('mititulo').innerHTML = registro;
            }
        }

        //para el modal de ver kits
        const mimodal = document.getElementById('mimodal')
        mimodal.addEventListener('show.bs.modal', event => {

            const button = event.relatedTarget
            const id = button.getAttribute('data-id')
            var urlregistro = "{{ url('admin/carroceria/showcarroceria') }}";
            $.get(urlregistro + '/' + id, function(data) {
                const modalTitle = mimodal.querySelector('.modal-title')
                modalTitle.textContent = `Ver Carroceria ${id}`
                document.getElementById("vercarroceria").value = data[0].tipocarroceria;
                $('#kits tbody tr').slice().remove();
                for (var i = 0; i < data.length; i++) {
                    filaDetalle = '<tr ><td>' + data[i].cantidad +
                        '</td><td> ' + data[i].unidad +
                        '</td><td> ' + data[i].nombre +
                        '</td></tr>';

                    $("#kits>tbody").append(filaDetalle);
                }
            });

        })

      
    </script>
@endpush
