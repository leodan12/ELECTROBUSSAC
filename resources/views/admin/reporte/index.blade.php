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
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12  ">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label is-required">EMPRESA</label>
                    <select class="form-select select2  borde" name="company_id" id="company_id" required>
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
                <div class="col">
                    <div>
                        <label for="">ventas del mes en soles</label>
                        <canvas id="myChart" name="myChart"></canvas>
                    </div>
                </div>
            </div>



        </div>
    </div>
@endsection


@push('script')
    <script type="text/javascript">
        var labelsF = @json($fechas);
        var midatasetV = @json($datosventas);
        var midatasetC = @json($datoscompras);
        var midatasetT = @json($datoscotizacions);
        titulov = "VENTAS";
        tituloc = "COMPRAS";
        titulot = "COTIZACIONES";
        const datasetV = midataset(titulov, midatasetV, '#53CAD4');
        const datasetC = midataset(tituloc, midatasetC, '#79EB68');
        const datasetT = midataset(titulot, midatasetT, '#F59075');
        const graph = document.querySelector("#myChart");
        const data = {
            labels: labelsF,
            datasets: [datasetV, datasetC, datasetT]
        };
        const config = {
            type: 'line',
            data: data,
        };
        new Chart(graph, config);

        function midataset(titulo, midataset, color) {
            const dataset = {
                label: titulo,
                data: midataset,
                borderColor: color,
                fill: false,
                tension: 0.1
            };
            return dataset;
        }

        $("#company_id").change(function() {
            var company = $(this).val();
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
        });
    </script>
    <script></script>
@endpush
