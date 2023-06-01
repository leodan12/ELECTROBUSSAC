@extends('layouts.admin')
@push('css')
    <link href="{{ asset('admin/required.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('admin/chartjs.min.js') }}"></script>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12  ">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">EMPRESA</label>
                    <select class="form-select  borde" name="company_id" id="company_id" required>
                        <option value="-1" selected>TODAS</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">PRODUCTO</label>
                    <select class="form-select select2 borde" name="producto" id="producto">
                        <option value="-1" selected>TODOS</option>
                        @foreach ($productos as $item)
                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">FECHA INICIO</label>
                    <input type="date" class="form-control borde" id="fechainicio" name="fechainicio" />
                </div>
                <div class="col-md-6 mb-3" id="cantidadcosto" name="cantidadcosto">
                    <label class="form-label is-required">FECHA FIN</label>
                    <input type="date" class="form-control borde" id="fechafin" name="fechafin" />
                </div>

            </div>
            <div class="row">
                <div class="col">
                    <table class="table table-bordered table-striped" style="width: 100%" id="mitablaprod"
                        name="mitablaprod">
                        <thead class="fw-bold text-primary">
                            <tr>

                                <th>FECHA</th>
                                <th>COMPRA O VENTA</th>
                                <th>EMPRESA</th>
                                <th>CLIENTE/PROVEEDOR</th>
                                <th>PRODUCTO</th>
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

            traerdatos();

        });

        function traerdatos() {
            var fechainicio = document.getElementById("fechainicio").value;
            var fechafin = document.getElementById("fechafin").value;
            var empresa = document.getElementById("company_id").value;
            var producto = document.getElementById("producto").value;

            var urldatosproductos = "{{ url('admin/reporte/datosproductos') }}";
            $.get(urldatosproductos + '/' + fechainicio + '/' + fechafin + '/' + empresa + '/' + producto, function(data) {
                console.log(data.length);
                llenartabla(data);
            });
        }

        function llenartabla(datos) {
            var btns = 'lBfrtip'; 
            if (contini > 0) {
                $("#mitablaprod").dataTable().fnDestroy(); //eliminar las filas de la tabla  
            }
            $('#mitablaprod tbody tr').slice().remove();
            for (var i = 0; i < datos.length; i++) {
                filaDetalle =
                    '<tr><td> ' + datos[i].fecha + '</td>' +
                    '<td> ' + datos[i].compraventa + '</td>' +
                    '<td> ' + datos[i].empresa + '</td>' +
                    '<td> ' + datos[i].cliente + '</td>' +
                    '<td> ' + datos[i].producto + '</td>' +
                    '<td> ' + datos[i].cantidad + '</td>' +
                    '<td> ' + datos[i].preciounitariomo + '</td>' +
                    '<td> ' + datos[i].preciofinal + '</td>' +
                    '<td> ' + datos[i].moneda + '</td>' +
                    '</tr>';
                $("#mitablaprod>tbody").append(filaDetalle);
            }

            inicializartabladatos( btns);
            contini++;
        }
        $("#company_id").change(function() {
            var company = $(this).val();
            traerdatos();

        });

        $("#producto").change(function() {
            var tipografico = $(this).val();
            traerdatos();

        });
        $("#fechainicio").change(function() {
            var Vreporte = $(this).val();
            traerdatos();


        });
        $("#fechafin").change(function() {
            var cant = $(this).val();
            traerdatos();


        });
    </script>
    <script></script>
@endpush
