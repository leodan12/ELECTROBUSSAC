@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <p>Corrige los siguientes errores:</p>
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="card">
                <div class="card-header">
                    <h4>AÑADIR UN PRECIO PARA UN CLIENTE
                        <a href="{{ url('admin/listaprecios') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/listaprecios') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">Seleccionar Producto</label>
                                <select name="product_id" id="product_id" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @foreach ($product as $prod)
                                        <option value="{{ $prod->id }}" data-moneda="{{ $prod->moneda }}">
                                            {{ $prod->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">Seleccionar Cliente</label>
                                <select name="cliente_id" id="cliente_id" class="form-select select2" required disabled>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                </select>
                                @error('cliente_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required ">PRECIO</label>
                                <input type="number" name="preciounitariomo" class="form-control" step="0.01"
                                    required />
                                @error('preciounitariomo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">Seleccionar Moneda</label>
                                <select name="moneda" id="moneda" class="form-select " required disabled>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                </select>
                            </div>


                            <div class="col-md-12 mb-3">
                                <button type="submit" class="btn btn-primary text-white float-end">Guardar</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        var clientes;
        $(document).ready(function() {
            $('.select2').select2({});
            clientes = @json($cliente);
        });
        $("#product_id").change(function() {
            $("#product_id option:selected").each(function() {
                var product = $(this).val();
                if (product) { 
                    $moneda = $(this).data("moneda");
                    console.log($moneda);
                    var urlvent = "{{ url('admin/listaprecios/clientesxproducto') }}";
                    $.get(urlvent + '/' + product, function(data) {
                        var cliente_select =
                            '<option value="" disabled selected>Seleccione una opción</option>'
                        for (var x = 0; x < clientes.length; x++) {
                            var disabled = "";
                            for (var i = 0; i < data.length; i++) {
                                if (clientes[x].id == data[i].id) {
                                    disabled = " disabled ";
                                }
                            }
                            cliente_select += '<option value="' + clientes[x].id + '"' +
                                disabled + '  >' + clientes[x].nombre + '</option>';
                        }
                        $("#cliente_id").html(cliente_select);
                        $('#cliente_id').removeAttr('disabled');
                    });
                    var moneda_select = '<option value="" disabled >Seleccione una opción</option>'
                    if ($moneda == "soles") {
                        moneda_select += '<option value="' + $moneda + '" selected> Soles </option>';
                    } else {
                        moneda_select += '<option value="' + $moneda + '" selected> Dolares </option>';
                    }
                    $("#moneda").html(moneda_select);
                    $('#moneda').removeAttr('disabled');

                }
            });

        });
    </script>
@endpush
