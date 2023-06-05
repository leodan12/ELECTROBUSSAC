@extends('layouts.admin')
@push('css')
    <link href="{{ asset('admin/required.css') }}" rel="stylesheet" type="text/css" />
@endpush

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
                    <h4>VENDER LA COTIZACION
                        <a href="{{ url('admin/cotizacion') }}" id="btnvolver" name="btnvolver"
                            class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/venta') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FECHA</label>
                                <input type="date" name="fecha" id="fecha" class="form-control borde" required /> 
                                @error('fecha')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label  ">NUMERO DE FACTURA</label>
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
                                    <input type="date" name="fechav" id="fechav" class="form-control borde"
                                        readonly />
                                    @error('fechav')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                @endif
                                @if ($venta->formapago == 'credito')
                                    <label id="labelfechav" class="form-label is-required">FECHA DE VENCIMIENTO</label>
                                    <input type="date" name="fechav" id="fechav" class="form-control borde"
                                        required />
                                    @error('fechav')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">MONEDA</label>
                                <select name="moneda" id="moneda" class="form-select borde" required>
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
                                <input type="number" name="tasacambio" id="tasacambio" step="0.01"
                                    class="form-control borde" value="{{ $venta->tasacambio }}" readonly />
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">EMPRESA</label>
                                <select class="form-select select2 borde" name="company_id" id="company_id" required>
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
                                <select class="form-select select2 borde" name="cliente_id" id="cliente_id" required>
                                    <option value="" select disabled>Seleccione una opción</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}"
                                            {{ $cliente->id == $venta->cliente_id ? 'selected' : '' }}>
                                            {{ $cliente->nombre }}
                                        </option>
                                    @endforeach
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
                                    <input type="number" name="costoventa" id="costoventa" min="0.1"
                                        step="0.01" class="form-control borde required" required readonly
                                        value="{{ $venta->costoventasinigv }}" />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">FACTURA PAGADA</label>
                                <select name="pagada" id="pagada" class="form-select borde" required>
                                    <option value="" disabled>Seleccion una opción</option>
                                    @if ($venta->formapago == 'credito')
                                        <option value="NO" selected>NO</option>
                                    @elseif($venta->formapago == 'contado')
                                        <option value="SI" selected>SI</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-12 mb-5">
                                <label class="form-label">OBSERVACION</label>
                                <input type="text" name="observacion" id="observacion" class="form-control borde"
                                    value="{{ $venta->observacion }}" />
                                @error('observacion')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <input type="hidden" name="idcotizacion" value="{{ $venta->id }}" required>
                            </div>
                            <hr>


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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $datobd="db" ;  @endphp
                                        @foreach ($detallesventa as $detalle)
                                            <tr>
                                                <td> <input type="hidden" name="Lproduct[]"
                                                        value="{{ $detalle->idproducto }}" required> <b>
                                                        {{ $detalle->producto }} </b>
                                                    @if ($detalle->tipo == 'kit')
                                                        : <br>
                                                        @foreach ($detalleskit as $kit)
                                                            @if ($detalle->idproducto == $kit->product_id)
                                                                -{{ $kit->cantidad }} {{ $kit->producto }} <br>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                @php $v="no"; @endphp
                                                <td>
                                                    @if ($detalle->observacionproducto != null)
                                                        <input type="text" class="form-control borde"
                                                            name="Lobservacionproducto[]"
                                                            value="{{ $detalle->observacionproducto }}" required>
                                                    @else
                                                        <input type="text" class="form-control borde"
                                                            name="Lobservacionproducto[]" value="{{ $v }}"
                                                            required>
                                                    @endif
                                                </td>
                                                <td><input type="hidden" name="Lcantidad[]"
                                                        value="{{ $detalle->cantidad }}" required>
                                                    {{ $detalle->cantidad }}</td>
                                                <td><input type="hidden" name="Lpreciounitario[]"
                                                        value="{{ $detalle->preciounitario }}" required>
                                                    @if ($detalle->moneda == 'soles')
                                                        S/.
                                                    @elseif($detalle->moneda == 'dolares')
                                                        $
                                                    @endif {{ $detalle->preciounitario }}
                                                </td>
                                                <td><input type="hidden" name="Lpreciounitariomo[]"
                                                        value="{{ $detalle->preciounitariomo }}" required>
                                                    @if ($venta->moneda == 'soles')
                                                        S/.
                                                    @elseif($venta->moneda == 'dolares')
                                                        $
                                                    @endif {{ $detalle->preciounitariomo }}
                                                </td>
                                                <td><input type="hidden" name="Lservicio[]"
                                                        value="{{ $detalle->servicio }}" required>
                                                    @if ($venta->moneda == 'soles')
                                                        S/.
                                                    @elseif($venta->moneda == 'dolares')
                                                        $
                                                    @endif {{ $detalle->servicio }}
                                                </td>
                                                <td><input type="hidden" name="Lpreciofinal[]"
                                                        value="{{ $detalle->preciofinal }}" />
                                                    @if ($venta->moneda == 'soles')
                                                        S/.
                                                    @elseif($venta->moneda == 'dolares')
                                                        $
                                                    @endif {{ $detalle->preciofinal }}
                                                </td>
                                                <td>


                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <hr>
                            <div class="col-md-12 mb-3">
                                <button type="submit" id="btnguardar" name="btnguardar"
                                    class="btn btn-primary text-white float-end">Guardar Cotizacion Como Venta</button>
                            </div>
                        </div>
                    </form>
                    <div class="toast-container position-fixed bottom-0 start-0 p-2" style="z-index: 1000">
                        <div class="toast " role="alert" aria-live="assertive" aria-atomic="true"
                            data-bs-autohide="false" style="width: 100%; box-shadow: 0px 3px 5px 3px rgb(255, 0, 0); ">
                            <div class="  card-header">
                                <i class="mdi mdi-information menu-icon"></i>
                                <strong class="mr-auto"> &nbsp; Productos sin stock suficiente:</strong>
                                <button type="button" class="btn-close float-end" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                <table id="listasinstock">
                                    <thead class="fw-bold text-danger">
                                        <tr>
                                            <th>STOCK &nbsp; </th>
                                            <th>FALTANTE &nbsp; </th>
                                            <th>PRODUCTO</th>
                                        </tr>
                                    </thead>
                                    <tbody style="text-align: center">
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
        var credito = @json($venta->formapago);
        var hoy = new Date();
        var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' + String(hoy
            .getDate()).padStart(2, '0');
        document.getElementById("fecha").value = fechaActual;
        var validez = hoy;
        validez.setDate(validez.getDate() + 15);
        var fechavalidez = validez.getFullYear() + '-' + (String(validez.getMonth() + 1).padStart(2, '0')) +
            '-' + String(validez.getDate()).padStart(2, '0');

        var data = @json($prodfaltantes);
        var sinstock = data.length;
        document.getElementById("validacionfactura").style.display = 'none';

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

        $(document).ready(function() {
            $('.toast').toast();
            if (sinstock > 0) {
                $('#detalleskit tbody tr').slice().remove();
                for (var i = 0; i < data.length; i++) {
                    filaDetalle = '<tr style="border-top: 1px solid silver;" ' +
                        '"><td> ' + data[i].stockdisponible +
                        '</td><td> ' + data[i].stockrequerido +
                        '</td><td> ' + data[i].producto +
                        '</td></tr>';
                    $("#listasinstock>tbody").append(filaDetalle);
                }
                $('.toast').toast('show');
                $("#btnguardar").prop("disabled", true);
            } else {
                $("#btnguardar").prop("disabled", false);
            }
            $('.select2').select2({});

        });

        if (credito == "credito") {
            document.getElementById("fechav").value = fechavalidez;
        }
    </script>
@endpush
