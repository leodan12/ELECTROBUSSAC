@extends('layouts.admin')
@push('css')
 <link href="{{ asset('admin/required.css') }}" rel="stylesheet" type="text/css" />
@endpush
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
                <h4>AÑADIR EMPRESA
                    <a href="{{ url('admin/company') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/company') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">Nombre</label>
                            <input type="text" name="nombre" class="form-control borde" required/>
                            @error('nombre') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">RUC</label>
                            <input type="number" name="ruc" id="ruc" class="form-control borde" required/>
                            @error('ruc') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Direccion</label>
                            <input type="text" name="direccion" class="form-control  borde" />
                            
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefono</label>
                            <input type="number" name="telefono" class="form-control  borde" />
                            
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control  borde" />
                            
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subir un Logo</label>
                            <input type="file" accept="image/png,image/jpeg,image/jpg,image/svg,image/webp" id="logo" name="logo" class="form-control  borde"  />
                        </div> 
                        <div class="col-md-6 mb-3">
                            <img id="imagenPrevisualizacion" width="200px" height="100px">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Status</label><br>
                            <input type="checkbox" name="status"  />
                        </div>
                        <div class="col-md-12 mb-3">
                            <button type= "submit" id="enviar" class="btn btn-primary text-white float-end">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
@push('script')
 
    <script>
    ruc.oninput = function() {
        //result.innerHTML = password.value;
        verificar();
    }
    function verificar() {

        ruc1 = document.getElementById('ruc');
        enviar = document.getElementById('enviar');
        
        if (ruc1.value.length == 11) {
                ruc1.style.borderColor = "green";
                enviar.disabled = false;
            }
        else {
            ruc1.style.borderColor = "red";
            enviar.disabled = true;
        }
    }

    const $subirlogo = document.querySelector("#logo"),
    $imagenPrevisualizacion = document.querySelector("#imagenPrevisualizacion");

        // Escuchar cuando cambie
        $subirlogo.addEventListener("change", () => {
        // Los archivos seleccionados, pueden ser muchos o uno
        const archivos = $subirlogo.files;
        // Si no hay archivos salimos de la función y quitamos la imagen
        if (!archivos || !archivos.length) {
        $imagenPrevisualizacion.src = "";
        return;
    }
        // Ahora tomamos el primer archivo, el cual vamos a previsualizar
        const primerArchivo = archivos[0];
        // Lo convertimos a un objeto de tipo objectURL
        const objectURL = URL.createObjectURL(primerArchivo);
        // Y a la fuente de la imagen le ponemos el objectURL
        $imagenPrevisualizacion.src = objectURL;
    });


</script>
@endpush
