@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @php  $detalles = count($detallesingreso) @endphp
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
                    <h4>EDITAR EL INGRESO
                        <a href="{{ url('admin/ingreso') }}" id="btnvolver" name="btnvolver"
                            class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/ingreso/' . $ingreso->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control " required
                                    value="{{ $ingreso->fecha }}" />
                                @error('fecha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label ">NUMERO DE FACTURA</label>
                                <input type="text" name="factura" id="factura" class="form-control  "
                                    value="{{ $ingreso->factura }}" />
                                @error('factura')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FORMA DE PAGO</label>
                                <select name="formapago" id="formapago" class="form-select " required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @if ($ingreso->formapago == 'credito')
                                        <option value="credito" data-formapago="credito" selected>Credito</option>
                                    @elseif($ingreso->formapago == 'contado')
                                        <option value="contado" data-formapago="contado" selected>Contado</option>
                                    @endif
                                </select>
                                @error('formapago')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                @if ($ingreso->formapago == 'contado')
                                    <label id="labelfechav" class="form-label">FECHA DE VENCIMIENTO</label>
                                    <input type="date" name="fechav" id="fechav" class="form-control " readonly
                                        value="{{ $ingreso->fechav }}" />
                                    @error('fechav')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                @endif
                                @if ($ingreso->formapago == 'credito')
                                    <label id="labelfechav" class="form-label is-required">FECHA DE VENCIMIENTO</label>
                                    <input type="date" name="fechav" id="fechav" class="form-control "
                                        value="{{ $ingreso->fechav }}" />
                                    @error('fechav')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">MONEDA</label>
                                <select name="moneda" id="moneda" class="form-select " required>
                                    <option value="" selected disabled>Seleccion una opción</option>
                                    @if ($ingreso->moneda == 'soles')
                                        <option value="soles" data-moneda="soles" selected>Soles</option>
                                    @elseif($ingreso->moneda == 'dolares')
                                        <option value="dolares" data-moneda="dolares" selected>Dolares Americanos</option>
                                    @endif
                                </select>
                                @error('tipo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label id="labeltasacambio" class="form-label is-required">TASA DE CAMBIO</label>
                                <input type="number" name="tasacambio" id="tasacambio" step="0.0001" readonly
                                    class="form-control " value="{{ $ingreso->tasacambio }}" />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">EMPRESA</label>
                                <select class="form-select select2 " name="company_id" required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $company->id == $ingreso->company_id ? 'selected' : '' }}>
                                            {{ $company->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">PROVEEDOR</label>
                                <select class="form-select select2 " name="cliente_id" id="cliente_id" required>
                                    <option value="" selected disabled>Seleccione una opción</option>

                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group is-required">PRECIO DE LA VENTA </label>
                                    @if ($ingreso->moneda == 'dolares')
                                        <span class="input-group-text" id="spancostoventa">$</span>
                                    @elseif($ingreso->moneda == 'soles')
                                        <span class="input-group-text" id="spancostoventa">S/.</span>
                                    @endif
                                    <input type="number" name="costoventa" id="costoventa" min="0.1" step="0.01"
                                        class="form-control  required" required readonly
                                        value="{{ $ingreso->costoventa }}" />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FACTURA PAGADA</label>
                                <select name="pagada" id="pagada" class="form-select " required>
                                    <option value="" disabled>Seleccion una opción</option>
                                    @if ($ingreso->pagada == 'NO')
                                        <option value="NO" selected>NO</option>
                                        <option value="SI">SI</option>
                                    @elseif($ingreso->pagada == 'SI')
                                        <option value="SI" selected>SI</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-12 mb-5">
                                <label class="form-label">OBSERVACION</label>
                                <input type="text" name="observacion" id="observacion" class="form-control "
                                    value="{{ $ingreso->observacion }}" />
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
                                                <h4>Agregar Detalle del Ingreso</h4>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label " id="labelproducto">PRODUCTO</label>
                                                    <select class="form-select select2 " name="product" id="product">
                                                        <option selected disabled value="">Seleccione una opción
                                                        </option>
                                                        @foreach ($products as $product)
                                                            @php $contp=0;    @endphp
                                                            @foreach ($detallesingreso as $item)
                                                                @if ($product->id == $item->idproducto)
                                                                    @php $contp++;    @endphp
                                                                @endif
                                                            @endforeach
                                                            @if ($contp == 0)
                                                                <option id="productoxempresa{{ $product->id }}"
                                                                    value="{{ $product->id }}"
                                                                    data-tipo="{{ $product->tipo }}"
                                                                    data-name="{{ $product->nombre }}"
                                                                    data-moneda="{{ $product->moneda }}"
                                                                    data-unidad="{{ $product->unidad }}"
                                                                    data-price="{{ $product->preciocompra }}">
                                                                    {{ $product->nombre }}</option>
                                                            @else
                                                                <option disabled id="productoxempresa{{ $product->id }}"
                                                                    value="{{ $product->id }}"
                                                                    data-tipo="{{ $product->tipo }}"
                                                                    data-name="{{ $product->nombre }}"
                                                                    data-moneda="{{ $product->moneda }}"
                                                                    data-unidad="{{ $product->unidad }}"
                                                                    data-price="{{ $product->preciocompra }}">
                                                                    {{ $product->nombre }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label" id="labelunidad">UNIDAD</label>
                                                    <input type="text" name="unidadproducto" id="unidadproducto"
                                                        class="form-control " />
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">CANTIDAD</label>
                                                    <input type="number" name="cantidad" id="cantidad" min="1"
                                                        step="1" class="form-control " />
                                                    @error('cantidad')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelpreciounitarioref">PRECIO UNITARIO
                                                            (REFERENCIAL)</label>
                                                        <span class="input-group-text" id="spanpreciounitarioref"></span>
                                                        <input type="number" name="preciounitario" min="0.1"
                                                            step="0.0001" id="preciounitario" readonly
                                                            class="form-control " />
                                                        @error('preciounitario')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelpreciounitario">PRECIO UNITARIO</label>
                                                        <span class="input-group-text" id="spanpreciounitario"></span>
                                                        <input type="number" name="preciounitariomo" min="0.1"
                                                            step="0.0001" id="preciounitariomo" class="form-control " />
                                                        @error('preciounitariomo')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelservicio">SERVICIO
                                                            ADICIONAL</label>
                                                        <span class="input-group-text" id="spanservicio"></span>
                                                        <input type="number" name="servicio" min="0.1"
                                                            step="0.0001" id="servicio"class="form-control " />
                                                        @error('servicio')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelpreciototal">PRECIO
                                                            TOTAL POR
                                                            PRODUCTO:</label>
                                                        <span class="input-group-text" id="spanpreciototal"></span>
                                                        <input type="number" name="preciofinal" min="0.1"
                                                            step="0.0001" id="preciofinal" readonly
                                                            class="form-control " />
                                                        @error('preciofinal')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-8 mb-3">
                                                    <label class="form-label "
                                                        id="labelobservacionproducto">OBSERVACION(Nro Serie):</label>
                                                    <input type="text" name="observacionproducto"
                                                        id="observacionproducto" class="form-control  gui-input" />
                                                </div>
                                                @php $ind=0 ; @endphp
                                                @php $indice=count($detallesingreso) ; @endphp
                                                <button type="button" class="btn btn-info" id="addDetalleBatch"
                                                    onclick="agregarFila('{{ $indice }}')"><i
                                                        class="fa fa-plus"></i> Agregar Producto
                                                    al ingreso</button>

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
                                                            @php $datobd="db" ;  @endphp
                                                            @foreach ($detallesingreso as $detalle)
                                                                @php $ind++;    @endphp
                                                                <tr id="fila{{ $ind }}">
                                                                    <td> <b> {{ $detalle->producto }} </b>
                                                                        @if ($detalle->tipo == 'kit')
                                                                            : <br>
                                                                            @foreach ($detalleskit as $kit)
                                                                                @if ($detalle->idproducto == $kit->product_id)
                                                                                    -{{ $kit->cantidad }}
                                                                                    {{ $kit->producto }} <br>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                    <td></td>
                                                                    <td> {{ $detalle->observacionproducto }}</td>
                                                                    <td> {{ $detalle->cantidad }}</td>
                                                                    <td>
                                                                        @if ($detalle->moneda == 'soles')
                                                                            S/.
                                                                        @elseif($detalle->moneda == 'dolares')
                                                                            $
                                                                        @endif
                                                                        {{ $detalle->preciounitario }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($ingreso->moneda == 'soles')
                                                                            S/.
                                                                        @elseif($ingreso->moneda == 'dolares')
                                                                            $
                                                                        @endif
                                                                        {{ $detalle->preciounitariomo }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($ingreso->moneda == 'soles')
                                                                            S/.
                                                                        @elseif($ingreso->moneda == 'dolares')
                                                                            $
                                                                        @endif
                                                                        {{ $detalle->servicio }}
                                                                    </td>
                                                                    <td><input type="hidden"
                                                                            id="preciof{{ $ind }}"
                                                                            value="{{ $detalle->preciofinal }}" />
                                                                        @if ($ingreso->moneda == 'soles')
                                                                            S/.
                                                                        @elseif($ingreso->moneda == 'dolares')
                                                                            $
                                                                        @endif
                                                                        {{ $detalle->preciofinal }}
                                                                    </td>
                                                                    <td><button type="button" class="btn btn-danger"
                                                                            onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{ $detalle->iddetalleingreso }}', '{{ $detalle->idproducto }}'  )"
                                                                            data-id="0"><i
                                                                                class="bi bi-trash-fill"></i>ELIMINAR</button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
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
                                                        @if ($ingreso->moneda == 'dolares')
                                                            <span class="input-group-text"
                                                                id="spanprecioventaconigv">$</span>
                                                        @elseif($ingreso->moneda == 'soles')
                                                            <span class="input-group-text"
                                                                id="spanprecioventaconigv">S/.</span>
                                                        @endif
                                                        <input type="number" name="precioventaconigv" readonly
                                                            id="precioventaconigv" class="input-group form-control " />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label">NRO OC</label>
                                                    <input type="text" name="nrooc" id="nrooc"
                                                        class="form-control" value="{{ $ingreso->nrooc }}" />
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label input-group">GUIA DE REMISION</label>
                                                    <input type="text" name="guiaremision" id="guiaremision"
                                                        class="form-control" value="{{ $ingreso->guiaremision }}" />
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label input-group">FECHA DE PAGO</label>
                                                    <input type="date" name="fechapago" id="fechapago"
                                                        class="form-control" value="{{ $ingreso->fechapago }}" />
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelacuenta">A CUENTA
                                                            1</label>
                                                        @if ($ingreso->moneda == 'dolares')
                                                            <span class="input-group-text" id="spancuenta1">$</span>
                                                        @elseif($ingreso->moneda == 'soles')
                                                            <span class="input-group-text" id="spancuenta1">S/.</span>
                                                        @endif
                                                        <input type="number" name="acuenta1" min="0"
                                                            step="0.0001" id="acuenta1" class="form-control"
                                                            value="{{ $ingreso->acuenta1 }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelacuenta2">A CUENTA
                                                            2</label>
                                                        @if ($ingreso->moneda == 'dolares')
                                                            <span class="input-group-text" id="spancuenta2">$</span>
                                                        @elseif($ingreso->moneda == 'soles')
                                                            <span class="input-group-text" id="spancuenta2">S/.</span>
                                                        @endif
                                                        <input type="number" name="acuenta2" min="0"
                                                            step="0.0001" id="acuenta2" class="form-control "
                                                            value="{{ $ingreso->acuenta2 }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelacuenta3">A CUENTA
                                                            3</label>
                                                        @if ($ingreso->moneda == 'dolares')
                                                            <span class="input-group-text" id="spancuenta3">$</span>
                                                        @elseif($ingreso->moneda == 'soles')
                                                            <span class="input-group-text" id="spancuenta3">S/.</span>
                                                        @endif
                                                        <input type="number" name="acuenta3" min="0"
                                                            step="0.0001" id="acuenta3" class="form-control "
                                                            value="{{ $ingreso->acuenta3 }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group"
                                                            id="labelsaldo">SALDO</label>
                                                        @if ($ingreso->moneda == 'dolares')
                                                            <span class="input-group-text" id="spansaldo">$</span>
                                                        @elseif($ingreso->moneda == 'soles')
                                                            <span class="input-group-text" id="spansaldo">S/.</span>
                                                        @endif
                                                        <input type="number" name="saldo" min="0"
                                                            step="0.0001" id="saldo" class="form-control "
                                                            value="{{ $ingreso->saldo }}" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <div class="input-group">
                                                        <label class="form-label input-group" id="labelmontopagado">MONTO
                                                            PAGADO</label>
                                                        @if ($ingreso->moneda == 'dolares')
                                                            <span class="input-group-text" id="spanmontopagado">$</span>
                                                        @elseif($ingreso->moneda == 'soles')
                                                            <span class="input-group-text" id="spanmontopagado">S/.</span>
                                                        @endif
                                                        <input type="number" name="montopagado" min="0"
                                                            step="0.0001" id="montopagado" class="form-control "
                                                            value="{{ $ingreso->montopagado }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        var tipoproducto = "";
        var idproducto = 0;
        var idcompany = 0;
        var idcliente = 0;
        var precioventaconigv = @json($ingreso->costoventa);
        estadoguardar = @json($detalles);
        idcliente = @json($ingreso->cliente_id);
        idcompany = @json($ingreso->company_id);
        //alert(estadoguardar);
        var funcion1 = "inicio";
        botonguardar(funcion1);
        var costoventa = $('[id="costoventa"]').val();
        ventatotal = costoventa;
        $(document).ready(function() {
            $('.toast').toast();
            $('.select2').select2({});
            var miurl = "{{ url('admin/venta/comboempresacliente') }}";
            $.get(miurl + '/' + idcompany, function(data) {
                var producto_select = '<option value="" disabled selected>Seleccione una opción</option>'
                for (var i = 0; i < data.length; i++) {
                    if (idcliente == data[i].id) {
                        producto_select += '<option value="' + data[i].id + '" data-name="' + data[i]
                            .nombre +
                            '" selected>' + data[i].nombre + '</option>';
                    } else {
                        producto_select += '<option value="' + data[i].id + '" data-name="' + data[i]
                            .nombre +
                            '" >' + data[i].nombre + '</option>';
                    }
                }
                $("#cliente_id").html(producto_select);
            });
            document.getElementById("precioventaconigv").value = parseFloat((precioventaconigv * 1.18).toFixed(2));
            document.getElementById("cantidad").onchange = function() {
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
                    document.getElementById('preciofinal').value = parseFloat(preciototalI.toFixed(4));
                }
            }

            //para los datos de los pagos
            document.getElementById("acuenta1").onchange = function() {
                pagocredito();
            };
            document.getElementById("acuenta2").onchange = function() {
                pagocredito();
            };
            document.getElementById("acuenta3").onchange = function() {
                pagocredito();
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

            //var tabla = document.getElementById(detallesVenta);
            $("#product").change(function() {
                $("#product option:selected").each(function() {
                    var miproduct = $(this).val();
                    if (miproduct) { 
                        $moneda = $(this).data("moneda"); 
                        monedaproducto = $moneda;
                        $named = $(this).data("name");
                        $tipo = $(this).data("tipo");
                        $unidad = $(this).data("unidad");
                        tipoproducto = $tipo;
                        idproducto = miproduct; 
                        $price = $(this).data("price");
                        monedafactura = $('[name="moneda"]').val();
                        //alert(tipo);
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
                        if (monedafactura == "dolares") {
                            simbolomonedafactura = "$";
                        } else if (monedafactura == "soles") {
                            simbolomonedafactura = "S/.";
                        }
                        var mitasacambio1 = $('[name="tasacambio"]').val();
                        var cant = document.getElementById('cantidad');
                        cant.setAttribute("min", 1);
                        if ($price) {
                            preciounit = parseFloat(($price).toFixed(4));
                            if (monedaproducto == "dolares" && monedafactura == "dolares") {
                                simbolomonedaproducto = "$";
                                preciototalI = parseFloat(($price).toFixed(4));
                                document.getElementById('preciounitario').value = parseFloat((
                                    $price).toFixed(
                                    2));
                                document.getElementById('preciounitariomo').value = parseFloat((
                                        $price)
                                    .toFixed(4));
                                document.getElementById('preciofinal').value = parseFloat(($price)
                                    .toFixed(4));
                            } else if (monedaproducto == "soles" && monedafactura == "soles") {
                                simbolomonedaproducto = "S/.";
                                preciototalI = parseFloat(($price).toFixed(4));
                                document.getElementById('preciounitario').value = parseFloat((
                                    $price).toFixed(
                                    2));
                                document.getElementById('preciounitariomo').value = parseFloat((
                                        $price)
                                    .toFixed(4));
                                document.getElementById('preciofinal').value = parseFloat(($price)
                                    .toFixed(4));
                            } else if (monedaproducto == "dolares" && monedafactura == "soles") {
                                simbolomonedaproducto = "$";
                                preciototalI = parseFloat(($price * mitasacambio1).toFixed(4));
                                document.getElementById('preciounitario').value = parseFloat((
                                    $price).toFixed(
                                    4));
                                document.getElementById('preciounitariomo').value = parseFloat((
                                    $price *
                                    mitasacambio1).toFixed(4));
                                document.getElementById('preciofinal').value = parseFloat(($price *
                                    mitasacambio1).toFixed(4));
                            } else if (monedaproducto == "soles" && monedafactura == "dolares") {
                                simbolomonedaproducto = "S/.";
                                preciototalI = parseFloat(($price / mitasacambio1).toFixed(4));;
                                document.getElementById('preciounitario').value = parseFloat((
                                    $price).toFixed(
                                    4));
                                document.getElementById('preciounitariomo').value = parseFloat((
                                    $price /
                                    mitasacambio1).toFixed(4));
                                document.getElementById('preciofinal').value = parseFloat(($price /
                                    mitasacambio1).toFixed(4));
                            }
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
                            document.getElementById('unidadproducto').value = $unidad;
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
            var unidadproducto = $('[name="unidadproducto"]').val();
            //alertas para los detallesBatch
            if (!product) {
                alert("Seleccione un producto");
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
                alert("Ingrese un precio");
                return;
            }
            var tasacambio = document.getElementById('tasacambio').value;;

            var preciocompranuevo = 0;
            if (monedaproducto == monedafactura) {
                preciocompranuevo = preciounitariomo;
            } else if (monedaproducto == "soles" && monedafactura == "dolares") {
                preciocompranuevo = parseFloat((preciounitariomo * tasacambio).toFixed(4));
            } else if (monedafactura == "soles" && monedaproducto == "dolares") {
                preciocompranuevo = parseFloat((preciounitariomo / tasacambio).toFixed(4));
            }

            var milista = '<br>';
            var puntos = '';

            var LVenta = [];
            var tam = LVenta.length;
            var datodb = "local";
            LVenta.push(product, nameproduct, cantidad, preciounitario, servicio, preciofinal, preciounitariomo,
                observacionproducto, unidadproducto, preciocompranuevo);
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
                        modificarStock(data[i].id, data[i].cantidad, "restar");
                    }
                    modificarStock(LVenta[0], LVenta[2], "sumar");
                    agregarFilasTabla(LVenta, puntos, milista);
                });
            } else {
                modificarStock(LVenta[0], LVenta[2], "sumar");
                agregarFilasTabla(LVenta, puntos, milista);
            }

        }

        function modificarStock(idproducto, cantidad, operacion) {
            //restar stock individual    
            var product1 = document.getElementById('productoxempresa' + idproducto);
            var stock = product1.dataset.stock;
            if (operacion == "sumar") {
                product1.setAttribute('data-stock', (stock + cantidad));
            } else if (operacion == "restar") {
                product1.setAttribute('data-stock', (stock - cantidad));
            }

        }

        function agregarFilasTabla(LVenta, puntos, milista) {
            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0] + '"required><b>' + LVenta[1] + '</b>' +
                puntos + milista + '</td><td><input  type="hidden" name="Lunidadprod[]" >' + LVenta[8] +
                '</td><td><input  type="hidden" name="Lobservacionproducto[]" id="observacionproducto' + indice +
                '" value="' + LVenta[7] + '" required>' + LVenta[7] +
                '</td><td><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice + '" value="' + LVenta[2] +
                '" required>' + LVenta[2] +
                '</td><td><input  type="hidden" name="Lpreciounitario[]" id="preciounitario' + indice + '" value="' +
                LVenta[3] + '" required>' + simbolomonedaproducto + LVenta[3] +
                '</td><td><input  type="hidden" name="Lpreciounitariomo[]" id="preciounitariomo' + indice + '" value="' +
                LVenta[6] + '" required>' + simbolomonedafactura + LVenta[6] +
                '</td><td><input  type="hidden" name="Lservicio[]" id="servicio' + indice + '" value="' + LVenta[4] +
                '" required>' + simbolomonedafactura + LVenta[4] +
                '</td><td ><input id="preciof' + indice + '"  type="hidden" name="Lpreciofinal[]" value="' + LVenta[5] +
                '" required> <input  type="hidden" name="Lpreciocompranuevo[]" value="' + LVenta[9] + '" required> ' +
                simbolomonedafactura + LVenta[5] +
                '</td><td> <button type="button" class="btn btn-danger" onclick="eliminarFila(' + indice + ',' + 0 + ',' +
                0 + ',' + LVenta[0] + ')" data-id="0">ELIMINAR</button></td></tr>';

            $("#detallesVenta>tbody").append(filaDetalle);
            $('.toast').toast('hide');
            indice++;
            ventatotal = parseFloat(ventatotal) + parseFloat(preciototalI);
            $('#product').val(null).trigger('change');
            document.getElementById('costoventa').value = (ventatotal).toFixed(2);
            limpiarinputs();
            document.getElementById('productoxempresa' + LVenta[0]).disabled = true;
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
                        var miurl2 = "{{ url('admin/deletedetalleingreso') }}";
                        $.get(miurl2 + '/' + iddetalle, function(data) {
                            if (data[0] == 1) {
                                Swal.fire({
                                    text: "Registro Eliminado",
                                    icon: "success"
                                });
                                quitarFila(ind);

                            } else if (data[0] == 0) {
                                Swal.fire({
                                    text: "No se puede eliminar",
                                    icon: "error"
                                });;
                            } else if (data[0] == 2) {
                                Swal.fire({
                                    text: "Registro no encontrado",
                                    icon: "error"
                                });
                            }
                        });
                    }
                })
            } else {
                quitarFila(ind);
            }
            document.getElementById('productoxempresa' + idproducto).disabled = false;
            return false;
        }

        function quitarFila(indicador) {
            var resta = 0;
            resta = $('[id="preciof' + indicador + '"]').val();
            ventatotal = ventatotal - resta;
            $('#fila' + indicador).remove();
            indice--;
            document.getElementById('costoventa').value = (ventatotal).toFixed(2);
            var funcion = "eliminar";
            botonguardar(funcion);
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
        }
    </script>
@endpush
