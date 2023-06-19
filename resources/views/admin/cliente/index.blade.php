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
                                <h4 id="mititulo">CLIENTES / PROVEEDORES:&nbsp;&nbsp;
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    @can('crear-cliente')
                                    <a href="{{ url('admin/cliente/create') }}" class="btn btn-primary float-end">Añadir
                                        Cliente/Proveedor</a>
                                @endcan
                                </h4>
                            </div>
                        </div>
 
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped display " id="mitabla" name="mitabla"
                           >
                            <thead class="fw-bold text-primary" >
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th>RUC</th>
                                    <th>TELEFONO</th>
                                    <th>EMAIL</th>
                                    <th>DIRECCION</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <Tbody id="tbody-mantenimientos">

                            </Tbody>
                        </table>


                    </div>


                    <div class="modal fade" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Empresa</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-sm-12 col-lg-12 mb-3">
                                                <label for="vernombre" class="col-form-label">NOMBRE:</label>
                                                <input type="text" class="form-control" id="vernombre" readonly>
                                            </div>
                                            <div class="col-sm-6 col-lg-6 mb-3">
                                                <label for="verruc" class="col-form-label">RUC:</label>
                                                <input type="number" class="form-control" id="verruc" readonly>
                                            </div>
                                            <div class="col-sm-6 col-lg-6 mb-3" id="divemail">
                                                <label for="veremail" class="col-form-label">EMAIL:</label>
                                                <input type="email" class="form-control" id="veremail" readonly>
                                            </div>
                                            <div class="col-sm-12 col-lg-12 mb-3" id="divdireccion">
                                                <label for="verdireccion" class="col-form-label">DIRECCION:</label>
                                                <input type="text" class="form-control" id="verdireccion" readonly>
                                            </div>
                                            <div class="col-sm-12 col-lg-12 mb-3" id="divtelefono">
                                                <label for="vertelefono" class="col-form-label">TELEFONO:</label>
                                                <input type="text" class="form-control" id="vertelefono" readonly>
                                            </div>


                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade " id="modalkits" tabindex="-1" aria-labelledby="modalkits" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Lista de Clientes Eliminados</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="table-responsive">
                                        <table class="table table-row-bordered gy-5 gs-5" 
                                            id="mitablarestore" name="mitablarestore">
                                            <thead class="fw-bold text-primary">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>NOMBRE</th>
                                                    <th>RUC</th>
                                                    <th>TELEFONO</th>
                                                    <th>EMAIL</th>
                                                    <th>DIRECCION</th>
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

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
        //para inicializar el datatable
        $(document).ready(function() {
            var tabla = "#mitabla";
            var ruta = "{{ route('cliente.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'nombre',
                    name: 'nombre'
                },
                {
                    data: 'ruc',
                    name: 'ruc'
                },
                {
                    data: 'telefono',
                    name: 'telefono'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'direccion',
                    name: 'direccion'
                },
                {
                    data: 'acciones',
                    name: 'acciones',
                    searchable: false,
                    orderable: false,
                },
            ];
            var btns = 'lfrtip';
            numeroeliminados = @json($datoseliminados);
            mostrarmensaje(numeroeliminados);
            iniciarTablaIndex(tabla, ruta, columnas, btns);

        });
        //para borrar un registro de la tabla
        $(document).on('click', '.btnborrar', function(event) {
            const idregistro = event.target.dataset.idregistro;
            var urlventa = "{{ url('admin/cliente') }}";
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
                        url: urlventa + '/' + idregistro + '/delete',
                        success: function(data1) {
                            if (data1 == "1") {
                                numeroeliminados++;
                                mostrarmensaje(numeroeliminados);
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
    <script>
        //para los modales
        const mimodal = document.getElementById('mimodal')
        mimodal.addEventListener('show.bs.modal', event => {

            const button = event.relatedTarget
            const id = button.getAttribute('data-id')
            var urlregistro = "{{ url('admin/cliente/show') }}";
            $.get(urlregistro + '/' + id, function(data) {
                const modalTitle = mimodal.querySelector('.modal-title');
                modalTitle.textContent = `Ver Registro ${id}`;

                document.getElementById("vernombre").value = data.nombre;
                document.getElementById("verruc").value = data.ruc;
                document.getElementById("verdireccion").value = data.direccion;
                document.getElementById("vertelefono").value = data.telefono;
                document.getElementById("veremail").value = data.email;
                if (data.direccion == null) {
                    document.getElementById('divdireccion').style.display = 'none';
                } else {
                    document.getElementById('divdireccion').style.display = 'inline';
                    document.getElementById("verdireccion").value = data.direccion;
                }
                if (data.telefono == null) {
                    document.getElementById('divtelefono').style.display = 'none';
                } else {
                    document.getElementById('divtelefono').style.display = 'inline';
                    document.getElementById("vertelefono").value = data.telefono;
                }
                if (data.email == null) {
                    document.getElementById('divemail').style.display = 'none';
                } else {
                    document.getElementById('divemail').style.display = 'inline';
                    document.getElementById("veremail").value = data.email;
                }

            });

        })
        window.addEventListener('close-modal', event => {
            $('#deleteModal').modal('hide');
        });

        //modal para ver los eliminados
        var inicializartabla = 0;
        const modalkits = document.getElementById('modalkits');
        modalkits.addEventListener('show.bs.modal', event => {
            var urlinventario = "{{ url('admin/cliente/showrestore') }}";
            $.get(urlinventario, function(data) {
                var btns = 'lfrtip';
                var tabla = '#mitablarestore';
                if (inicializartabla > 0) {
                    $("#mitablarestore").dataTable().fnDestroy(); //eliminar las filas de la tabla  
                }
                $('#mitablarestore tbody tr').slice().remove();
                for (var i = 0; i < data.length; i++) {
                    filaDetalle = '<tr id="fila' + i +
                        '"><td>' + data[i].id +
                        '</td><td>' + data[i].nombre +
                        '</td><td>' + data[i].ruc +
                        '</td><td>' + data[i].telefono +
                        '</td><td>' + data[i].email +
                        '</td><td>' + data[i].direccion +
                        '</td><td><button type="button" class="btn btn-info"  ' +
                        ' onclick="RestaurarRegistro(' + data[i].id + ')" >Restaurar</button></td>  ' +
                        '</tr>';
                    $("#mitablarestore>tbody").append(filaDetalle);
                }
                inicializartabladatos(btns, tabla, "");
                inicializartabla++;
            });
        });

        function RestaurarRegistro(idregistro) {
            var urlregistro = "{{ url('admin/cliente/restaurar') }}";
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
                                numeroeliminados--;
                                mostrarmensaje(numeroeliminados);
                                recargartabla();
                                $('#modalkits').modal('hide');
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
            var registro = "CLIENTES / PROVEEDORES: ";
            var boton =
                ' @can('recuperar-cliente') <button id="btnrestore" class="btn btn-info btn-sm" data-bs-toggle="modal"  data-bs-target="#modalkits"> Restaurar Eliminados </button> @endcan ';
            if (numeliminados > 0) {
                document.getElementById('mititulo').innerHTML = registro + boton;
            } else {
                document.getElementById('mititulo').innerHTML = registro;
            }
        }
    </script>
@endpush
