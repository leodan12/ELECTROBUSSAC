
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
                    <h4>KITS 
                        <a href="{{ url('admin/kits/create') }}" class="btn btn-primary float-end">Añadir Kit</a>
                    </h4>
                </div>
                <div class="card-body"> 
                    <table class="table table-bordered table-striped" style="width:100%" id="mitabla" name="mitabla" >
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>CATEGORIA</th>
                                <th>NOMBRE</th>
                                <th>UNIDAD</th>
                                <th>TIPO DE MONEDA</th>
                                <th>PRECIO SIN IGV</th>
                                
                                <th>PRECIO CON IGV</th>
                                
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <Tbody id="tbody-mantenimientos">
                           
                            @forelse ($kits as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>
                                    @if($item->category)
                                        {{$item->category->nombre}}
                                    @else
                                        No tiene Categoria
                                    @endif
                                </td>
                                <td>{{$item->nombre}}</td>
                                <td>{{$item->unidad}}</td>
                                <td>{{$item->moneda}}</td>
                                <td>{{$item->NoIGV}}</td>
                                <td>{{$item->SiIGV}}</td>
                                
                                <td>
                                    <a href="{{ url('admin/kits/'.$item->id.'/edit')}}" class="btn btn-success">Editar</a>
                                    <button type="button" class="btn btn-secondary" data-id="{{ $item->id}}" data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
                                    <form action="{{ url('admin/kits/'.$item->id.'/delete') }}" class="d-inline formulario-eliminar">
                                    <button type="submit" class="btn btn-danger formulario-eliminar">
                                        Eliminar
                                    </button>
                                    </form>
                        
                                   
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7">No hay Kits Disponibles</td>
                            </tr>
                            @endforelse
                        </Tbody>
                    </table>
                    <div>
                        
                    </div>
                </div>


        <div class="modal fade" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mimodalLabel">Ver Kit de Productos</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="col-sm-4  mb-3">
                                    <label for="vercategoria" class="col-form-label">CATEGORIA:</label>
                                    <input type="text" class="form-control" id="vercategoria" readonly>
                                </div>
                               
                                <div class="col-sm-8 mb-3">
                                    <label for="vernombre" class="col-form-label">NOMBRE:</label>
                                    <input type="text" class="form-control" id="vernombre" readonly>
                                </div>
                                <div class="col-sm-3   mb-3">
                                    <label for="vercodigo" class="col-form-label">CÓDIGO:</label>
                                    <input type="text" class="form-control" id="vercodigo" readonly>
                                </div>
                                <div class="col-sm-3   mb-3">
                                    <label for="verunidad" class="col-form-label">UNIDAD:</label>
                                    <input type="text" class="form-control" id="verunidad" readonly>
                                </div>
                                <div class="col-sm-3   mb-3">
                                    <label for="verund" class="col-form-label">UND:</label>
                                    <input type="text" class="form-control" id="verund" readonly>
                                </div>
                                <div class="col-sm-3   mb-3">
                                    <label for="vermoneda" class="col-form-label">TIPO DE MONEDA:</label>
                                    <input type="text" class="form-control" id="vermoneda" readonly>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <label for="vernoigv" class="col-form-label">PRECIO SIN IGV:</label>
                                    <input type="number" class="form-control" id="vernoigv" readonly>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <label for="versiigv" class="col-form-label">PRECIO CON IGV:</label>
                                    <input type="number" class="form-control" id="versiigv" readonly>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <label for="verminimo" class="col-form-label">PRECIO MÍNIMO:</label>
                                    <input type="number" class="form-control" id="verminimo" readonly>
                                </div>
                                <div class="col-sm-3 mb-3">
                                    <label for="vermaximo" class="col-form-label">PRECIO MÁXIMO:</label>
                                    <input type="number" class="form-control" id="vermaximo" readonly >
                                </div>
                                
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-row-bordered gy-5 gs-5" id="kits">
                                <thead class="fw-bold text-primary">
                                    <tr>
                                        <th>Producto</th>  
                                        <th>Cantidad</th>
                                        <th>Precio Unitario Referencial</th>
                                        <th>Precio Unitario</th>
                                        <th>Precio Total Por Producto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

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
        var urlregistro = "{{ url('admin/kits/show') }}";
        $.get(urlregistro + '/' + id, function(data) {
             console.log(data);
 
            const modalTitle = mimodal.querySelector('.modal-title')
            modalTitle.textContent = `Ver Kit de Productos ${id}` 
            
            document.getElementById("vercategoria").value=data[0].nombrecategoria;  
            document.getElementById("vernombre").value=data[0].nombre;
            document.getElementById("vercodigo").value=data[0].codigo;
            document.getElementById("verunidad").value=data[0].unidad;
            document.getElementById("verund").value=data[0].und;  
            document.getElementById("vermoneda").value=data[0].moneda;  
            document.getElementById("vernoigv").value=data[0].NoIGV; 
            document.getElementById("versiigv").value=data[0].SiIGV; 
            document.getElementById("verminimo").value=data[0].minimo; 
            document.getElementById("vermaximo").value=data[0].maximo; 
            
            var monedafactura=data[0].moneda;
            if(monedafactura=="dolares"){simbolomonedafactura="$";}
            else if(monedafactura=="soles"){simbolomonedafactura="S/.";}
            $('#kits tbody tr').slice().remove();
            for(var i =0 ; i<data.length;i++){
            var monedaproducto=data[i].kitproductmoneda; 
            if(monedaproducto=="dolares"){simbolomonedaproducto="$";}
            else if(monedaproducto=="soles"){simbolomonedaproducto="S/.";}

                filaDetalle ='<tr id="fila' + i + 
                '"><td><input  type="hidden" name="LEmpresa[]" value="' + data[i].kitproductname  + '"required>'+ data[i].kitproductname+
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].kitcantidad + '"required>'+ data[i].kitcantidad+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].kitpreciounitario + '"required>'+simbolomonedaproducto+ data[i].kitpreciounitario+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].kitpreciounitariomo + '"required>'+simbolomonedafactura+ data[i].kitpreciounitariomo+ 
                '</td><td><input  type="hidden" name="Lstockempresa[]" value="' + data[i].kitpreciofinal + '"required>'+simbolomonedafactura+ data[i].kitpreciofinal+ 
                '</td></tr>';
               
                $("#kits>tbody").append(filaDetalle);
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