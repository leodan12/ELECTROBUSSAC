
<div>

    <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Eliminar Empresa</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form wire:submit.prevent="destroyCompany" >
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
                    <h4>MIS EMPRESAS
                        <a href="{{ url('admin/company/create') }}" class="btn btn-primary float-end">Añadir Empresa</a>
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
                                <th>RUC</th>
                                <th>TELEFONO</th> 
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <Tbody id="tbody-mantenimientos">
                            @foreach ($companies as $company)
                            <tr>
                                <td>{{ $company->id}}</td>
                                <td>{{ $company->nombre}}</td>
                                <td>{{ $company->ruc}}</td>
                                <td>{{ $company->telefono}}</td> 
                                <td>
                                    <a href="{{ url('admin/company/'.$company->id.'/edit')}}" class="btn btn-success">Editar</a>
                                    <button type="button" class="btn btn-secondary" data-id="{{ $company->id}}" data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
                                    <a href="#" wire:click="deleteCompany({{$company->id}})" data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger">Eliminar</a>
                                </td>
                            </tr>
                            @endforeach
                        </Tbody>
                    </table>
                    
                    <div>
                        {{ $companies->links() }}
                    </div>
                </div>


        <div class="modal fade" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="mimodalLabel">VER PROVEEDOR</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                               <div class="col-sm-12 col-lg-12 mb-5">
                                    <label for="vernombre" class="col-form-label">NOMBRE:</label>
                                    <input type="text" class="form-control" id="vernombre" readonly>
                                </div>
                                <div class="col-sm-5 col-lg-5 mb-5">
                                    <label for="verruc" class="col-form-label">RUC:</label>
                                    <input type="number" class="form-control" id="verruc" readonly>
                                </div>
                                <div class="col-sm-7 col-lg-7 mb-5" id="divemail">
                                    <label for="veremail" class="col-form-label">Email:</label>
                                    <input type="email" class="form-control" id="veremail" readonly>
                                </div>
                                <div class="col-sm-12 col-lg-12 mb-5" id="divdireccion">
                                    <label for="verdireccion" class="col-form-label">DIRECCION:</label>
                                    <input type="text" class="form-control" id="verdireccion" readonly>
                                </div>
                                <div class="col-sm-12 col-lg-12 mb-5" id="divtelefono">
                                    <label for="vertelefono" class="col-form-label">TELEFONO:</label>
                                    <input type="number" class="form-control" id="vertelefono" readonly>
                                </div>
                                <div class="col-md-6 mb-3" id="divlogo">
                                    <img id="verLogo" width="300px" height="150px" >
                                </div>
                                
                            </div>
                        </form>
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
<script>
    document.getElementById("input-search").addEventListener("input",onInputChange)
    const mimodal = document.getElementById('mimodal')
    mimodal.addEventListener('show.bs.modal', event => {

        const button = event.relatedTarget
        const id = button.getAttribute('data-id')
        var urlregistro = "{{ url('admin/company/show') }}";
        $.get(urlregistro + '/' + id, function(data) {
            console.log(data);

           
            const modalTitle = mimodal.querySelector('.modal-title')
            modalTitle.textContent = `Ver Registro ${id}` 
            
            document.getElementById("vernombre").value=data.nombre;  
            document.getElementById("verruc").value=data.ruc;
            document.getElementById("verdireccion").value=data.direccion;
            document.getElementById("vertelefono").value=data.telefono;
            document.getElementById("veremail").value=data.email;   
            document.getElementById("verLogo").src= "/logos/"+data.logo;   
            if(data.direccion == null){
                document.getElementById('divdireccion').style.display = 'none';
            }else{ 
                document.getElementById('divdireccion').style.display = 'inline';
                document.getElementById("verdireccion").value=data.direccion;  
            }
            if(data.email == null){
                document.getElementById('divemail').style.display = 'none';
            }else{ 
                document.getElementById('divemail').style.display = 'inline';
                document.getElementById("veremail").value=data.email;  
            }
            if(data.telefono == null){
                document.getElementById('divtelefono').style.display = 'none';
            }else{ 
                document.getElementById('divtelefono').style.display = 'inline';
                document.getElementById("vertelefono").value=data.telefono;  
            }
            if(data.logo == null){
                document.getElementById('divlogo').style.display = 'none';
            }else{ 
                document.getElementById('divlogo').style.display = 'inline';
                document.getElementById("verlogo").value=data.logo;  
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
