@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @php  $detalles = count($detallesventa) @endphp
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
                    <h4>EDITAR LA VENTA
                        <a href="{{ url('admin/venta') }}" id="btnvolver" name="btnvolver"
                            class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/venta/' . $venta->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control " required
                                    value="{{ $venta->fecha }}" />
                                @error('fecha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label  ">NUMERO DE FACTURA</label>
                                <input type="text" name="factura" id="factura" class="form-control  "
                                    value="{{ $venta->factura }}" />
                                @error('factura')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FORMA DE PAGO</label>
                                <select name="formapago" id="formapago" class="form-select " required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @if ($venta->formapago == 'credito')
                                        <option value="credito" data-formapago="credito" selected>Credito</option>
                                    @elseif($venta->formapago == 'contado')
                                        <option value="contado" data-formapago="contado" selected>Contado</option>
                                    @endif
                                </select>
                                @error('formapago')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>



                            <div class="col-md-6 mb-3">
                                @if ($venta->formapago == 'contado')
                                    <label id="labelfechav" class="form-label">FECHA DE VENCIMIENTO</label>
                                    <input type="date" name="fechav" id="fechav" class="form-control " readonly
                                        value="{{ $venta->fechav }}" />
                                    @error('fechav')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                @endif
                                @if ($venta->formapago == 'credito')
                                    <label id="labelfechav" class="form-label is-required">FECHA DE VENCIMIENTO</label>
                                    <input type="date" name="fechav" id="fechav" class="form-control " required
                                        value="{{ $venta->fechav }}" />
                                    @error('fechav')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">MONEDA</label>
                                <select name="moneda" id="moneda" class="form-select " required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @if ($venta->moneda == 'soles')
                                        <option value="soles" data-moneda="soles" selected>Soles</option>
                                    @elseif($venta->moneda == 'dolares')
                                        <option value="dolares" data-moneda="dolares" selected>Dolares Americanos</option>
                                    @endif
                                </select>
                                @error('tipo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label id="labeltasacambio" class="form-label is-required">TASA DE CAMBIO</label>
                                <input type="number" name="tasacambio" id="tasacambio" step="0.001" class="form-control "
                                    value="{{ $venta->tasacambio }}" readonly />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">EMPRESA</label>
                                <select class="form-select select2 " name="company_id" required>
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $company->id == $venta->company_id ? 'selected' : '' }}>
                                            {{ $company->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">CLIENTE</label>
                                <select class="form-select select2 " name="cliente_id" id="cliente_id" required>
                                    <option value="" select disabled>Seleccione una opción</option>

                                </select>

                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">PRECIO DE LA VENTA </label>
                                    @if ($venta->moneda == 'dolares')
                                        <span class="input-group-text" id="spancostoventa">$</span>
                                    @elseif($venta->moneda == 'soles')
                                        <span class="input-group-text" id="spancostoventa">S/.</span>
                                    @endif
                                    <input type="number" name="costoventa" id="costoventa" min="0.1" step="0.01"
                                        class="form-control  required" required readonly
                                        value="{{ $venta->costoventa }}" />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FACTURA PAGADA</label>
                                <select name="pagada" id="pagada" class="form-select " required>
                                    <option value="" disabled>Seleccion una opción</option>
                                    @if ($venta->pagada == 'NO')
                                        <option value="NO" selected>NO</option>
                                        <option value="SI">SI</option>
                                    @elseif($venta->pagada == 'SI')
                                        <option value="SI" selected>SI</option>
                                        {{-- <option value="NO" >NO</option> --}}
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-12 mb-5">
                                <label class="form-label">OBSERVACION</label>
                                <input type="text" name="observacion" id="observacion" class="form-control "
                                    value="{{ $venta->observacion }}" />
                                @error('observacion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <hr>
                            <h4>Agregar Detalle de la Venta</h4>
                            <div class="col-md-6 mb-3">
                                <label class="form-label " id="labelproducto">PRODUCTO</label>
                                <select class="form-select select2  " name="product" id="product">
                                    <option selected disabled value="">Seleccione una opción</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" name="labelcantidad" id="labelcantidad">CANTIDAD</label>
                                <input type="number" name="cantidad" id="cantidad" min="1"
                                    step="1"class="form-control " />
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group" id="labelpreciounitarioref">PRECIO UNITARIO
                                        (REFERENCIAL):</label>
                                    <span class="input-group-text" id="spanpreciounitarioref"></span>
                                    <input type="number" name="preciounitario" min="0" step="0.01"
                                        id="preciounitario" readonly class="form-control " />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group" id="labelpreciounitario">PRECIO UNITARIO</label>
                                    <span class="input-group-text" id="spanpreciounitario"></span>
                                    <input type="number" name="preciounitariomo" min="0" step="0.01"
                                        id="preciounitariomo" class="form-control " />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group" id="labelservicio"
                                        name="labelservicio">SERVICIO ADICIONAL:</label>
                                    <span class="input-group-text" id="spanservicio"></span>
                                    <input type="number" name="servicio" min="0" step="0.01"
                                        id="servicio"class="form-control " />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group" id="labelpreciototal">PRECIO TOTAL POR
                                        PRODUCTO</label>
                                    <span class="input-group-text" id="spanpreciototal"></span>
                                    <input type="number" name="preciofinal" min="0" step="0.01"
                                        id="preciofinal" readonly class="form-control " />
                                </div>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label " id="labelobservacionproducto">OBSERVACION:</label>
                                <input type="text" name="observacionproducto" id="observacionproducto"
                                    class="form-control  gui-input" />
                            </div>
                            @php $ind=0 ; @endphp
                            @php $indice=count($detallesventa) ; @endphp
                            <button type="button" class="btn btn-info" id="addDetalleBatch"
                                onclick="agregarFila('{{ $indice }}')"><i class="fa fa-plus"></i> Agregar Producto
                                a la Venta</button>

                            <div class="table-responsive">
                                <table class="table table-row-bordered gy-5 gs-5" id="detallesVenta">
                                    <thead class="fw-bold text-primary" name="mitabla" id="mitabla">
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
                                        @php $datobd="db" ;  @endphp
                                        @foreach ($detallesventa as $detalle)
                                            @php $ind++;    @endphp
                                            <tr id="fila{{ $ind }}">
                                                <td> <b> {{ $detalle->producto }} </b>
                                                    @if ($detalle->tipo == 'kit')
                                                        : <br>
                                                        @foreach ($detalleskit as $kit)
                                                            @if ($detalle->idproducto == $kit->product_id)
                                                                -{{ $kit->cantidad }} {{ $kit->producto }} <br>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td> {{ $detalle->observacionproducto }}</td>
                                                <td> {{ $detalle->cantidad }}</td>
                                                <td>
                                                    @if ($detalle->moneda == 'soles')
                                                        S/.
                                                    @elseif($detalle->moneda == 'dolares')
                                                        $
                                                    @endif {{ $detalle->preciounitario }}
                                                </td>
                                                <td>
                                                    @if ($venta->moneda == 'soles')
                                                        S/.
                                                    @elseif($venta->moneda == 'dolares')
                                                        $
                                                    @endif {{ $detalle->preciounitariomo }}
                                                </td>
                                                <td>
                                                    @if ($venta->moneda == 'soles')
                                                        S/.
                                                    @elseif($venta->moneda == 'dolares')
                                                        $
                                                    @endif {{ $detalle->servicio }}
                                                </td>
                                                <td><input type="hidden" id="preciof{{ $ind }}"
                                                        value="{{ $detalle->preciofinal }}" />
                                                    @if ($venta->moneda == 'soles')
                                                        S/.
                                                    @elseif($venta->moneda == 'dolares')
                                                        $
                                                    @endif {{ $detalle->preciofinal }}
                                                </td>
                                                <td>

                                                    <button type="button" class="btn btn-danger"
                                                        onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{ $detalle->iddetalleventa }}', '{{ $detalle->idproducto }}'  )"
                                                        data-id="0"><i class="bi bi-trash-fill"></i>ELIMINAR</button>

                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <hr>
                            <div class="col-md-12 mb-3">
                                <button type="submit" id="btnguardar" name="btnguardar"
                                    class="btn btn-primary text-white float-end">Actualizar</button>
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
        var simbolomonedaproducto = "";
        var simbolomonedafactura = "";
        var idcompany = 0;
        var tipoproducto = "";
        var idproducto = 0;
        var stockmaximo = 0;
        var idcliente = 0;
        var micantidad2 = null;
        var miprecio2 = null;
        var micantidad3 = null;
        var miprecio3 = null;
        var miprecio = 0;
        estadoguardar = @json($detalles);
        idcompany = @json($venta->company_id);
        idcliente = @json($venta->cliente_id);
        var idventa = @json($venta->id);
        var mipreciounit = "";
        var funcion1 = "inicio";
        botonguardar(funcion1);
        var costoventa = $('[id="costoventa"]').val();
        ventatotal = costoventa;
        var detallesagregados = [];
        var misdetalles = @json($detallesventa);
        var precioespecial = -1;
        var preciomo = 0;

        $(document).ready(function() {
            $('.toast').toast();
            var url1 = "{{ url('admin/venta/comboempresacliente') }}";
            $.get(url1 + '/' + idcompany, function(data) {
                var producto_select = '<option value="" disabled selected>Seleccione una opción</option>'
                for (var i = 0; i < data.length; i++) {
                    if (idcliente == data[i].id) {
                        producto_select += '<option value="' + data[i].id + '" data-name="' +
                            data[i].nombre + '" selected>' + data[i].nombre + '</option>';
                    } else {
                        producto_select += '<option value="' + data[i].id + '" data-name="' +
                            data[i].nombre + '" >' + data[i].nombre + '</option>';
                    }

                }
                $("#cliente_id").html(producto_select);
            });
            var url2 = "{{ url('admin/venta/productosxempresa') }}";
            $.get(url2 + '/' + idcompany, function(data) {
                var producto_select = '<option value="" disabled selected>Seleccione una opción</option>';
                for (var i = 0; i < data.length; i++) {
                    if (data[i].stockempresa == null) {
                        alert(data[i].stockempresa);
                    }
                    var desabilitado = "";
                    var contx = 0;
                    for (var x = 0; x < misdetalles.length; x++) {
                        if (misdetalles[x].idproducto == data[i].id) {
                            contx++;
                        }
                    }
                    if (contx > 0) {
                        desabilitado = "disabled";
                    }
                    producto_select += '<option ' + desabilitado + ' id="productoxempresa' + data[i].id +
                        '" value="' + data[i].id +
                        '" data-name="' + data[i].nombre + '" data-tipo="' + data[i].tipo +
                        '"data-stock="' + data[i].stockempresa + '" data-moneda="' + data[i].moneda +
                        '"data-cantidad2="' + data[i].cantidad2 + '" data-precio2="' + data[i].precio2 +
                        '"data-cantidad3="' + data[i].cantidad3 + '" data-precio3="' + data[i].precio3 +
                        '" data-price="' + data[i].NoIGV + '">' + data[i].nombre + '</option>';
                }
                $("#product").html(producto_select);
            });

            $('.select2').select2({});

            document.getElementById("cantidad").onchange = function() {
                var xcantidad = document.getElementById("cantidad").value;
                if (micantidad2 != null) {
                    var mipreciounit2 = "PRECIO UNITARIO: " + monedafactura;
                    if (precioespecial != -1 && precioespecial < miprecio) {
                        mipreciounit2 = "PRECIO UNITARIO: " + monedafactura + "(precio especial)";
                    }
                    document.getElementById("preciounitariomo").value = preciomo;
                    document.getElementById('labelpreciounitario').innerHTML = mipreciounit2;

                    if (xcantidad >= micantidad2) {
                        if (parseFloat(miprecio2, 10) < parseFloat(preciomo, 10)) {
                            document.getElementById("preciounitariomo").value = miprecio2;
                            document.getElementById('labelpreciounitario').innerHTML = mipreciounit + '(x' +
                                micantidad2 +
                                ')';
                        }
                        if (micantidad3 != null) {
                            if (xcantidad >= micantidad3) {
                                if (parseFloat(miprecio3, 10) < parseFloat(preciomo, 10)) {
                                    document.getElementById("preciounitariomo").value = miprecio3;
                                    document.getElementById('labelpreciounitario').innerHTML = mipreciounit +
                                        '(x' +
                                        micantidad3 + ')';
                                }
                            }
                        }
                    } else {
                        if (parseFloat(miprecio, 10) < parseFloat(preciomo, 10)) {
                            document.getElementById("preciounitariomo").value = miprecio;
                            document.getElementById('labelpreciounitario').innerHTML = mipreciounit;
                        } else {

                            var mipreciounit2 = "PRECIO UNITARIO: " + monedafactura;
                            if (precioespecial != -1 && parseFloat(preciomo, 10) < parseFloat(miprecio, 10)) {
                                mipreciounit2 = "PRECIO UNITARIO: " + monedafactura + "(precio especial)";
                            }
                            document.getElementById("preciounitariomo").value = preciomo;
                            document.getElementById('labelpreciounitario').innerHTML = mipreciounit2;
                        }
                    }
                } else {
                    if (parseFloat(miprecio, 10) < parseFloat(preciomo, 10)) {
                        document.getElementById("preciounitariomo").value = miprecio;
                        document.getElementById('labelpreciounitario').innerHTML = mipreciounit;
                        console.log("aqui");
                    } else {
                        var mipreciounit2 = "PRECIO UNITARIO: " + monedafactura;
                        if (precioespecial != -1 && parseFloat(preciomo, 10) < parseFloat(miprecio, 10)) {
                            mipreciounit2 = "PRECIO UNITARIO: " + monedafactura + "(precio especial)";
                        }
                        console.log("aqui2");
                        document.getElementById("preciounitariomo").value = preciomo;
                        document.getElementById('labelpreciounitario').innerHTML = mipreciounit2;
                    }
                }
                preciofinal();
            };
            document.getElementById("servicio").onchange = function() {
                preciofinal();
            };
            document.getElementById("preciounitariomo").onchange = function() {
                preciofinal();
            };

            function preciofinal() {

                var cantidad = $('[name="cantidad"]').val();
                var preciounit = $('[name="preciounitariomo"]').val();
                var servicio = $('[name="servicio"]').val();
                if (cantidad >= 1 && preciounit >= 0 && servicio >= 0) {
                    preciototalI = (parseFloat(parseFloat(cantidad) * parseFloat(preciounit)) + parseFloat(
                        parseFloat(cantidad) * parseFloat(servicio)));
                    document.getElementById('preciofinal').value = preciototalI.toFixed(2);
                }
            }

            $("#product").change(function() {
                $("#product option:selected").each(function() {
                    precioespecial = -1;
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

                        micantidad2 = $cantidad2;
                        micantidad3 = $cantidad3;

                        monedaproducto = $moneda;
                        idproducto = miproduct;
                        tipoproducto = $tipo;
                        stockmaximo = $stock;
                        monedafactura = $('[name="moneda"]').val();
                        if (monedafactura == "dolares") {
                            simbolomonedafactura = "$";
                        } else if (monedafactura == "soles") {
                            simbolomonedafactura = "S/.";
                        }

                        preciomo = $price
                        var urlpe = "{{ url('admin/venta/precioespecial') }}";
                        $.ajax({
                            type: "GET",
                            url: urlpe + '/' + idcliente + '/' + idproducto,
                            async: false,
                            success: function(data) {
                                if (data != 'x') {
                                    precioespecial = data.preciounitariomo;
                                }
                            }
                        });
                        if (precioespecial != -1 && precioespecial < $price) {
                            preciomo = precioespecial;
                        } else {
                            preciomo = $price;
                        }

                        //alert(stocke);
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
                        if ($tipo == "estandar") {
                            $('.toast').toast('hide');
                            document.getElementById('labelproducto').innerHTML = "PRODUCTO";
                        } else if ($tipo == "kit") {
                            document.getElementById('labelproducto').innerHTML =
                                "PRODUCTO TIPO KIT";
                        }
                        var mitasacambio1 = $('[name="tasacambio"]').val();
                        document.getElementById('labelcantidad').innerHTML = "CANTIDAD(max:" +
                            $stock + ")";
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
                                preciomo = preciomo;
                                document.getElementById('preciounitario').value = ($price).toFixed(
                                    2);
                                document.getElementById('preciounitariomo').value = preciomo;
                                document.getElementById('preciofinal').value = preciomo;
                            } else if (monedaproducto == "soles" && monedafactura == "soles") {
                                preciototalI = ($price).toFixed(2);
                                simbolomonedaproducto = "S/.";
                                miprecio = $price;
                                miprecio2 = $precio2;
                                miprecio3 = $precio3;
                                preciomo = preciomo;
                                document.getElementById('preciounitario').value = ($price).toFixed(
                                    2);
                                document.getElementById('preciounitariomo').value = preciomo;
                                document.getElementById('preciofinal').value = preciomo;
                            } else if (monedaproducto == "dolares" && monedafactura == "soles") {
                                preciototalI = ($price * mitasacambio1).toFixed(2);
                                simbolomonedaproducto = "$";
                                miprecio = ($price * mitasacambio1).toFixed(2);
                                miprecio2 = ($precio2 * mitasacambio1).toFixed(2);
                                miprecio3 = ($precio3 * mitasacambio1).toFixed(2);
                                preciomo = (preciomo * mitasacambio1).toFixed(2);
                                document.getElementById('preciounitario').value = ($price).toFixed(
                                    2);
                                document.getElementById('preciounitariomo').value = preciomo;
                                document.getElementById('preciofinal').value = preciomo;
                            } else if (monedaproducto == "soles" && monedafactura == "dolares") {
                                simbolomonedaproducto = "S/.";
                                miprecio = ($price / mitasacambio1).toFixed(2);
                                miprecio2 = ($precio2 / mitasacambio1).toFixed(2);
                                miprecio3 = ($precio3 / mitasacambio1).toFixed(2);
                                preciototalI = ($price / mitasacambio1).toFixed(2);;
                                preciomo = (preciomo / mitasacambio1).toFixed(2);
                                document.getElementById('preciounitario').value = ($price).toFixed(
                                    2);
                                document.getElementById('preciounitariomo').value = preciomo;
                                document.getElementById('preciofinal').value = preciomo;
                            }
                            document.getElementById('labelpreciounitarioref').innerHTML =
                                "PRECIO UNITARIO(REFERENCIAL): " + monedaproducto;
                            var mipreciounitariot = "PRECIO UNITARIO: " + monedafactura;
                            if (precioespecial != -1 && precioespecial < $price) {
                                mipreciounitariot += "(precio especial)";
                            }
                            document.getElementById('labelpreciounitario').innerHTML =
                                mipreciounitariot;
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
                        } else if ($price == null) {
                            document.getElementById('cantidad').value = "";
                            document.getElementById('servicio').value = "";
                            document.getElementById('preciofinal').value = "";
                            document.getElementById('preciounitario').value = "";
                            document.getElementById('preciounitariomo').value = "";
                        }
                        //alert(nameprod);

                        mipreciounit = "PRECIO UNITARIO: " + monedafactura;
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

                    } else if ($mimoneda == "contado") {
                        $("#fechav").prop("readonly", true);
                        $("#fechav").prop("required", false);
                        var fechav = document.getElementById("labelfechav");
                        fechav.className = "form-label ";
                    }
                });
            });

        });

        //funcion para agregar una fila
        var indice = 0;
        var pv = 0;

        function agregarFila(indice1) {

            if (pv == 0) {
                indice = indice1;
                pv++;
                indice++;
            } else {
                indice++;
            }
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
                alert("Seleccione un producto");
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
                alert("Ingrese un precio");
                return;
            }
            if (!servicio) {
                alert("Ingrese un servicio");
                return;
            }

            var milista = '<br>';
            var puntos = '';
            var LVenta = [];
            var tam = LVenta.length;
            //var datodb ="local";
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
                        //modificarStock(data[i].id, data[i].cantidad, "restar");
                    }
                    // modificarStock(LVenta[0], LVenta[2], "restar");
                    agregarFilasTabla(LVenta, puntos, milista);
                });
            } else {
                // modificarStock(LVenta[0], LVenta[2], "restar");
                agregarFilasTabla(LVenta, puntos, milista);
            }
        }

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
                '</td><td> <button type="button" class="btn btn-danger" onclick="eliminarFila(' + indice + ',' + 0 + ',' +
                0 + ',' + LVenta[0] + ')" data-id="0">ELIMINAR</button></td></tr>';

            $("#detallesVenta>tbody").append(filaDetalle);
            $('.toast').toast('hide');
            indice++;
            ventatotal = (parseFloat(ventatotal) + parseFloat(preciototalI)).toFixed(2);
            limpiarinputs();
            document.getElementById('costoventa').value = ventatotal;
            document.getElementById('productoxempresa' + LVenta[0]).disabled = true;
            detallesagregados.push(LVenta[0]);
            var funcion = "agregar";
            botonguardar(funcion);

        }

        function eliminarFila(ind, lugardato, iddetalle, idproducto) {

            if (lugardato == "db") {
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
                        document.getElementById('product').disabled = true;
                        $('.product').select2("destroy");
                        var url2 = "{{ url('admin/deletedetalleventa') }}";
                        $.get(url2 + '/' + iddetalle, function(data) {
                            //alert(data[0]);
                            if (data[0] == 1) {
                                Swal.fire({
                                    text: "Registro Eliminado",
                                    icon: "success"
                                });
                                quitarFila(ind);

                                llenarselectproducto();
                            } else if (data[0] == 0) {
                                alert("no se puede eliminar");
                            } else if (data[0] == 2) {
                                alert("registro no encontrado");
                            }
                        });

                    }

                });
            } else {
                quitarFila(ind);
                var datos = detallesagregados.filter((item) => item != idproducto);
                detallesagregados = datos;
            }
            document.getElementById('productoxempresa' + idproducto).disabled = false;
            return false;
        }

        function quitarFila(indicador) {
            var resta = 0;
            resta = $('[id="preciof' + indicador + '"]').val();
            ventatotal = (ventatotal - resta).toFixed(2);
            $('#fila' + indicador).remove();
            indice--;
            document.getElementById('costoventa').value = ventatotal;
            var funcion = "eliminar";
            botonguardar(funcion);
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
            monedaproducto = "";
            simbolomonedaproducto = "";
            $('.toast').toast('hide');
        }

        function llenarselectproducto() {

            var url3 = "{{ url('admin/venta/productosxempresa') }}";
            $.get(url3 + '/' + idcompany, function(data) {

                var urlregistro = "{{ url('admin/venta/misdetallesventa') }}";
                var misdatosdetalles;
                $.ajax({
                    type: "GET",
                    async: false,
                    url: urlregistro + '/' + idventa,
                    success: function(data1) {
                        misdatosdetalles = data1;
                    }
                });

                var producto_select = '<option  value="" disabled selected>Seleccione una opción</option>';
                for (var i = 0; i < data.length; i++) {
                    var desabilitado = "";
                    var contx = 0;
                    for (var x = 0; x < misdatosdetalles.length; x++) {
                        if (misdatosdetalles[x].idproducto == data[i].id) {
                            contx++;
                        }
                    }
                    for (var z = 0; z < detallesagregados.length; z++) {
                        if (detallesagregados[z] == data[i].id) {
                            contx++;
                        }
                    }
                    if (contx > 0) {
                        desabilitado = "disabled";
                    } else {
                        desabilitado = "";
                    }

                    producto_select += '<option ' + desabilitado + ' id="productoxempresa' + data[i].id +
                        '" value="' + data[i].id +
                        '" data-name="' + data[i].nombre + '" data-tipo="' + data[i].tipo +
                        '"data-stock="' + data[i].stockempresa + '" data-moneda="' + data[i].moneda +
                        '"data-cantidad2="' + data[i].cantidad2 + '" data-precio2="' + data[i].precio2 +
                        '"data-cantidad3="' + data[i].cantidad3 + '" data-precio3="' + data[i].precio3 +
                        '" data-price="' + data[i].NoIGV + '">' + data[i].nombre + '</option>';
                }
                $("#product").html(producto_select);
                document.getElementById('product').disabled = false;
                $('.select2').select2({});
            });
        }
    </script>
@endpush
