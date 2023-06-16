@extends('layouts.admin')
@push('css')
    <link href="{{ asset('admin/required.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12  ">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">EMPRESA</label>
                    <select class="form-select  " name="company_id" id="company_id" required>
                        <option value="-1" selected>TODAS</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" data-miempresa="{{ $company->nombre }}">
                                {{ $company->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">PRODUCTO</label>
                    <select class="form-select select2 " name="producto" id="producto">
                        <option value="-1" selected>TODOS</option>
                        @foreach ($productos as $item)
                            <option value="{{ $item->id }}" data-miproducto="{{ $item->nombre }}">{{ $item->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">FECHA INICIO</label>
                    <input type="date" class="form-control " id="fechainicio" name="fechainicio" />
                </div>
                <div class="col-md-6 mb-3" id="cantidadcosto" name="cantidadcosto">
                    <label class="form-label is-required">FECHA FIN</label>
                    <input type="date" class="form-control " id="fechafin" name="fechafin" />
                </div>

            </div>
            <div class="row">
                <div class="col">
                    <table class="table table-bordered table-striped" id="mitablaprod" name="mitablaprod">
                        <thead class="fw-bold text-primary">
                            <tr>

                                <th>FECHA</th>
                                <th>COMPRA O VENTA</th>
                                <th>FACTURA</th>
                                <th>EMPRESA</th>
                                <th>CLIENTE/PROVEEDOR</th>
                                <th>PRODUCTO</th>
                                <th>TIPO</th>
                                <th>CANTIDAD</th>
                                <th>PRECIO UNITARIO</th>
                                <th>PRECIO FINAL</th>
                                <th>MONEDA</th>

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
    <script src="{{ asset('admin/midatatable.js') }}"></script>
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
            var producto = document.getElementById("producto").value;

            var urldatosproductos = "{{ url('admin/reporte/datosproductos') }}";
            $.get(urldatosproductos + '/' + fechainicio + '/' + fechafin + '/' + empresa + '/' + producto, function(data) {
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
                filaDetalle =
                    '<tr><td> ' + datos[i].fecha + '</td>' +
                    '<td> ' + datos[i].compraventa + '</td>' +
                    '<td> ' + datos[i].factura + '</td>' +
                    '<td> ' + datos[i].empresa + '</td>' +
                    '<td> ' + datos[i].cliente + '</td>' +
                    '<td> ' + datos[i].producto + '</td>' +
                    '<td> ' + datos[i].tipo + '</td>' +
                    '<td> ' + datos[i].cantidad + '</td>' +
                    '<td> ' + datos[i].preciounitariomo + '</td>' +
                    '<td> ' + datos[i].preciofinal + '</td>' +
                    '<td> ' + datos[i].moneda + '</td>' +
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

        $("#producto").change(function() {
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
            var idproducto = document.getElementById("producto").value;
            var company_id = document.getElementById("company_id");
            var producto = document.getElementById("producto");
            if (idcompany_id != "-1") {
                if (idproducto != "-1") {
                    var namecompany_id = company_id[company_id.selectedIndex].getAttribute('data-miempresa');
                    var nameproducto = producto[producto.selectedIndex].getAttribute('data-miproducto');
                    mitituloexcel = 'Ventas y compras_' + namecompany_id + '_' + nameproducto + '_' + fechainicio + '_' +
                        fechafin;
                } else {
                    var namecompany_id = company_id[company_id.selectedIndex].getAttribute('data-miempresa');
                    mitituloexcel = 'Ventas y compras_' + namecompany_id + '_' + fechainicio + '_' + fechafin;
                }
            } else {
                if (idproducto != "-1") {
                    var nameproducto = producto[producto.selectedIndex].getAttribute('data-miproducto');
                    mitituloexcel = 'Ventas y compras_' + nameproducto + '_' + fechainicio + '_' + fechafin;
                } else {
                    mitituloexcel = 'Ventas y compras_' + fechainicio + '_' + fechafin;
                }
            }
        }
    </script>
    <script></script>
@endpush
