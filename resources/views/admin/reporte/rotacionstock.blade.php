@extends('layouts.admin')

@section('content')
<div class="row" style="text-align: center;">
    <div class="col">
        <h3>
            ROTACION DE STOCK DE LOS PRODUCTOS
        </h3>
    </div>
</div><br>
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
                    <table class="table table-bordered table-striped" id="mitablaprod"
                        name="mitablaprod">
                        <thead class="fw-bold text-primary">
                            <tr>

                                <th>COMPRA O VENTA</th>
                                <th>EMPRESA</th>
                                <th>PRODUCTO</th>
                                <th>CANTIDAD TOTAL</th>
                                <th>MINIMO</th>
                                <th>MAXIMO</th>
                                <th>PRECIO TOTAL</th>
                                <th>MONEDA</th>
                                <th>VER REGISTROS</th>

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
    {{-- mis modales para ver los creditos vencidos --}}
    <div class="modal fade" id="modalVer2" aria-hidden="true" aria-labelledby="modalVer2Label" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalVer2Label1"> </h1>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <table class="table table-bordered table-striped " id="mitablaresultados" name="mitablaresultados">
                        <thead class="fw-bold text-primary">
                            <tr>
                                <th>FECHA</th>
                                <th>COMPRA O VENTA</th>
                                <th>EMPRESA</th>
                                <th>CLIENTE</th>
                                <th>PRODUCTO</th>
                                <th>CANTIDAD TOTAL</th>
                                <th>PRECIO TOTAL </th>
                                <th>MONEDA</th>

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
@endsection


@push('script')
    <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
    <script type="text/javascript">
        var contini = 0;
        var contini2 = 0;
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

            var urldatosproductos = "{{ url('admin/reporte/datosrotacionstock') }}";
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
                    '<tr> <td> ' + datos[i].compraventa + '</td>' +
                    '<td> ' + datos[i].empresa + '</td>' +
                    '<td> ' + datos[i].producto + '</td>' +
                    '<td> ' + datos[i].cantidad + '</td>' +
                    '<td> ' + datos[i].minimo + '</td>' +
                    '<td> ' + datos[i].maximo + '</td>' +
                    '<td> ' + datos[i].preciofinal + '</td>' +
                    '<td> ' + datos[i].moneda + '</td>' +
                    '<td> <button class= "btn btn-info" data-empresa="' + datos[i].idempresa + '" data-producto="' + datos[i]
                    .idproducto + '" data-compraventa="' + datos[i].compraventa +
                    '" data-bs-target="#modalVer2" data-bs-toggle="modal">Ver </button>   ' +
                    '</td>' +
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

        const mimodalcreditos = document.getElementById('modalVer2')
        mimodalcreditos.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const xempresa = button.getAttribute('data-empresa');
            const xproducto = button.getAttribute('data-producto');
            const compraventa = button.getAttribute('data-compraventa');
            var miurl = "";
            if (compraventa == "venta") {
                miurl = "{{ url('admin/reporte/detalleventas') }}";
            } else if (compraventa == "compra") {
                miurl = "{{ url('admin/reporte/detallecompras') }}";
            }
            var xfechainicio = document.getElementById("fechainicio").value;
            var xfechafin = document.getElementById("fechafin").value;
            $.get(miurl + '/' + xfechainicio + '/' + xfechafin + '/' + xempresa + '/' + xproducto, function(
                midata) {
                llenartabla2(midata);


            });
        });

        function llenartabla2(datos) {
            var fechainicio = document.getElementById("fechainicio").value;
            var fechafin = document.getElementById("fechafin").value;
            var btns = 'lBfrtip';
            var tabla = '#mitablaresultados';
            if (contini2 > 0) {
                $("#mitablaresultados").dataTable().fnDestroy(); //eliminar las filas de la tabla   
            }
            $('#mitablaresultados tbody tr').slice().remove();
            for (var i = 0; i < datos.length; i++) {
                filaDetalle =
                    '<tr> <td> ' + datos[i].fecha + '</td>' +
                    '<td> ' + datos[i].compraventa + '</td>' +
                    '<td> ' + datos[i].empresa + '</td>' +
                    '<td> ' + datos[i].cliente + '</td>' +
                    '<td> ' + datos[i].producto + '</td>' +
                    '<td> ' + datos[i].cantidad + '</td>' +
                    '<td> ' + datos[i].preciofinal + '</td>' +
                    '<td> ' + datos[i].moneda + '</td>' +
                    '</tr>';
                $("#mitablaresultados>tbody").append(filaDetalle);
            } 
            inicializartabladatos(btns, tabla, datos[0].compraventa + '_' + datos[0].empresa + '_' + datos[0].producto +
                '_' + fechainicio + '_' + fechafin);
            contini2++;
        }
    </script>
    <script></script>
@endpush
