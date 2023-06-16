@extends('layouts.admin')
@push('css')
    <style type="text/css">
        .ingresos {
            border: 1px solid #79EB68;
            background-color: #C4EAA4
        }

        .ventas {
            border: 1px solid #53CAD4;
            background-color: #90D4D4
        }

        .cotizaciones {
            border: 1px solid #F59075;
            background-color: #F4BEA8
        }

        .productos {
            border: 1px solid #D6BD2C;
            background-color: #D7D080
        }

        .borde {
            border-radius: 10px;
            color: black;
        }

        .centro {
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    
    <script src="{{ asset('admin/jsusados/chartjs.min.js') }}"></script>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12  ">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">EMPRESA</label>
                    <select class="form-select " name="company_id" id="company_id" required>
                        <option value="-1" selected>TODAS</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3">
                    <div class="card ingresos borde">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h6> COMPRAS&nbsp; <br> DEL MES: &nbsp;</h6>
                                    </div>
                                    <div class="col centro">
                                        <h6 id="verIngresomes"> S/.{{ number_format((float) $ingresomes, 2, '.', '') }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-text">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Esta Semana:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verIngresosemana">S/.{{ number_format((float) $ingresosemana, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Este Día:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verIngresodia">S/.{{ number_format((float) $ingresodia, 2, '.', '') }}</h5>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card ventas borde">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h6> VENTAS&nbsp;&nbsp; <br>DEL MES: &nbsp; </h6>
                                    </div>
                                    <div class="col centro">
                                        <h6 id="verVentames"> S/.{{ number_format((float) $ventames, 2, '.', '') }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-text">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Esta Semana:</h5>
                                    </div>
                                    <div class="col " style="text-align: center">
                                        <h5 id="verVentasemana">S/.{{ number_format((float) $ventasemana, 2, '.', '') }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Este Día:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verVentadia">S/.{{ number_format((float) $ventadia, 2, '.', '') }}</h5>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card cotizaciones borde">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h6> COTIZACIONES <br> DEL MES:</h6>
                                    </div>
                                    <div class="col centro">
                                        <h6 id="verCotizacionmes">
                                            S/.{{ number_format((float) $cotizacionmes, 2, '.', '') }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-text">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Esta Semana:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verCotizacionsemana">
                                            S/.{{ number_format((float) $cotizacionsemana, 2, '.', '') }}</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Este Día:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verCotizaciondia">
                                            S/.{{ number_format((float) $cotizaciondia, 2, '.', '') }}</h5>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card productos borde">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h6> TOTAL PRODUCTOS:</h6>
                                    </div>
                                    <div class="col centro">
                                        <h6 id="verProductomes">{{ $productomes }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="card-text">
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Stock Minimo:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verProductominimo">{{ $productominimo }}</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col" style="text-align: left">
                                        <h5>Sin Stock:</h5>
                                    </div>
                                    <div class="col" style="text-align: center">
                                        <h5 id="verProductosin">{{ $productosinstock }}</h5>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">Reporte Grafico Sobre:</label>
                    <select class="form-select " name="reporte" id="reporte">
                        <option value="ccv" selected>Compras, Ventas Y Cotizaciones del Mes(Soles):</option>
                        <option value="pmv">Productos Mas Vendidos:</option>
                        <option value="cmc">Clientes Con Mas Compras:</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">Tipo de grafico</label>
                    <select class="form-select " name="tipo_grafico" id="tipo_grafico">
                        <option value="line" selected>Lineas</option>
                        <option value="bar">Barras</option>
                        <option value="polarArea">Area Polar</option>
                        <option value="radar">Radar</option>
                        <option value="doughnut">Pastel</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3" id="cantidadcosto" name="cantidadcosto">
                    <label class="form-label is-required">Por Cantidad o Costo(S/.):</label>
                    <select class="form-select " name="repcantidad" id="repcantidad">
                        <option value="cantidad" selected>Cantidad</option>
                        <option value="costo">Costo</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3" id="divnumero" name="divnumero">
                    <label class="form-label is-required">Numero de Resultados:</label>
                    <input type="number" class="form-control " id="numero" name="numero" value="20"
                        min="1" step="1" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <canvas id="myChart" name="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script type="text/javascript">
        document.getElementById('cantidadcosto').style.display = 'none';
        document.getElementById('divnumero').style.display = 'none';
        var tipo = 'line';
        var reporte = 'ccv';
        var empresa = '-1';
        var cantidadcosto = 'cantidad';
        var midatasetV = @json($datosventas);
        var midatasetC = @json($datoscompras);
        var midatasetT = @json($datoscotizacions);
        var midatasetP = [];
        var midatasetCL = [];
        var traer = 20;
        titulov = "VENTAS";
        tituloc = "COMPRAS";
        titulot = "COTIZACIONES";
        titulop = "PRODUCTOS MAS VENDIDOS";
        titulocl = "CLIENTES CON MAS COMPRAS";

        var labelsF = @json($fechas);

        const graph = document.querySelector("#myChart");
        llamarydibujargraficoccv(labelsF, tipo, titulov, tituloc, titulot, midatasetV, midatasetC, midatasetT, graph);

        function midataset(mitipo, mititulo, midataset, micolor) {
            const dataset = {
                type: mitipo,
                label: mititulo,
                data: midataset,
                borderColor: micolor,
                backgroundColor: micolor,
                fill: false,
                tension: 0.1
            };
            return dataset;
        }

        function midata(milabelsF, midatasetx) {
            const data = {
                labels: milabelsF,
                datasets: midatasetx
            };
            return data;
        }

        function miconfig(midata) {
            const config = {
                // type: 'bar',
                data: midata,
            };
            return config;
        }

        function migrafico(migraph, miconfig) {
            if (window.grafica) {
                window.grafica.clear();
                window.grafica.destroy();
            }
            window.grafica = new Chart(migraph, miconfig);
        }
        //para las compras,ventas y cotizaciones
        function llamarydibujargraficoccv(D_labelsF, D_tipo, D_titulov, D_tituloc, D_titulot, D_midatasetV, D_midatasetC,
            D_midatasetT, D_graph) {
            const datasetV = midataset(D_tipo, D_titulov, D_midatasetV, '#53CAD4');
            const datasetC = midataset(D_tipo, D_tituloc, D_midatasetC, '#79EB68');
            const datasetT = midataset(D_tipo, D_titulot, D_midatasetT, '#F59075');
            const datasetx = [datasetV, datasetC, datasetT];
            const data = midata(D_labelsF, datasetx);
            const config = miconfig(data);

            migrafico(D_graph, config);

        }

        function llamarydibujargraficoproductos(D_labelsF, D_tipo, D_titulop, D_midatasetP, D_graph) {
            var color = colores();
            const datasetP = midataset(D_tipo, D_titulop, D_midatasetP, color);
            const datasetx = [datasetP];
            const data = midata(D_labelsF, datasetx);
            const config = miconfig(data);

            migrafico(D_graph, config);
        }

        $("#company_id").change(function() {
            var company = $(this).val();
            empresa = company;
            var urlbalance = "{{ url('admin/reporte/obtenerbalance') }}";
            $.get(urlbalance + '/' + company, function(data) {
                document.getElementById('verIngresomes').innerHTML = "S/." + (parseFloat(data.ingresomes)
                    .toFixed(2));
                document.getElementById('verIngresosemana').innerHTML = "S/." + (parseFloat(data
                    .ingresosemana).toFixed(2));
                document.getElementById('verIngresodia').innerHTML = "S/." + (parseFloat(data.ingresodia)
                    .toFixed(2));
                document.getElementById('verVentames').innerHTML = "S/." + (parseFloat(data.ventames)
                    .toFixed(2));
                document.getElementById('verVentasemana').innerHTML = "S/." + (parseFloat(data.ventasemana)
                    .toFixed(2));
                document.getElementById('verVentadia').innerHTML = "S/." + (parseFloat(data.ventadia)
                    .toFixed(2));
                document.getElementById('verCotizacionmes').innerHTML = "S/." + (parseFloat(data
                    .cotizacionmes).toFixed(2));
                document.getElementById('verCotizacionsemana').innerHTML = "S/." + (parseFloat(data
                    .cotizacionsemana).toFixed(2));
                document.getElementById('verCotizaciondia').innerHTML = "S/." + (parseFloat(data
                    .cotizaciondia).toFixed(2));

                document.getElementById('verProductomes').innerHTML = (data.productomes);
                document.getElementById('verProductominimo').innerHTML = (data.productominimo);
                document.getElementById('verProductosin').innerHTML = (data.productosinstock);
            });

            obtenerreporte();
        });

        $("#tipo_grafico").change(function() {
            var tipografico = $(this).val();
            tipo = tipografico;
            if (reporte == 'ccv') {
                llamarydibujargraficoccv(labelsF, tipo, titulov, tituloc, titulot, midatasetV, midatasetC,
                    midatasetT, graph);
            } else if (reporte == 'pmv') {
                llamarydibujargraficoproductos(labelsF, tipo, titulop, midatasetP, graph);
            } else if (reporte == 'cmc') {
                llamarydibujargraficoproductos(labelsF, tipo, titulocl, midatasetCL, graph);
            }
        });
        $("#reporte").change(function() {
            var Vreporte = $(this).val();
            reporte = Vreporte;
            if (Vreporte == "cmc") {
                document.getElementById('cantidadcosto').style.display = 'inline';
                document.getElementById('divnumero').style.display = 'inline';
            } else if (Vreporte == "pmv") {
                document.getElementById('cantidadcosto').style.display = 'none';
                document.getElementById('divnumero').style.display = 'inline';
            } else {
                document.getElementById('cantidadcosto').style.display = 'none';
                document.getElementById('divnumero').style.display = 'none';
            }
            obtenerreporte();

        });
        $("#repcantidad").change(function() {
            var cant = $(this).val();
            cantidadcosto = cant;
            obtenerreporte();

        });
        $("#numero").change(function() {
            var cant = $(this).val();
            if (cant > 0) {
                traer = cant;
                obtenerreporte();
            }

        });

        function obtenerreporte() {
            //para las compras,, cotizaciones y ventas
            if (reporte == 'ccv') {
                var urldatosgrafico = "{{ url('admin/reporte/obtenerdatosgrafico') }}";
                $.get(urldatosgrafico + '/' + empresa, function(data) {
                    labelsF = data['fechas'];
                    midatasetV = data['datosventas'];
                    midatasetC = data['datoscompras'];
                    midatasetT = data['datoscotizacions'];

                    llamarydibujargraficoccv(labelsF, tipo, titulov, tituloc, titulot, midatasetV,
                        midatasetC, midatasetT, graph);
                });
            } else if (reporte == 'pmv') {
                var urldatosgrafico = "{{ url('admin/reporte/obtenerproductosmasv') }}";
                $.get(urldatosgrafico + '/' + empresa + '/' + traer, function(data) {
                    labelsF = data['productos'];
                    midatasetP = data['cantidades'];

                    llamarydibujargraficoproductos(labelsF, tipo, titulop, midatasetP, graph);
                });
            } else {
                var urldatosgrafico = "{{ url('admin/reporte/obtenerclientesmasc') }}";
                $.get(urldatosgrafico + '/' + empresa + '/' + cantidadcosto + '/' + traer, function(data) {
                    labelsF = data['clientes'];
                    midatasetCL = data['costos'];

                    llamarydibujargraficoproductos(labelsF, tipo, titulocl, midatasetCL, graph);
                });

            }
        }

        function colores() {
            miscolores = [
                '#53CAD4', '#79EB68', '#F59075', '#ADA9FC', '#6AD9C9', '#B9F081', '#D9B76A', '#FC877C', '#f5938b',
                '#e3b8b2', '#f9df94', '#808f12', '#f4324a', '#17a7a8', '#ff6e4a', '#e33258',
                '#a4f7d4', '#fbc5d8', '#d9764d', '#d1dc5a', '#89fcb3', '#0b8770', '#b1ceaf'
            ];
            return miscolores;
        }
    </script>
    <script></script>
@endpush
