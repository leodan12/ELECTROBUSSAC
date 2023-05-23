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
                                <h4 id="mititulo">REGISTRO DE VENTAS:
                                </h4>
                            </div>
                            <div class="col">
                                <h4>
                                    <a href="{{ url('admin/venta/create') }}" class="btn btn-primary float-end">Añadir
                                        venta</a>
                                </h4>
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
                                        <th>CLIENTE</th>
                                        <th>EMPRESA</th>
                                        <th>MONEDA</th>
                                        <th>FORMA PAGO</th>
                                        <th>COSTO VENTA </th>
                                        <th>PAGADA </th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <Tbody id="tbody-mantenimientos">

                                    @forelse ($ventas as $venta)
                                        <tr>
                                            <td>{{ $venta->id }}</td>
                                            <td>{{ $venta->factura }}</td>
                                            <td>{{ $venta->fecha }}</td>
                                            <td>
                                                @if ($venta->cliente)
                                                    {{ $venta->cliente->nombre }}
                                                @else
                                                    No esta la empresa registrada
                                                @endif
                                            </td>
                                            <td>
                                                @if ($venta->company)
                                                    {{ $venta->company->nombre }}
                                                @else
                                                    No esta la empresa registrada
                                                @endif
                                            </td>
                                            <td> {{ $venta->moneda }}</td>
                                            <td> {{ $venta->formapago }}</td>
                                            @if ($venta->moneda == 'soles')
                                                <td>S/. {{ $venta->costoventa }}</td>
                                            @elseif($venta->moneda == 'dolares')
                                                <td>$ {{ $venta->costoventa }}</td>
                                            @endif
                                            <td id="ventapagada{{ $venta->id }}">{{ $venta->pagada }}</td>

                                            <td>
                                                <a href="{{ url('admin/venta/' . $venta->id . '/edit') }}"
                                                    class="btn btn-success">Editar</a>
                                                <button type="button" class="btn btn-secondary"
                                                    data-id="{{ $venta->id }}" data-bs-toggle="modal"
                                                    data-bs-target="#mimodal">Ver</button>
                                                <form action="{{ url('admin/venta/' . $venta->id . '/delete') }}"
                                                    class="d-inline formulario-eliminar">
                                                    <button type="submit" class="btn btn-danger formulario-eliminar">
                                                        Eliminar
                                                    </button>
                                                </form>


                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No hay Productos Disponibles</td>
                                        </tr>
                                    @endforelse
                                </Tbody>
                            </table>

                        </div>
                    </div>
                    {{-- modal paera ver la venta --}}
                    <div class="modal fade " id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Venta</h1>
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
                                                <label for="verCliente" class="col-form-label">CLIENTE:</label>
                                                <input type="text" class="form-control " id="verCliente" readonly>
                                            </div>
                                            <div class=" col-md-4   mb-3">
                                                <div class="input-group">
                                                    <label for="verPrecioventa" class="col-form-label input-group">PRECIO
                                                        VENTA:</label>
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
                                    <button type="button" class="btn btn-success" id="generarfactura"> Generar Pdf de la
                                        Factura </button>
                                    <button type="button" class="btn btn-warning" id="pagarfactura">Pagar
                                        Factura</button>
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
                                    <h1 class="modal-title fs-5" id="modalCreditos1Label1"> </h1>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <table class="table table-bordered table-striped " id="mitabla1" name="mitabla1">
                                        <thead class="fw-bold text-primary">
                                            <tr>
                                                <th>ID</th>
                                                <th>FECHA</th>
                                                <th>FECHA VENC</th>
                                                <th>CLIENTE</th>
                                                <th>EMPRESA</th>
                                                <th>MONEDA</th>
                                                <th>FORMA PAGO</th>
                                                <th>COSTO VENTA </th>
                                                <th>ACCIONES</th>
                                            </tr>
                                        </thead>
                                        <Tbody id="tbody-mantenimientos">
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
                    <div class="modal fade" id="modalVer2" aria-hidden="true" aria-labelledby="modalCreditos2Label2"
                        tabindex="-1">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="modalCreditos2Label2">Ver Venta a Credito</h1>
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
                                                <label for="verCliente1" class="col-form-label">CLIENTE:</label>
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
                                    <button type="button" class="btn btn-success" id="generarfactura1"> Generar Pdf de
                                        la Factura </button>
                                    <button type="button" class="btn btn-warning" id="pagarfactura1">Pagar
                                        Factura</button>
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
    @push('script')
        <script src="{{ asset('admin/midatatable.js') }}"></script>
        <script>
            //para el modal ver venta
            var idventa = "";
            var nrocreditos = 0;
            var hoy = new Date();
            var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' + String(hoy
                .getDate()).padStart(2, '0');
            var inicializartabla = 0;

            var numerocreditos = 0;
            const mimodal = document.getElementById('mimodal');
            mimodal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                var urlventa = "{{ url('admin/venta/show') }}";
                $.get(urlventa + '/' + id, function(data) {
                    var midata = data;
                    //console.log(midata);
                    const modalTitle = mimodal.querySelector('.modal-title')
                    modalTitle.textContent = `Ver Registro ${id}`;
                    idventa = id;
                    document.getElementById("verFecha").value = midata[0].fecha;
                    document.getElementById("verFactura").value = midata[0].factura;
                    document.getElementById("verMoneda").value = midata[0].moneda;
                    document.getElementById("verFormapago").value = midata[0].formapago;
                    document.getElementById("verEmpresa").value = midata[0].company;
                    document.getElementById("verCliente").value = midata[0].cliente
                    document.getElementById("verPagada").value = midata[0].pagada;
                    document.getElementById("verPrecioventa").value = midata[0].costoventa;
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
                        document.getElementById('pagarfactura').style.display = 'inline';
                    } else if (midata[0].pagada == "SI") {
                        document.getElementById('pagarfactura').style.display = 'none';
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

                    //comprobar la iteracion del midata porque se pasa un numero y genera error

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
                        //termina el for    
                    }
                });
            })
            //mostrar el modal de las ventas por vencer-------------------------------------------------------------------------------------------------
            const mimodalVercreditosxvencer = document.getElementById('modalCreditos1')
            mimodalVercreditosxvencer.addEventListener('show.bs.modal', event => {
                var nroxvencer = 0;
                var nrovencidos = 0;
                var hoy = new Date();
                var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' +
                    String(hoy.getDate()).padStart(2, '0');

                const button = event.relatedTarget;
                //  const id = button.getAttribute('data-id');
                var urlventa = "{{ url('admin/venta/showcreditos') }}";
                $.get(urlventa, function(data) {
                    const modalTitle = mimodalVercreditosxvencer.querySelector('.modal-title');
                    modalTitle.textContent = `Ventas a credito por vencer `;
                    var simbolomonedafact = "";
                    $('#mitabla1 tbody tr').slice().remove();
                    var miurl = "{{ url('/admin/venta/') }}";
                    for (var i = 0; i < data.length; i++) {
                        var monedafactura = data[i].moneda;
                        if (monedafactura == "dolares") {
                            simbolomonedafact = "$";
                        } else if (monedafactura == "soles") {
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
                            '</td><td><a  href="' + miurl + '/' + data[i].id +
                            '/edit" class="btn btn-success">Editar</a> ' +
                            '<button type="button" class="btn btn-secondary" data-id="' + data[i].id +
                            '" data-bs-target="#modalVer2" data-bs-toggle="modal">Ver</button>' +
                            '<form action="' + miurl + '/' + data[i].id +
                            '/delete" class="d-inline formulario-eliminar"> <button type="submit" class="btn btn-danger formulario-eliminar">Eliminar </button></form>' +
                            '</td></tr>';

                        $("#mitabla1>tbody").append(filaDetalle);
                    }
                    inicializartabla1(inicializartabla);
                    inicializartabla++;
                    mostrarmensajemodal(nroxvencer, nrovencidos);
                });

            });

            //mostrar el modal de los datos de los creditos
            const mimodalcreditos = document.getElementById('modalVer2')
            mimodalcreditos.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                var urlventa = "{{ url('admin/venta/show') }}";
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
                        document.getElementById('pagarfactura1').style.display = 'inline';
                    } else if (midata[0].pagada == "SI") {
                        document.getElementById('pagarfactura1').style.display = 'none';
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
                            console.log(ite);
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

            });
            // fin de los modales 
            window.addEventListener('close-modal', event => {
                $('#deleteModal').modal('hide');
            });



            $('#pagarfactura').click(function() {
                pagarfactura();
            });
            $('#pagarfactura1').click(function() {
                pagarfactura();
            });

            $('#generarfactura').click(function() {
                generarfactura(idventa);
            });
            $('#generarfactura1').click(function() {
                generarfactura(idventa);
            });

            function generarfactura($id) {
                if ($id != -1) {
                    window.open('/admin/venta/generarfacturapdf/' + $id);
                }
            }

            function pagarfactura() {
                var urlventa = "{{ url('/admin/venta/pagarfactura') }}";
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
                var registro = "REGISTRO DE VENTAS: ";
                var tienes = "Tienes ";
                var pago = " Creditos por Cobrar ";
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
@endsection
@section('js')
    <script>
        $('.formulario-eliminar').submit(function(e) {
            e.preventDefault();

            Swal.fire({
                title: '¿Esta seguro de Eliminar?',
                text: "No lo podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí,Eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>
@endsection
