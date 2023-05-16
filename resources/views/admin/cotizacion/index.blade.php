@extends('layouts.admin')
 
@section('content')

<div>
        <div class="row">
            <div class="col-md-12">
            
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>REGISTRO DE COTIZACION
                        <a href="{{ url('admin/cotizacion/create') }}" class="btn btn-primary float-end">Añadir cotizacion</a>
                    </h4>
                </div>
                <div class="card-body" style="text-align: left;" >
                    <div class="table-responsive">
                    <table class="table table-bordered table-striped "   id="mitabla" name="mitabla">
                        <thead style="width:100%">
                            <tr  class="fw-bold text-primary ">
                                <th>ID</th> 
                                <th>NUMERO</th>
                                <th>FECHA</th>
                                <th>CLIENTE</th>
                                <th>EMPRESA</th>
                                <th>MONEDA</th> 
                                <th>FORMA PAGO</th> 
                                <th>COSTO SIN IGV</th>
                                <th>COSTO CON IGV</th>
                                <th>VENDIDA </th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <Tbody  >
                           
                            @forelse ($cotizaciones as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->numero}}</td>
                                <td>{{$item->fecha}}</td>
                                <td> 
                                    {{$item->nombrecliente}}
                                    
                                </td>
                                <td>
                                    {{$item->nombreempresa}}
                                </td>
                                <td> {{$item->moneda}}</td> 
                                <td> {{$item->formapago}}</td> 
                                @if($item->moneda == 'soles')
                                <td>S/. {{$item->costoventasinigv}}</td>
                                <td>S/. {{$item->costoventaconigv}}</td>
                                @elseif($item->moneda == 'dolares')
                                <td>$ {{$item->costoventasinigv}}</td> 
                                <td>$ {{$item->costoventaconigv}}</td>
                                @endif
                                <td >{{$item->vendida}}</td>
                                
                                <td>
                                    <a href="{{ url('admin/cotizacion/'.$item->id.'/edit')}}" class="btn  btn-success">Editar</a>
                                    <button type="button" class="btn  btn-secondary" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
                                    <form action="{{ url('admin/cotizacion/'.$item->id.'/delete') }}" class="d-inline formulario-eliminar">
                                    <button type="submit" class="btn  btn-danger formulario-eliminar">
                                        Eliminar
                                    </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">No hay Cotizaciones Disponibles</td>
                            </tr>
                            @endforelse
                        </Tbody>
                    </table>
                    </div>
                </div>
                <div class="modal fade " id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mimodalLabel">Ver Cotizacion</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-md-3   mb-3">
                                    <label for="verFecha" class="col-form-label">FECHA:</label>
                                    <input type="text" class="form-control " id="verFecha" readonly>
                                </div>
                                <div class="col-md-3   mb-3">
                                    <label for="verFechav" class="col-form-label">FECHA VALIDEZ:</label>
                                    <input type="text" class="form-control " id="verFechav" readonly>
                                </div>
                                <div class=" col-md-3   mb-3">
                                    <label for="verMoneda" class="col-form-label">MONEDA:</label>
                                    <input type="text" class="form-control " id="verMoneda" readonly>
                                </div>
                                <div class=" col-md-3   mb-3" id="divtasacambio">
                                    <label for="verTipocambio" class="col-form-label">TIPO DE CAMBIO:</label>
                                    <input type="text" class="form-control " id="verTipocambio" readonly>
                                </div>
                                <div class=" col-md-6   mb-3">
                                    <label for="verEmpresa" class="col-form-label">EMPRESA:</label>
                                    <input type="text" class="form-control " id="verEmpresa" readonly>
                                </div>
                                <div class=" col-md-6   mb-3">
                                    <label for="verCliente" class="col-form-label">CLIENTE:</label>
                                    <input type="text" class="form-control " id="verCliente" readonly>
                                </div>
                                <div class=" col-md-8   mb-3" id="divobservacion"> 
                                    <label for="verObservacion" class="col-form-label">OBSERVACION:</label>
                                    <input type="text" class="form-control " id="verObservacion" readonly>
                                </div>
                                <div class=" col-md-4   mb-3" id="divobservacion"> 
                                    <label for="verFormapago" class="col-form-label">FORMA DE PAGO:</label>
                                    <input type="text" class="form-control " id="verFormapago" readonly>
                                </div>
                                <div class=" col-md-4   mb-3">
                                <div class="input-group">
                                    <label for="verPrecioventasinigv" class="col-form-label input-group">PRECIO SIN IGV:</label>
                                    <span class="input-group-text" id="spancostoventasinigv"></span>
                                    <input type="text" class="form-control " id="verPrecioventasinigv" readonly>
                                </div> 
                                </div>
                                <div class=" col-md-4   mb-3">
                                <div class="input-group">
                                    <label for="verPrecioventaconigv" class="col-form-label input-group">PRECIO CON IGV:</label>
                                    <span class="input-group-text" id="spancostoventaconigv"></span>
                                    <input type="text" class="form-control " id="verPrecioventaconigv" readonly>
                                </div> 
                                </div> 
                                <div class=" col-md-4   mb-3"  > 
                                    <label for="verVendida" class="col-form-label">COTIZACION VENDIDA:</label>
                                    <input type="text" class="form-control " id="verVendida" readonly>
                                </div>
                                
                                 
                            </div>
                        </form>
                        <div class="table-responsive"  >
                        <table class="table table-bordered table-striped" id="detallesventa" >
                            <thead class="fw-bold text-primary">
                                <tr>
                                    <th>Producto</th> 
                                    <th>Observacion</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario(referencial)</th>
                                    <th>precio Unitario</th> 
                                    <th>Servicio Adicional</th> 
                                    <th>Costo Productos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr></tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="generarcotizacion"> Generar Pdf de la Cotizacion  </button>
                        <button type="button" class="btn btn-warning" id="realizarventa"  >Realizar Venta</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
                </div> 
            </div> 
            </div> 
        </div>     
</div>
@push('script')
<script src="{{ asset('admin/midatatable.js') }}"></script>
<script>
    var idventa="";
     const mimodal = document.getElementById('mimodal')
    mimodal.addEventListener('show.bs.modal', event => {

        const button = event.relatedTarget
        const id = button.getAttribute('data-id')
        var urlventa = "{{ url('admin/cotizacion/show') }}";
        $.get(urlventa + '/' + id, function(data) { 
            const modalTitle = mimodal.querySelector('.modal-title')
            modalTitle.textContent = `Ver Cotizacion Nro: `+data[0].numero ;
            idventa = id;
            document.getElementById("verFecha").value=data[0].fecha;     
            document.getElementById("verFechav").value=data[0].fechav;
            document.getElementById("verMoneda").value=data[0].moneda;   
            document.getElementById("verEmpresa").value=data[0].company; 
            document.getElementById("verCliente").value=data[0].cliente
            document.getElementById("verVendida").value=data[0].vendida; 
            document.getElementById("verFormapago").value=data[0].formapago; 
            document.getElementById("verPrecioventasinigv").value=data[0].costoventasinigv;  
            document.getElementById("verPrecioventaconigv").value=data[0].costoventaconigv;  
            if(data[0].moneda=="dolares"){
                document.getElementById('spancostoventasinigv').innerHTML = "$";
                document.getElementById('spancostoventaconigv').innerHTML = "$";}
            else if(data[0].moneda=="soles"){
                document.getElementById('spancostoventasinigv').innerHTML = "S/.";
                document.getElementById('spancostoventaconigv').innerHTML = "S/.";}

            document.getElementById("verTipocambio").value=data[0].tasacambio;   
             
            if(data[0].vendida=="NO"){document.getElementById('realizarventa').style.display = 'inline'; }
            else if(data[0].vendida=="SI"){document.getElementById('realizarventa').style.display = 'none';}

            if(data[0].observacion == null){
                document.getElementById('divobservacion').style.display = 'none';
            }else{ 
                document.getElementById('divobservacion').style.display = 'inline';
                document.getElementById("verObservacion").value=data[0].observacion;  
            }
            
            
            var monedafactura=data[0].moneda;
            var simbolomonedaproducto="";
            var simbolomonedafactura="";

            
            if(monedafactura=="dolares"){simbolomonedafactura="$";}
            else if(monedafactura=="soles"){simbolomonedafactura="S/.";}
             
            var tabla = document.getElementById(detallesventa);
            $('#detallesventa tbody tr').slice().remove();
            for(var i =0 ; i<data.length;i++){
            var monedaproducto=data[i].monedaproducto;
            if(monedaproducto=="dolares"){simbolomonedaproducto="$";}
            else if(monedaproducto=="soles"){simbolomonedaproducto="S/.";}

                filaDetalle ='<tr id="fila' + i + 
                '"><td><input  type="hidden" name="LEmpresa[]" value="' + data[i].producto  + '"required>'+ data[i].producto+
                    '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].observacionproducto + '"required>'+ data[i].observacionproducto+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].cantidad + '"required>'+ data[i].cantidad+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].preciounitario + '"required>'+simbolomonedaproducto+ data[i].preciounitario+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].preciounitariomo + '"required>'+simbolomonedafactura+ data[i].preciounitariomo+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].servicio + '"required>'+simbolomonedafactura+ data[i].servicio+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].preciofinal + '"required>'+simbolomonedafactura+ data[i].preciofinal+ 
                '</td></tr>';
               
                $("#detallesventa>tbody").append(filaDetalle);
            }
                 
        });

    })
        window.addEventListener('close-modal', event => {
            $('#deleteModal').modal('hide');
        });

    // $('#realizarventa').click(function() {
    //     var urlventa = "{{ url('/admin/venta/pagarfactura') }}";
    //     Swal.fire({
    //             title: '¿Esta seguro que desea pagar?',
    //             //text: "No lo podra revertir!",
    //             icon: 'warning',
    //             showCancelButton: true,
    //             confirmButtonColor: '#3085d6',
    //             cancelButtonColor: '#d33',
    //             confirmButtonText: 'Sí,Pagar!'
    //         }).then((result) => {
    //         if (result.isConfirmed) {
 
    //         $.get(urlventa + '/' + idventa, function(data) {
    //             $('#mimodal').modal('hide');
    //             if(data[0]==1){  
    //                 document.getElementById('ventapagada'+idventa).innerHTML = "SI"; 
    //         Swal.fire({
    //             text: "Factura Pagada",
    //             icon: "success"
    //         });   
    //             }else if(data[0]==0){
    //                 Swal.fire({
    //                 text: "No se puede pagar",
    //                 icon: "error"
    //                 }); 
    //             }else if(data[0]==2){
    //                 Swal.fire({
    //             text: "registro no encontrado",
    //             icon: "error"
    //             });
                    
    //             } 
    //         });  
    //         }  
    //         })
    // });    

    $('#generarcotizacion').click(function() {
        generarfactura(idventa);
    });


    function generarfactura($id){
        if($id != -1){
            window.open( '/admin/venta/generarfacturapdf/' + $id );}
        }     

    </script>
@endpush

@endsection
@section('js') 
    <script>
        $('.formulario-eliminar').submit(function(e){
            e.preventDefault();
        
        Swal.fire({
        title: '¿Esta seguro de Eliminar?',
        text: "No lo podra revertir!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí,Eliminar!',
        cancelButtonText: 'Cancelar'
        }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
        })
    });
    </script>
@endsection