@extends('layouts.admin')
@push('css')
    <script>
        var mostrar = "NO";

        function mostrarstocks() {
            mostrar = "SI";
        }
    </script>
@endpush
@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">

                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }} </div>
                @endif
                @if (session('verstock'))
                    <script>
                        mostrarstocks();
                    </script>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 id="mititulo">INVENTARIOS:&nbsp;&nbsp;
                                </h4>
                            </div>
                            <div class="col-md-2">
                                <h4 id="mirestore">
                                </h4>
                            </div>
                            <div class="col-md-4">
                                @can('crear-inventario')
                                    <a href="{{ url('admin/inventario/create') }}" class="btn btn-primary float-end"> Añadir
                                        Stocks</a>
                                @endcan
                                <h4> <a type="button" class="btn btn-success float-end" data-bs-toggle="modal"
                                        data-bs-target="#modalkits" style="margin-right: 10px;">Stock de Kits</a>

                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered table-striped" style="width: 100%" id="mitabla" name="mitabla">
                            <thead class="fw-bold text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>CATEGORIA</th>
                                    <th>PRODUCTO</th>
                                    <th>STOCK MINIMO</th>
                                    <th>STOCK TOTAL</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-mantenimientos">


                            </tbody>
                        </table>
                        <div>

                        </div>
                    </div>
                    <div class="modal fade " id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Inventario</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-sm-4 col-lg-4 mb-3">
                                                <label for="verProducto" class="col-form-label">PRODUCTO:</label>
                                                <input type="text" class="form-control " id="verProducto" readonly>
                                            </div>
                                            <div class="col-sm-4 col-lg-4 mb-3">
                                                <label for="verStockminimo" class="col-form-label">STOCK MINIMO:</label>
                                                <input type="number" class="form-control" id="verStockminimo" readonly>
                                            </div>
                                            <div class="col-sm-4 col-lg-4 mb-3">
                                                <label for="verStocktotal" class="col-form-label">STOCK TOTAL:</label>
                                                <input type="number" class="form-control" id="verStocktotal" readonly>
                                            </div>

                                        </div>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered gy-5 gs-5" id="detallesInventario">
                                            <thead class="fw-bold text-primary">
                                                <tr>
                                                    <th>Empresa</th>
                                                    <th>Stock por Empresa</th>
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
                    <div class="modal fade " id="modalkits" tabindex="-1" aria-labelledby="modalkits" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Lista de kits</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="table-responsive">
                                        <table class="table table-row-bordered gy-5 gs-5" style="width: 100%" id="mitabla1"
                                            name="mitabla1">
                                            <thead class="fw-bold text-primary">
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Precio</th>
                                                    <th>Moneda</th>
                                                    <th>Ver Stock</th>
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
                    <div class="modal fade " id="mikit" tabindex="-1" aria-labelledby="mikitlabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mikitlabel1">Stock Total del Kit: </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        onclick="cerrartoast()"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col-sm-6 mb-3">
                                                        <label for="verKit" class="col-form-label">KIT:</label>
                                                        <input type="text" class="form-control " id="verKit"
                                                            readonly>
                                                    </div>
                                                    <div class="col-sm-6  mb-3">
                                                        <label for="verUnidad" class="col-form-label">UNIDAD:</label>
                                                        <input type="text" class="form-control" id="verUnidad"
                                                            readonly>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <label for="verPrecio" class="col-form-label">PRECIO:</label>
                                                        <input type="text" class="form-control" id="verPrecio"
                                                            readonly>
                                                    </div>
                                                    <div class="col-sm-6  mb-3">
                                                        <label for="verMoneda" class="col-form-label">MONEDA:</label>
                                                        <input type="text" class="form-control" id="verMoneda"
                                                            readonly>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="col">
                                                <h5>Productos que incluye el kit</h5>
                                                <table class="  table-row-bordered gy-5 gs-5" id="listaproductoskit">
                                                    <thead class="fw-bold text-primary">
                                                        <tr>
                                                            <th>Cantidad &nbsp;&nbsp;</th>
                                                            <th>Producto</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered gy-5 gs-5" id="listastockempresas">
                                            <thead class="fw-bold text-primary">
                                                <tr>
                                                    <th>Empresa</th>
                                                    <th>Stock por Empresa</th>
                                                    <th>Ver stock de productos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="toast-container position-fixed bottom-0 start-0 p-2"
                                        style="z-index: 1000">
                                        <div class="toast " role="alert" aria-live="assertive" aria-atomic="true"
                                            data-bs-autohide="false"
                                            style="width: 100%; box-shadow: 0 2px 5px 2px rgb(0, 89, 255); ">
                                            <div class="  card-header">
                                                <i class="mdi mdi-information menu-icon"></i>
                                                <strong class="mr-auto"> &nbsp; Stock de cada producto:</strong>
                                                <button type="button" class="btn-close float-end"
                                                    data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                            <div class="toast-body">
                                                <table id="detalleskit">
                                                    <thead class="fw-bold text-primary">
                                                        <tr>
                                                            <th>CANTIDAD</th>
                                                            <th>PRODUCTO</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-target="#modalkits"
                                        data-bs-toggle="modal" onclick="cerrartoast()">Volver</button>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade " id="modalrestore" tabindex="-1" aria-labelledby="modalrestore"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Lista de Inventarios Eliminadas</h1>
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
                                                    <th>PRODUCTO</th>
                                                    <th>STOCK MINIMO</th>
                                                    <th>STOCK TOTAL</th>
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

                    <div class="modal fade " id="modalsinstock" tabindex="-1" aria-labelledby="modalrestore"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Inventarios Con Stock Minimo Y Sin
                                        Stock</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="table-responsive">
                                        <table class="table table-row-bordered gy-5 gs-5" style="width: 100%"
                                            id="mitablasinstock" name="mitablasinstock">
                                            <thead class="fw-bold text-primary">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>CATEGORIA</th>
                                                    <th>PRODUCTO</th>
                                                    <th>STOCK MINIMO</th>
                                                    <th>STOCK TOTAL</th>
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
            var numerosinstock = 0;
            $(document).ready(function() {
                if (mostrar == "SI") {
                    $('#modalsinstock').modal('show');
                }
                var tabla = "#mitabla";
                var ruta = "{{ route('inventario.index') }}"; //darle un nombre a la ruta index
                var columnas = [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'categoria',
                        name: 'c.nombre'
                    },
                    {
                        data: 'producto',
                        name: 'p.nombre'
                    },
                    {
                        data: 'stockminimo',
                        name: 'stockminimo'
                    },
                    {
                        data: 'stocktotal',
                        name: 'stocktotal'
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
                numerosinstock = @json($productossinstock);
                mostrarmensajetitulo(numerosinstock);
                iniciarTablaIndex(tabla, ruta, columnas, btns);
            });
            //para borrar un registro de la tabla
            $(document).on('click', '.btnborrar', function(event) {
                const idregistro = event.target.dataset.idregistro;
                var urlregistro = "{{ url('admin/inventario') }}";
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
                                    $('#modalsinstock').modal('hide');
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

            var inicializartabla = 0;
            const mimodal = document.getElementById('mimodal');
            mimodal.addEventListener('show.bs.modal', event => {

                const button = event.relatedTarget;
                const id = button.getAttribute('data-id')
                var urlinventario = "{{ url('admin/inventario/show') }}";
                $.get(urlinventario + '/' + id, function(data) {
                    const modalTitle = mimodal.querySelector('.modal-title');
                    modalTitle.textContent = `Ver Registro ${id}`;
                    document.getElementById("verProducto").value = data['inventario'][0].nombre;
                    document.getElementById("verStockminimo").value = data['inventario'][0].stockminimo;
                    document.getElementById("verStocktotal").value = data['inventario'][0].stocktotal;

                    if (data['haydetalle'] == "si") {
                        var tabla = document.getElementById(detallesInventario);
                        $('#detallesInventario tbody tr').slice().remove();
                        for (var i = 0; i < data['detalle'].length; i++) {
                            filaDetalle = '<tr id="fila' + i +
                                '"><td><input  type="hidden" name="LEmpresa[]" value="' + data['detalle'][i]
                                .nombrempresa +
                                '"required>' + data['detalle'][i].nombrempresa +
                                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[
                                    'detalle'][i]
                                .stockempresa + '"required>' + data['detalle'][i].stockempresa +
                                '</td></tr>';

                            $("#detallesInventario>tbody").append(filaDetalle);
                        }
                    }
                });

            })

            const modalkits = document.getElementById('modalkits');
            modalkits.addEventListener('show.bs.modal', event => {
                var urlinventario = "{{ url('admin/inventario/showkits') }}";
                $.get(urlinventario, function(data) {
                    $('#mitabla1 tbody tr').slice().remove();
                    for (var i = 0; i < data.length; i++) {
                        filaDetalle = '<tr id="fila' + i +
                            '"><td>' + data[i].kit +
                            '</td><td>' + data[i].precio +
                            '</td><td>' + data[i].moneda +
                            '</td><td><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#mikit"' +
                            ' data-id="' + data[i].id + '">Ver</button></td>  ' +
                            '</tr>';
                        //  const id = button.getAttribute('data-id');
                        $("#mitabla1>tbody").append(filaDetalle);
                    }
                    inicializartabla1(inicializartabla);
                    inicializartabla++;
                });
            });
            const mikit = document.getElementById('mikit');
            mikit.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const idkit = button.getAttribute('data-id');
                var urlkit = "{{ url('admin/products/show') }}";
                $.get(urlkit + '/' + idkit, function(datak) {

                    document.getElementById("verKit").value = datak.nombre;
                    document.getElementById("verUnidad").value = datak.unidad;
                    document.getElementById("verMoneda").value = datak.moneda;
                    document.getElementById("verPrecio").value = datak.NoIGV;
                });
                var urllistaprod = "{{ url('admin/venta/stocktotalxkit') }}";
                $.get(urllistaprod + '/' + idkit, function(data) {
                    const modalTitle = mikit.querySelector('.modal-title');
                    modalTitle.textContent = `Stock Total del Kit: ${data} Unidades`;

                });
                var urllistaprod = "{{ url('admin/venta/productosxkit') }}";
                $.get(urllistaprod + '/' + idkit, function(data) {
                    $('#listaproductoskit tbody tr').slice().remove();
                    for (var i = 0; i < data.length; i++) {
                        filaDetalle = '<tr style="border-top: 1px solid silver;" id="fila' + i +
                            '"><td>' + data[i].cantidad +
                            '</td><td>' + data[i].producto +
                            '</td> </tr>';
                        $("#listaproductoskit>tbody").append(filaDetalle);
                    }
                });

                var urlstockkit = "{{ url('admin/venta/stockkitxempresa') }}";
                $.get(urlstockkit + '/' + idkit, function(data) {
                    $('#listastockempresas tbody tr').slice().remove();
                    //console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        filaDetalle = '<tr  id="fila' + i +
                            '"><td>' + data[i].empresa +
                            '</td><td>' + data[i].stock +
                            '</td><td><button type="button" class="btn btn-info" onclick="verstocks(' + idkit +
                            ',' + data[i].id +
                            ')" >Ver</button></td>  </tr>';
                        $("#listastockempresas>tbody").append(filaDetalle);
                    }
                });

            });

            function verstocks(idkit, idcompanie) {
                var urlstockprod = "{{ url('admin/venta/stockxprodxempresa') }}";
                $.get(urlstockprod + '/' + idkit + '/' + idcompanie, function(data) {
                    $('#detalleskit tbody tr').slice().remove();
                    //console.log(data);
                    for (var i = 0; i < data.length; i++) {
                        filaDetalle = '<tr  id="fila' + i +
                            '"><td>' + data[i].stockempresa +
                            '</td><td>' + data[i].nombre +
                            '</td> </tr>';
                        $("#detalleskit>tbody").append(filaDetalle);
                    }
                    $('.toast').toast('show');
                });
            }
            $(document).ready(function() {
                $('.toast').toast();
            });

            function cerrartoast() {
                $('.toast').toast('hide');
            }

            //modal para ver los eliminados
            var inicializartablares = 0;
            const modalrestore = document.getElementById('modalrestore');
            modalrestore.addEventListener('show.bs.modal', event => {
                var urlinventario = "{{ url('admin/inventario/showrestore') }}";
                $.get(urlinventario, function(data) {
                    var btns = 'lfrtip';
                    var tabla = '#mitablarestore';
                    if (inicializartablares > 0) {
                        $("#mitablarestore").dataTable().fnDestroy(); //eliminar las filas de la tabla  
                    }
                    $('#mitablarestore tbody tr').slice().remove();
                    for (var i = 0; i < data.length; i++) {
                        filaDetalle = '<tr id="fila' + i +
                            '"><td>' + data[i].id +
                            '</td><td>' + data[i].categoria +
                            '</td><td>' + data[i].producto +
                            '</td><td>' + data[i].stockminimo +
                            '</td><td>' + data[i].stocktotal +
                            '</td><td><button type="button" class="btn btn-info"  ' +
                            ' onclick="RestaurarRegistro(' + data[i].id + ')" >Restaurar</button></td>  ' +
                            '</tr>';
                        $("#mitablarestore>tbody").append(filaDetalle);
                    }
                    inicializartabladatos(btns, tabla, "");
                    inicializartablares++;
                });
            });

            function RestaurarRegistro(idregistro) {
                var urlregistro = "{{ url('admin/inventario/restaurar') }}";
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
                                    $('#modalrestore').modal('hide');
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
                var registro = " ";
                var boton =
                    ' @can('recuperar-empresa') <button id="btnrestore" class="btn btn-info btn-sm " data-bs-toggle="modal"  data-bs-target="#modalrestore"> Restaurar Eliminados</button> @endcan ';
                if (numeliminados > 0) {
                    document.getElementById('mirestore').innerHTML = registro + boton;
                } else {
                    document.getElementById('mirestore').innerHTML = registro;
                }
            }

            function mostrarmensajetitulo(numsinstock) {
                var registro = "INVENTARIOS: ";
                var tienes = "";
                var stock = " productos en stock minimo";
                var boton =
                    ' @can('recuperar-empresa') <button id="btnsinstock" class="btn btn-info btn-sm " data-bs-toggle="modal"  data-bs-target="#modalsinstock"> Ver </button> @endcan ';
                if (numsinstock > 0) {
                    tienes = "Tienes ";
                    document.getElementById('mititulo').innerHTML = registro + tienes + numsinstock + stock + boton;
                } else {
                    document.getElementById('mititulo').innerHTML = registro;
                }
            }
            var inicializartablasinstock = 0;
            const modalsinstock = document.getElementById('modalsinstock');
            modalsinstock.addEventListener('show.bs.modal', event => {
                var nrosinstock = 0;
                var nrostockminimo = 0;
                var urlinventario = "{{ url('admin/inventario/showsinstock') }}";

                $.get(urlinventario, function(data) {
                    var miurl = "{{ url('/admin/inventario/') }}";
                    var btns = 'lfrtip';
                    var tabla = '#mitablasinstock';
                    if (inicializartablasinstock > 0) {
                        $("#mitablasinstock").dataTable().fnDestroy(); //eliminar las filas de la tabla  
                    }
                    $('#mitablasinstock tbody tr').slice().remove();
                    for (var i = 0; i < data.length; i++) {
                        var colorfondo = '<tr id="fila' + i + '">';
                        if (data[i].stocktotal <= 0) {
                            colorfondo = '<tr style="background-color:  #f89f9f" id="fila' + i + '">';
                            nrosinstock++;
                        } else {
                            nrostockminimo++;
                        }
                        const modalTitle = modalsinstock.querySelector('.modal-title');
                        modalTitle.textContent = `Tienes ${nrosinstock} productos sin stock y ${nrostockminimo} con stock minimo`;

                        filaDetalle = colorfondo +
                            '<td>' + data[i].id +
                            '</td><td>' + data[i].categoria +
                            '</td><td>' + data[i].producto +
                            '</td><td>' + data[i].stockminimo +
                            '</td><td>' + data[i].stocktotal +
                            '</td><td>@can('editar-inventario')<a  href="' + miurl + '/' + data[i]
                            .id +
                            '/edit" class="btn btn-success">Editar</a> @endcan' +
                            '<button type="button" class="btn btn-secondary" data-id="' + data[i].id +
                            '" data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>' +
                            ' @can('eliminar-inventario')<button type="button" class="btn btn-danger btnborrar" data-idregistro="' +
                            data[i].id +
                            '">Eliminar</button>@endcan ' +
                            '</td> ' +
                            '</tr>';
                        $("#mitablasinstock>tbody").append(filaDetalle);
                    }
                    inicializartabladatos(btns, tabla, "");
                    inicializartablasinstock++;
                });
            });
        </script>
    @endpush
@endsection
