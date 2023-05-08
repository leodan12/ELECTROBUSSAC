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
                <h4>EDITAR STOCKS DE LOS PRODUCTOS
                    <a href="{{ url('admin/inventario') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/inventario/'.$inventario->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label is-required">PRODUCTO</label>
                            <select class="form-select select2 borde" name="product_id" required>
                                <option value="" class="silver">Seleccione una opción</option>    
                                @foreach ($products as $product)
                                
                                <option value="{{ $product->id }}" {{$product->id == $inventario->product_id ? 'selected':''}}>{{$product->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">STOCK MINIMO</label>
                            <input type="number" name="stockminimo" value="{{ $inventario->stockminimo }}" class="form-control borde" required />
                            @error('stockminimo') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">STOCK TOTAL</label>
                            <input type="number" name="stocktotal" id="stocktotal" value="{{ $inventario->stocktotal }}" readonly class="form-control" />
                            
                        </div>
                    
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Status</label><br>
                            <input type="checkbox" name="status"  />
                        </div>
                        <hr>
                        <h5>Agregar Detalle de Inventario</h5>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">EMPRESA</label>
                            <select class="form-select select2 borde" name="empresa" id="empresa">
                                <option value="" class="silver" >Seleccione una opción</option>    
                                @foreach ($companies as $company)
                                <option value="{{ $company->id }}" data-name="{{$company->nombre}}">{{$company->nombre}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">STOCK POR EMPRESA</label>
                            <input type="number" name="stockempresa" id="stockempresa" class="form-control borde" />
                            @error('stockempresa') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        @php $ind=0 ; @endphp
                        @php $indice=count($detalleinventario) ; @endphp
                        <button type="button" class="btn btn-info" id="addDetalleBatch" onclick="agregarFila('{{$indice}}')"><i class="fa fa-plus"></i> Agregar Stock por Empresa</button>
                        <div class="table-responsive">
                        <table class="table table-row-bordered gy-5 gs-5" id="detallesCompra">
                            <thead class="fw-bold text-primary">
                                <tr>
                                    <th>EMPRESA</th>
                                    <th>STOCK POR EMPRESA</th>
                                    <th>ELIMINAR</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($detalleinventario as $detalle)
                            @php $ind++; @endphp
                                    <tr id="fila{{$ind}}">
                                        <td> {{$detalle->nombre}}</td>
                                        <td> {{$detalle->stockempresa}}</td>
                                        <td><button type="button" class="btn btn-danger" onclick="eliminarFila(  '{{$detalle->iddetalleinventario}}','{{$ind}}','{{$detalle->stockempresa}}'  )" data-id="0">ELIMINAR</button></td>

                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                        <hr>
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

    var indice = 0;
    var nameempresa = 0;
    var stocktotal = 0;
    var pv = 0;
var tabla = document.getElementById(detallesCompra);
       
       
function agregarFila(indice1) {

if (pv == 0) {
    indice = indice1;
    pv++;
    indice++;
} else {
    indice++;
}   
         
           //datos del detalleSensor
           var empresa = $('[name="empresa"]').val();
           var stockempresa = $('[name="stockempresa"]').val();
           
            
           //alertas para los detallesBatch
           if (!empresa) {  alert("Seleccione una empresa"); return;   }
           if (!stockempresa) {  alert("ingrese un stockempresa"); return;   }
           var LDInventario = [];
           var tam = LDInventario.length;
           LDInventario.push(empresa,stockempresa,nameempresa);
       
               filaDetalle ='<tr id="fila' + indice + 
               '"><td><input  type="hidden" name="Lempresa[]" value="' + LDInventario[0]  + '"required>'+ LDInventario[2]+
               '</td><td><input  type="hidden" name="Lstockempresa[]" id="stockempresa' + indice +'" value="' + LDInventario[1] + '"required>'+ LDInventario[1]+  
               '</td><td><button type="button" class="btn btn-danger" onclick="quitarFila(' + indice + ')" data-id="0">ELIMINAR</button></td></tr>';
              
               $("#detallesCompra>tbody").append(filaDetalle);
               indice++;
               var mistocktotal = $('[name="stocktotal"]').val();
               stocktotal = parseInt(stockempresa)+parseInt(mistocktotal);
               document.getElementById('stocktotal').value = stocktotal;
               document.getElementById('stockempresa').value = null; 
       };
       $("#empresa").change(function () {
      
      $("#empresa option:selected").each(function () { 
          $named = $(this).data("name");
          nameempresa = $named;
          document.getElementById('stockempresa').value = 1; 
          //alert(nameempresa);
  });  
  });

  

function eliminarFila(idBD, ind,stockemp) {

            $.get('/admin/deletedetalleinventario/' + idBD, function(data) {
                $('#fila' + ind).remove();
                var stocktotal2 =0;
   
         //document.getElementById('preciot' + ind).value();
         stocktotal2 = $('[id="stocktotal"]').val();
         //alert(resta);
            stocktotal2 = stocktotal2 - stockemp;
            document.getElementById('stocktotal').value = stocktotal2;
            stocktotal = stocktotal2;
            });       
        }

function quitarFila(ind) {

        var resta =0;
   
         //document.getElementById('preciot' + ind).value();
         resta = $('[id="stockempresa' + ind+'"]').val();
         //alert(resta);
       stocktotal = stocktotal - resta;

   $('#fila' + ind).remove();
       indice-- ;
   // damos el valor
   document.getElementById('stocktotal').value = stocktotal;
   //alert(resta);

   return false;

    }
    
    $(document).ready(function() {
    $('.select2').select2({ });
});
</script>

 
@endpush

