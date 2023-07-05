@extends('layouts.admin')
@section('content')
    <div class="row" style="text-align: center;">
        <div class="col">
            <h3>
                COBRO DE LAS VENTAS
            </h3>
        </div>
    </div><br>
    <div class="row">
        <div class="col-md-12  ">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label is-required">EMPRESA</label>
                    <select class="form-select  " name="company_id" id="company_id" required>
                        <option value="-1" selected>TODAS</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" data-miempresa="{{ $company->nombre }}">
                                {{ $company->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label is-required">CLIENTE</label>
                    <select class="form-select select2 " name="cliente_id" id="cliente_id" required>
                        <option value="-1" selected>TODOS</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}" data-micliente="{{ $cliente->nombre }}">
                                {{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label is-required">FECHA INICIO</label>
                    <input type="date" class="form-control " id="fechainicio" name="fechainicio" />
                </div>
                <div class="col-md-4 mb-3" id="cantidadcosto" name="cantidadcosto">
                    <label class="form-label is-required">FECHA FIN</label>
                    <input type="date" class="form-control " id="fechafin" name="fechafin" />
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label is-required">¿FACTURA PAGADA?</label>
                    <select class="form-select " name="pagada" id="pagada">
                        <option value="" selected>TODOS</option>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table class="table table-bordered table-striped" id="mitablaprod" name="mitablaprod">
                        <thead class="fw-bold text-primary">
                            <tr>
                                <th>EMPRESA</th>
                                <th>CLIENTE</th>
                                <th>N° FACTURA</th>
                                <th>N° OC</th>
                                <th>FECHA</th>
                                <th>FECHA VENCIMIENTO</th>
                                <th>GUIA REMISION</th>
                                <th>MONTO FACTURA</th>
                                <th>MONEDA</th>
                                <th>A CUENTA 1</th>
                                <th>A CUENTA 2</th>
                                <th>A CUENTA 3</th>
                                <th>SALDO</th>
                                <th>CONSTANCIA RETENCION</th>
                                <th>DETRACCION/RETENCION</th>
                                <th>MONTO DE PAGO</th>
                                <th>FECHA DE PAGO</th>
                                <th>PAGADA</th>
                            </tr>
                        </thead>
                        <Tbody id="tbody-mantenimientos">
                            <tr></tr>
                        </Tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
    <script type="text/javascript">
        var contini = 0;
        var mitituloexcel = "";
        $(document).ready(function() {
            $('.select2').select2({});
            var hoy = new Date();
            var fechaFin = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' +
                String(hoy.getDate()).padStart(2, '0');
            document.getElementById("fechafin").value = fechaFin;
            var inicio = hoy;
            var diadelmes = hoy.getDate();
            inicio.setDate(inicio.getDate() - (diadelmes - 1));
            var fechaInicio = inicio.getFullYear() + '-' + (String(inicio.getMonth() + 1).padStart(2, '0')) +
                '-' + String(inicio.getDate()).padStart(2, '0');
            document.getElementById("fechainicio").value = fechaInicio;
            mititulo();
            traerdatos();

        });

        function traerdatos() {
            var fechainicio = document.getElementById("fechainicio").value;
            var fechafin = document.getElementById("fechafin").value;
            var empresa = document.getElementById("company_id").value;
            var cliente = document.getElementById("cliente_id").value;
            var pagada = document.getElementById("pagada").value;

            var urldatosproductos = "{{ url('admin/reporte/datoscobroventas') }}";
            $.get(urldatosproductos + '/' + fechainicio + '/' + fechafin + '/' + empresa + '/' + cliente, function(data) {
                llenartabla(data);
            });
        }

        function llenartabla(datos) {
            var btns = 'lBfrtip';
            var tabla = '#mitablaprod';
            if (contini > 0) {
                $("#mitablaprod").dataTable().fnDestroy(); //eliminar las filas de la tabla  
            }
            $('#mitablaprod tbody tr').slice().remove();
            for (var i = 0; i < datos.length; i++) {
                var factura = "";
                var nrooc = "";
                var fecha = "";
                var fechav = "";
                var guiaremision = "";
                var costoventa = "";
                var moneda = "";
                var acuenta1 = "";
                var acuenta2 = "";
                var acuenta3 = "";
                var saldo = "";
                var constanciaretencion = "";
                var retencion = "";
                var montopagado = "";
                var fechapago = "";
                if (datos[i].factura) {
                    factura = datos[i].factura;
                }
                if (datos[i].nrooc) {
                    nrooc = datos[i].nrooc;
                }
                if (datos[i].fecha) {
                    fecha = datos[i].fecha;
                }
                if (datos[i].fechav) {
                    fechav = datos[i].fechav;
                }
                if (datos[i].guiaremision) {
                    guiaremision = datos[i].guiaremision;
                }
                if (datos[i].costoventa) {
                    costoventa = datos[i].costoventa;
                }
                if (datos[i].moneda) {
                    moneda = datos[i].moneda;
                }
                if (datos[i].acuenta1) {
                    acuenta1 = datos[i].acuenta1;
                }
                if (datos[i].acuenta2) {
                    acuenta2 = datos[i].acuenta2;
                }
                if (datos[i].acuenta3) {
                    acuenta3 = datos[i].acuenta3;
                }
                if (datos[i].saldo) {
                    saldo = datos[i].saldo;
                }
                if (datos[i].constanciaretencion) {
                    constanciaretencion = datos[i].constanciaretencion;
                }
                if (datos[i].retencion) {
                    retencion = datos[i].retencion;
                }
                if (datos[i].montopagado) {
                    montopagado = datos[i].montopagado;
                }
                if (datos[i].fechapago) {
                    fechapago = datos[i].fechapago;
                }

                filaDetalle =
                    '<tr><td> ' + datos[i].empresa + '</td>' +
                    '<td> ' + datos[i].cliente + '</td>' +
                    '<td> ' + factura + ' </td>' +
                    '<td> ' + nrooc + ' </td>' +
                    '<td> ' + fecha + ' </td>' +
                    '<td> ' + fechav + ' </td>' +
                    '<td> ' + guiaremision + ' </td>' +
                    '<td> ' + costoventa + ' </td>' +
                    '<td> ' + moneda + ' </td>' +
                    '<td> ' + acuenta1 + ' </td>' +
                    '<td> ' + acuenta2 + ' </td>' +
                    '<td> ' + acuenta3 + ' </td>' +
                    '<td> ' + saldo + ' </td>' +
                    '<td> ' + constanciaretencion + ' </td>' +
                    '<td> ' + retencion + ' </td>' +
                    '<td> ' + montopagado + ' </td>' +
                    '<td> ' + fechapago + ' </td>' +
                    '<td>' + datos[i].pagada + '</td>' +

                    '</tr>';
                $("#mitablaprod>tbody").append(filaDetalle);
            }
            inicializartabladatos(btns, tabla, mitituloexcel);
            contini++;
        }
        $("#company_id").change(function() {
            var company = $(this).val();
            mititulo();
            traerdatos();
        });

        $("#cliente_id").change(function() {
            var tipografico = $(this).val();
            mititulo();
            traerdatos();
        }); 
        $("#fechainicio").change(function() {
            var Vreporte = $(this).val();
            mititulo();
            traerdatos();
        });
        $("#fechafin").change(function() {
            var cant = $(this).val();
            mititulo();
            traerdatos();
        });

        function mititulo() {
            var fechainicio = document.getElementById("fechainicio").value;
            var fechafin = document.getElementById("fechafin").value;
            var idcompany_id = document.getElementById("company_id").value;
            var idcliente = document.getElementById("cliente_id").value;
            var company_id = document.getElementById("company_id");
            var cliente_id = document.getElementById("cliente_id");
            if (idcompany_id != "-1") {
                if (idcliente != "-1") {
                    var namecompany_id = company_id[company_id.selectedIndex].getAttribute('data-miempresa');
                    var namecliente_id = cliente_id[cliente_id.selectedIndex].getAttribute('data-micliente');
                    mitituloexcel = 'Cobro ventas_' + namecompany_id + '_' + namecliente_id + '_' + fechainicio + '_' +
                        fechafin;
                } else {
                    var namecompany_id = company_id[company_id.selectedIndex].getAttribute('data-miempresa');
                    mitituloexcel = 'Cobro ventas_' + namecompany_id + '_' + fechainicio + '_' + fechafin;
                }
            } else {
                if (idcliente != "-1") {
                    var namecliente_id = cliente_id[cliente_id.selectedIndex].getAttribute('data-micliente');
                    mitituloexcel = 'Cobro ventas_' + namecliente_id + '_' + fechainicio + '_' + fechafin;
                } else {
                    mitituloexcel = 'Cobro ventas_' + fechainicio + '_' + fechafin;
                }
            }
        }
        $("select[name=pagada]").change(function(e) {
            mitabladatos.column(17).search(e.target.value).draw(); 
        });
    </script>
    <script></script>
@endpush
