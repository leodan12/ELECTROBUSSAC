@extends('layouts.admin')
@push('css')
    <link href="{{ asset('admin/required.css') }}" rel="stylesheet" type="text/css" />
@endpush

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
            @if (session('message'))
                <div class="alert alert-danger">{{ session('message') }}</div>
            @endif


            <div class="card">
                <form action="{{ url('admin/venta') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <h4>AÑADIR VENTA &nbsp;&nbsp;&nbsp;
                            <label>¿REGISTRAR TAMBIEN INGRESO?</label>
                            {{-- <input class="form-check-input" type="checkbox" name="ingreso" id="ingreso" /> --}}
                            <select name="ingreso" id="ingreso" class=" borde" style="border-radius: 5px">
                                <option value="NO" selected>NO</option>
                                <option value="SI">SI</option>
                            </select>
                            <a href="{{ url('admin/venta') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        {{-- <form action="{{ url('admin/venta') }}" method="POST" enctype="multipart/form-data">
                        @csrf --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control borde" required />
                                @error('fecha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" id="labelfactura" name="labelfactura">NUMERO DE FACTURA</label>
                                <input type="text" name="factura" id="factura" class="form-control borde" />
                                <div class="invalid-feedback" name="validacionfactura" id="validacionfactura"
                                    style="color: red;">
                                    ¡Numero de Factura ya Registrado!
                                </div>
                                @error('factura')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FORMA DE PAGO</label>
                                <select name="formapago" id="formapago" class="form-select borde" required>
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
                                <input type="date" name="fechav" id="fechav" class="form-control borde" readonly />
                                @error('fechav')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">MONEDA</label>
                                <select name="moneda" id="moneda" class="form-select borde" required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    <option value="dolares" data-moneda="dolares">Dolares Americanos</option>
                                    <option value="soles" data-moneda="soles">Soles</option>
                                </select>
                                @error('tipo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label id="labeltasacambio" class="form-label is-required">TASA DE CAMBIO</label>
                                <input type="number" name="tasacambio" id="tasacambio" step="0.01"
                                    class="form-control borde" min="1" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">EMPRESA</label>
                                <select class="form-select select2  borde" name="company_id" id="company_id" required
                                    disabled>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">CLIENTE</label>
                                <select class="form-select select2  borde" name="cliente_id" id="cliente_id" required
                                    disabled>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">PRECIO DE LA VENTA </label>
                                    <span class="input-group-text" id="spancostoventa"></span>
                                    <input type="number" name="costoventa" id="costoventa" min="0.1"
                                        step="0.01" class="form-control borde required" required readonly />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FACTURA PAGADA</label>
                                <input type="text" name="pagada" id="pagada" class="form-control borde " required
                                    readonly />
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">OBSERVACION</label>
                                <input type="text" name="observacion" id="observacion" class="form-control borde" />
                                @error('observacion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <hr>
                            <h4>Agregar Detalle de la Venta</h4>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" id="labelproducto" name="labelproducto">PRODUCTO</label>
                                <select class="form-select select2 borde" name="product" id="product" disabled>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" name="labelcantidad" id="labelcantidad">CANTIDAD</label>
                                <input type="number" name="cantidad" id="cantidad" min="1" step="1"
                                    class="form-control borde" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group" id="labelpreciounitarioref">PRECIO UNITARIO
                                        (REFERENCIAL):</label>
                                    <span class="input-group-text" id="spanpreciounitarioref"></span>
                                    <input type="number" name="preciounitario" min="0" step="0.01"
                                        id="preciounitario" readonly class="form-control borde" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group" id="labelpreciounitario">PRECIO UNITARIO</label>
                                    <span class="input-group-text" id="spanpreciounitario"></span>
                                    <input type="number" name="preciounitariomo" min="0" step="0.01"
                                        id="preciounitariomo" class="form-control borde" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group" id="labelservicio"
                                        name="labelservicio">SERVICIO ADICIONAL:</label>
                                    <span class="input-group-text" id="spanservicio"></span>
                                    <input type="number" name="servicio" min="0" step="0.01"
                                        id="servicio"class="form-control borde" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group" id="labelpreciototal">PRECIO TOTAL POR
                                        PRODUCTO</label>
                                    <span class="input-group-text" id="spanpreciototal"></span>
                                    <input type="number" name="preciofinal" min="0" step="0.01"
                                        id="preciofinal" readonly class="form-control borde" />
                                </div>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label " id="labelobservacionproducto">OBSERVACION(Nro Serie):</label>
                                <input type="text" name="observacionproducto" id="observacionproducto"
                                    class="form-control borde gui-input" />
                                <input type="hidden" name="idcotizacion" value="-1" required>
                            </div>
                            <button type="button" class="btn btn-info" id="addDetalleBatch"><i class="fa fa-plus"></i>
                                Agregar Producto a la Venta</button>
                            <div class="table-responsive">
                                <table class="table table-row-bordered gy-5 gs-5" id="detallesVenta">
                                    <thead class="fw-bold text-primary">
                                        <tr>
                                            <th>PRODUCTO</th>
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
                            <hr>
                            <div class="col-md-12 mb-3">
                                <button type="submit" id="btnguardar" name="btnguardar"
                                    class="btn btn-primary text-white float-end">Guardar</button>
                            </div>
                        </div>
                        {{-- </form> --}}
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
                </form>
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
        var stockmaximo = 0;
        var idempresa = "-1";
        var facturadisponible = "";
        var urlclientexempresa = "{{ url('admin/venta/comboempresacliente') }}";
        var micantidad2 = null;
        var miprecio2 = null;
        var micantidad3 = null;
        var miprecio3 = null;
        var miprecio = 0;
        var mipreciounit = "";
        document.getElementById("validacionfactura").style.display = 'none';
        $(document).ready(function() {
            $('.toast').toast();
            document.getElementById('tasacambio').value = "3.71";
            $('.select2').select2({});
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
        });
        factura.oninput = function() {
            var mifactura = document.getElementById("factura");
            var empresa = document.getElementById("company_id").value;
            verificarfactura(empresa, mifactura.value);

        };

        function verificarfactura(empresa, factura) {
            var xfactura = document.getElementById("factura");
            var validacion = document.getElementById("validacionfactura");
            if (empresa && factura) {
                var urlvent = "{{ url('admin/venta/facturadisponible') }}";
                $.get(urlvent + '/' + empresa + '/' + factura, function(data) {
                    enviar = document.getElementById('btnguardar');
                    facturadisponible = data;
                    if (data == "SI") {
                        xfactura.style.borderColor = "green";
                        enviar.disabled = false;
                        validacion.style.display = 'none';
                    } else {
                        xfactura.style.borderColor = "red";
                        enviar.disabled = true;
                        validacion.style.display = 'inline';
                    }
                });
            }
        }

        $("#ingreso").change(function() {

            $("#ingreso option:selected").each(function() {
                var ingreso = $(this).val();
                if (ingreso == "SI") {
                    urlclientexempresa = "{{ url('admin/venta/comboempresaclientevi') }}";
                    if (idempresa != "-1") {
                        clientesxempresa(idempresa);
                    }
                } else {
                    urlclientexempresa = "{{ url('admin/venta/comboempresacliente') }}";
                    if (idempresa != "-1") {
                        clientesxempresa(idempresa);
                    }
                }
            });
        });

        document.getElementById("cantidad").onchange = function() {
            var xcantidad = document.getElementById("cantidad").value;
            if (micantidad2 != null) {
                if (xcantidad >= micantidad2) {
                    document.getElementById("preciounitariomo").value = miprecio2;
                    document.getElementById('labelpreciounitario').innerHTML = mipreciounit + '(x' + micantidad2 + ')';
                    if (micantidad3 != null) {
                        if (xcantidad >= micantidad3) {
                            document.getElementById("preciounitariomo").value = miprecio3;
                            document.getElementById('labelpreciounitario').innerHTML = mipreciounit + '(x' +
                                micantidad3 + ')';
                        }
                    }
                } else {
                    document.getElementById("preciounitariomo").value = miprecio;
                    document.getElementById('labelpreciounitario').innerHTML = mipreciounit;
                }
            } else {
                document.getElementById("preciounitariomo").value = miprecio;
                document.getElementById('labelpreciounitario').innerHTML = mipreciounit;
            }
            preciofinal();
        };
        document.getElementById("servicio").onchange = function() {
            preciofinal();
        };
        document.getElementById("preciounitariomo").onchange = function() {
            preciofinal();
        };

        $("#company_id").change(function() {
            var company = $(this).val();
            idempresa = company;

            var factura = document.getElementById("factura").value;
            verificarfactura(company, factura);

            $('#product').removeAttr('disabled');
            var urlvent = "{{ url('admin/venta/productosxempresa') }}";
            $.get(urlvent + '/' + company, function(data) {
                var producto_select = '<option value="" disabled selected>Seleccione una opción</option>'
                for (var i = 0; i < data.length; i++) {
                    if (data[i].stockempresa == null) {
                        alert(data[i].stockempresa);
                    }
                    producto_select += '<option  id="productoxempresa' + data[i].id + '" value="' + data[i]
                        .id + '" data-name="' + data[i].nombre + '" data-tipo="' + data[i].tipo +
                        '"data-stock="' + data[i].stockempresa + '" data-moneda="' + data[i].moneda +
                        '"data-cantidad2="' + data[i].cantidad2 + '" data-precio2="' + data[i].precio2 +
                        '"data-cantidad3="' + data[i].cantidad3 + '" data-precio3="' + data[i].precio3 +
                        '" data-price="' + data[i].NoIGV + '">' + data[i].nombre + '</option>';
                }
                $("#product").html(producto_select);
            });

            clientesxempresa(company);
            if (indice > 0) {
                var indice2 = indicex;
                for (var i = 0; i < indice2; i++) {
                    eliminarFila(i);
                }
            }
            limpiarinputs();
        });

        function clientesxempresa(idempresa) {
            $('#cliente_id').removeAttr('disabled');
            $.get(urlclientexempresa + '/' + idempresa, function(data) {
                var producto_select =
                    '<option value="" disabled selected>Seleccione una opción</option>'
                for (var i = 0; i < data.length; i++) {
                    producto_select += '<option value="' + data[i].id + '" data-name="' + data[i]
                        .nombre +
                        '" >' + data[i].nombre + '</option>';
                }
                $("#cliente_id").html(producto_select);
            });
        }

        function preciofinal() {
            var cantidad = $('[name="cantidad"]').val();
            var preciounit = $('[name="preciounitariomo"]').val();
            var servicio = $('[name="servicio"]').val();

            if (cantidad >= 1 && preciounit >= 0 && servicio >= 0) {
                preciototalI = (parseFloat(parseFloat(cantidad) * parseFloat(preciounit)) + parseFloat(parseFloat(
                    cantidad) * parseFloat(servicio)));
                document.getElementById('preciofinal').value = preciototalI.toFixed(2);
            }
        }

        $('#addDetalleBatch').click(function() {

            //datos del detalleSensor
            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var preciounitario = $('[name="preciounitario"]').val();
            var servicio = $('[name="servicio"]').val();
            var preciofinal = $('[name="preciofinal"]').val();
            var preciounitariomo = $('[name="preciounitariomo"]').val();
            var observacionproducto = $('[name="observacionproducto"]').val();

            //alertas para los detallesBatch
            if (!product) {
                alert("Seleccione un Producto");
                return;
            }
            if (!cantidad) {
                alert("Ingrese una cantidad");
                return;
            }
            if (cantidad > stockmaximo) {
                alert("La cantidad máxima permitida es: " + stockmaximo);
                document.getElementById('cantidad').value = stockmaximo;
                return;
            }
            if (cantidad < 1) {
                alert("La cantidad mínima permitida es: 1");
                document.getElementById('cantidad').value = 1;
                return;
            }
            if (!preciounitariomo) {
                alert("Ingrese un precio unitario");
                return;
            }
            if (!servicio) {
                alert("Ingrese un servicio");
                return;
            }
            if (!observacionproducto) {
                alert("ingrese una observacion(Nro Serie):");
                $("#observacionproducto").focus();
                return;
            }
            var milista = '<br>';
            var puntos = '';

            var LVenta = [];
            var tam = LVenta.length;
            LVenta.push(product, nameproduct, cantidad, preciounitario, servicio, preciofinal, preciounitariomo,
                observacionproducto);

            if (tipoproducto == "kit") {
                puntos = ': ';
                var urlventa = "{{ url('admin/venta/productosxkit') }}";
                $.get(urlventa + '/' + idproducto, function(data) {
                    for (var i = 0; i < data.length; i++) {
                        var coma = '<br>';
                        if (i + 1 == data.length) {
                            coma = '';
                        }
                        milista = milista + '-' + data[i].cantidad + ' ' + data[i].producto + coma;
                        //agregar la resta para cadaa stock individual 
                        var product1 = document.getElementById('productoxempresa' + data[i].id);
                        var stock = product1.dataset.stock;
                        product1.setAttribute('data-stock', (stock - data[i].cantidad));
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
                    $price = $(this).data("price");
                    $named = $(this).data("name");
                    $moneda = $(this).data("moneda");
                    $stock = $(this).data("stock");
                    $tipo = $(this).data("tipo");

                    $cantidad2 = $(this).data("cantidad2");
                    $precio2 = $(this).data("precio2");
                    $cantidad3 = $(this).data("cantidad3");
                    $precio3 = $(this).data("precio3");

                    monedaproducto = $moneda;
                    idproducto = miproduct;
                    tipoproducto = $tipo;
                    stockmaximo = $stock;

                    micantidad2 = $cantidad2;
                    micantidad3 = $cantidad3;


                    //mostramos la notificacion
                    if ($tipo == "kit") {
                        var urlventa = "{{ url('admin/venta/productosxkit') }}";
                        $.get(urlventa + '/' + miproduct, function(data) {
                            $('#detalleskit tbody tr').slice().remove();
                            for (var i = 0; i < data.length; i++) {
                                filaDetalle = '<tr style="border-top: 1px solid silver;" id="fila' +
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
                    //var mimoneda1 = $('[name="moneda"]').val();

                    if ($tipo == "estandar") {
                        $('.toast').toast('hide');
                        document.getElementById('labelproducto').innerHTML = "PRODUCTO";
                    } else if ($tipo == "kit") {
                        document.getElementById('labelproducto').innerHTML = "PRODUCTO TIPO KIT";
                    }
                    document.getElementById('labelcantidad').innerHTML = "CANTIDAD(max:" + $stock + ")";

                    var cant = document.getElementById('cantidad');
                    cant.setAttribute("max", $stock);
                    cant.setAttribute("min", 1);
                    if ($price != null) {
                        preciounit = ($price).toFixed(2);
                        if (monedaproducto == "dolares" && monedafactura == "dolares") {
                            simbolomonedaproducto = "$";
                            preciototalI = ($price).toFixed(2);
                            miprecio = $price;
                            miprecio2 = $precio2;
                            miprecio3 = $precio3;
                            document.getElementById('preciounitario').value = ($price).toFixed(2);
                            document.getElementById('preciounitariomo').value = ($price).toFixed(2);
                            document.getElementById('preciofinal').value = ($price).toFixed(2);
                        } else if (monedaproducto == "soles" && monedafactura == "soles") {
                            preciototalI = ($price).toFixed(2);
                            simbolomonedaproducto = "S/.";
                            miprecio = $price;
                            miprecio2 = $precio2;
                            miprecio3 = $precio3;
                            document.getElementById('preciounitario').value = ($price).toFixed(2);
                            document.getElementById('preciounitariomo').value = ($price).toFixed(2);
                            document.getElementById('preciofinal').value = ($price).toFixed(2);
                        } else if (monedaproducto == "dolares" && monedafactura == "soles") {
                            preciototalI = ($price * mitasacambio1).toFixed(2);
                            simbolomonedaproducto = "$";
                            miprecio = ($price * mitasacambio1).toFixed(2);
                            miprecio2 = ($precio2 * mitasacambio1).toFixed(2);
                            miprecio3 = ($precio3 * mitasacambio1).toFixed(2);
                            document.getElementById('preciounitario').value = ($price).toFixed(2);
                            document.getElementById('preciounitariomo').value = ($price * mitasacambio1)
                                .toFixed(2);
                            document.getElementById('preciofinal').value = ($price * mitasacambio1).toFixed(
                                2);
                        } else if (monedaproducto == "soles" && monedafactura == "dolares") {
                            preciototalI = ($price / mitasacambio1).toFixed(2);
                            simbolomonedaproducto = "S/.";
                            miprecio = ($price / mitasacambio1).toFixed(2);
                            miprecio2 = ($precio2 / mitasacambio1).toFixed(2);
                            miprecio3 = ($precio3 / mitasacambio1).toFixed(2);
                            document.getElementById('preciounitario').value = ($price).toFixed(2);
                            document.getElementById('preciounitariomo').value = ($price / mitasacambio1)
                                .toFixed(2);
                            document.getElementById('preciofinal').value = ($price / mitasacambio1).toFixed(
                                2);

                        }
                        document.getElementById('labelpreciounitarioref').innerHTML =
                            "PRECIO UNITARIO(REFERENCIAL): " + monedaproducto;
                        document.getElementById('labelpreciounitario').innerHTML = "PRECIO UNITARIO: " +
                            monedafactura;
                        document.getElementById('labelservicio').innerHTML = "SERVICIO ADICIONAL: " +
                            monedafactura;
                        document.getElementById('labelpreciototal').innerHTML =
                            "PRECIO TOTAL POR PRODUCTO: " + monedafactura;
                        document.getElementById('spanpreciounitarioref').innerHTML = simbolomonedaproducto;
                        document.getElementById('spanpreciounitario').innerHTML = simbolomonedafactura;
                        document.getElementById('spanservicio').innerHTML = simbolomonedafactura;
                        document.getElementById('spanpreciototal').innerHTML = simbolomonedafactura;

                        document.getElementById('cantidad').value = 1;
                        document.getElementById('servicio').value = 0;
                        nameproduct = $named;
                    } else if ($price == null) {
                        document.getElementById('cantidad').value = "";
                        document.getElementById('servicio').value = "";
                        document.getElementById('preciofinal').value = "";
                        document.getElementById('preciounitario').value = "";
                        document.getElementById('preciounitariomo').value = "";
                    }
                    mipreciounit = document.getElementById('labelpreciounitario').innerHTML;
                }
            });
        });

        function agregarFilasTabla(LVenta, puntos, milista) {
            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0] + '"required><b>' + LVenta[1] + '</b>' +
                puntos + milista +
                '</td><td><input  type="hidden" name="Lobservacionproducto[]" id="observacionproducto' + indice +
                '" value="' + LVenta[7] + '"required>' + LVenta[7] +
                '</td><td><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice + '" value="' + LVenta[2] +
                '"required>' + LVenta[2] +
                '</td><td><input  type="hidden" name="Lpreciounitario[]" id="preciounitario' + indice + '" value="' +
                LVenta[3] + '"required>' + simbolomonedaproducto + LVenta[3] +
                '</td><td><input  type="hidden" name="Lpreciounitariomo[]" id="preciounitariomo' + indice + '" value="' +
                LVenta[6] + '"required>' + simbolomonedafactura + LVenta[6] +
                '</td><td><input  type="hidden" name="Lservicio[]" id="servicio' + indice + '" value="' + LVenta[4] +
                '"required>' + simbolomonedafactura + LVenta[4] +
                '</td><td ><input id="preciof' + indice + '"  type="hidden" name="Lpreciofinal[]" value="' + LVenta[5] +
                '"required>' + simbolomonedafactura + LVenta[5] +
                '</td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(' + indice+','+ LVenta[0]+
                ')" data-id="0">ELIMINAR</button></td></tr>';
            $("#detallesVenta>tbody").append(filaDetalle);
            $('.toast').toast('hide');
            indice++;
            indicex++;
            ventatotal = parseFloat(ventatotal) + parseFloat(preciototalI);
            limpiarinputs();
            document.getElementById('costoventa').value = (ventatotal * 1).toFixed(2);
            document.getElementById('productoxempresa' + LVenta[0]).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
        }

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
                    fechav.className = "form-label";
                    document.getElementById('pagada').value = "SI";
                }
            });
        });

        //para cambiar la moneda de pago y deshabilitar la tasa de cambio
        $("#moneda").change(function() {
            $('#company_id').removeAttr('disabled');
            $("#moneda option:selected").each(function() {
                $mimoneda = $(this).data("moneda");
                if ($mimoneda == "dolares") {
                    simbolomonedafactura = "$";
                } else if ($mimoneda == "soles") {
                    simbolomonedafactura = "S/.";
                }
                document.getElementById('spancostoventa').innerHTML = simbolomonedafactura;

                if (monedaantigua = 0) {
                    monedafactura = $mimoneda;
                    monedaantigua = 1;
                } else {
                    monedaantigua = monedafactura;
                    monedafactura = $mimoneda;
                    var indice3 = indicex;
                    for (var i = 0; i < indice3; i++) {
                        eliminarTabla(i);
                    }
                }
            });
            limpiarinputs();
        });

        function eliminarFila(ind,idproducto) {
            var resta = 0;
            resta = $('[id="preciof' + ind + '"]').val();
            ventatotal = (ventatotal - resta).toFixed(2);

            $('#fila' + ind).remove();
            indice--;
            // damos el valor
            document.getElementById('costoventa').value = (ventatotal * 1).toFixed(2);

            var funcion = "eliminar";
            botonguardar(funcion);
            document.getElementById('productoxempresa' + idproducto).disabled = false;

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
            if (facturadisponible == "NO") {
                $("#btnguardar").prop("disabled", true);
            } else {
                $("#btnguardar").prop("disabled", false);
            }
        }

        function limpiarinputs() {
            $('#product').val(null).trigger('change');
            document.getElementById('labelcantidad').innerHTML = "CANTIDAD";
            document.getElementById('labelpreciounitario').innerHTML = "PRECIO UNITARIO: ";
            document.getElementById('labelpreciounitarioref').innerHTML = "PRECIO UNITARIO (REFERENCIAL): ";
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
            monedaproducto = "";
            simbolomonedaproducto = "";
            $('.toast').toast('hide');
        }
    </script>
@endpush
