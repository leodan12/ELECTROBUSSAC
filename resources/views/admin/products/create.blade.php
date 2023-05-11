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
                <h4>AÑADIR PRODUCTO
                    <a href="{{ url('admin/products') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/products') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label is-required">CATEGORIA</label>
                            <select class="form-select select2" id="category_id" name="category_id" required data-show-subtext="true" data-live-search="true">
                                <option value="" selected disabled>Seleccione una opción</option>    
                                @foreach ($categories as $category)
                                
                                <option value="{{ $category->id }}">{{ $category->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label is-required">NOMBRE</label>
                            <input type="text" name="nombre" class="form-control borde" required />
                            @error('nombre') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CÓDIGO</label>
                            <input type="text" name="codigo" class="form-control borde" />
                            
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">UNIDAD</label>
                            <input type="text" name="unidad" class="form-control borde" required/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">UND</label>
                            <input type="text" name="und" class="form-control borde" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label  class="form-label is-required">Tipo de Moneda</label>
                            <select name="moneda" id="moneda" class="form-select" required>
                            <option value="" selected disabled>Seleccion una opción</option>
                            <option value="dolares">Dolares Americanos</option>
                            <option value="soles">Soles</option>
                            </select>
                            @error('tipo') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">PRECIO SIN IGV</label>
                            <input type="number" name="NoIGV" id="cantidad" min="0" step="0.01" class="form-control borde" required/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">PRECIO CON IGV</label>
                            <input type="number" name="SiIGV" id="cantidad2" min="0" step="0.01" readonly  class="form-control borde" required/>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label ">Status</label><br>
                            <input type="checkbox" name="status"  />
                        </div>
                        <div class="col-md-12 mb-3">
                            <button type= "submit" class="btn btn-primary text-white float-end">Guardar</button>
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
$(document).ready(function() {
       document.getElementById("cantidad").onchange = function() {
        IGVtotal();
       }; 
    $('.select2').select2();
 
    });

    
function IGVtotal() {
        preciototal=0;
        var cantidad = $('[name="NoIGV"]').val();
        if(cantidad.length != 0){
                    //alert("final");
                    preciototal = parseFloat(cantidad) + (parseFloat(cantidad) * 0.18);
                    document.getElementById('cantidad2').value = preciototal;       
        
    }
}
</script>

@endpush

