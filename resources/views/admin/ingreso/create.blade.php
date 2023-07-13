@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <p>Corrige los siguientes errores:</p>
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>AÑADIR INGRESO
                        <a href="{{ url('admin/ingreso') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/ingreso') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control " required />
                                @error('fecha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label ">NUMERO DE FACTURA</label>
                                <input type="text" name="factura" id="factura" class="form-control  " />
                                @error('factura')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FORMA DE PAGO</label>
                                <select name="formapago" id="formapago" class="form-select " required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    <option value="credito" data-formapago="credito">Credito</option>
                                    <option value="contado" data-formapago="contado">Contado</option>
                                </select>
                                @error('formapago')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label id="labelfechav" class="form-label">FECHA DE VENCIMIENTO</label>
                                <input type="date" name="fechav" id="fechav" class="form-control " readonly />
                                @error('fechav')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">MONEDA</label>
                                <select name="moneda" id="moneda" class="form-select " required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    <option value="dolares" data-moneda="dolares">Dolares Americanos</option>
                                    <option value="soles" data-moneda="soles">Soles</option>
                                </select>
                                @error('tipo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label id="labeltasacambio" class="form-label is-required">TASA DE CAMBIO</label>
                                <input type="number" name="tasacambio" id="tasacambio" step="0.0001"  
                                    min="1" class="form-control " required />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">EMPRESA</label>
                                <select class="form-select select2  " name="company_id" id="company_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">PROVEEDOR</label>
                                <select class="form-select select2  " name="cliente_id" id="cliente_id" required disabled>
                                    <option value="" disabled selected>Seleccione una opción</option>

                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">PRECIO DE LA VENTA </label>
                                    <span class="input-group-text" id="spancostoventa"></span>
                                    <input type="number" name="costoventa" id="costoventa" min="0.1" step="0.01"
                                        class="form-control  required" required readonly />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FACTURA PAGADA</label>
                                <input type="text" name="pagada" id="pagada" class="form-control  " required
                                    readonly />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">OBSERVACION</label>
                                <input type="text" name="observacion" id="observacion" class="form-control " />
                                @error('observacion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-lg-12">
                                    <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                                    <nav class="" style="border-radius: 5px; ">
                                        <div class="nav nav-pills nav-justified" id="nav-tab" role="tablist">

                                            <button class="nav-link active" id="nav-detalles-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-detalles" type="button" role="tab"
                                                aria-controls="nav-detalles" aria-selected="false">DETALLES</button>
                                            <button class="nav-link " id="nav-condiciones-tab" data-bs-toggle="tab"
                                                data-bs-target="#nav-condiciones" type="button" role="tab"
                                                aria-controls="nav-condiciones" aria-selected="false">¿AGREGAR DATOS DE
                                                PAGO?</button>
                                        </div>
                                    </nav>
                                    <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-detalles" role="tabpanel"
                                            aria-labelledby="nav-detalles-tab" tabindex="0">
                                            <br>
                                            <div class="row">
                                                <h4>Agregar Detalle de la Compra</h4>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label" name="labelproducto"
                                                        id="labelproducto">PRODUCTO</label>
                                                    <select class="form-select select2 " name="product" id="product"
                                                        disabled>
                                                        <option value="" disabled selected>Seleccione una opción
                                                        </option>
                                                        @foreach ($products as $product)
                                                            <option id="miproducto{{ $product->id }}"
                                                                value="{{ $product->id }}"
                                                                data-name="{{ $product->nombre }}"
                                                                data-moneda="{{ $product->moneda }}"
                                                                data-tipo="{{ $product->tipo }}"
                                                                data-unidad="{{ $product->unidad }}"
                                                                data-price="{{ $product->preciocompra }}">
                                                                {{ $product->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label" id="labelunidad">UNIDAD</label>
                                                    <input type="text" name="unidadproducto" id="unidadproducto"
                                                        class="form-control " />
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label" id="labelcantidad">CANTIDAD</label>
                                                    <input type="number" name="cantidad" id="cantidad" min="1"
                                                        step="1" class="form-control " />
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelpreciounitarioref">PRECIO UNITARIO
                                                            (REFERENCIAL):</label>
                                                        <span class="input-group-text" id="spanpreciounitarioref"></span>
                                                        <input type="number" name="preciounitario" min="0"
                                                            step="0.0001" id="preciounitario" readonly
                                                            class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelpreciounitario">PRECIO
                                                            UNITARIO:</label>
                                                        <span class="input-group-text" id="spanpreciounitario"></span>
                                                        <input type="number" name="preciounitariomo" min="0"
                                                            step="0.0001" id="preciounitariomo" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelservicio"
                                                            name="labelservicio">SERVICIO ADICIONAL:</label>
                                                        <span class="input-group-text" id="spanservicio"></span>
                                                        <input type="number" name="servicio" min="0"
                                                            step="0.0001" id="servicio"class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelpreciototal">PRECIO
                                                            TOTAL POR
                                                            PRODUCTO:</label>
                                                        <span class="input-group-text" id="spanpreciototal"></span>
                                                        <input type="number" name="preciofinal" min="0"
                                                            step="0.0001" id="preciofinal" readonly
                                                            class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-8 mb-3">
                                                    <label class="form-label "
                                                        id="labelobservacionproducto">OBSERVACION(Nro Serie):</label>
                                                    <input type="text" name="observacionproducto"
                                                        id="observacionproducto" class="form-control  gui-input" />
                                                </div>
                                                <button type="button" class="btn btn-info" id="addDetalleBatch"><i
                                                        class="fa fa-plus"></i>
                                                    Agregar Producto a la Venta</button>
                                                <div class="table-responsive">
                                                    <table class="table table-row-bordered gy-5 gs-5" id="detallesVenta">
                                                        <thead class="fw-bold text-primary">
                                                            <tr>
                                                                <th>PRODUCTO</th>
                                                                <th>UNIDAD</th>
                                                                <th>OBSERVACION</th>
                                                                <th>CANTIDAD</th>
                                                                <th>PRECIO UNITARIO(REFERENCIAL)</th>
                                                                <th>PRECIO UNITARIO</th>
                                                                <th>SERVICIO ADICIONAL</th>
                                                                <th>PRECIO FINAL DEL PRODUCTO</th>
                                                                <th>ELIMINAR</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr></tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade  " id="nav-condiciones" role="tabpanel"
                                            aria-labelledby="nav-condiciones-tab" tabindex="0">
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="input-group form-label">PRECIO DE LA VENTA CON
                                                            IGV</label>
                                                        <span class="input-group-text" id="spanprecioventaconigv"></span>
                                                        <input type="number" name="precioventaconigv" readonly
                                                            id="precioventaconigv" class="input-group form-control "
                                                            step="0.01" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">NRO OC</label>
                                                    <input type="text" name="nrooc" id="nrooc"
                                                        class="form-control" />
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label input-group">GUIA DE REMISION</label>
                                                    <input type="text" name="guiaremision" id="guiaremision"
                                                        class="form-control" />
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label input-group">FECHA DE PAGO</label>
                                                    <input type="date" name="fechapago" id="fechapago"
                                                        class="form-control" />
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelacuenta">A CUENTA
                                                            1</label>
                                                        <span class="input-group-text" id="spanacuenta1"></span>
                                                        <input type="number" name="acuenta1" min="0"
                                                            step="0.0001" id="acuenta1" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelacuenta2">A CUENTA
                                                            2</label>
                                                        <span class="input-group-text" id="spanacuenta2"></span>
                                                        <input type="number" name="acuenta2" min="0"
                                                            step="0.0001" id="acuenta2" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelacuenta3">A CUENTA
                                                            3</label>
                                                        <span class="input-group-text" id="spanacuenta3"></span>
                                                        <input type="number" name="acuenta3" min="0"
                                                            step="0.0001" id="acuenta3" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelsaldo">SALDO</label>
                                                        <span class="input-group-text" id="spansaldo"></span>
                                                        <input type="number" name="saldo" min="0"
                                                            step="0.0001" id="saldo" class="form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelmontopagado">MONTO
                                                            PAGADO</label>
                                                        <span class="input-group-text" id="spanmontopagado"></span>
                                                        <input type="number" name="montopagado" min="0"
                                                            step="0.0001" id="montopagado" class="form-control " />
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <button type="submit" id="btnguardar" name="btnguardar"
                                    class="btn btn-primary text-white float-end">Guardar</button>
                            </div>
                        </div>
                    </form>
                    <div class="toast-container position-fixed bottom-0 start-0 p-2" style="z-index: 1000">
                        <div class="toast " role="alert" aria-live="assertive" aria-atomic="true"
                            data-bs-autohide="false" style="width: 100%; box-shadow: 0 2px 5px 2px rgb(0, 89, 255); ">
                            <div class="  card-header">
                                <i class="mdi mdi-information menu-icon"></i>
                                <strong class="mr-auto"> &nbsp; Productos que incluye el kit:</strong>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
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
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript">
        var indice = 0;
        var ventatotal = 0;
        var preciounit = 0;
        var nameproduct = 0;
        var preciototalI = 0;
        var estadoguardar = 0;
        var monedafactura = "";
        var monedaproducto = "";
        var monedaantigua = 0;
        var simbolomonedaproducto = "";
        var simbolomonedafactura = "";
        var indicex = 0;
        var tipoproducto = "";
        var idproducto = 0;
        var misproductos = @json($products);

        $(document).ready(function() {
            traertasacambio();
            $('.toast').toast();
            $('.select2').select2({});
            //para diferente comprador y vendedor
            $("#company_id").change(function() {
                var company = $(this).val();
                var miurl = "{{ url('admin/venta/comboempresacliente') }}";
                $.get(miurl + '/' + company, function(data) {
                    var producto_select =
                        '<option value="" disabled selected>Seleccione una opcion</option>'
                    for (var i = 0; i < data.length; i++) {
                        producto_select += '<option id="productoxempresa' + data[i].id +
                            '" value="' + data[i].id + '" data-name="' + data[i].nombre +
                            '">' + data[i].nombre + '</option>';
                    }
                    $("#cliente_id").html(producto_select);
                    $('#cliente_id').removeAttr('disabled');
                });
            });

            $("#btnguardar").prop("disabled", true);
            //Para poner automaticamente la fecha actual
            var hoy = new Date();
            var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' +
                String(hoy.getDate()).padStart(2, '0');
            document.getElementById("fecha").value = fechaActual;
            var validez = hoy;
            validez.setDate(validez.getDate() + 15);
            var fechavalidez = validez.getFullYear() + '-' + (String(validez.getMonth() + 1).padStart(2, '0')) +
                '-' + String(validez.getDate()).padStart(2, '0');
            document.getElementById("fechav").value = fechavalidez;

            document.getElementById("cantidad").onchange = function() {
                preciofinal();
            };
            document.getElementById("servicio").onchange = function() {
                preciofinal();
            };
            document.getElementById("preciounitariomo").onchange = function() {
                preciofinal();
            };

            document.getElementById("acuenta1").onchange = function() {
                pagocredito();
            };
            document.getElementById("acuenta2").onchange = function() {
                pagocredito();
            };
            document.getElementById("acuenta3").onchange = function() {
                pagocredito();
            };
            document.getElementById("saldo").onchange = function() {
                montopagado();
            };
            document.getElementById("montopagado").onchange = function() {
                saldo();
            };

            function pagocredito() {
                var acuenta1 = $('[name="acuenta1"]').val();
                var acuenta2 = $('[name="acuenta2"]').val();
                var acuenta3 = $('[name="acuenta3"]').val();
                var precioventaconigv = $('[name="precioventaconigv"]').val();
                var montopagado = 0;
                var saldo = 0;
                if (parseFloat(acuenta1)) {
                    montopagado += parseFloat(acuenta1);
                }
                if (parseFloat(acuenta2)) {
                    montopagado += parseFloat(acuenta2);
                }
                if (parseFloat(acuenta3)) {
                    montopagado += parseFloat(acuenta3);
                }
                if (parseFloat(precioventaconigv)) {
                    saldo = parseFloat(precioventaconigv) - parseFloat(montopagado);
                }

                document.getElementById('saldo').value = parseFloat(saldo.toFixed(4));
                document.getElementById('montopagado').value = parseFloat(montopagado.toFixed(4));
            }

            function preciofinal() {
                var cantidad = $('[name="cantidad"]').val();
                var preciounit = $('[name="preciounitariomo"]').val();
                var servicio = $('[name="servicio"]').val();
                if (cantidad >= 1 && preciounit >= 0 && servicio >= 0) {
                    preciototalI = (parseFloat(parseFloat(cantidad) * parseFloat(preciounit)) + parseFloat(
                        parseFloat(cantidad) * parseFloat(servicio)));
                    document.getElementById('preciofinal').value = parseFloat(preciototalI.toFixed(4));
                }
            }

            var tabla = document.getElementById(detallesVenta);
            $('#addDetalleBatch').click(function() {

                //datos del detalleSensor
                var product = $('[name="product"]').val();
                var cantidad = $('[name="cantidad"]').val();
                var preciounitario = $('[name="preciounitario"]').val();
                var servicio = $('[name="servicio"]').val();
                var preciofinal = $('[name="preciofinal"]').val();
                var preciounitariomo = $('[name="preciounitariomo"]').val();
                var observacionproducto = $('[name="observacionproducto"]').val();
                var unidadproducto = $('[name="unidadproducto"]').val();
                //alertas para los detallesBatch

                if (!product) {
                    alert("Seleccione un Producto");
                    return;
                }
                if (!cantidad) {
                    alert("Ingrese una cantidad");
                    return;
                }
                if (cantidad < 1) {
                    alert("La cantidad mínima permitida es: 1");
                    document.getElementById('cantidad').value = 1;
                    return;
                }
                if (!preciounitariomo) {
                    alert("Ingrese una cantidad");
                    return;
                }
                if (!servicio) {
                    alert("Ingrese un servicio");
                    return;
                }
                var tasacambio = document.getElementById('tasacambio').value;;
                var milista = '<br>';
                var puntos = '';
                var preciocompranuevo = 0;
                if (monedaproducto == monedafactura) {
                    preciocompranuevo = preciounitariomo;
                } else if (monedaproducto == "soles" && monedafactura == "dolares") {
                    preciocompranuevo = parseFloat((preciounitariomo * tasacambio).toFixed(4));
                } else if (monedafactura == "soles" && monedaproducto == "dolares") {
                    preciocompranuevo = parseFloat((preciounitariomo / tasacambio).toFixed(4));
                }
                var LVenta = [];
                var tam = LVenta.length;
                LVenta.push(product, nameproduct, cantidad, preciounitario, servicio, preciofinal,
                    preciounitariomo, observacionproducto, unidadproducto, preciocompranuevo);

                if (tipoproducto == "kit") {
                    puntos = ': ';
                    var urlventa = "{{ url('admin/venta/productosxkit') }}";
                    $.get(urlventa + '/' + idproducto, function(data) {
                        for (var i = 0; i < data.length; i++) {
                            var coma = '<br>';
                            if (i + 1 == data.length) {
                                coma = '';
                            }
                            milista = milista + '-' + data[i].cantidad + ' ' + data[i].producto +
                                coma;

                        }
                        agregarFilasTabla(LVenta, puntos, milista);
                    });


                } else {
                    agregarFilasTabla(LVenta, puntos, milista);
                }

            });

            $("#product").change(function() {

                $("#product option:selected").each(function() {
                    var miproduct = $(this).val();
                    if (miproduct) {

                        $moneda = $(this).data("moneda");
                        monedaproducto = $moneda;
                        $named = $(this).data("name");
                        $tipo = $(this).data("tipo");
                        $unidad = $(this).data("unidad");
                        idproducto = miproduct;
                        tipoproducto = $tipo;
                        $price = $(this).data("price");
                        //mostramos la notificacion
                        if ($tipo == "kit") {
                            var urlventa = "{{ url('admin/venta/productosxkit') }}";
                            $.get(urlventa + '/' + miproduct, function(data) {
                                $('#detalleskit tbody tr').slice().remove();
                                for (var i = 0; i < data.length; i++) {
                                    filaDetalle =
                                        '<tr style="border-top: 1px solid silver;" id="fila' +
                                        i +
                                        '"><td> ' + data[i].cantidad +
                                        '</td><td> ' + data[i].producto +
                                        '</td></tr>';
                                    $("#detalleskit>tbody").append(filaDetalle);
                                }
                            });
                            $('.toast').toast('show');
                        }
                        var mitasacambio1 = $('[name="tasacambio"]').val();
                        if ($tipo == "estandar") {
                            $('.toast').toast('hide');
                            document.getElementById('labelproducto').innerHTML = "PRODUCTO";
                        } else if ($tipo == "kit") {
                            document.getElementById('labelproducto').innerHTML =
                                "PRODUCTO TIPO KIT";
                        }
                        var cant = document.getElementById('cantidad');
                        cant.setAttribute("min", 1);
                        if ($price) {
                            preciounit = parseFloat(($price).toFixed(4));
                            if (monedaproducto == "dolares" && monedafactura == "dolares") {
                                simbolomonedaproducto = "$";
                                preciototalI = parseFloat(($price).toFixed(4));
                                document.getElementById('preciounitario').value = parseFloat((
                                    $price).toFixed(2));
                                document.getElementById('preciounitariomo').value = parseFloat((
                                        $price)
                                    .toFixed(4));
                                document.getElementById('preciofinal').value = parseFloat(($price)
                                    .toFixed(4));
                            } else if (monedaproducto == "soles" && monedafactura == "soles") {
                                simbolomonedaproducto = "S/.";
                                preciototalI = parseFloat(($price).toFixed(4));
                                document.getElementById('preciounitario').value = parseFloat((
                                    $price).toFixed(2));
                                document.getElementById('preciounitariomo').value = parseFloat((
                                    $price).toFixed(4));
                                document.getElementById('preciofinal').value = parseFloat(($price)
                                    .toFixed(4));
                            } else if (monedaproducto == "dolares" && monedafactura == "soles") {
                                simbolomonedaproducto = "$";
                                preciototalI = parseFloat(($price * mitasacambio1).toFixed(4));
                                document.getElementById('preciounitario').value = parseFloat((
                                    $price).toFixed(2));
                                document.getElementById('preciounitariomo').value = parseFloat((
                                    $price * mitasacambio1).toFixed(4));
                                document.getElementById('preciofinal').value = parseFloat(($price *
                                    mitasacambio1).toFixed(4));
                            } else if (monedaproducto == "soles" && monedafactura == "dolares") {
                                simbolomonedaproducto = "S/.";
                                preciototalI = parseFloat(($price / mitasacambio1).toFixed(4));
                                document.getElementById('preciounitario').value = parseFloat((
                                    $price).toFixed(2));
                                document.getElementById('preciounitariomo').value = parseFloat((
                                    $price / mitasacambio1).toFixed(4));
                                document.getElementById('preciofinal').value = parseFloat(($price /
                                    mitasacambio1).toFixed(4));
                            }
                            document.getElementById('unidadproducto').value = $unidad;
                            document.getElementById('labelpreciounitarioref').innerHTML =
                                "PRECIO UNITARIO(REFERENCIAL): " + monedaproducto;
                            document.getElementById('labelpreciounitario').innerHTML =
                                "PRECIO UNITARIO: " + monedafactura;
                            document.getElementById('labelservicio').innerHTML =
                                "SERVICIO ADICIONAL: " + monedafactura;
                            document.getElementById('labelpreciototal').innerHTML =
                                "PRECIO TOTAL POR PRODUCTO: " + monedafactura;
                            document.getElementById('spanpreciounitarioref').innerHTML =
                                simbolomonedaproducto;
                            document.getElementById('spanpreciounitario').innerHTML =
                                simbolomonedafactura;
                            document.getElementById('spanservicio').innerHTML =
                                simbolomonedafactura;
                            document.getElementById('spanpreciototal').innerHTML =
                                simbolomonedafactura;
                            document.getElementById('cantidad').value = 1;
                            document.getElementById('servicio').value = 0;
                            nameproduct = $named;
                        } else {
                            nameproduct = $named;
                            document.getElementById('cantidad').value = 1;
                            document.getElementById('servicio').value = 0;
                            document.getElementById('preciofinal').value = 0;
                            document.getElementById('preciounitario').value = 0;
                            document.getElementById('preciounitariomo').value = 0;
                            document.getElementById('unidadproducto').value = $unidad;
                            var simbolomon = "";
                            if (monedaproducto == "soles") {
                                simbolomon = "S/.";
                            }
                            if (monedaproducto == "dolares") {
                                simbolomon = "$";
                            }
                            document.getElementById('spanpreciounitarioref').innerHTML = simbolomon;
                            document.getElementById('spanpreciounitario').innerHTML =
                                simbolomonedafactura;
                            document.getElementById('spanservicio').innerHTML =
                                simbolomonedafactura;
                            document.getElementById('spanpreciototal').innerHTML =
                                simbolomonedafactura;
                        }
                        //alert(nameprod);
                    }
                });
            });

            //para cambiar la forma de pago  y dehabilitar la fecha de vencimiento
            $("#formapago").change(function() {
                $("#formapago option:selected").each(function() {
                    $mimoneda = $(this).data("formapago");
                    if ($mimoneda == "credito") {
                        $("#fechav").prop("readonly", false);
                        $("#fechav").prop("required", true);
                        var fechav = document.getElementById("labelfechav");
                        fechav.className += " is-required";
                        document.getElementById('pagada').value = "NO";
                    } else if ($mimoneda == "contado") {
                        $("#fechav").prop("readonly", true);
                        $("#fechav").prop("required", false);
                        var fechav = document.getElementById("labelfechav");
                        fechav.className = "form-label ";
                        document.getElementById('pagada').value = "SI";
                    }
                });
            });

            //para cambiar la moneda de pago y deshabilitar la tasa de cambio
            $("#moneda").change(function() {
                $('#product').removeAttr('disabled');
                $("#moneda option:selected").each(function() {
                    $mimoneda = $(this).data("moneda");
                    if ($mimoneda == "dolares") {
                        simbolomonedafactura = "$";
                    } else if ($mimoneda == "soles") {
                        simbolomonedafactura = "S/.";
                    }
                    document.getElementById('spancostoventa').innerHTML = simbolomonedafactura;
                    document.getElementById('spanacuenta1').innerHTML = simbolomonedafactura;
                    document.getElementById('spanacuenta2').innerHTML = simbolomonedafactura;
                    document.getElementById('spanacuenta3').innerHTML = simbolomonedafactura;
                    document.getElementById('spansaldo').innerHTML = simbolomonedafactura;
                    document.getElementById('spanmontopagado').innerHTML = simbolomonedafactura;
                    document.getElementById('spanprecioventaconigv').innerHTML =
                        simbolomonedafactura;

                    if (monedaantigua = 0) {
                        monedafactura = $mimoneda;
                        monedaantigua = 1;
                    } else {
                        monedaantigua = monedafactura;
                        monedafactura = $mimoneda;
                        var indice3 = indice;
                        for (var i = 0; i < indice3; i++) {
                            eliminarTabla(i);
                        }
                        habilitaroptionsproductos();
                    }
                });
                limpiarinputs();
            });
        });

        function agregarFilasTabla(LVenta, puntos, milista) {
            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0] + '"required><b>' + LVenta[1] + '</b>' +
                puntos + milista + '</td><td><input  type="hidden" name="Lunidadprod[]"  >' + LVenta[8] +
                '</td><td><input  type="hidden" name="Lobservacionproducto[]" id="observacionproducto' +
                indice + '" value="' + LVenta[7] + '" required> ' + LVenta[7] +
                '</td><td><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice +
                '" value="' + LVenta[2] + '" required> ' + LVenta[2] +
                '</td><td><input  type="hidden" name="Lpreciounitario[]" id="preciounitario' + indice +
                '" value="' + LVenta[3] + '" required> ' + simbolomonedaproducto + LVenta[3] +
                '</td><td><input  type="hidden" name="Lpreciounitariomo[]" id="preciounitariomo' +
                indice + '" value="' + LVenta[6] + '" required> ' + simbolomonedafactura + LVenta[6] +
                '</td><td><input  type="hidden" name="Lservicio[]" id="servicio' + indice +
                '" value="' + LVenta[4] + '" required> ' + simbolomonedafactura + LVenta[4] +
                '</td><td ><input id="preciof' + indice + '"  type="hidden" name="Lpreciofinal[]" value="' + LVenta[5] +
                '" required> <input  type="hidden" name="Lpreciocompranuevo[]" value="' + LVenta[9] +
                '" required> ' + simbolomonedafactura + LVenta[5] +
                '</td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(' +
                indice + ',' + LVenta[0] + ')" data-id="0">ELIMINAR</button></td></tr>';

            $("#detallesVenta>tbody").append(filaDetalle);
 
            indice++;
            indicex++;
            ventatotal = parseFloat(ventatotal) + parseFloat(preciototalI);

            limpiarinputs();
            document.getElementById('costoventa').value = parseFloat(ventatotal.toFixed(2));
            document.getElementById('precioventaconigv').value = parseFloat((ventatotal * 1.18).toFixed(2));
            document.getElementById('miproducto' + LVenta[0]).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
        }

        function eliminarFila(ind, idproducto) {
            var resta = 0;
            //document.getElementById('preciot' + ind).value();
            resta = $('[id="preciof' + ind + '"]').val();
            //alert(resta);
            ventatotal = ventatotal - resta;

            $('#fila' + ind).remove();
            indice--;
            indicex--;
            // damos el valor
            document.getElementById('costoventa').value = (ventatotal.toFixed(2));
            //alert(resta);
            document.getElementById('miproducto' + idproducto).disabled = false;
            var funcion = "eliminar";
            botonguardar(funcion);

            return false;
        }

        function eliminarTabla(ind) {

            $('#fila' + ind).remove();
            indice--;

            // damos el valor
            document.getElementById('costoventa').value = 0
            //alert(resta);

            var funcion = "eliminar";
            botonguardar(funcion);

            ventatotal = 0;
            preciounit = 0;
            nameproduct = 0;
            preciototalI = 0;

            return false;
        }

        function habilitaroptionsproductos() {
            for (var i = 0; i < misproductos.length; i++) {
                document.getElementById('miproducto' + misproductos[i].id).disabled = false;
            }
        }

        function botonguardar(funcion) {

            if (funcion == "eliminar") {
                estadoguardar--;
            } else if (funcion == "agregar") {
                estadoguardar++;
            }
            if (estadoguardar == 0) {
                $("#btnguardar").prop("disabled", true);
            } else if (estadoguardar > 0) {
                $("#btnguardar").prop("disabled", false);
            }
        }

        function limpiarinputs() {
            $('#product').val(null).trigger('change');
            document.getElementById('labelcantidad').innerHTML = "CANTIDAD";
            document.getElementById('labelpreciounitario').innerHTML = "PRECIO UNITARIO: ";
            document.getElementById('labelpreciounitarioref').innerHTML = "PRECIO UNITARIO(REFERENCIAL): ";
            document.getElementById('labelservicio').innerHTML = "SERVICIO ADICIONAL:";
            document.getElementById('labelpreciototal').innerHTML = "PRECIO TOTAL POR PRODUCTO:";
            document.getElementById('spanpreciounitarioref').innerHTML = "";
            document.getElementById('spanpreciounitario').innerHTML = "";
            document.getElementById('spanservicio').innerHTML = "";
            document.getElementById('spanpreciototal').innerHTML = "";
            document.getElementById('cantidad').value = "";
            document.getElementById('servicio').value = "";
            document.getElementById('preciofinal').value = "";
            document.getElementById('preciounitario').value = "";
            document.getElementById('preciounitariomo').value = "";
            document.getElementById('observacionproducto').value = "";
            document.getElementById('unidadproducto').value = "";
            monedaproducto = "";
            simbolomonedaproducto = "";
            $('.toast').toast('hide');
        }
    </script>
@endpush
