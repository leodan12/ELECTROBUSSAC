@extends('layouts.admin')

@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">

                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>REGISTRO DE COTIZACIÓN
                            @can('crear-cotizacion')
                                <a href="{{ url('admin/cotizacion/create') }}" class="btn btn-primary float-end">Añadir
                                    Cotización</a>
                            @endcan
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="mitabla" name="mitabla">
                                <thead>
                                    <tr class="fw-bold text-primary ">
                                        <th>ID</th>
                                        <th>NUMERO</th>
                                        <th>FECHA</th>
                                        <th>CLIENTE</th>
                                        <th>EMPRESA</th>
                                        <th>MONEDA</th>
                                        <th>FORMA PAGO</th>
                                        <th>COSTO SIN IGV</th>
                                        <th>COSTO CON IGV</th>
                                        <th>VENDIDA </th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal fade " id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Cotización</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-3   mb-3">
                                                <label for="verFecha" class="col-form-label">FECHA:</label>
                                                <input type="text" class="form-control " id="verFecha" readonly>
                                            </div>
                                            <div class="col-md-3   mb-3">
                                                <label for="verFechav" class="col-form-label">FECHA VALIDEZ:</label>
                                                <input type="text" class="form-control " id="verFechav" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verMoneda" class="col-form-label">MONEDA:</label>
                                                <input type="text" class="form-control " id="verMoneda" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3" id="divtasacambio">
                                                <label for="verTipocambio" class="col-form-label">TIPO DE CAMBIO:</label>
                                                <input type="text" class="form-control " id="verTipocambio" readonly>
                                            </div>
                                            <div class=" col-md-6   mb-3">
                                                <label for="verEmpresa" class="col-form-label">EMPRESA:</label>
                                                <input type="text" class="form-control " id="verEmpresa" readonly>
                                            </div>
                                            <div class=" col-md-6   mb-3">
                                                <label for="verCliente" class="col-form-label">CLIENTE:</label>
                                                <input type="text" class="form-control " id="verCliente" readonly>
                                            </div>
                                            <div class=" col-md-9   mb-3" id="divobservacion">
                                                <label for="verObservacion" class="col-form-label">OBSERVACION:</label>
                                                <input type="text" class="form-control " id="verObservacion" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3" id="divobservacion">
                                                <label for="verFormapago" class="col-form-label">FORMA DE PAGO:</label>
                                                <input type="text" class="form-control " id="verFormapago" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3" name="divdiascredito" id="divdiascredito">
                                                <label for="verPersona" class="col-form-label">DIAS DE CREDITO PARA LA
                                                    COMPRA:</label>
                                                <input type="text" class="form-control " id="verDiascredito" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <div class="input-group">
                                                    <label for="verPrecioventasinigv"
                                                        class="col-form-label input-group">PRECIO SIN IGV:</label>
                                                    <span class="input-group-text" id="spancostoventasinigv"></span>
                                                    <input type="text" class="form-control " id="verPrecioventasinigv"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <div class="input-group">
                                                    <label for="verPrecioventaconigv"
                                                        class="col-form-label input-group">PRECIO CON IGV:</label>
                                                    <span class="input-group-text" id="spancostoventaconigv"></span>
                                                    <input type="text" class="form-control " id="verPrecioventaconigv"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verVendida" class="col-form-label">COTIZACIÓN VENDIDA:</label>
                                                <input type="text" class="form-control " id="verVendida" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3" name="divpersona" id="divpersona">
                                                <label for="verPersona" class="col-form-label">SOLICITANTE:</label>
                                                <input type="text" class="form-control " id="verPersona" readonly>
                                            </div>

                                        </div>
                                    </form>

                                    <div class="row justify-content-center">
                                        <div class="col-lg-12">
                                            <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                                            <nav class="borde" style="border-radius: 5px; ">
                                                <div class="nav nav-pills nav-justified" id="nav-tab" role="tablist">

                                                    <button class="nav-link active" id="nav-detalles-tab"
                                                        data-bs-toggle="tab" data-bs-target="#nav-detalles"
                                                        type="button" role="tab" aria-controls="nav-detalles"
                                                        aria-selected="false">Detalles</button>
                                                    <button class="nav-link " id="nav-condiciones-tab"
                                                        data-bs-toggle="tab" data-bs-target="#nav-condiciones"
                                                        type="button" role="tab" aria-controls="nav-condiciones"
                                                        aria-selected="false">Condiciones</button>
                                                </div>
                                            </nav>
                                            <hr style="border: 0; height: 0; box-shadow: 0 2px 5px 2px rgb(0, 89, 255);">
                                            <div class="tab-content" id="nav-tabContent">

                                                <div class="tab-pane fade show active" id="nav-detalles" role="tabpanel"
                                                    aria-labelledby="nav-detalles-tab" tabindex="0">
                                                    <br>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped"
                                                            id="detallesventa">
                                                            <thead class="fw-bold text-primary">
                                                                <tr>
                                                                    <th>Producto</th>
                                                                    <th>Observacion</th>
                                                                    <th>Cantidad</th>
                                                                    <th>Precio Unitario(referencial)</th>
                                                                    <th>precio Unitario</th>
                                                                    <th>Servicio Adicional</th>
                                                                    <th>Costo Productos</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr></tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade show " id="nav-condiciones" role="tabpanel"
                                                    aria-labelledby="nav-condiciones-tab" tabindex="0">
                                                    <br>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped"
                                                            id="condiciones">
                                                            <thead class="fw-bold text-primary">
                                                                <tr>
                                                                    <th>Condicion</th>
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" id="generarcotizacion"> Generar Pdf de
                                        la Cotización </button>
                                    <div id="btnvender" name="btnvender">
                                        @can('editar-cotizacion')
                                            <button type="button" class="btn btn-warning" id="realizarventa"
                                                onclick="venderCotizacion()">Realizar
                                                Venta</button>
                                        @endcan
                                    </div>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset('admin/midatatable.js') }}"></script>
    <script>
        $(document).ready(function() {
            var tabla = "#mitabla";
            var ruta = "{{ route('cotizacion.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'numero',
                    name: 'numero'
                },
                {
                    data: 'fecha',
                    name: 'fecha'
                },

                {
                    data: 'cliente',
                    name: 'cl.nombre'
                },
                {
                    data: 'empresa',
                    name: 'e.nombre'
                },
                {
                    data: 'moneda',
                    name: 'moneda'
                },
                {
                    data: 'formapago',
                    name: 'formapago'
                },
                {
                    data: 'costoventasinigv',
                    name: 'costoventasinigv'
                },
                {
                    data: 'costoventaconigv',
                    name: 'costoventaconigv'
                },
                {
                    data: 'vendida',
                    name: 'vendida'
                },
                {
                    data: 'acciones',
                    name: 'acciones',
                    searchable: false,
                    orderable: false,
                },
            ];
            var btns = 'lfrtip';

            iniciarTablaIndex(tabla, ruta, columnas, btns);

        });
        //para borrar un registro de la tabla
        $(document).on('click', '.btnborrar', function(event) {
            const idregistro = event.target.dataset.idregistro;
            var urlregistro = "{{ url('admin/cotizacion') }}";
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
                    $.ajax({
                        type: "GET",
                        url: urlregistro + '/' + idregistro + '/delete',
                        success: function(data1) {
                            if (data1 == "1") {
                                $(event.target).closest('tr').remove();
                                Swal.fire({
                                    icon: "success",
                                    text: "Registro Eliminado",
                                });
                            } else if (data1 == "0") {
                                Swal.fire({
                                    icon: "error",
                                    text: "Registro No Eliminado",
                                });
                            } else if (data1 == "2") {
                                Swal.fire({
                                    icon: "error",
                                    text: "Registro No Encontrado",
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>
    <script>
        var idventa = "";
        const mimodal = document.getElementById('mimodal')
        mimodal.addEventListener('show.bs.modal', event => {

            const button = event.relatedTarget
            const id = button.getAttribute('data-id')
            var urlventa = "{{ url('admin/cotizacion/show') }}";
            $.get(urlventa + '/' + id, function(midata) {
                const modalTitle = mimodal.querySelector('.modal-title')
                modalTitle.textContent = `Ver Cotizacion Nro: ` + midata[0].numero;
                idventa = id;
                document.getElementById("verFecha").value = midata[0].fecha;
                document.getElementById("verFechav").value = midata[0].fechav;
                document.getElementById("verMoneda").value = midata[0].moneda;
                document.getElementById("verEmpresa").value = midata[0].company;
                document.getElementById("verCliente").value = midata[0].cliente
                document.getElementById("verVendida").value = midata[0].vendida;
                document.getElementById("verFormapago").value = midata[0].formapago;
                document.getElementById("verPrecioventasinigv").value = midata[0].costoventasinigv;
                document.getElementById("verPrecioventaconigv").value = midata[0].costoventaconigv;
                if (midata[0].moneda == "dolares") {
                    document.getElementById('spancostoventasinigv').innerHTML = "$";
                    document.getElementById('spancostoventaconigv').innerHTML = "$";
                } else if (midata[0].moneda == "soles") {
                    document.getElementById('spancostoventasinigv').innerHTML = "S/.";
                    document.getElementById('spancostoventaconigv').innerHTML = "S/.";
                }
                if (midata[0].persona == null) {
                    document.getElementById('divpersona').style.display = 'none';
                } else {
                    document.getElementById('divpersona').style.display = 'inline';
                    document.getElementById("verPersona").value = midata[0].persona;
                }
                if (midata[0].diascredito == null) {
                    document.getElementById('divdiascredito').style.display = 'none';
                } else {
                    document.getElementById('divdiascredito').style.display = 'inline';
                    document.getElementById("verDiascredito").value = midata[0].diascredito;
                }
                if (midata[0].vendida == "SI") {
                    document.getElementById('btnvender').style.display = 'none';
                } else if (midata[0].vendida == "NO") {
                    document.getElementById('btnvender').style.display = 'inline';
                }

                document.getElementById("verTipocambio").value = midata[0].tasacambio;

                if (midata[0].observacion == null) {
                    document.getElementById('divobservacion').style.display = 'none';
                } else {
                    document.getElementById('divobservacion').style.display = 'inline';
                    document.getElementById("verObservacion").value = midata[0].observacion;
                }


                var monedafactura = midata[0].moneda;
                var simbolomonedaproducto = "";
                var simbolomonedafactura = "";


                if (monedafactura == "dolares") {
                    simbolomonedafactura = "$";
                } else if (monedafactura == "soles") {
                    simbolomonedafactura = "S/.";
                }

                var tabla = document.getElementById(detallesventa);
                $('#detallesventa tbody tr').slice().remove();
                for (var ite = 0; ite < midata.length; ite++) {
                    var monedaproducto = midata[ite].monedaproducto;
                    if (monedaproducto == "dolares") {
                        simbolomonedaproducto = "$";
                    } else if (monedaproducto == "soles") {
                        simbolomonedaproducto = "S/.";
                    }
                    var obsproducto = "";
                    if (midata[ite].observacionproducto != null) {
                        obsproducto = midata[ite].observacionproducto;
                    }

                    if (midata[ite].tipo == 'kit') {

                        var urlventa = "{{ url('admin/venta/productosxkit') }}";
                        $.ajax({
                            type: "GET",
                            url: urlventa + '/' + midata[ite].idproducto,
                            async: false,
                            data: {
                                id: id
                            },
                            success: function(data1) {
                                var milista = '<br>';
                                var puntos = ': ';
                                for (var j = 0; j < data1.length; j++) {
                                    var coma = '<br>';
                                    milista = milista + '-' + data1[j].cantidad + ' ' + data1[j]
                                        .producto + coma;
                                }
                                filaDetalle = '<tr id="fila' + ite +
                                    '"><td> <b>' + midata[ite].producto + '</b>' + puntos +
                                    milista + coma +
                                    '</td><td> ' + midata[ite].observacionproducto +
                                    '</td><td> ' + midata[ite].cantidad +
                                    '</td><td> ' + simbolomonedaproducto + midata[ite]
                                    .preciounitario +
                                    '</td><td> ' + simbolomonedafactura + midata[ite]
                                    .preciounitariomo +
                                    '</td><td> ' + simbolomonedafactura + midata[ite].servicio +
                                    '</td><td> ' + simbolomonedafactura + midata[ite]
                                    .preciofinal +
                                    '</td></tr>';
                                $("#detallesventa>tbody").append(filaDetalle);
                                milista = '<br>';
                            }
                        });

                    } else
                    if (midata[ite].tipo == 'estandar') {
                        filaDetalle = '<tr id="fila' + ite +
                            '"><td> <b>' + midata[ite].producto + '</b>' +
                            '</td><td> ' + midata[ite].observacionproducto +
                            '</td><td> ' + midata[ite].cantidad +
                            '</td><td> ' + simbolomonedaproducto + midata[ite].preciounitario +
                            '</td><td> ' + simbolomonedafactura + midata[ite].preciounitariomo +
                            '</td><td> ' + simbolomonedafactura + midata[ite].servicio +
                            '</td><td> ' + simbolomonedafactura + midata[ite].preciofinal +
                            '</td></tr>';
                        $("#detallesventa>tbody").append(filaDetalle);
                    }
                }
                if (midata[0].vendida == "NO") {
                    var btnventa = document.getElementById('realizarventa')
                    if (btnventa) {
                        btnventa.style.display = 'inline';
                    }
                } else if (midata[0].vendida == "SI") {
                    var btnventa = document.getElementById('realizarventa')
                    if (btnventa) {
                        btnventa.style.display = 'none';
                    }
                }
            });

            var urlcondicion = "{{ url('admin/cotizacion/showcondiciones') }}";
            $.get(urlcondicion + '/' + id, function(data) {
                $('#condiciones tbody tr').slice().remove();
                for (var i = 0; i < data.length; i++) {
                    filaDetalle = '<tr id="filacondicion' + i +
                        '"><td><input  type="hidden" name="LCondicion[]" value="' + data[i].condicion +
                        '"required>' + data[i].condicion +
                        '</td></tr>';
                    $("#condiciones>tbody").append(filaDetalle);
                }
            });

        })
        window.addEventListener('close-modal', event => {
            $('#deleteModal').modal('hide');
        });
        $('#generarcotizacion').click(function() {
            generarfactura(idventa);
        });

        function generarfactura($id) {
            if ($id != -1) {
                window.open('/admin/cotizacion/generarcotizacionpdf/' + $id);
            }
        }

        function venderCotizacion() {
            var urlventa = "{{ url('admin/venta/create2') }}";
            window.location = (urlventa + '/' + idventa);
        }
    </script>
@endpush
