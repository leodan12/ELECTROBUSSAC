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
                                <h4 id="mititulo">REGISTRO DE INGRESOS O COMPRAS:
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    @can('crear-ingreso')
                                        <a href="{{ url('admin/ingreso/create') }}" class="btn btn-primary float-end">Añadir
                                            Ingreso</a>
                                    @endcan
                                </h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <h5>Tienes {{ $sinnumero }} compras sin numero de factura</h5>
                            </div>
                        </div>

                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped " id="mitabla" name="mitabla">
                                <thead class="fw-bold text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>FACTURA</th>
                                        <th>FECHA</th>
                                        <th>PROVEEDOR</th>
                                        <th>EMPRESA</th>
                                        <th>MONEDA</th>
                                        <th>FORMA PAGO</th>
                                        <th>COSTO COMPRA</th>
                                        <th>PAGADA</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mantenimientos">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- modal de ver venta --}}
                    <div class="modal fade " id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Ingreso</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-4   mb-3">
                                                <label for="verFecha" class="col-form-label">FECHA:</label>
                                                <input type="text" class="form-control " id="verFecha" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verFactura" class="col-form-label">NUMERO FACTURA:</label>
                                                <input type="text" class="form-control" id="verFactura" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verFormapago" class="col-form-label">FORMA PAGO:</label>
                                                <input type="text" class="form-control" id="verFormapago" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3 " id="divfechav">
                                                <label for="verFechav" class="col-form-label">FECHA VENCIMIENTO:</label>
                                                <input type="text" class="form-control" id="verFechav" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verMoneda" class="col-form-label">MONEDA:</label>
                                                <input type="text" class="form-control " id="verMoneda" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3" id="divtasacambio">
                                                <label for="verTipocambio" class="col-form-label">TIPO DE CAMBIO:</label>
                                                <input type="text" class="form-control " id="verTipocambio" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verEmpresa" class="col-form-label">EMPRESA:</label>
                                                <input type="text" class="form-control " id="verEmpresa" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verCliente" class="col-form-label">PROVEEDOR:</label>
                                                <input type="text" class="form-control " id="verCliente" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <div class="input-group">
                                                    <label for="verPrecioventa" class="col-form-label input-group">PRECIO
                                                        COMPRA:</label>
                                                    <span class="input-group-text" id="spancostoventa"></span>
                                                    <input type="text" class="form-control " id="verPrecioventa"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class=" col-md-4   mb-3" id="divobservacion">
                                                <label for="verObservacion" class="col-form-label">OBSERVACION:</label>
                                                <input type="text" class="form-control " id="verObservacion" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verPagada" class="col-form-label">FACTURA PAGADA:</label>
                                                <input type="text" class="form-control " id="verPagada" readonly>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered gy-5 gs-5" id="detallesventa">
                                            <thead class="fw-bold text-primary">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Observacion</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Unitario(referencial)</th>
                                                    <th>precio Unitario</th>
                                                    <th>Servicio Adicional</th>
                                                    <th>Costo Productos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    @can('editar-ingreso')
                                        <button type="button" class="btn btn-success" id="pagarfactura">Pagar
                                            Factura</button>
                                    @endcan
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- mis modales para ver los creditos vencidos --}}
                    <div class="modal fade" id="modalCreditos1" aria-hidden="true" aria-labelledby="modalCreditos1Label"
                        tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modalCreditos1Label1">
                                        TIENES: &nbsp;
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <table class="table table-bordered table-striped " style="width: 100%" id="mitabla1"
                                        name="mitabla1">
                                        <thead class="fw-bold text-primary">
                                            <tr>
                                                <th>ID</th>
                                                <th>FECHA</th>
                                                <th>FECHA VENC</th>
                                                <th>PROVEEDOR</th>
                                                <th>EMPRESA</th>
                                                <th>MONEDA</th>
                                                <th>FORMA PAGO</th>
                                                <th>COSTO VENTA </th>
                                                <th>ACCIONES</th>
                                            </tr>
                                        </thead>
                                        <Tbody>
                                            <tr></tr>
                                        </Tbody>
                                    </table>
                                </div>
                                <div class="modal-footer">

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- modal para ver los datosde los creditos x vencer --}}
                    <div class="modal fade" id="modalVer2" aria-hidden="true" aria-labelledby="modalCreditos1Label2"
                        tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modalCreditos1Label2">Ver Compra a Credito</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-4   mb-3">
                                                <label for="verFecha1" class="col-form-label">FECHA:</label>
                                                <input type="text" class="form-control " id="verFecha1" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verFactura1" class="col-form-label">NUMERO FACTURA:</label>
                                                <input type="text" class="form-control" id="verFactura1" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verFormapago1" class="col-form-label">FORMA PAGO:</label>
                                                <input type="text" class="form-control" id="verFormapago1" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3 " id="divfechav1">
                                                <label for="verFechav1" class="col-form-label">FECHA VENCIMIENTO:</label>
                                                <input type="text" class="form-control" id="verFechav1" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verMoneda1" class="col-form-label">MONEDA:</label>
                                                <input type="text" class="form-control " id="verMoneda1" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3" id="divtasacambio">
                                                <label for="verTipocambio1" class="col-form-label">TIPO DE CAMBIO:</label>
                                                <input type="text" class="form-control " id="verTipocambio1" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verEmpresa1" class="col-form-label">EMPRESA:</label>
                                                <input type="text" class="form-control " id="verEmpresa1" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verCliente1" class="col-form-label">PROVEEDOR:</label>
                                                <input type="text" class="form-control " id="verCliente1" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <div class="input-group">
                                                    <label for="verPrecioventa1" class="col-form-label input-group">PRECIO
                                                        VENTA:</label>
                                                    <span class="input-group-text" id="spancostoventa1"></span>
                                                    <input type="text" class="form-control " id="verPrecioventa1"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class=" col-md-4   mb-3" id="divobservacion1">
                                                <label for="verObservacion1" class="col-form-label">OBSERVACION:</label>
                                                <input type="text" class="form-control " id="verObservacion1"
                                                    readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <label for="verPagada1" class="col-form-label">FACTURA PAGADA:</label>
                                                <input type="text" class="form-control " id="verPagada1" readonly>
                                            </div>

                                        </div>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-row-bordered gy-5 gs-5" id="detallesventa1">
                                            <thead class="fw-bold text-primary">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Observacion</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Unitario(referencial)</th>
                                                    <th>precio Unitario</th>
                                                    <th>Servicio Adicional</th>
                                                    <th>Costo Productos</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    @can('editar-ingreso')
                                        <button type="button" class="btn btn-warning" id="pagarfactura1">Pagar
                                            Factura</button>
                                    @endcan
                                    <button class="btn btn-primary" data-bs-target="#modalCreditos1"
                                        data-bs-toggle="modal">Volver</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- fin del modal --}}

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('admin/midatatable.js') }}"></script>
    <script>
        $(document).ready(function() {
            var tabla = "#mitabla";
            var ruta = "{{ route('ingreso.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'factura',
                    name: 'factura'
                },
                {
                    data: 'fecha',
                    name: 'fecha'
                },

                {
                    data: 'cliente',
                    name: 'c.nombre'
                },
                {
                    data: 'empresa',
                    name: 'e.nombre'
                },
                {
                    data: 'moneda',
                    name: 'moneda'
                },
                {
                    data: 'formapago',
                    name: 'formapago'
                },
                {
                    data: 'costoventa',
                    name: 'costoventa'
                },
                {
                    data: 'pagada',
                    name: 'pagada'
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
            var urlregistro = "{{ url('admin/ingreso') }}";
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
        var idventa = "";
        var inicializartabla = 0;
        var numerocreditos = 0;
        //para el modal de ver venta
        const mimodal = document.getElementById('mimodal')
        mimodal.addEventListener('show.bs.modal', event => {

            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            var urlventa = "{{ url('admin/ingreso/show') }}";
            $.get(urlventa + '/' + id, function(midata) {
                idventa = id;
                const modalTitle = mimodal.querySelector('.modal-title')
                modalTitle.textContent = `Ver Registro ${id}`
                document.getElementById("verFecha").value = midata[0].fecha;
                document.getElementById("verFactura").value = midata[0].factura;
                document.getElementById("verMoneda").value = midata[0].moneda;
                document.getElementById("verFormapago").value = midata[0].formapago;
                document.getElementById("verEmpresa").value = midata[0].company;
                document.getElementById("verCliente").value = midata[0].cliente;
                document.getElementById("verPagada").value = midata[0].pagada;
                document.getElementById("verPrecioventa").value = (midata[0].costoventa).toFixed(2);
                if (midata[0].moneda == "dolares") {
                    document.getElementById('spancostoventa').innerHTML = "$";
                } else if (midata[0].moneda == "soles") {
                    document.getElementById('spancostoventa').innerHTML = "S/.";
                }

                if (midata[0].fechav == null) {
                    document.getElementById('divfechav').style.display = 'none';
                } else {
                    document.getElementById('divfechav').style.display = 'inline';
                    document.getElementById("verFechav").value = midata[0].fechav;
                }
                document.getElementById("verTipocambio").value = midata[0].tasacambio;

                if (midata[0].pagada == "NO") {
                    var btnventa = document.getElementById('pagarfactura')
                    if (btnventa) {
                        btnventa.style.display = 'inline';
                    }
                } else if (midata[0].pagada == "SI") {
                    var btnventa = document.getElementById('pagarfactura')
                    if (btnventa) {
                        btnventa.style.display = 'none';
                    }
                }

                if (midata[0].observacion == null) {
                    document.getElementById('divobservacion').style.display = 'none';
                } else {
                    document.getElementById('divobservacion').style.display = 'inline';
                    document.getElementById("verObservacion").value = midata[0].observacion;
                }
                var monedafactura = midata[0].moneda;
                var simbolomonedaproducto = "";
                var simbolomonedafactura = "";

                if (monedafactura == "dolares") {
                    simbolomonedafactura = "$";
                } else if (monedafactura == "soles") {
                    simbolomonedafactura = "S/.";
                }


                var tabla = document.getElementById(detallesventa);
                $('#detallesventa tbody tr').slice().remove();
                for (var ite = 0; ite < midata.length; ite++) {
                    var monedaproducto = midata[ite].monedaproducto;
                    if (monedaproducto == "dolares") {
                        simbolomonedaproducto = "$";
                    } else if (monedaproducto == "soles") {
                        simbolomonedaproducto = "S/.";
                    }


                    if (midata[ite].tipo == 'kit') {
                        var urlventa = "{{ url('admin/venta/productosxkit') }}";
                        $.ajax({
                            type: "GET",
                            url: urlventa + '/' + midata[ite].idproducto,
                            async: false,
                            data: {
                                id: id
                            },
                            success: function(data1) {
                                var milista = '<br>';
                                var puntos = ': ';
                                for (var j = 0; j < data1.length; j++) {
                                    var coma = '<br>';
                                    milista = milista + '-' + data1[j].cantidad + ' ' + data1[j]
                                        .producto + coma;

                                }
                                filaDetalle = '<tr id="fila' + ite +
                                    '"><td> <b>' + midata[ite].producto + '</b>' + puntos +
                                    milista + coma +
                                    '</td><td> ' + midata[ite].observacionproducto +
                                    '</td><td> ' + midata[ite].cantidad +
                                    '</td><td> ' + simbolomonedaproducto + midata[ite]
                                    .preciounitario +
                                    '</td><td> ' + simbolomonedafactura + midata[ite]
                                    .preciounitariomo +
                                    '</td><td> ' + simbolomonedafactura + midata[ite].servicio +
                                    '</td><td> ' + simbolomonedafactura + midata[ite]
                                    .preciofinal +
                                    '</td></tr>';
                                $("#detallesventa>tbody").append(filaDetalle);

                                milista = '<br>';
                            }
                        });

                    } else
                    if (midata[ite].tipo == 'estandar') {
                        filaDetalle = '<tr id="fila' + ite +
                            '"><td> <b>' + midata[ite].producto + '</b>' +
                            '</td><td> ' + midata[ite].observacionproducto +
                            '</td><td> ' + midata[ite].cantidad +
                            '</td><td> ' + simbolomonedaproducto + midata[ite].preciounitario +
                            '</td><td> ' + simbolomonedafactura + midata[ite].preciounitariomo +
                            '</td><td> ' + simbolomonedafactura + midata[ite].servicio +
                            '</td><td> ' + simbolomonedafactura + midata[ite].preciofinal +
                            '</td></tr>';
                        $("#detallesventa>tbody").append(filaDetalle);
                    }
                }

            });

        })

        //mostrar el modal de la lista de los creditos---------------------------

        const modallistacreditos = document.getElementById('modalCreditos1')
        modallistacreditos.addEventListener('show.bs.modal', event => {
            var nroxvencer = 0;
            var nrovencidos = 0;
            var hoy = new Date();
            var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' +
                String(hoy.getDate()).padStart(2, '0');
            // alert(fechaActual);
            const button = event.relatedTarget;
            // const id = button.getAttribute('data-id');
            var urlventa = "{{ url('admin/ingreso/showcreditos') }}";
            $.get(urlventa, function(data) {
                const modalTitle = modallistacreditos.querySelector('.modal-title')
                modalTitle.textContent = `Ingresos a credito por vencer: `;

                var simbolomonedafact = "";
                $('#mitabla1 tbody tr').slice().remove();
                var miurl = "{{ url('/admin/ingreso/') }}";
                for (var i = 0; i < data.length; i++) {
                    var monedafact = data[i].moneda;
                    if (monedafact == "dolares") {
                        simbolomonedafact = "$";
                    } else if (monedafact == "soles") {
                        simbolomonedafact = "S/.";
                    }
                    var colorfondo = '<tr id="fila' + i + '">';
                    if (data[i].fechav < fechaActual) {
                        colorfondo = '<tr style="background-color:  #f89f9f" id="fila' + i + '">';
                        nrovencidos++;
                    } else {
                        nroxvencer++;
                    }
                    filaDetalle = colorfondo +
                        '<td><input  type="hidden"   value="' + data[i].id + '"required>' + data[i].id +
                        '</td><td><input  type="hidden"  value="' + data[i].fecha + '"required>' + data[i]
                        .fecha +
                        '</td><td><input  type="hidden"  value="' + data[i].fechav + '"required>' + data[i]
                        .fechav +
                        '</td><td><input  type="hidden"  value="' + data[i].nombrecliente + '"required>' +
                        data[i].nombrecliente +
                        '</td><td><input  type="hidden"  value="' + data[i].nombreempresa + '"required>' +
                        data[i].nombreempresa +
                        '</td><td><input  type="hidden"  value="' + data[i].moneda + '"required>' + data[i]
                        .moneda +
                        '</td><td><input  type="hidden"  value="' + data[i].formapago + '"required>' + data[
                            i].formapago +
                        '</td><td><input  type="hidden"  value="' + data[i].costoventa + '"required>' +
                        simbolomonedafact + data[i].costoventa +
                        '</td><td>@can('editar-ingreso')<a  href="' + miurl + '/' + data[i]
                        .id +
                        '/edit" class="btn btn-success">Editar</a> @endcan' +
                        '<button type="button" class="btn btn-secondary" data-id="' + data[i].id +
                        '" data-bs-target="#modalVer2" data-bs-toggle="modal">Ver</button>' +
                        '@can('eliminar-ingreso')<form action="' + miurl + '/' + data[i].id +
                        '/delete" class="d-inline formulario-eliminar"> <button type="submit" class="btn btn-danger formulario-eliminar">Eliminar </button></form>@endcan' +
                        '</td></tr>';

                    $("#mitabla1>tbody").append(filaDetalle);
                }
                inicializartabla1(inicializartabla);
                inicializartabla++;
                mostrarmensajemodal(nroxvencer, nrovencidos);
            });

        })
        //mostrar el modal de los datos de los creditos---------------------------

        const mimodalcreditos = document.getElementById('modalVer2');
        mimodalcreditos.addEventListener('show.bs.modal', event => {

            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            var urlventa = "{{ url('admin/ingreso/show') }}";
            $.get(urlventa + '/' + id, function(midata) {
                const modalTitle = mimodalcreditos.querySelector('.modal-title')
                modalTitle.textContent = `Ver Registro ${id}`;
                idventa = id;
                document.getElementById("verFecha1").value = midata[0].fecha;
                document.getElementById("verFactura1").value = midata[0].factura;
                document.getElementById("verMoneda1").value = midata[0].moneda;
                document.getElementById("verFormapago1").value = midata[0].formapago;
                document.getElementById("verEmpresa1").value = midata[0].company;
                document.getElementById("verCliente1").value = midata[0].cliente
                document.getElementById("verPagada1").value = midata[0].pagada;
                document.getElementById("verPrecioventa1").value = midata[0].costoventa;
                if (midata[0].moneda == "dolares") {
                    document.getElementById('spancostoventa1').innerHTML = "$";
                } else if (midata[0].moneda == "soles") {
                    document.getElementById('spancostoventa1').innerHTML = "S/.";
                }

                if (midata[0].fechav == null) {
                    document.getElementById('divfechav1').style.display = 'none';
                } else {
                    document.getElementById('divfechav1').style.display = 'inline';
                    document.getElementById("verFechav1").value = midata[0].fechav;
                }
                document.getElementById("verTipocambio1").value = midata[0].tasacambio;

                if (midata[0].pagada == "NO") {
                    var pagarfactura = document.getElementById('pagarfactura1');
                    if (pagarfactura) {
                        pagarfactura.style.display = 'inline';
                    }
                } else if (midata[0].pagada == "SI") {
                    var pagarfactura = document.getElementById('pagarfactura1');
                    if (pagarfactura) {
                        pagarfactura.style.display = 'none';
                    }
                }

                if (midata[0].observacion == null) {
                    document.getElementById('divobservacion1').style.display = 'none';
                } else {
                    document.getElementById('divobservacion1').style.display = 'inline';
                    document.getElementById("verObservacion1").value = midata[0].observacion;
                }


                var monedafactura = midata[0].moneda;
                var simbolomonedaproducto = "";
                var simbolomonedafactura = "";


                if (monedafactura == "dolares") {
                    simbolomonedafactura = "$";
                } else if (monedafactura == "soles") {
                    simbolomonedafactura = "S/.";
                }

                var tabla = document.getElementById(detallesventa);
                $('#detallesventa1 tbody tr').slice().remove();
                for (var ite = 0; ite < midata.length; ite++) {
                    var monedaproducto = midata[ite].monedaproducto;
                    if (monedaproducto == "dolares") {
                        simbolomonedaproducto = "$";
                    } else if (monedaproducto == "soles") {
                        simbolomonedaproducto = "S/.";
                    }

                    if (midata[ite].tipo == 'kit') {

                        var urlventa = "{{ url('admin/venta/productosxkit') }}";

                        $.ajax({
                            type: "GET",
                            url: urlventa + '/' + midata[ite].idproducto,
                            async: false,
                            data: {
                                id: id
                            },
                            success: function(data1) {
                                var milista = '<br>';
                                var puntos = ': ';
                                for (var j = 0; j < data1.length; j++) {
                                    var coma = '<br>';
                                    milista = milista + '-' + data1[j].cantidad + ' ' + data1[j]
                                        .producto + coma;
                                }
                                filaDetalle = '<tr id="fila' + ite +
                                    '"><td> <b>' + midata[ite].producto + '</b>' + puntos +
                                    milista + coma +
                                    '</td><td> ' + midata[ite].observacionproducto +
                                    '</td><td> ' + midata[ite].cantidad +
                                    '</td><td> ' + simbolomonedaproducto + midata[ite]
                                    .preciounitario +
                                    '</td><td> ' + simbolomonedafactura + midata[ite]
                                    .preciounitariomo +
                                    '</td><td> ' + simbolomonedafactura + midata[ite].servicio +
                                    '</td><td> ' + simbolomonedafactura + midata[ite]
                                    .preciofinal +
                                    '</td></tr>';
                                $("#detallesventa1>tbody").append(filaDetalle);

                                milista = '<br>';
                            }
                        });

                    } else
                    if (midata[ite].tipo == 'estandar') {
                        filaDetalle = '<tr id="fila' + ite +
                            '"><td> <b>' + midata[ite].producto + '</b>' +
                            '</td><td> ' + midata[ite].observacionproducto +
                            '</td><td> ' + midata[ite].cantidad +
                            '</td><td> ' + simbolomonedaproducto + midata[ite].preciounitario +
                            '</td><td> ' + simbolomonedafactura + midata[ite].preciounitariomo +
                            '</td><td> ' + simbolomonedafactura + midata[ite].servicio +
                            '</td><td> ' + simbolomonedafactura + midata[ite].preciofinal +
                            '</td></tr>';
                        $("#detallesventa1>tbody").append(filaDetalle);
                    }
                }

            });

        })
        //fin de los modales
        window.addEventListener('close-modal', event => {
            $('#deleteModal').modal('hide');
        });

        $('#pagarfactura').click(function() {
            pagarfacturaingreso();
        });
        $('#pagarfactura1').click(function() {
            pagarfacturaingreso();
        });



        function pagarfacturaingreso() {
            var urlventa = "{{ url('/admin/ingreso/pagarfactura') }}";
            Swal.fire({
                title: '¿Esta seguro que desea pagar?',
                //text: "No lo podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí,Pagar!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.get(urlventa + '/' + idventa, function(data) {
                        $('#mimodal').modal('hide');
                        $('#modalVer2').modal('hide');
                        if (data[0] == 1) {
                            document.getElementById('ventapagada' + idventa).innerHTML = "SI";
                            numerocreditos--;
                            mostrarmensaje(numerocreditos);
                            Swal.fire({
                                text: "Factura Pagada",
                                icon: "success"
                            });
                        } else if (data[0] == 0) {
                            Swal.fire({
                                text: "No se puede pagar",
                                icon: "error"
                            });
                        } else if (data[0] == 2) {
                            Swal.fire({
                                text: "registro no encontrado",
                                icon: "error"
                            });

                        }
                    });

                }

            });

        }

        $(document).ready(function() {
            numerocreditos = @json($creditosxvencer);
            mostrarmensaje(numerocreditos);

        });

        function mostrarmensaje(numCred) {
            var registro = "REGISTRO DE INGRESOS O COMPRAS: ";
            var tienes = "Tienes ";
            var pago = " Creditos por Pagar ";
            var boton =
                '<button class="btn btn-info" data-bs-target="#modalCreditos1" data-bs-toggle="modal">  Ver</button>';
            if (numCred > 0) {
                document.getElementById('mititulo').innerHTML = registro + tienes + numCred + pago + boton;
            } else {
                document.getElementById('mititulo').innerHTML = registro;
            }

        }

        function mostrarmensajemodal(nroxvencer, nrovencidos) {
            var xvencer = " Creditos por Vencer";
            var vencidos = " Creditos Vencidos";
            var y = " y ";
            var tienes = "TIENES: ";
            if (nroxvencer > 0 && nrovencidos > 0) {
                document.getElementById('modalCreditos1Label1').innerHTML = tienes + nroxvencer + xvencer + y +
                    nrovencidos + vencidos;
            } else if (nroxvencer > 0 && nrovencidos == 0) {
                document.getElementById('modalCreditos1Label1').innerHTML = tienes + nroxvencer + xvencer;
            } else if (nroxvencer == 0 && nrovencidos > 0) {
                document.getElementById('modalCreditos1Label1').innerHTML = tienes + nrovencidos + vencidos;
            }

        }
    </script>
@endpush
