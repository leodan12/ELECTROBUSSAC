@extends('layouts.admin')


@push('css')
<style>
    .borde {
        border: 1px solid silver ; 
    }
    .silver {
        color: silver ; 
    }
    .is-required:after {
        content: '*';
        margin-left: 3px;
        color: red;
        font-weight: bold;
    }
</style>
@endpush

@section('content')

<div class="row">
    <div class="col-md-12">
    @php  $detalles = count($detallesingreso) @endphp
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
                <h4>EDITAR EL INGRESO
                    <a href="{{ url('admin/ingreso') }}" id="btnvolver" name="btnvolver" class="btn btn-danger text-white float-end">VOLVER</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/ingreso/'.$ingreso->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                    <div class="col-md-6 mb-3">
                            <label class="form-label is-required"  >FECHA</label>
                            <input type="date" name="fecha" id="fecha" class="form-control borde"  required value="{{ $ingreso->fecha }}"/>
                            @error('fecha') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                             <label class="form-label is-required">NUMERO DE FACTURA</label>
                            <input type="text" name="factura" id="factura" class="form-control borde" readonly required value="{{ $ingreso->factura }}"/>
                            @error('factura') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">FORMA DE PAGO</label>
                            <select name="formapago" id="formapago" class="form-select borde" required  >
                            <option value="" class="silver">Seleccion una opción</option>
                            @if($ingreso->formapago == "credito")
                            <option value="credito" data-formapago="credito" selected >Credito</option>
                            <option value="contado" data-formapago="contado">Contado</option>
                            @elseif($ingreso->formapago == "contado")
                            <option value="credito" data-formapago="credito"  >Credito</option>
                            <option value="contado" data-formapago="contado" selected>Contado</option>
                            @endif
                            </select>
                            @error('formapago') <small class="text-danger">{{$message}}</small> @enderror
                        </div>

                         
                        
                        <div class="col-md-6 mb-3">
                            @if($ingreso->formapago == "contado")
                                <label id="labelfechav" class="form-label"  >FECHA DE VENCIMIENTO</label>
                                <input type="date" name="fechav" id="fechav" class="form-control borde"   readonly  value="{{ $ingreso->fechav }}"/>
                                @error('fechav') <small class="text-danger">{{$message}}</small> @enderror
                            @endif 
                            @if($ingreso->formapago == "credito")
                            <label id="labelfechav" class="form-label is-required"  >FECHA DE VENCIMIENTO</label>
                            <input type="date" name="fechav" id="fechav" class="form-control borde"  value="{{ $ingreso->fechav }}"/>
                            @error('fechav') <small class="text-danger">{{$message}}</small> @enderror
                        @endif 
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">MONEDA</label>
                            <select name="moneda" id="moneda"  class="form-select borde"  required>
                            <option value="" class="silver">Seleccion una opción</option>
                            @if($ingreso->moneda == "soles")
                                <option value="dolares" data-moneda="dolares" >Dolares Americanos</option>
                                <option value="soles" data-moneda="soles" selected>Soles</option>
                            @elseif($ingreso->moneda == "dolares")
                                <option value="dolares" data-moneda="dolares" selected>Dolares Americanos</option>
                                <option value="soles" data-moneda="soles" >Soles</option>
                            @endif
 
                            </select>
                            @error('tipo') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            @if($ingreso->moneda == "soles")
                                <label id="labeltasacambio" class="form-label ">TASA DE CAMBIO</label>
                                <input type="number" name="tasacambio" id= "tasacambio" step="0.01" readonly  class="form-control borde" value="{{ $ingreso->tasacambio }}"/>
                            @elseif($ingreso->moneda == "dolares")
                                <label id="labeltasacambio" class="form-label is-required">TASA DE CAMBIO</label>
                                <input type="number" name="tasacambio" id= "tasacambio" step="0.01"   class="form-control borde" value="{{ $ingreso->tasacambio }}"/>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                             <label class="form-label is-required">EMPRESA</label>
                            <select  class="form-select select2 borde" name="company_id" required>
                                <option value="" class="silver">Seleccione una opción</option>    
                                @foreach ($companies as $company)
                                
                                <option value="{{ $company->id }}" {{$company->id==$ingreso->company_id ? 'selected':''}} >{{ $company->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                             <label class="form-label is-required">CLIENTE</label>
                            <select  class="form-select select2 borde" name="cliente_id" required>
                                <option value="" class="silver">Seleccione una opción</option>    
                                @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}" {{$cliente->id==$ingreso->cliente_id ? 'selected':''}} >{{ $cliente->nombre }}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class="col-md-6 mb-3">
                             <label class="form-label is-required">PRECIO DE LA COMPRA </label>
                            <input type="number" name="costoventa" id= "costoventa"  step="0.01" class="form-control borde required" required readonly value="{{ $ingreso->costoventa }}"/>
                            
                        </div>
                        <div class="col-md-12 mb-3">
                             <label class="form-label">OBSERVACION</label>
                            <input type="text" name="observacion" id="observacion" class="form-control borde" value="{{ $ingreso->observacion }}"/>
                            @error('observacion') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <hr>
                        <h4>Agregar Detalle del Ingreso</h4>
                        <div class="col-md-6 mb-3">
                             <label class="form-label">PRODUCTO</label>
                            <select  class="form-select select2 borde" name="product" id="product">
                                <option  class="silver" value="">Seleccione una opción</option>    
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
                        @php $ind=0 ; @endphp
                        @php $indice=count($detallesingreso) ; @endphp
                        <button type="button" class="btn btn-info" id="addDetalleBatch"  onclick="agregarFila('{{$indice}}')"><i class="fa fa-plus"></i> Agregar Producto al ingreso</button>
                       
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
                                @php $datobd="db" ;  @endphp
                                @foreach($detallesingreso as $detalle)
                                    @php $ind++;    @endphp
                                    <tr id="fila{{$ind}}">
                                        <td> {{$detalle->producto}}</td>
                                        <td> {{$detalle->cantidad}}</td>
                                        <td> {{$detalle->preciounitario}}</td>
                                        <td> {{$detalle->preciounitariomo}}</td>
                                        <td> {{$detalle->servicio}}</td>
                                        <td> <input type="hidden" id="preciof{{ $ind }}" value="{{$detalle->preciofinal}}" > {{$detalle->preciofinal}} </td>
                                         
                                        <td><button type="button" class="btn btn-danger" onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{$detalle->iddetalleingreso}}'  )" data-id="0"><i class="bi bi-trash-fill"></i>ELIMINAR</button></td>

                                    </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                        <hr>
                        <div class="col-md-12 mb-3">
                            <button type= "submit" id="btnguardar" name="btnguardar" class="btn btn-primary text-white float-end">Actualizar</button>
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
    var ventatotal = 0;
    var preciounit = 0;
    var nameproduct = 0;
    var preciototalI=0;
    var estadoguardar=0;
    var detallesborrados = [];

    estadoguardar = @json($detalles);
    //alert(estadoguardar);
    var funcion1="inicio";
    botonguardar(funcion1);
    var costoventa = $('[id="costoventa"]').val();
    ventatotal =costoventa;
    $(document).ready(function() {
 
        $('.select2').select2({  }); 
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

        //var tabla = document.getElementById(detallesVenta);
        
         
        $("#product").change(function () {
       
       $("#product option:selected").each(function () { 
           $price = $(this).data("price");
           $named = $(this).data("name");
           preciounit = $price;
           document.getElementById('preciounitario').value = $price;
           document.getElementById('preciounitariomo').value = $price;
           document.getElementById('cantidad').value = 1;
           document.getElementById('servicio').value = 0;
           document.getElementById('preciofinal').value = $price;
           nameproduct = $named;
           preciototalI = $price;
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

    //funcion para agregar una fila
    var indice = 0;
    var pv = 0;

    function agregarFila(indice1) {

        if (pv == 0) {
            indice = indice1;
            pv++;
            indice++;
        } else {
            indice++;
        }
          //datos del detalleSensor
            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var preciounitario = $('[name="preciounitario"]').val();
            var servicio = $('[name="servicio"]').val();
            var preciofinal = $('[name="preciofinal"]').val();
            var preciounitariomo = $('[name="preciounitariomo"]').val();
             
            //alertas para los detallesBatch
            if (!product) {  alert("Seleccione un producto"); return;   }
            if (!cantidad) {  alert("Ingrese una cantidad"); return;   }
            if (!preciounitariomo) {  alert("Ingrese un precio"); return;   }
            $("#product option:contains('Seleccione una opción')").attr('selected',false);
            var LVenta = [];
            var tam = LVenta.length;
            var datodb ="local";
            LVenta.push(product,nameproduct,cantidad,preciounitario,servicio,preciofinal,preciounitariomo);
        
                filaDetalle ='<tr id="fila' + indice + 
                '"><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0]  + '"required>'+ LVenta[1]+
                '</td><td><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice +'" value="' + LVenta[2] + '"required>'+  
                '</td><td><input  type="hidden" name="Lpreciounitario[]" id="preciounitario' + indice +'" value="' + LVenta[3] + '"required>'+ LVenta[3]+ 
                '</td><td><input  type="hidden" name="Lpreciounitariomo[]" id="preciounitariomo' + indice +'" value="' + LVenta[6] + '"required>'+ LVenta[6]+ 
                '</td><td><input  type="hidden" name="Lservicio[]" id="servicio' + indice +'" value="' + LVenta[4] + '"required>'+ LVenta[4]+
                '</td><td ><input id="preciof' + indice +'"  type="hidden" name="Lpreciofinal[]" value="' + LVenta[5] + '"required>'+ LVenta[5]+ 
                '</td><td> <button type="button" class="btn btn-danger" onclick="eliminarFila(' + indice  +','+  0  + ','+  0  +')" data-id="0">ELIMINAR</button></td></tr>';
               
                $("#detallesVenta>tbody").append(filaDetalle);

                indice++;
                ventatotal = parseFloat(ventatotal) + parseFloat(preciototalI);
                $('#product').val(null).trigger('change');
                document.getElementById('cantidad').value = "";
                document.getElementById('servicio').value = "";
                document.getElementById('preciofinal').value = "";
                document.getElementById('preciounitario').value = "";
                document.getElementById('preciounitariomo').value = "";
                document.getElementById('costoventa').value = ventatotal;
 
                var funcion="agregar";
                botonguardar(funcion);

    }
      
   

    function eliminarFila(ind,lugardato,iddetalle) {
        if(lugardato=="db"){
            Swal.fire({
                title: '¿Esta seguro de Eliminar?',
                text: "No lo podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí,Eliminar!'
            }).then((result) => {
            if (result.isConfirmed) {
 
            $.get('/admin/deletedetalleingreso/' + iddetalle, function(data) {
                //alert(data[0]);
                if(data[0]==1){ 
            Swal.fire({
                text: "Registro Eliminado",
                icon: "success"
            });  
                quitarFila(ind);
                
                }else if(data[0]==0){
                    alert("no se puede eliminar");  
                }else if(data[0]==2){
                    alert("registro no encontrado");  
                } 
            });     
            } 
            })  
            }else{
            quitarFila(ind);
        } 
    return false;
} 

function quitarFila(indicador){
    var resta =0;
    resta = $('[id="preciof' + indicador+'"]').val();
    ventatotal = ventatotal - resta;
    $('#fila' + indicador).remove();
    indice-- ;
    document.getElementById('costoventa').value = ventatotal;
    var funcion="eliminar";
    botonguardar(funcion);
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

