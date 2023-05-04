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
                    <h4>REGISTRO DE VENTA
                        <a href="{{ url('admin/venta/create') }}" class="btn btn-primary float-end">Añadir venta</a>
                    </h4>
                </div>

                <div class="card-body">
                <div>
                        <input type="text" class="form-control" id="input-search" placeholder="Filtrar por cliente...">
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>FACTURA</th>
                                <th>FECHA</th>
                                <th>CLIENTE</th>
                                <th>EMPRESA</th>
                                <th>MONEDA</th>
                                <th>FORMA DE PAGO</th>
                                <th>COSTO DE LA VENTA</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <Tbody id="tbody-mantenimientos">
                           
                            @forelse ($ventas as $venta)
                            <tr>
                                <td>{{$venta->id}}</td>
                                <td>{{$venta->factura}}</td>
                                <td>{{$venta->fecha}}</td>
                                <td>
                                    @if($venta->cliente)
                                        {{$venta->cliente->nombre}}
                                    @else
                                        No esta la empresa registrada
                                    @endif
                                </td>
                                <td>
                                    @if($venta->company)
                                        {{$venta->company->nombre}}
                                    @else
                                        No esta la empresa registrada
                                    @endif
                                </td>
                                <td> {{$venta->moneda}}</td>
                                <td> {{$venta->formapago}}</td>
                                @if($venta->moneda == 'soles')
                                <td>S/. {{$venta->costoventa}}</td>
                                @elseif($venta->moneda == 'dolares')
                                <td>$. {{$venta->costoventa}}</td>
                                @endif
                                
                                
                                <td>
                                    <a href="{{ url('admin/venta/'.$venta->id.'/edit')}}" class="btn btn-success">Editar</a>
                                    <button type="button" class="btn btn-secondary" data-id="{{$venta->id}}" data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
                                    <form action="{{ url('admin/venta/'.$venta->id.'/delete') }}" class="d-inline formulario-eliminar">
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
                        <h1 class="modal-title fs-5" id="mimodalLabel">Ver Venta</h1>
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
                                    <label for="moneda" class="col-form-label">PRECIO VENTA:</label>
                                    <input type="text" class="form-control " id="verPrecioventa" readonly>
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
<script>
    document.getElementById("input-search").addEventListener("input",onInputChange)
    const mimodal = document.getElementById('mimodal')
    mimodal.addEventListener('show.bs.modal', event => {

        const button = event.relatedTarget
        const id = button.getAttribute('data-id')
        var urlventa = "{{ url('admin/venta/show') }}";
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

            if(data[0].fechav == null){
                document.getElementById('divfechav').style.display = 'none';
            }else{ 
                document.getElementById('divfechav').style.display = 'inline';
                document.getElementById("verFechav").value=data[0].fechav;  
            }
            if(data[0].tasacambio == null){
                document.getElementById('divtasacambio').style.display = 'none';
            }else{ 
                document.getElementById('divtasacambio').style.display = 'inline';
                document.getElementById("verTipocambio").value=data[0].tasacambio;   
            }
            if(data[0].observacion == null){
                document.getElementById('divobservacion').style.display = 'none';
            }else{ 
                document.getElementById('divobservacion').style.display = 'inline';
                document.getElementById("verObservacion").value=data[0].observacion;  
            }
            
            
             
            var tabla = document.getElementById(detallesventa);
            $('#detallesventa tbody tr').slice().remove();
            for(var i =0 ; i<data.length;i++){
                filaDetalle ='<tr id="fila' + i + 
                '"><td><input  type="hidden" name="LEmpresa[]" value="' + data[i].producto  + '"required>'+ data[i].producto+
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].cantidad + '"required>'+ data[i].cantidad+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].preciounitario + '"required>'+ data[i].preciounitario+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].preciounitariomo + '"required>'+ data[i].preciounitariomo+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].servicio + '"required>'+ data[i].servicio+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].preciofinal + '"required>'+ data[i].preciofinal+ 
                '</td></tr>';
               
                $("#detallesventa>tbody").append(filaDetalle);
            }
                 
        });

    })
        window.addEventListener('close-modal', event => {
            $('#deleteModal').modal('hide');
        });

        function onInputChange(){
            let inputText = document.getElementById("input-search").value.toString().toLowerCase();
            /*console.log(inputText);*/
            let tableBody = document.getElementById("tbody-mantenimientos");
            let tableRows = tableBody.getElementsByTagName("tr");
            for(let i = 0; i < tableRows.length; i++){
                let textoConsulta = tableRows[i].cells[3].textContent.toString().toLowerCase();
                if(textoConsulta.indexOf(inputText) === -1){
                    tableRows[i].style.visibility = "collapse";
                }else{
                    tableRows[i].style.visibility = "";
                }
            
            }
        }
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