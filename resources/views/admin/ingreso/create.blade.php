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
                <h4>AÑADIR INGRESO
                    <a href="{{ url('admin/ingreso') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/ingreso') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                    <div class="col-md-6 mb-3">
                            <label class="form-label is-required"  >FECHA</label>
                            <input type="date" name="fecha" id="fecha" class="form-control borde"  required />
                            @error('fecha') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                             <label class="form-label is-required">NUMERO DE FACTURA</label>
                            <input type="text" name="factura" id="factura" class="form-control borde"  required/>
                            @error('factura') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">FORMA DE PAGO</label>
                            <select name="formapago" id="formapago" class="form-select borde" required  >
                            <option value="" class="silver">Seleccion una opción</option>
                            <option value="credito" data-formapago="credito"  >Credito</option>
                            <option value="contado" data-formapago="contado">Contado</option>
                            </select>
                            @error('formapago') <small class="text-danger">{{$message}}</small> @enderror
                        </div>

                         
                        
                        <div class="col-md-6 mb-3">
                             <label id="labelfechav" class="form-label">FECHA DE VENCIMIENTO</label>
                            <input type="date" name="fechav" id="fechav" class="form-control borde" readonly/>
                            @error('fechav') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                             <label class="form-label is-required">MONEDA</label>
                            <select name="moneda" id="moneda"  class="form-select borde"  required>
                            <option value="" class="silver">Seleccion una opción</option>
                            <option value="dolares" data-moneda="dolares" >Dolares Americanos</option>
                            <option value="soles" data-moneda="soles" >Soles</option>
                            </select>
                            @error('tipo') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                             <label id="labeltasacambio" class="form-label">TASA DE CAMBIO</label>
                            <input type="number" name="tasacambio" id= "tasacambio" step="0.01" readonly class="form-control borde" />
                        </div>
                        
                        <div class="col-md-6 mb-3">
                             <label class="form-label is-required">EMPRESA</label>
                            <select  class="form-select   borde" name="company_id" id="company_id" required>
                                <option value="" disabled selected>Seleccione una opción</option>    
                                @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                             <label class="form-label is-required">PROVEEDOR</label>
                            <select  class="form-select   borde" name="cliente_id"  id="cliente_id"required>
                                <option value="" disabled selected>Seleccione una opción</option>    
                                @foreach ($clientes as $cliente)
                                
                                <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                             <label class="form-label is-required">PRECIO DE LA VENTA </label>
                            <input type="number" name="costoventa" id= "costoventa"  step="0.01" class="form-control borde required" required readonly />
                            
                        </div>
                        <div class="col-md-12 mb-3">
                             <label class="form-label">OBSERVACION</label>
                            <input type="text" name="observacion" id="observacion" class="form-control borde" />
                            @error('observacion') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <hr>
                        <h4>Agregar Detalle de la Compra</h4>
                        <div class="col-md-6 mb-3">
                             <label class="form-label">PRODUCTO</label>
                            <select  class="form-select select2 borde" name="product" id="product"  >
                                <option value="" disabled selected>Seleccione una opción</option>    
                                @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-name="{{$product->nombre}}" data-price="{{$product->NoIGV}}">{{ $product->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                             <label class="form-label">CANTIDAD</label>
                            <input type="number" name="cantidad" id="cantidad" class="form-control borde" />
                            @error('cantidad') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                             <label class="form-label">PRECIO UNITARIO (REFERENCIAL)</label>
                            <input type="number" name="preciounitario" id="preciounitario" readonly class="form-control borde" />
                            @error('preciounitario') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                             <label class="form-label">PRECIO UNITARIO</label>
                            <input type="number" name="preciounitariomo" id="preciounitariomo" class="form-control borde" />
                            @error('preciounitariomo') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                             <label class="form-label">SERVICIO ADICIONAL</label>
                            <input type="number" name="servicio" id="servicio"class="form-control borde" />
                            @error('servicio') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                             <label class="form-label">PRECIO TOTAL POR PRODUCTO</label>
                            <input type="number" name="preciofinal" id="preciofinal" readonly class="form-control borde" />
                            @error('preciofinal') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <button type="button" class="btn btn-info" id="addDetalleBatch"><i class="fa fa-plus"></i> Agregar Producto a la Venta</button>
                        <div class="table-responsive">
                        <table class="table table-row-bordered gy-5 gs-5" id="detallesVenta">
                            <thead class="fw-bold text-primary">
                                <tr>
                                    <th>PRODUCTO</th>
                                    <th>CANTIDAD</th>
                                    <th>PRECIO UNITARIO(REFERENCIAL)</th>
                                    <th>PRECIO UNITARIO</th>
                                    <th>SERVICIO ADICIONAL</th>
                                    <th>PRECIO FINAL DEL PRODUCTO</th>
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
                             <button type= "submit" id="btnguardar" name="btnguardar" class="btn btn-primary text-white float-end">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@push('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script type="text/javascript">

    var indice = 0;
    var ventatotal = 0;
    var preciounit = 0;
    var nameproduct = 0;
    var preciototalI=0;
    var estadoguardar=0;

    $(document).ready(function() {

       /* $('.select2').select2({
        placeholder: "Buscar y Seleccionar Opción",
        allowClear: true,
        minimumResultsForSearch: 1,
        dropdownAutoWidth: false
        });*/

        //para diferente comprador y vendedor
        $("#company_id").change(function(){
        var company = $(this).val(); 
        $('#cliente_id').removeAttr('disabled');
        $.get('/admin/venta/comboempresacliente/'+company, function(data){ 
            var producto_select = '<option value="" disabled selected>Seleccione una opcion</option>'
              for (var i=0; i<data.length;i++){
                producto_select+='<option value="'+data[i].id+'" data-name="'+data[i].nombre+'" data-price="'+data[i].NoIGV+'">'+data[i].nombre+'</option>';
              }
              $("#cliente_id").html(producto_select);
        });


      });
 
        $("#btnguardar").prop("disabled", true);
        //Para poner automaticamente la fecha actual
       var hoy = new Date();  
       var fechaActual = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' + String(hoy.getDate()).padStart(2, '0');
       document.getElementById("fecha").value = fechaActual;
        
       //var fechaActual2 = hoy.getFullYear() + '-' + (String(hoy.getMonth() + 1).padStart(2, '0')) + '-' + String(hoy.getDate()).padStart(2, '0');
       //document.getElementById("fechav").value = fechaActual2;
       
       document.getElementById("cantidad").onchange = function() {
       preciofinal();
       };
       document.getElementById("servicio").onchange = function() {
        preciofinal();
       };
       document.getElementById("preciounitariomo").onchange = function() {
        preciofinal();
       };

       function preciofinal() {
         
         var cantidad = $('[name="cantidad"]').val(); 
         var preciounit = $('[name="preciounitariomo"]').val(); 
         var servicio = $('[name="servicio"]').val();
         if(cantidad >= 1   && preciounit >= 0 && servicio >=0 ){
              
                     
                    preciototalI = (parseFloat(parseFloat(cantidad) * parseFloat(preciounit)) + parseFloat(parseFloat(cantidad) * parseFloat(servicio)));
                     
                    document.getElementById('preciofinal').value = preciototalI.toFixed(2);      
         }
    }

        var tabla = document.getElementById(detallesVenta);
       
        $('#addDetalleBatch').click(function() {
          
            //datos del detalleSensor
            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var preciounitario = $('[name="preciounitario"]').val();
            var servicio = $('[name="servicio"]').val();
            var preciofinal = $('[name="preciofinal"]').val();
            var preciounitariomo = $('[name="preciounitariomo"]').val();
             
            //alertas para los detallesBatch
            
            if (!product) {  alert("Seleccione un Producto"); return;   }
            if (!cantidad) {  alert("Ingrese una cantidad"); return;   }
            if (!preciounitariomo) {  alert("Ingrese una cantidad"); return;   }

            
            $("#product option:contains('Seleccione una opción')").attr('selected',false);  
            var LVenta = [];
            var tam = LVenta.length;
            LVenta.push(product,nameproduct,cantidad,preciounitario,servicio,preciofinal,preciounitariomo);
        
                filaDetalle ='<tr id="fila' + indice + 
                '"><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0]  + '"required>'+ LVenta[1]+
                '</td><td><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice +'" value="' + LVenta[2] + '"required>'+ LVenta[2]+
                '</td><td><input  type="hidden" name="Lpreciounitario[]" id="preciounitario' + indice +'" value="' + LVenta[3] + '"required>'+ LVenta[3]+ 
                    '</td><td><input  type="hidden" name="Lpreciounitariomo[]" id="preciounitariomo' + indice +'" value="' + LVenta[6] + '"required>'+ LVenta[6]+ 
                '</td><td><input  type="hidden" name="Lservicio[]" id="servicio' + indice +'" value="' + LVenta[4] + '"required>'+ LVenta[4]+
                '</td><td ><input id="preciof' + indice +'"  type="hidden" name="Lpreciofinal[]" value="' + LVenta[5] + '"required>'+ LVenta[5]+ 
                '</td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(' + indice + ')" data-id="0">ELIMINAR</button></td></tr>';
               
                $("#detallesVenta>tbody").append(filaDetalle);

                indice++;
                ventatotal = parseFloat(ventatotal) + parseFloat(preciototalI);

                $("#product option:contains('Seleccione una opción')").attr('selected',true);   
                document.getElementById('cantidad').value = "";
                document.getElementById('servicio').value = "";
                document.getElementById('preciofinal').value = "";
                document.getElementById('preciounitario').value = "";
                document.getElementById('preciounitariomo').value = "";
                document.getElementById('costoventa').value = ventatotal;

                 
                var funcion="agregar";
                botonguardar(funcion);
        });
        
        $("#product").change(function () {
       
       $("#product option:selected").each(function () { 
            $price = $(this).data("price");
            $named = $(this).data("name");
            if($price != null){
                preciounit = $price;
                document.getElementById('preciounitario').value = $price;
                document.getElementById('preciounitariomo').value = $price;
                document.getElementById('cantidad').value = 1;
                document.getElementById('servicio').value = 0;
                document.getElementById('preciofinal').value = $price;
                nameproduct = $named;
                preciototalI = $price;}
           else if($price == null){
                document.getElementById('cantidad').value = "";
                document.getElementById('servicio').value = "";
                document.getElementById('preciofinal').value = "";
                document.getElementById('preciounitario').value = "";
                document.getElementById('preciounitariomo').value = "";
           }
           //alert(nameprod);
   });  });

   //para cambiar la forma de pago  y dehabilitar la fecha de vencimiento
   $("#formapago").change(function () { 
       $("#formapago option:selected").each(function () {
        $mimoneda = $(this).data("formapago"); 
        if ($mimoneda == "credito") { 
                $("#fechav").prop("readonly", false);
                $("#fechav").prop("required", true);
                var fechav = document.getElementById("labelfechav");
                fechav.className += " is-required";
                
            } else if ($mimoneda == "contado") {
                $("#fechav").prop("readonly", true);
                $("#fechav").prop("required", false); 
                var fechav = document.getElementById("labelfechav");
                fechav.className -= " is-required";
            } 
   });
    });

    //para cambiar la moneda de pago y deshabilitar la tasa de cambio
   $("#moneda").change(function () {
       $("#moneda option:selected").each(function () {
        $mimoneda = $(this).data("moneda"); 
        if ($mimoneda == "soles") {
                //alert("selecciono soles");
                $("#tasacambio").prop("readonly", true);
                $("#tasacambio").prop("required", false);
                var tasacambio = document.getElementById("labeltasacambio");
                tasacambio.className -= " is-required";

            } else if ($mimoneda == "dolares"){
                $("#tasacambio").prop("readonly", false);
                $("#tasacambio").prop("required", true);
                var tasacambio = document.getElementById("labeltasacambio");
                tasacambio.className += " is-required";

            } 
   });  });

 
});

    
      
   

    function eliminarFila(ind) {
        var resta =0;
          //document.getElementById('preciot' + ind).value();
          resta = $('[id="preciof' + ind+'"]').val();
          //alert(resta);
          ventatotal = ventatotal - resta;

    $('#fila' + ind).remove();
        indice-- ;
    // damos el valor
    document.getElementById('costoventa').value = ventatotal;
    //alert(resta);

    var funcion="eliminar";
    botonguardar(funcion);

    return false;
} 

function botonguardar(funcion){

if(funcion == "eliminar"){
    estadoguardar--;
}else if(funcion == "agregar"){
    estadoguardar++;
}
if(estadoguardar == 0){
    $("#btnguardar").prop("disabled", true);
}else if(estadoguardar > 0){
    $("#btnguardar").prop("disabled", false);
}    
}   

</script>

 
@endpush

