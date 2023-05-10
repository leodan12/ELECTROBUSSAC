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
                    <h4>REGISTRO DE INGRESOS
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
                                    <label for="fecha" class="col-form-label">FECHA:</label>
                                    <input type="text" class="form-control " id="verFecha" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="descripcion" class="col-form-label">NUMERO FACTURA:</label>
                                    <input type="text" class="form-control" id="verFactura" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="costoCompra" class="col-form-label">FORMA PAGO:</label>
                                    <input type="text" class="form-control" id="verFormapago" readonly>
                                </div>
                                <div class=" col-md-4   mb-5 " id="divfechav">
                                    <label for="costoCompra" class="col-form-label">FECHA VENCIMIENTO:</label>
                                    <input type="text" class="form-control" id="verFechav" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="moneda" class="col-form-label">MONEDA:</label>
                                    <input type="text" class="form-control " id="verMoneda" readonly>
                                </div>
                                <div class=" col-md-4   mb-5" id="divtasacambio">
                                    <label for="moneda" class="col-form-label">TIPO DE CAMBIO:</label>
                                    <input type="text" class="form-control " id="verTipocambio" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="empresa" class="col-form-label">EMPRESA:</label>
                                    <input type="text" class="form-control " id="verEmpresa" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <label for="cliente" class="col-form-label">CLIENTE:</label>
                                    <input type="text" class="form-control " id="verCliente" readonly>
                                </div>
                                <div class=" col-md-4   mb-5">
                                    <div class="input-group">
                                    <label for="moneda" class="col-form-label input-group">PRECIO COMPRA:</label>
                                    <span class="input-group-text" id="spancostoventa"></span>
                                    <input type="text" class="form-control " id="verPrecioventa" readonly>
                                </div> 
                                </div>
                                <div class=" col-md-4   mb-5" id="divobservacion">
                                    <label for="moneda" class="col-form-label">OBSERVACION:</label>
                                    <input type="text" class="form-control " id="verObservacion" readonly>
                                </div>
                                 
                            </div>
                        </form>
                        <div class="table-responsive">
                        <table class="table table-row-bordered gy-5 gs-5" id="detallesventa">
                            <thead class="fw-bold text-primary">
                                <tr>
                                    <th>Producto</th>
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
    const mimodal = document.getElementById('mimodal')
    mimodal.addEventListener('show.bs.modal', event => {

        const button = event.relatedTarget
        const id = button.getAttribute('data-id')
        var urlventa = "{{ url('admin/ingreso/show') }}";
        $.get(urlventa + '/' + id, function(data) {
            console.log(data);
            const modalTitle = mimodal.querySelector('.modal-title')
            modalTitle.textContent = `Ver Registro ${id}` 
            document.getElementById("verFecha").value=data[0].fecha;  
            document.getElementById("verFactura").value=data[0].factura;   
            document.getElementById("verMoneda").value=data[0].moneda;  
            document.getElementById("verFormapago").value=data[0].formapago; 
            document.getElementById("verEmpresa").value=data[0].company; 
            document.getElementById("verCliente").value=data[0].cliente; 
            document.getElementById("verPrecioventa").value=data[0].costoventa; 
            if(data[0].moneda=="dolares"){document.getElementById('spancostoventa').innerHTML = "$";}
            else if(data[0].moneda=="soles"){document.getElementById('spancostoventa').innerHTML = "S/.";} 

            if(data[0].fechav == null){
                document.getElementById('divfechav').style.display = 'none';
            }else{ 
                document.getElementById('divfechav').style.display = 'inline';
                document.getElementById("verFechav").value=data[0].fechav;  
            } 
            
                document.getElementById("verTipocambio").value=data[0].tasacambio;   
             
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

         
    </script>
@endpush

@endsection
@section('js')
  <!--  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

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