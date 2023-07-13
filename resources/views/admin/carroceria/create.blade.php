@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>AÑADIR CARROCERIA
                        <a href="{{ url('admin/carroceria') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/carroceria') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label is-required">TIPO DE CARROCERIA</label>
                                <input type="text" name="tipocarroceria" class="form-control " required
                                    value="{{ old('tipocarroceria') }}" />
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
                                        <option id="miproducto{{ $product->id }}" value="{{ $product->id }}"
                                            data-name="{{ $product->nombre }}" data-unidad="{{ $product->unidad }}">
                                            {{ $product->nombre }}</option>
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
                            <button type="button" class="btn btn-info" id="addDetalleBatch"><i class="fa fa-plus"></i>
                                Agregar Producto </button>
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
                                        <tr></tr>
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
        $('#addDetalleBatch').click(function() {

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
                '</td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(' + indice + ',' +
                product +
                ')" data-id="0">ELIMINAR</button></td></tr>';

            $("#detallesKit>tbody").append(filaDetalle);

            indice++;
            limpiarinputs();

            document.getElementById('miproducto' + product).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
        });

         
        function eliminarFila(ind, product) {
           
            $('#fila' + ind).remove();
            indice--;
            // damos el valor
          
            document.getElementById('miproducto' + product).disabled = false;
            
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
