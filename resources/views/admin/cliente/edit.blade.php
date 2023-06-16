@extends('layouts.admin')
 
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>EDITAR CLIENTE / PROVEEDOR
                        <a href="{{ url('admin/cliente') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/cliente/' . $cliente->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">Nombre</label>
                                <input type="text" name="nombre" value="{{ $cliente->nombre }}"
                                    class="form-control  " required />
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">RUC o DNI</label>
                                <input type="number" name="ruc" id="ruc" value="{{ $cliente->ruc }}"
                                    class="form-control " required />
                                @error('ruc')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Direccion</label>
                                <input type="text" name="direccion" value="{{ $cliente->direccion }}"
                                    class="form-control " />

                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telefono</label>
                                <input type="text" name="telefono" value="{{ $cliente->telefono }}"
                                    class="form-control " />

                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ $cliente->email }}"
                                    class="form-control " />

                            </div>
                            <div class="col-md-12 mb-3">
                                <button type="submit" class="btn btn-primary text-white float-end">Actualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    function verificar() {
    <script>
        ruc.oninput = function() {
            //result.innerHTML = password.value;
            verificar();
        }

        function verificar() {

            ruc1 = document.getElementById('ruc');
            enviar = document.getElementById('enviar');

            if (ruc1.value.length == 11 || ruc1.value.length == 8) {
                ruc1.style.borderColor = "green";
                enviar.disabled = false;
            } else {
                ruc1.style.borderColor = "red";
                enviar.disabled = true;
            }
        }
    </script>
@endpush
