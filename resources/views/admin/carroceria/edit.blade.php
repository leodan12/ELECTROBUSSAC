@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>EDITAR CARROCERIA
                        <a href="{{ url('admin/carroceria') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/carroceria/' . $carroceria->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label is-required">TIPO DE CARROCERIA</label>
                                <input type="text" name="tipocarroceria" class="form-control " required
                                    value="{{ $carroceria->tipocarroceria }}" value="{{ old('tipocarroceria') }}" />
                                @error('tipocarroceria')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <hr>
                            <h4>Agregar Detalle de la Carroceria</h4>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">PRODUCTO</label>
                                <select class="form-select select2 " name="product" id="product">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($productos as $product)
                                        @php $contp=0;    @endphp
                                        @foreach ($detalles as $item)
                                            @if ($product->id == $item->producto_id)
                                                @php $contp++;    @endphp
                                            @endif
                                        @endforeach
                                        @if ($contp == 0)
                                            <option id="miproducto{{ $product->id }}" value="{{ $product->id }}"
                                                data-name="{{ $product->nombre }}" data-unidad="{{ $product->unidad }}">
                                                {{ $product->nombre }}</option>
                                        @else
                                            <option disabled id="miproducto{{ $product->id }}"
                                                value="{{ $product->id }}" data-name="{{ $product->nombre }}"
                                                data-unidad="{{ $product->unidad }}">
                                                {{ $product->nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label" id="labelcantidad">CANTIDAD</label>
                                <input type="number" name="cantidad" id="cantidad" min="1" step="1"
                                    class="form-control " />
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label" id="labelunidad">UNIDAD</label>
                                <input type="text" name="unidad" id="unidad" class="form-control " />
                            </div>
                            @php $ind=0 ; @endphp
                            @php $indice=count($detalles) ; @endphp
                            <button type="button" class="btn btn-info" id="addDetalleBatch"
                                onclick="agregarFila('{{ $indice }}')"><i class="fa fa-plus"></i> Agregar
                                Producto</button>
                            <div class="table-responsive">
                                <table class="table table-row-bordered gy-5 gs-5" id="detallesKit">
                                    <thead class="fw-bold text-primary">
                                        <tr>
                                            <th>PRODUCTO</th>
                                            <th>CANTIDAD</th>
                                            <th>UNIDAD</th>
                                            <th>ELIMINAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $datobd="db" ;  @endphp
                                        @foreach ($detalles as $detalle)
                                            @php $ind++;    @endphp
                                            <tr id="fila{{ $ind }}">
                                                <td>{{ $detalle->nombre }}</td>
                                                <td>{{ $detalle->cantidad }}</td>
                                                <td>{{ $detalle->unidad }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{ $item->id }}', '{{ $item->producto_id }}'  )"
                                                        data-id="0"><i class="bi bi-trash-fill"></i>ELIMINAR</button>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <hr>

                            <div class="col-md-12 mb-3">
                                <button type="submit" class="btn btn-primary text-white float-end">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript">
        var indice = 0;
        var nameproduct = 0;
        var estadoguardar = 0;
        var indicex = 0;
        var misproductos = @json($productos);

        $(document).ready(function() {
            $('.select2').select2();
            $("#btnguardar").prop("disabled", true);

        });

        $("#product").change(function() {
            $("#product option:selected").each(function() {
                $name = $(this).data("name");
                $unidad = $(this).data("unidad");
                var mitasacambio1 = $('[name="tasacambio"]').val();
                document.getElementById('cantidad').value = 1;
                document.getElementById('unidad').value = $unidad;
                nameproduct = $name;
            });
        });

        var indice = 0;
        var pv = 0;

        function agregarFila(indice1) {

            if (pv == 0) {
                indice = indice1;
                pv++;
                indice++;
            } else {
                indice++;
            }

            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var unidad = $('[name="unidad"]').val();

            //alertas para los detallesBatch
            if (!product) {
                alert("Seleccione un producto");
                return;
            }
            if (!cantidad) {
                alert("Ingrese una cantidad");
                return;
            }
            if (!unidad) {
                alert("Ingrese una unidad");
                return;
            }

            var LDetalle = [];
            var tam = LDetalle.length;
            LDetalle.push(product, nameproduct, cantidad, unidad);

            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lproduct[]" value="' + LDetalle[0] + '"required>' + LDetalle[1] +
                '</td><td><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice + '" value="' + LDetalle[
                    2] + '"required>' + LDetalle[2] +
                '</td><td><input  type="hidden" name="Lunidad[]" id="unidad' + indice +
                '" value="' + LDetalle[3] + '"required>' + LDetalle[3] +
                '</td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(' + indice + ',' + 0 + ',' +
                0 + ',' + product + ')" data-id="0">ELIMINAR</button>  </td></tr>';

            $("#detallesKit>tbody").append(filaDetalle);
            indice++;
            limpiarinputs();

            document.getElementById('miproducto' + product).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
        }


        function eliminarFila(ind, lugardato, iddetalle, producto) {
            if (lugardato == "db") {
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
                        var miurl = "{{ url('admin/carroceria/deletedetalle') }}";
                        $.get(miurl + '/' + iddetalle, function(data) {
                            //alert(data[0]);
                            if (data[0] == 1) {
                                Swal.fire({
                                    text: "Registro Eliminado",
                                    icon: "success"
                                });
                                quitarFila(ind);
                            } else if (data[0] == 0) {
                                alert("no se puede eliminar");
                            } else if (data[0] == 2) {
                                alert("registro no encontrado");
                            }
                        });
                    }
                });
            } else {
                quitarFila(ind);
            }
            document.getElementById('miproducto' + producto).disabled = false;
            return false;
        }

        function quitarFila(ind) {

            $('#fila' + ind).remove();
            indice--;

            var funcion = "eliminar";
            botonguardar(funcion);
            return false;
        }

        function limpiarinputs() {
            $('#product').val(null).trigger('change');
            document.getElementById('cantidad').value = null;
            document.getElementById('unidad').value = "";
        }

        function botonguardar(funcion) {

            if (funcion == "eliminar") {
                estadoguardar--;
            } else if (funcion == "agregar") {
                estadoguardar++;
            }
            if (estadoguardar <= 1) {
                $("#btnguardar").prop("disabled", true);
            } else if (estadoguardar > 1) {
                $("#btnguardar").prop("disabled", false);
            }
        }
    </script>
@endpush
