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
                        <div class="row">
                            <div class="col">
                                <h4 id="mititulo">REGISTRO DE VENTAS ANTIGUAS:
                                </h4>
                            </div>

                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped " id="mitabla" name="mitabla">
                                <thead class="fw-bold text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>ACCIONES</th>
                                        <th>TIPO</th>
                                        <th>PRODUCTO</th>
                                        <th>PRECIO UNIT SIN IGV</th>
                                        <th>PRECIO TOTAL SIN IGV</th>
                                        <th>MONEDA</th>
                                        <th>CANTIDAD</th>
                                        <th>UNIDAD </th>
                                        <th>FACTURA </th>
                                        <th>FECHA </th>
                                        <th>CLIENTE </th>
                                        <th>EMPRESA </th>
                                        <th>CODIGO </th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mantenimientos">

                                </tbody>
                            </table>

                        </div>
                    </div>
                    {{-- modal paera ver la venta --}}
                    <div class="modal fade " id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Venta</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class=" col-md-3   mb-3">
                                                <label for="verTIPO" class="col-form-label">TIPO:</label>
                                                <input type="text" class="form-control" id="verTIPO" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verFACTURA" class="col-form-label">FACTURA:</label>
                                                <input type="text" class="form-control " id="verFACTURA" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3" id="divobservacion">
                                                <label for="verFECHA" class="col-form-label">FECHA:</label>
                                                <input type="text" class="form-control " id="verFECHA" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verCODIGO" class="col-form-label">CODIGO:</label>
                                                <input type="text" class="form-control " id="verCODIGO" readonly>
                                            </div>
                                            <div class=" col-md-6   mb-3">
                                                <label for="verPRODUCTO" class="col-form-label">PRODUCTO:</label>
                                                <input type="text" class="form-control" id="verPRODUCTO" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3 " id="divfechav">
                                                <label for="verPUNITSIGV" class="col-form-label">PRECIO UNITARIO SIN
                                                    IGV:</label>
                                                <input type="text" class="form-control" id="verPUNITSIGV" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verPTOTSIGV" class="col-form-label">PRECIO TOTAL SIN
                                                    IGV:</label>
                                                <input type="text" class="form-control " id="verPTOTSIGV" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3" id="divtasacambio">
                                                <label for="verMONEDA" class="col-form-label">MONEDA:</label>
                                                <input type="text" class="form-control " id="verMONEDA" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verCANTIDAD" class="col-form-label">CANTIDAD:</label>
                                                <input type="text" class="form-control " id="verCANTIDAD" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verUNIDAD" class="col-form-label">UNIDAD:</label>
                                                <input type="text" class="form-control " id="verUNIDAD" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verBOLETAFACTURA" class="col-form-label">BOLETA/FACTURA:</label>
                                                <input type="text" class="form-control " id="verBOLETAFACTURA" readonly>
                                            </div>
                                            <div class=" col-md-6   mb-3">
                                                <label for="verCLIENTE" class="col-form-label">CLIENTE:</label>
                                                <input type="text" class="form-control " id="verCLIENTE" readonly>
                                            </div>
                                            <div class=" col-md-6   mb-3">
                                                <label for="verEMPRESA" class="col-form-label">EMPRESA:</label>
                                                <input type="text" class="form-control " id="verEMPRESA" readonly>
                                            </div>
                                            <div class=" col-md-3   mb-3">
                                                <label for="verDEVOLUCION" class="col-form-label">DEVOLUCION:</label>
                                                <input type="text" class="form-control " id="verDEVOLUCION" readonly>
                                            </div>


                                            <div class=" col-md-9   mb-3">
                                                <label for="verDETALLE" class="col-form-label">DETALLE:</label>
                                                <input type="text" class="form-control " id="verDETALLE" readonly>
                                            </div>

                                        </div>
                                    </form>

                                </div>
                                <div class="modal-footer">
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
    <script src="{{ asset('admin/jsusados/midatatable.js') }}"></script>
    <script>
        $(document).ready(function() {

            var tabla = "#mitabla";
            var ruta = "{{ route('ventasantiguas.index') }}"; //darle un nombre a la ruta index
            var columnas = [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'acciones',
                    name: 'acciones',
                    searchable: false,
                    orderable: false,
                },
                {
                    data: 'tipo',
                    name: 'tipo'
                },
                {
                    data: 'producto',
                    name: 'producto'
                },
                {
                    data: 'preciounitatiosinigv',
                    name: 'preciounitatiosinigv'
                },
                {
                    data: 'preciototalsinigv',
                    name: 'preciototalsinigv'
                },
                {
                    data: 'moneda',
                    name: 'moneda'
                },
                {
                    data: 'cantidad',
                    name: 'cantidad'
                },
                {
                    data: 'unidad',
                    name: 'unidad'
                },
                {
                    data: 'factura',
                    name: 'factura'
                },
                {
                    data: 'fecha',
                    name: 'fecha'
                },
                {
                    data: 'cliente',
                    name: 'cliente'
                },
                {
                    data: 'empresa',
                    name: 'empresa'
                },
                {
                    data: 'codigo',
                    name: 'codigo'
                },

            ];
            var btns = 'lfrtip';

            iniciarTablaIndex(tabla, ruta, columnas, btns);

        });
        //para borrar un registro de la tabla
        $(document).on('click', '.btnborrar', function(event) {
            const idregistro = event.target.dataset.idregistro;
            var urlregistro = "{{ url('admin/ventasantiguas') }}";
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
                                $('#modalCreditos1').modal('hide');
                                recargartabla();
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

        //para el modal ver venta 
        var nrocreditos = 0;
        var hoy = new Date();
        var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' + String(hoy
            .getDate()).padStart(2, '0');
        var inicializartabla = 0;

        var numerocreditos = 0;
        const mimodal = document.getElementById('mimodal');
        mimodal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            var urlventa = "{{ url('admin/ventasantiguas/show') }}";
            $.get(urlventa + '/' + id, function(data) {
                console.log(data);
                const modalTitle = mimodal.querySelector('.modal-title')
                modalTitle.textContent = `Ver Registro ${id}`;

                document.getElementById("verPUNITSIGV").value = data[0].preciounitatiosinigv;
                document.getElementById("verPTOTSIGV").value = data[0].preciototalsinigv;
                document.getElementById("verMONEDA").value = data[0].moneda
                document.getElementById("verCANTIDAD").value = data[0].cantidad;
                document.getElementById("verUNIDAD").value = data[0].unidad;
                document.getElementById("verFACTURA").value = data[0].factura;
                document.getElementById("verFECHA").value = data[0].fecha;
                document.getElementById("verEMPRESA").value = data[0].empresa;
                document.getElementById("verCLIENTE").value = data[0].cliente;
                document.getElementById("verDEVOLUCION").value = data[0].devolucion;
                document.getElementById("verCODIGO").value = data[0].codigo
                document.getElementById("verBOLETAFACTURA").value = data[0].boletafactura;
                document.getElementById("verDETALLE").value = data[0].detalle;
                document.getElementById("verPRODUCTO").value = data[0].producto;
                document.getElementById("verTIPO").value = data[0].tipo;

            });
        });
    </script>
@endpush
