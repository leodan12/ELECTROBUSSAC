@extends('layouts.admin')
@section('content')

<div>
        <div class="row">
            <div class="col-md-12">
            
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            @php  $numerodecreditos = count($creditosxvencer) @endphp
            <div class="card">
                <div class="card-header">
                    <h4>REGISTRO DE INGRESOS:  @if(count($creditosxvencer)>0)  &nbsp;&nbsp;Tienes {{ count($creditosxvencer) }} Compras o Ingresos por Pagar:&nbsp;
                        <button class="btn btn-info" data-bs-target="#modalCreditos1" data-bs-toggle="modal">  Ver</button>
                        @endif
                        <a href="{{ url('admin/ingreso/create') }}" class="btn btn-primary float-end">Añadir ingreso</a>
                    </h4>
                </div>

                <div class="card-body">
                 
                    <table class="table table-bordered table-striped display nowrap"  style="width:100%" id="mitabla" name="mitabla">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>FACTURA</th>
                                <th>FECHA</th>
                                <th>CLIENTE</th>
                                <th>EMPRESA</th>
                                <th>MONEDA</th>
                                <th>FORMA PAGO</th>
                                <th>COSTO COMPRA</th>
                                <th>PAGADA</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <Tbody id="tbody-mantenimientos">
                           
                            @forelse ($ingresos as $ingreso)
                            <tr>
                                <td>{{$ingreso->id}}</td>
                                <td>{{$ingreso->factura}}</td>
                                <td>{{$ingreso->fecha}}</td>
                                <td>
                                    @if($ingreso->cliente)
                                        {{$ingreso->cliente->nombre}}
                                    @else
                                        No esta la empresa registrada
                                    @endif
                                </td>
                                <td>
                                    @if($ingreso->company)
                                        {{$ingreso->company->nombre}}
                                    @else
                                        No esta la empresa registrada
                                    @endif
                                </td>
                                <td> {{$ingreso->moneda}}</td>
                                <td> {{$ingreso->formapago}}</td>
                                @if($ingreso->moneda == 'soles')
                                <td>S/. {{$ingreso->costoventa}}</td>
                                @elseif($ingreso->moneda == 'dolares')
                                <td>$. {{$ingreso->costoventa}}</td>
                                @endif
                                <td id="ventapagada{{$ingreso->id  }}">{{$ingreso->pagada}}</td>
                                
                                <td>
                                    <a href="{{ url('admin/ingreso/'.$ingreso->id.'/edit')}}" class="btn btn-success">Editar</a>
                                    <button type="button" class="btn btn-secondary" data-id="{{$ingreso->id}}" data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
                                    <form action="{{ url('admin/ingreso/'.$ingreso->id.'/delete') }}" class="d-inline formulario-eliminar">
                                    <button type="submit" class="btn btn-danger formulario-eliminar">
                                        Eliminar
                                    </button>
                                    </form>
                        
                                   
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">No hay Productos Disponibles</td>
                            </tr>
                            @endforelse
                        </Tbody>
                    </table>
                    <div>  
                    </div>
                </div>
                {{-- modal de ver venta --}}
            <div class="modal fade " id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mimodalLabel">Ver Ingreso</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-md-4   mb-5">
                                    <label for="verFecha" class="col-form-label">FECHA:</label>
                                    <input type="text" class="form-control " id="verFecha" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="verFactura" class="col-form-label">NUMERO FACTURA:</label>
                                    <input type="text" class="form-control" id="verFactura" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="verFormapago" class="col-form-label">FORMA PAGO:</label>
                                    <input type="text" class="form-control" id="verFormapago" readonly>
                                </div>
                                <div class=" col-md-4   mb-5 " id="divfechav">
                                    <label for="verFechav" class="col-form-label">FECHA VENCIMIENTO:</label>
                                    <input type="text" class="form-control" id="verFechav" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="verMoneda" class="col-form-label">MONEDA:</label>
                                    <input type="text" class="form-control " id="verMoneda" readonly>
                                </div>
                                <div class=" col-md-4   mb-5" id="divtasacambio">
                                    <label for="verTipocambio" class="col-form-label">TIPO DE CAMBIO:</label>
                                    <input type="text" class="form-control " id="verTipocambio" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="verEmpresa" class="col-form-label">EMPRESA:</label>
                                    <input type="text" class="form-control " id="verEmpresa" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="verCliente" class="col-form-label">CLIENTE:</label>
                                    <input type="text" class="form-control " id="verCliente" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <div class="input-group">
                                    <label for="verPrecioventa" class="col-form-label input-group">PRECIO COMPRA:</label>
                                    <span class="input-group-text" id="spancostoventa"></span>
                                    <input type="text" class="form-control " id="verPrecioventa" readonly>
                                </div> 
                                </div>
                                <div class=" col-md-4   mb-5" id="divobservacion">
                                    <label for="verObservacion" class="col-form-label">OBSERVACION:</label>
                                    <input type="text" class="form-control " id="verObservacion" readonly>
                                </div>
                                <div class=" col-md-4   mb-5"  > 
                                    <label for="verPagada" class="col-form-label">FACTURA PAGADA:</label>
                                    <input type="text" class="form-control " id="verPagada" readonly>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                        <table class="table table-row-bordered gy-5 gs-5" id="detallesventa">
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
                        <button type="button" class="btn btn-success" id="pagarfactura"  >Pagar Factura</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    </div>
                </div>
            </div>
        </div>
        
        {{-- mis modales para ver los creditos vencidos --}}
        <div class="modal fade" id="modalCreditos1" aria-hidden="true" aria-labelledby="modalCreditos1Label" tabindex="-1">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="modalCreditos1Label">
                    TIENES:  &nbsp;
                    {{ (count($creditosxvencer)-$nrocreditosvencidos) }} Compras a credito por vencer 
                    @if($nrocreditosvencidos>0)y  {{ ( $nrocreditosvencidos)}} Compras a creditos vencidas. @endif</h1>  
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped "  style="width: 100%" id="mitabla1" name="mitabla1">
                        <thead>
                            <tr>
                                <th>ID</th> 
                                <th>FECHA</th>
                                <th>FECHA VENC</th>
                                <th>CLIENTE</th>
                                <th>EMPRESA</th>
                                <th>MONEDA</th>
                                <th>FORMA PAGO</th>
                                <th>COSTO VENTA </th>
                                <th>PAGADA </th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <Tbody id="tbody-mantenimientos">
                           
                            @forelse ($creditosxvencer as $item)
                            @php  $fechahoy = date('Y-m-d')  @endphp
                            <tr @if($item->fechav < $fechahoy )  style="background-color: #f89f9f" @endif >
                                <td>{{$item->id}}</td> 
                                <td>{{$item->fecha}}</td>

                                <td>{{$item->fechav}}</td>

                                <td>{{$item->nombrecliente}}  </td>
                                <td>{{$item->nombreempresa}} </td>
                                <td> {{$item->moneda}}</td>
                                <td> {{$item->formapago}}</td>
                                @if($item->moneda == 'soles')
                                <td>S/. {{$item->costoventa}}</td>
                                @elseif($item->moneda == 'dolares')
                                <td>$ {{$item->costoventa}}</td>
                                @endif
                                <td id="ventapagada{{$item->id  }}">{{$item->pagada}}</td>
                                
                                <td>
                                    <a href="{{ url('admin/ingreso/'.$item->id.'/edit')}}" class="btn btn-success">Editar</a>
                                    <button type="button" class="btn btn-secondary" data-id="{{$item->id}}" data-bs-target="#modalVer2" data-bs-toggle="modal">Ver</button>
                                    <form action="{{ url('admin/ingreso/'.$item->id.'/delete') }}" class="d-inline formulario-eliminar">
                                    <button type="submit" class="btn btn-danger formulario-eliminar">
                                        Eliminar
                                    </button>
                                    </form>
                        
                                   
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">No hay Productos Disponibles</td>
                            </tr>
                            @endforelse
                        </Tbody>
                    </table>
                </div>
                <div class="modal-footer">
                   
                </div>
              </div>
            </div>
          </div>

 {{-- modal para ver los datosde los creditos x vencer --}}
          <div class="modal fade" id="modalVer2" aria-hidden="true" aria-labelledby="modalCreditos1Label2" tabindex="-1">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="modalCreditos1Label2">Ver Compra a Credito</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> 
                </div>
                <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-md-4   mb-5">
                                    <label for="verFecha1" class="col-form-label">FECHA:</label>
                                    <input type="text" class="form-control " id="verFecha1" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="verFactura1" class="col-form-label">NUMERO FACTURA:</label>
                                    <input type="text" class="form-control" id="verFactura1" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="verFormapago1" class="col-form-label">FORMA PAGO:</label>
                                    <input type="text" class="form-control" id="verFormapago1" readonly>
                                </div>
                                <div class=" col-md-4   mb-5 " id="divfechav1">
                                    <label for="verFechav1" class="col-form-label">FECHA VENCIMIENTO:</label>
                                    <input type="text" class="form-control" id="verFechav1" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="verMoneda1" class="col-form-label">MONEDA:</label>
                                    <input type="text" class="form-control " id="verMoneda1" readonly>
                                </div>
                                <div class=" col-md-4   mb-5" id="divtasacambio">
                                    <label for="verTipocambio1" class="col-form-label">TIPO DE CAMBIO:</label>
                                    <input type="text" class="form-control " id="verTipocambio1" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="verEmpresa1" class="col-form-label">EMPRESA:</label>
                                    <input type="text" class="form-control " id="verEmpresa1" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="verCliente1" class="col-form-label">CLIENTE:</label>
                                    <input type="text" class="form-control " id="verCliente1" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <div class="input-group">
                                    <label for="verPrecioventa1" class="col-form-label input-group">PRECIO VENTA:</label>
                                    <span class="input-group-text" id="spancostoventa1"></span>
                                    <input type="text" class="form-control " id="verPrecioventa1" readonly>
                                </div> 
                                </div>
                                <div class=" col-md-4   mb-5" id="divobservacion1"> 
                                    <label for="verObservacion1" class="col-form-label">OBSERVACION:</label>
                                    <input type="text" class="form-control " id="verObservacion1" readonly>
                                </div>
                                <div class=" col-md-4   mb-5"  > 
                                    <label for="verPagada1" class="col-form-label">FACTURA PAGADA:</label>
                                    <input type="text" class="form-control " id="verPagada1" readonly>
                                </div>
                                 
                            </div>
                        </form>
                        <div class="table-responsive">
                        <table class="table table-row-bordered gy-5 gs-5" id="detallesventa1">
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
                    <button type="button" class="btn btn-success" id="generarfactura1"> Generar Pdf de la Factura  </button>
                    <button type="button" class="btn btn-warning" id="pagarfactura1"  >Pagar Factura</button>
                  <button class="btn btn-primary" data-bs-target="#modalCreditos1" data-bs-toggle="modal">Volver</button>
                </div>
              </div>
            </div>
          </div>
          {{-- fin del modal --}}

                </div> 
            </div> 
            </div> 
        </div>     
</div>
@push('script')

<script src="{{ asset('admin/midatatable.js') }}"></script>

<script> 
  var idventa="";
  var nrocreditos=0;
    nrocreditos = @json($numerodecreditos);
  //para el modal de ver venta
    const mimodal = document.getElementById('mimodal')
    mimodal.addEventListener('show.bs.modal', event => {

        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        var urlventa = "{{ url('admin/ingreso/show') }}";
        $.get(urlventa + '/' + id, function(data) { 
            idventa = id;
            const modalTitle = mimodal.querySelector('.modal-title')
            modalTitle.textContent = `Ver Registro ${id}` 
            document.getElementById("verFecha").value=data[0].fecha;  
            document.getElementById("verFactura").value=data[0].factura;   
            document.getElementById("verMoneda").value=data[0].moneda;  
            document.getElementById("verFormapago").value=data[0].formapago; 
            document.getElementById("verEmpresa").value=data[0].company; 
            document.getElementById("verCliente").value=data[0].cliente; 
            document.getElementById("verPagada").value=data[0].pagada; 
            document.getElementById("verPrecioventa").value=(data[0].costoventa).toFixed(2); 
            if(data[0].moneda=="dolares"){document.getElementById('spancostoventa').innerHTML = "$";}
            else if(data[0].moneda=="soles"){document.getElementById('spancostoventa').innerHTML = "S/.";} 

            if(data[0].fechav == null){
                document.getElementById('divfechav').style.display = 'none';
            }else{ 
                document.getElementById('divfechav').style.display = 'inline';
                document.getElementById("verFechav").value=data[0].fechav;  
            } 
            document.getElementById("verTipocambio").value=data[0].tasacambio;   
            if(data[0].pagada=="NO"){document.getElementById('pagarfactura').style.display = 'inline'; }
            else if(data[0].pagada=="SI"){document.getElementById('pagarfactura').style.display = 'none';}

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

//mostrar el modal de los datos de los creditos
     
const mimodalcreditos = document.getElementById('modalVer2')
    mimodalcreditos.addEventListener('show.bs.modal', event => {

        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        var urlventa = "{{ url('admin/ingreso/show') }}";
        $.get(urlventa + '/' + id, function(data) { 
            const modalTitle = mimodalcreditos.querySelector('.modal-title')
            modalTitle.textContent = `Ver Registro ${id}` ;
            idventa = id;
            document.getElementById("verFecha1").value=data[0].fecha;  
            document.getElementById("verFactura1").value=data[0].factura;   
            document.getElementById("verMoneda1").value=data[0].moneda;  
            document.getElementById("verFormapago1").value=data[0].formapago; 
            document.getElementById("verEmpresa1").value=data[0].company; 
            document.getElementById("verCliente1").value=data[0].cliente
            document.getElementById("verPagada1").value=data[0].pagada; 
            document.getElementById("verPrecioventa1").value=data[0].costoventa;  
            if(data[0].moneda=="dolares"){document.getElementById('spancostoventa1').innerHTML = "$";}
            else if(data[0].moneda=="soles"){document.getElementById('spancostoventa1').innerHTML = "S/.";}

            if(data[0].fechav == null){
                document.getElementById('divfechav1').style.display = 'none';
            }else{ 
                document.getElementById('divfechav1').style.display = 'inline';
                document.getElementById("verFechav1").value=data[0].fechav;  
            } 
                document.getElementById("verTipocambio1").value=data[0].tasacambio;   
             
            if(data[0].pagada=="NO"){document.getElementById('pagarfactura1').style.display = 'inline'; }
            else if(data[0].pagada=="SI"){document.getElementById('pagarfactura1').style.display = 'none';}

            if(data[0].observacion == null){
                document.getElementById('divobservacion1').style.display = 'none';
            }else{ 
                document.getElementById('divobservacion1').style.display = 'inline';
                document.getElementById("verObservacion1").value=data[0].observacion;  
            }
            
            
            var monedafactura=data[0].moneda;
            var simbolomonedaproducto="";
            var simbolomonedafactura="";

            
            if(monedafactura=="dolares"){simbolomonedafactura="$";}
            else if(monedafactura=="soles"){simbolomonedafactura="S/.";}
             
            var tabla = document.getElementById(detallesventa);
            $('#detallesventa1 tbody tr').slice().remove();
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
               
                $("#detallesventa1>tbody").append(filaDetalle);
            }
                 
        });

    })

    //fin de los modales
        window.addEventListener('close-modal', event => {
            $('#deleteModal').modal('hide');
        });

        // $( document ).ready(function() {
        // $('#modalCreditos1').modal('toggle')
        // });

        $('#pagarfactura').click(function() {
            pagarfacturaingreso();
        }); 
        $('#pagarfactura1').click(function() {
            pagarfacturaingreso();
        });



    function pagarfacturaingreso(){
        var urlventa = "{{ url('/admin/ingreso/pagarfactura') }}";
        Swal.fire({
                title: '¿Esta seguro que desea pagar?',
                //text: "No lo podra revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí,Pagar!'
            }).then((result) => {
            if (result.isConfirmed) {
 
            $.get(urlventa + '/' + idventa, function(data) {
                $('#mimodal').modal('hide');
                $('#modalVer2').modal('hide');
                if(data[0]==1){  
                    document.getElementById('ventapagada'+idventa).innerHTML = "SI"; 
            Swal.fire({
                text: "Factura Pagada",
                icon: "success"
            });   
                }else if(data[0]==0){
                    Swal.fire({
                    text: "No se puede pagar",
                    icon: "error"
                    }); 
                }else if(data[0]==2){
                    Swal.fire({
                text: "registro no encontrado",
                icon: "error"
                });
                    
                } 
            }); 

            } 
            
            });

     }    

     if(nrocreditos>0){
        
        $('#mitabla1').DataTable({
            "language": {
           "sProcessing":     "Procesando...",
           "sLengthMenu":     "Mostrar _MENU_ registros",
           "sZeroRecords":    "No se encontraron resultados",
           "sEmptyTable":     "Ningún dato disponible en esta tabla",
           "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
           "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
           "sInfoFiltered":   "( filtrado de un total de _MAX_ registros )",
           "sInfoPostFix":    "",
           "sSearch":         "Buscar Registro:",
           "sUrl":            "",
           "sInfoThousands":  ",",
           "sLoadingRecords": "Cargando...",
           "loadingRecords": "Cargando...",
            "processing": "Procesando...",
           "oPaginate": {
               "sFirst":    "Primero",
               "sLast":     "Último",
               "sNext":     "Siguiente",
               "sPrevious": "Anterior"
           },
           "oAria": {
               "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
               "sSortDescending": ": Activar para ordenar la columna de manera descendente"
           }
        },  "order": [[ 0, "desc" ]], 
        scrollX: true,
        });
            }
    </script> 
@endpush

@endsection
@section('js') 
    <script src="{{ asset('admin/sweetalert.min.js') }}"></script>
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
        confirmButtonText: 'Sí,Eliminar!'
        }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
        })
    });
    </script>
@endsection