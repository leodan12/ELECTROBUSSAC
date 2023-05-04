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
                <div>
                        <input type="text" class="form-control" id="input-search" placeholder="Filtrar por producto...">
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>FACTURA</th>
                                <th>FECHA</th> 
                                <th>EMPRESA</th>
                                <th>PROVEEDOR</th>
                                <th>MONEDA</th>
                                <th>FORMA PAGO</th>
                                <th>COSTO DE LA COMPRA</th>
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
                                    @if($ingreso->company)
                                        {{$ingreso->company->nombre}}
                                    @else
                                        No esta la empresa registrada
                                    @endif
                                </td>
                                <td>
                                    @if($ingreso->cliente)
                                        {{$ingreso->cliente->nombre}}
                                    @else
                                        No esta la empresa registrada
                                    @endif
                                </td>
                                
                                <td> {{$ingreso->moneda}}</td>
                                <td>  {{$ingreso->formapago}}</td>
                                <td>S/. {{$ingreso->costoventa}}</td>
                                
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
                <div class="modal fade modal-lg" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mimodalLabel">Ver Inventario</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-sm-4 col-lg-4 mb-5">
                                    <label for="fecha" class="col-form-label">PRODUCTO:</label>
                                    <input type="text" class="form-control " id="verProducto" readonly>
                                </div>
                                <div class="col-sm-4 col-lg-4 mb-5">
                                    <label for="descripcion" class="col-form-label">STOCK MINIMO:</label>
                                    <input type="number" class="form-control" id="verStockminimo" readonly>
                                </div>
                                <div class="col-sm-4 col-lg-4 mb-5">
                                    <label for="costoCompra" class="col-form-label">STOCK TOTAL:</label>
                                    <input type="number" class="form-control" id="verStocktotal" readonly>
                                </div>
                                 
                            </div>
                        </form>
                        <div class="table-responsive">
                        <table class="table table-row-bordered gy-5 gs-5" id="detallesInventario">
                            <thead class="fw-bold text-primary">
                                <tr>
                                    <th>Empresa</th>
                                    <th>Stock por Empresa</th>
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
        var urlinventario = "{{ url('admin/inventario/show') }}";
        $.get(urlinventario + '/' + id, function(data) {
            console.log(data);
            const modalTitle = mimodal.querySelector('.modal-title')
            modalTitle.textContent = `Ver Registro ${id}` 
            document.getElementById("verProducto").value=data[0].nombre;  
            document.getElementById("verStockminimo").value=data[0].stockminimo;  
            document.getElementById("verStocktotal").value=data[0].stocktotal;  
  ;  
             
            var tabla = document.getElementById(detallesInventario);
            $('#detallesInventario tbody tr').slice().remove();
            for(var i =0 ; i<data.length;i++){
                filaDetalle ='<tr id="fila' + i + 
                '"><td><input  type="hidden" name="LEmpresa[]" value="' + data[i].nombrempresa  + '"required>'+ data[i].nombrempresa+
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].stockempresa + '"required>'+ data[i].stockempresa+ 
                '</td></tr>';
               
                $("#detallesInventario>tbody").append(filaDetalle);
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
                let textoConsulta = tableRows[i].cells[2].textContent.toString().toLowerCase();
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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