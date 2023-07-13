<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ELECTROBUS S.A.C') }}</title>

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('admin/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/base/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- inject:css -->


    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <!-- endinject -->

    {{-- librerias para el datatables server side --}}
    <link rel="stylesheet" href="{{ asset('admin/jsusados/datatables.min.css') }}">
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"
        rel="stylesheet" /> --}}
    {{-- <link
        href="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/r-2.4.1/datatables.min.css"
        rel="stylesheet" /> --}}





    @livewireStyles
    @stack('css')
</head>

<body>

    @yield('alertax')

    <div class="container-scroller">
        @include('layouts.inc.admin.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('layouts.inc.admin.sidebar')
            <div class="main-panel">
                <div class="content-wrapper">
                    {{-- ------------------ espacio content-------------------------- --}}
                    @yield('content')
                    {{-- ------------------ espacio content -------------------------- --}}

                    <div class="modal fade" id="modaltasacambio" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="modaltasacambioLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modaltasacambioLabel">ACTUALIZAR LA TASA CAMBIO</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label is-required">TASA CAMBIO</label>
                                        <input type="number" name="tasacambiom" min="0" step="0.0001"
                                            class="form-control " id="tasacambiom" />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="actualizartasa();"
                                        class="btn btn-success">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{ asset('admin/vendors/base/vendor.bundle.base.js') }}"></script>

    <script src="{{ asset('admin/js/off-canvas.js') }}"></script>
    <script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('admin/js/template.js') }}"></script>


    {{-- librerias para el datatables serverside --}}
    <script src="{{ asset('admin/jsusados/datatables.min.js') }}"></script>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
     --}}
    {{-- <script
        src="https://cdn.datatables.net/v/bs5/jq-3.6.0/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/r-2.4.1/datatables.min.js">
    </script> --}}

    <!-- js para la alerta-->

    <script src="{{ asset('admin/jsusados/sweetalert.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('admin/css/select2.min.css') }}" />
    <script src="{{ asset('admin/js/select2.min.js') }}"></script>

    <script type="text/javascript">
        var hoy = new Date();
        var iddato = -1;
        var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' +
            String(hoy.getDate()).padStart(2, '0');

        function vertasacambio() {
            var myModal = new bootstrap.Modal(document.getElementById('modaltasacambio'), {
                keyboard: false
            })
            var urlvertasacambio = "{{ url('admin/dato/vertasacambio') }}";
            $.ajax({
                type: "GET",
                url: urlvertasacambio,
                async: false,
                success: function(data) {
                    if (fechaActual > data['fecha']) {
                        myModal.show();
                        iddato = data['id'];
                        document.getElementById('tasacambiom').value = data['valor'];
                    }
                }
            });
        }

        function actualizartasa() { 
            var urlupdate = "{{ url('admin/dato/actualizartasacambio') }}";
            var mitasacambio = document.getElementById('tasacambiom').value;
            $.ajax({
                type: "GET",
                url: urlupdate + '/' + mitasacambio + '/' + fechaActual + '/' + iddato,
                async: false,
                success: function(data) {
                    if (data == "1") {
                        $('#modaltasacambio').modal('hide');
                        Swal.fire({
                            icon: "success",
                            text: "Tasa de cambio Actualizada",
                        });
                    } else if (data == "0") {
                        Swal.fire({
                            icon: "error",
                            text: "No se Actualizó la tasa de cambio",
                        });
                    } else if (data == "2") {
                        Swal.fire({
                            icon: "error",
                            text: "No se Encontró la tasa de cambio",
                        });
                    }
                }
            });
        }

        function vertasa() {
            var myModal = new bootstrap.Modal(document.getElementById('modaltasacambio'), {
                keyboard: false
            })
            var urlpe = "{{ url('admin/dato/vertasacambio') }}";
            $.ajax({
                type: "GET",
                url: urlpe,
                async: false,
                success: function(data) {
                    myModal.show();
                    iddato = data['id'];
                    document.getElementById('tasacambiom').value = data['valor'];

                }
            });
        }

        function traertasacambio() {

            var urlvertasacambio = "{{ url('admin/dato/vertasacambio') }}";
            $.ajax({
                type: "GET",
                url: urlvertasacambio,
                async: false,
                success: function(data) {
                    document.getElementById('tasacambio').value = data['valor'];
                }
            });
        }
    </script>

    @livewireScripts
    @stack('script')
    @yield('js')


</body>

</html>
