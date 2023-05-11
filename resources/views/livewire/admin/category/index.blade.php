
<div>

    <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Categoria Eliminada</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form wire:submit.prevent="destroyCategory" >
            <div class="modal-body">
                <h6>¿Esta seguro de eliminar?</h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Sí,Eliminar</button>
            </div>
            </form>
        </div>
    </div>
    </div>
        <div class="row">
            <div class="col-md-12">
            
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4>CATEGORIA
                        <a href="{{ url('admin/category/create') }}" class="btn btn-primary float-end">Añadir Categoria</a>
                    </h4>
                </div>

                <div class="card-body">
                    <div>
                        <input type="text" class="form-control" id="input-search" placeholder="Filtrar por nombre...">
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NOMBRE</th> 
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-mantenimientos">
                            @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id}}</td>
                                <td>{{ $category->nombre}}</td> 
                                <td>
                                    <a href="{{ url('admin/category/'.$category->id.'/edit')}}" class="btn btn-success">Editar</a>
                                    <a href="#" wire:click="deleteCategory({{$category->id}})" data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger">Eliminar</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')

    <script>
        document.getElementById("input-search").addEventListener("input",onInputChange)

        window.addEventListener('close-modal', event => {
            $('#deleteModal').modal('hide');
        });

        function onInputChange(){
            let inputText = document.getElementById("input-search").value.toString().toLowerCase();
            /*console.log(inputText);*/
            let tableBody = document.getElementById("tbody-mantenimientos");
            let tableRows = tableBody.getElementsByTagName("tr");
            for(let i = 0; i < tableRows.length; i++){
                let textoConsulta = tableRows[i].cells[1].textContent.toString().toLowerCase();
                if(textoConsulta.indexOf(inputText) === -1){
                    tableRows[i].style.visibility = "collapse";
                }else{
                    tableRows[i].style.visibility = "";
                }
            
            }
        }
    </script>
@endpush