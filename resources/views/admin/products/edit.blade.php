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
                <h4>EDITAR PRODUCTO
                    <a href="{{ url('admin/products') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/products/'.$product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label is-required" >CATEGORIA</label>
                            <select name="category_id" class="form-select select2 borde" required>
                                <option value="" class="silver">Seleccione una opción</option>  
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{$category->id == $product->category_id ? 'selected':''}}>
                                    {{ $category->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label is-required" >NOMBRE</label>
                            <input type="text" name="nombre" value="{{ $product->nombre }}" class="form-control borde" required />
                            @error('nombre') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label ">CÓDIGO</label>
                            <input type="text" name="codigo" value="{{ $product->codigo }}" class="form-control borde" />
                            
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">UNIDAD</label>
                            <input type="text" name="unidad" value="{{ $product->unidad }}" class="form-control borde" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label ">UND</label>
                            <input type="text" name="und" value="{{ $product->und}}" class="form-control borde" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">TIPO DE MONEDA</label>
                            <select name="moneda" class="form-select borde" required>
                                <option value="">Seleccione Tipo de Moneda</option>
                                <option value="dolares" {{ $product->moneda == 'dolares' ? 'selected':''}}>Dolares Americanos</option>
                                <option value="soles" {{ $product->moneda == 'soles' ? 'selected':''}}>Soles</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">PRECIO SIN IGV</label>
                            <input type="number" name="NoIGV" id="cantidad" value="{{ $product->NoIGV }}" min="0" step="0.01" class="form-control borde" required/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">PRECIO CON IGV</label>
                            <input type="number" name="SiIGV" id="cantidad2" value="{{ $product->SiIGV }}" min="0" step="0.01" readonly  class="form-control borde" required/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">PRECIO MÍNIMO</label>
                            <input type="number" name="minimo" id="minimo" value="{{ $product->minimo }}" min="0" step="0.01" class="form-control borde" required/>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">PRECIO MÁXIMO</label>
                            <input type="number" name="maximo" id="maximo" value="{{ $product->maximo }}" min="0" step="0.01"    class="form-control borde" required/>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Status</label><br>
                            <input type="checkbox" name="status"  />
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
 <script type="text/javascript">
$(document).ready(function() {
       document.getElementById("cantidad").onchange = function() {
        IGVtotal();
       };

       $('.select2').select2({     });
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