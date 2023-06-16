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
                                <h4 id="mititulo">KITS:
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    @can('crear-kit')
                                        <a href="{{ url('admin/kits/create') }}" class="btn btn-primary float-end">Añadir Kit</a>
                                    @endcan
                                </h4>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped" style="width:100%;" id="mitabla" name="mitabla">
                            <thead class="fw-bold text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>CATEGORIA</th>
                                    <th>NOMBRE</th>
                                    <th>CODIGO</th>
                                    <th>UNIDAD</th>
                                    <th>MONEDA</th>
                                    <th>PRECIO SIN IGV</th>
                                    <th>PRECIO CON IGV</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <Tbody id="tbody-mantenimientos">


                            </Tbody>
                        </table>
                        <div>
                        </div>
                    </div>

                    <div class="modal fade" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Kit de Productos</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-sm-4  mb-3">
                                                <label for="vercategoria" class="col-form-label">CATEGORIA:</label>
                                                <input type="text" class="form-control" id="vercategoria" readonly>
                                            </div>
                                            <div class="col-sm-8 mb-3">
                                                <label for="vernombre" class="col-form-label">NOMBRE:</label>
                                                <input type="text" class="form-control" id="vernombre" readonly>
                                            </div>
                                            <div class="col-sm-3   mb-3">
                                                <label for="vermoneda" class="col-form-label">TIPO DE MONEDA:</label>
                                                <input type="text" class="form-control" id="vermoneda" readonly>
                                            </div>
                                            <div class="col-sm-3   mb-3">
                                                <label for="vertasacambio" class="col-form-label">TASA CAMBIO:</label>
                                                <input type="text" class="form-control" id="vertasacambio" readonly>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <div class="input-group">
                                                    <label for="vernoigv" class="col-form-label input-group">PRECIO SIN
                                                        IGV:</label>
                                                    <span class="input-group-text" id="spannoigv"></span>
                                                    <input type="number" class="form-control" id="vernoigv" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <div class="input-group">
                                                    <label for="versiigv" class="col-form-label  input-group">PRECIO CON
                                                        IGV:</label>
                                                    <span class="input-group-text" id="spansiigv"></span>
                                                    <input type="number" class="form-control" id="versiigv" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <div class="input-group">
                                                    <label for="verminimo" class="col-form-label input-group">PRECIO
                                                        MÍNIMO:</label>
                                                    <span class="input-group-text" id="spanminimo"></span>
                                                    <input type="number" class="form-control" id="verminimo" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                                <div class="input-group">
                                                    <label for="vermaximo" class="col-form-label input-group">PRECIO
                                                        MÁXIMO:</label>
                                                    <span class="input-group-text" id="spanmaximo"></span>
                                                    <input type="number" class="form-control" id="vermaximo" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-3   mb-3">
                                                <label for="vercodigo" class="col-form-label">CÓDIGO:</label>
                                                <input type="text" class="form-control" id="vercodigo" readonly>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered gy-5 gs-5" id="kits">
                                            <thead class="fw-bold text-primary">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Unitario Referencial</th>
                                                    <th>Precio Unitario</th>
                                                    <th>Precio Total Por Producto</th>
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
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Lista de Kits Eliminados</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="table-responsive">
                                        <table class="table table-row-bordered gy-5 gs-5" style="width: 100%"
                                            id="mitablarestore" name="mitablarestore">
                                            <thead class="fw-bold text-primary">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>CATEGORIA</th>
                                                    <th>NOMBRE</th>
                                                    <th>CODIGO</th>
                                                    <th>UNIDAD</th>
                                                    <th>MONEDA</th>
                                                    <th>PRECIO SIN IGV</th>
                                                    <th>PRECIO CON IGV</th>
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

    @push('script')
        <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
        <script>
            var numeroeliminados = 0;
            $(document).ready(function() {
                var tabla = "#mitabla";
                var ruta = "{{ route('kit.index') }}"; //darle un nombre a la ruta index
                var columnas = [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'categoria',
                        name: 'c.nombre'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },

                    {
                        data: 'codigo',
                        name: 'codigo'
                    },
                    {
                        data: 'unidad',
                        name: 'unidad'
                    },
                    {
                        data: 'moneda',
                        name: 'moneda'
                    },
                    {
                        data: 'NoIGV',
                        name: 'NoIGV'
                    },
                    {
                        data: 'SiIGV',
                        name: 'SiIGV'
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
                var urlregistro = "{{ url('admin/kits') }}";
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
            //para el modal de ver kits
            const mimodal = document.getElementById('mimodal')
            mimodal.addEventListener('show.bs.modal', event => {

                const button = event.relatedTarget
                const id = button.getAttribute('data-id')
                var urlregistro = "{{ url('admin/kits/show') }}";
                $.get(urlregistro + '/' + id, function(data) {
                    const modalTitle = mimodal.querySelector('.modal-title')
                    modalTitle.textContent = `Ver Kit de Productos ${id}`

                    document.getElementById("vercategoria").value = data[0].nombrecategoria;
                    document.getElementById("vernombre").value = data[0].nombre;
                    document.getElementById("vercodigo").value = data[0].codigo;
                    document.getElementById("vermoneda").value = data[0].moneda;
                    document.getElementById("vertasacambio").value = data[0].tasacambio;
                    document.getElementById("vernoigv").value = data[0].NoIGV;
                    document.getElementById("versiigv").value = data[0].SiIGV;
                    document.getElementById("verminimo").value = data[0].minimo;
                    document.getElementById("vermaximo").value = data[0].maximo;

                    var monedafactura = data[0].moneda;
                    if (monedafactura == "dolares") {
                        simbolomonedafactura = "$";
                    } else if (monedafactura == "soles") {
                        simbolomonedafactura = "S/.";
                    }

                    document.getElementById("spannoigv").innerHTML = simbolomonedafactura;
                    document.getElementById("spansiigv").innerHTML = simbolomonedafactura;
                    document.getElementById("spanmaximo").innerHTML = simbolomonedafactura;
                    document.getElementById("spanminimo").innerHTML = simbolomonedafactura;

                    $('#kits tbody tr').slice().remove();
                    for (var i = 0; i < data.length; i++) {
                        var monedaproducto = data[i].kitproductmoneda;
                        if (monedaproducto == "dolares") {
                            simbolomonedaproducto = "$";
                        } else if (monedaproducto == "soles") {
                            simbolomonedaproducto = "S/.";
                        }

                        filaDetalle = '<tr id="fila' + i +
                            '"><td><input  type="hidden" name="LEmpresa[]" value="' + data[i].kitproductname +
                            '"required>' + data[i].kitproductname +
                            '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i]
                            .kitcantidad + '"required>' + data[i].kitcantidad +
                            '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i]
                            .kitpreciounitario + '"required>' + simbolomonedaproducto + data[i]
                            .kitpreciounitario +
                            '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i]
                            .kitpreciounitariomo + '"required>' + simbolomonedafactura + data[i]
                            .kitpreciounitariomo +
                            '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i]
                            .kitpreciofinal + '"required>' + simbolomonedafactura + data[i].kitpreciofinal +
                            '</td></tr>';

                        $("#kits>tbody").append(filaDetalle);
                    }

                });

            })

            //modal para ver los eliminados
            var inicializartabla = 0;
            const modalkits = document.getElementById('modalkits');
            modalkits.addEventListener('show.bs.modal', event => {
                var urlinventario = "{{ url('admin/kits/showrestore') }}";
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
                            '</td><td>' + data[i].categoria +
                            '</td><td>' + data[i].nombre +
                            '</td><td>' + data[i].codigo +
                            '</td><td>' + data[i].unidad +
                            '</td><td>' + data[i].moneda +
                            '</td><td>' + data[i].NoIGV +
                            '</td><td>' + data[i].SiIGV +
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
                var urlregistro = "{{ url('admin/kits/restaurar') }}";
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
                var registro = "KITS: ";
                var boton =
                    ' @can('recuperar-kit') <button id="btnrestore" class="btn btn-info btn-sm" data-bs-toggle="modal"  data-bs-target="#modalkits"> Restaurar Eliminados </button> @endcan ';
                if (numeliminados > 0) {
                    document.getElementById('mititulo').innerHTML = registro + boton;
                } else {
                    document.getElementById('mititulo').innerHTML = registro;
                }
            }
        </script>
    @endpush
@endsection
