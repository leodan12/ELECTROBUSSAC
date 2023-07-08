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
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12  ">
            <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                    <div class="me-md-3 me-xl-5">
                        @if (session('message'))
                            <h2 class="alert alert-success">{{ session('message') }}</h2>
                        @endif
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-sm-3">
                    <div class="card ingresos borde">
                        <div class="card-body">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h6>COMPRAS DEL MES: </h6>
                                    </div>
                                    <div class="col-3 centro">
                                        <h6 id="verIngresomes"> </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Al Contado:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verIngresocontado"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>A Credito:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verIngresocredito"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Por Pagar:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verIngresoxpagar"> </h5>
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
                                    <div class="col-9" style="text-align: left">
                                        <h6>VENTAS DEL MES: </h6>
                                    </div>
                                    <div class="col-3 centro">
                                        <h6 id="verVentames"> </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Al Contado:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verVentacontado"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>A Credito:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verVentacredito"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Por Cobrar:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verVentaxpagar"> </h5>
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
                                    <div class="col-10" style="text-align: left">
                                        <h6>COTIZACIONES DEL MES: </h6>
                                    </div>
                                    <div class="col-2 centro">
                                        <h6 id="verCotizacionmes"> </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Al Contado:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verCotizacioncontado"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>A Credito:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verCotizacioncredito"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Vendidas:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verCotizacionvendida"> </h5>
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
                                    <div class="col-10" style="text-align: left">
                                        <h6>TOTAL PRODUCTOS:
                                        </h6>
                                    </div>
                                    <div class="col-2 centro">
                                        <h6 id="verProducto"> </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-text">
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>En Stock:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verProductostock"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>En Stock Min:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verProductominimo"> </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-9" style="text-align: left">
                                        <h5>Sin Stock:</h5>
                                    </div>
                                    <div class="col-3" style="text-align: center">
                                        <h5 id="verProductosin"> </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            @if ($sinstock > 0)
                <div class="row">
                    <div class="col">
                        <div class="alert alert-danger" role="alert">

                            <h2 class="alert-heading"><svg xmlns="http://www.w3.org/2000/svg" style="width: 40px;"
                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                    role="img" aria-label="Warning:">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                ¡PRODUCTOS SIN STOCK!
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </h2>
                            Tienes {{ $sinstock }} productos Con Stock Minimo y sin stock &nbsp;&nbsp;
                            <a class="btn btn-info" href="{{ url('admin/inventario2') }}" style="border-radius: 20px;">
                                <i class="mdi mdi-eye menu-icon"> VER PRODUCTOS</i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @if ($ingresosxpagar > 0)
                <div class="row">
                    <div class="col">
                        <div class="alert alert-danger" role="alert">
                            <h2 class="alert-heading"><svg xmlns="http://www.w3.org/2000/svg" style="width: 40px;"
                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                    role="img" aria-label="Warning:">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                COMPRAS O INGRESOS POR PAGAR!
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </h2>
                            Tienes {{ $ingresosxpagar }} Ingresos Por Pagar &nbsp;&nbsp;
                            <a class="btn btn-info" href="{{ url('admin/ingreso2') }}" style="border-radius: 20px;">
                                <i class="mdi mdi-eye menu-icon"> VER INGRESOS</i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            @if ($ventasxcobrar > 0)
                <div class="row">
                    <div class="col">
                        <div class="alert alert-danger" role="alert">
                            <h2 class="alert-heading"><svg xmlns="http://www.w3.org/2000/svg" style="width: 40px;"
                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16"
                                    role="img" aria-label="Warning:">
                                    <path
                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </svg>
                                VENTAS POR COBRAR!
                                <button type="button" class="btn-close float-end" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </h2>
                            Tienes {{ $ventasxcobrar }} Ventas Por Cobrar &nbsp;&nbsp;
                            <a class="btn btn-info" href="{{ url('admin/venta2') }}" style="border-radius: 20px;">
                                <i class="mdi mdi-eye menu-icon"> VER VENTAS</i>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var urlbalance = "{{ url('admin/reporte/balancemensualinicio') }}";
            $.get(urlbalance, function(data) {
                document.getElementById('verIngresomes').innerHTML = (data.ingresomes);
                document.getElementById('verIngresocredito').innerHTML = (data.ingresocredito);
                document.getElementById('verIngresocontado').innerHTML = (data.ingresocontado);
                document.getElementById('verIngresoxpagar').innerHTML = (data.ingresoxpagar);

                document.getElementById('verVentames').innerHTML = (data.ventames);
                document.getElementById('verVentacredito').innerHTML = (data.ventacredito);
                document.getElementById('verVentacontado').innerHTML = (data.ventacontado);
                document.getElementById('verVentaxpagar').innerHTML = (data.ventaxpagar);

                document.getElementById('verCotizacionmes').innerHTML = (data.cotizacionmes);
                document.getElementById('verCotizacioncredito').innerHTML = (data.cotizacioncredito);
                document.getElementById('verCotizacioncontado').innerHTML = (data.cotizacioncontado);
                document.getElementById('verCotizacionvendida').innerHTML = (data.cotizacionvendida);

                document.getElementById('verProducto').innerHTML = (data.producto);
                document.getElementById('verProductostock').innerHTML = (data.productostock);
                document.getElementById('verProductominimo').innerHTML = (data.productominimo);
                document.getElementById('verProductosin').innerHTML = (data.productosin);
            });
        });
    </script>
@endpush
