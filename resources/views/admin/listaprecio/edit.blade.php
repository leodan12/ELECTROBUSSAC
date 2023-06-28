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
                    <h4>EDITAR EL PRECIO
                        <a href="{{ url('admin/listaprecios') }}" id="btnvolver" name="btnvolver"
                            class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/listaprecios/' . $precio->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">Seleccionar Producto</label>
                                <select name="product_id" id="product_id" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccionar</option>
                                    @foreach ($product as $prod)
                                        <option value="{{ $prod->id }}" {{ $prod->id == $precio->product_id ? 'selected' : '' }}> {{ $prod->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">Seleccionar Cliente</label>
                                <select name="cliente_id" id="cliente_id" class="form-select select2" required>
                                    <option value="" selected disabled>Seleccionar</option>
                                    @foreach ($cliente as $clie)
                                        <option value="{{ $clie->id }}" {{ $clie->id == $precio->cliente_id ? 'selected' : '' }}> {{ $clie->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required ">PRECIO</label>
                                <input type="number" name="preciounitariomo" class="form-control"
                                    value="{{ $precio->preciounitariomo }}" required step="0.01" />
                                @error('preciounitariomo')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">Seleccionar Moneda</label>
                                <select name="moneda" class="form-select " required>
                                    <option value="" selected disabled>Seleccionar</option>
                                    @if ($precio->moneda == 'soles')
                                        <option value="soles" selected>Soles</option> 
                                    @endif
                                    @if ($precio->moneda == 'dolares') 
                                        <option value="dolares" selected>Dolares</option>
                                    @endif
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
        $(document).ready(function() {
            $('.select2').select2({});
        });
        
    </script>
@endpush
