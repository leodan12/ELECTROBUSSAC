@extends('layouts.admin')
@push('css')
 <link href="{{ asset('admin/required.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>EDITAR EMPRESA
                    <a href="{{ url('admin/company') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/company/'.$company->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-8 ">
                            <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label is-required">Nombre</label>
                                <input type="text" name="nombre" class="form-control borde" required  value="{{ $company->nombre }}"/>
                                @error('nombre') <small class="text-danger">{{$message}}</small> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">RUC</label>
                                <input type="number" name="ruc" id="ruc" class="form-control borde" required value="{{ $company->ruc }}"/>
                                @error('ruc') <small class="text-danger">{{$message}}</small> @enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control  borde" value="{{ $company->email }}"/>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Direccion</label>
                                <input type="text" name="direccion" class="form-control  borde" value="{{ $company->direccion }}"/>
                                
                            </div>  
                            <h5>Datos de la cuenta soles</h5>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tipo Cuenta(tipo, banco, moneda)</label>
                                <input type="text" name="tipocuentasoles" id="tipocuentasoles" class="form-control borde" placeholder="Cuenta Soles" value="{{ $company->tipocuentasoles }}"/> 
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Numero de Cuenta(S/.)</label>
                                <input type="number"   name="numerocuentasoles" id="numerocuentasoles" class="form-control borde" value="{{ $company->numerocuentasoles }}"/> 
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">CCI(S/.)</label>
                                <input type="number"  name="ccisoles"  id="ccisoles" class="form-control borde" value="{{ $company->ccisoles }}"/> 
                            </div> 
                            <h5>Datos de la cuenta dolares</h5>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tipo Cuenta(tipo, banco, moneda)</label>
                                <input type="text" name="tipocuentadolares" id="tipocuentadolares" class="form-control borde" placeholder="Cuenta Dolares" value="{{ $company->tipocuentadolares }}"/> 
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Numero de Cuenta($)</label>
                                <input type="number" name="numerocuentadolares"  id="numerocuentadolares" class="form-control borde" value="{{ $company->numerocuentadolares }}"/> 
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">CCI($)</label>
                                <input type="number" name="ccidolares"  id="ccidolares" class="form-control borde" value="{{ $company->ccidolares }}"/> 
                            </div>
                        </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Subir un Logo</label>
                                <input type="file" accept="image/png,image/jpeg,image/jpg,image/svg,image/webp" id="logo" name="logo" class="form-control  borde"  />
                            </div>
                            <div class="col-md-12 mb-3">
                                <img id="imagenPrevisualizacion" width="100%" height="170px" src="/logos/{{$company->logo}}">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Telefono</label>
                                <input type="number" name="telefono" class="form-control  borde" value="{{ $company->telefono }}"/>
                                
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Status</label><br>
                                <input type="checkbox" name="status" {{ $company->status == '1' ? 'checked':''}}  />
                            </div>
                        </div>
                        </div> 
                         
                       

                        <div class="col-md-12 mb-3">
                            <button type= "submit" class="btn btn-primary text-white float-end">Actualizar</button>
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
