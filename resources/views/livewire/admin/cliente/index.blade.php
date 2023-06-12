<div>

    <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"> Eliminar Cliente/Proveedor</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="destroyCliente">
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
                    <h4>CLIENTES / PROVEEDORES
                        <a href="{{ url('admin/cliente/create') }}" class="btn btn-primary float-end">Añadir
                            Cliente/Proveedor</a>
                    </h4>
                </div>
                <div class="card-body">
                    <div>
                        <input type="text" class="form-control" id="input-search"
                            placeholder="Filtrar por nombre...">
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NOMBRE</th>
                                <th>RUC</th>
                                <th>TELEFONO</th>
                                <th>ESTADO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <Tbody id="tbody-mantenimientos">
                            @foreach ($clientes as $cliente)
                                <tr>
                                    <td>{{ $cliente->id }}</td>
                                    <td>{{ $cliente->nombre }}</td>
                                    <td>{{ $cliente->ruc }}</td>
                                    <td>{{ $cliente->telefono }}</td>
                                    <td>{{ $cliente->status == '1' ? 'Oculto' : 'Visible' }}</td>
                                    <td>
                                        <a href="{{ url('admin/cliente/' . $cliente->id . '/edit') }}"
                                            class="btn btn-success">Editar</a>
                                        <button type="button" class="btn btn-secondary" data-id="{{ $cliente->id }}"
                                            data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
                                        <a href="#" wire:click="deleteCliente({{ $cliente->id }})"
                                            data-bs-toggle="modal" data-bs-target="#deleteModal"
                                            class="btn btn-danger">Eliminar</a>
                                    </td>
                                </tr>
                            @endforeach
                        </Tbody>
                    </table>

                    <div>
                        {{ $clientes->links() }}
                    </div>
                </div>


                <div class="modal fade" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="mimodalLabel">Ver Empresa</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="row">
                                        <div class="col-sm-6 col-lg-6 mb-5">
                                            <label for="nombre" class="col-form-label">NOMBRE:</label>
                                            <input type="text" class="form-control" id="vernombre" readonly>
                                        </div>
                                        <div class="col-sm-12 col-lg-12 mb-5">
                                            <label for="ruc" class="col-form-label">RUC:</label>
                                            <input type="number" class="form-control" id="verruc" readonly>
                                        </div>
                                        <div class="col-sm-12 col-lg-12 mb-5">
                                            <label for="direccion" class="col-form-label">DIRECCION:</label>
                                            <input type="text" class="form-control" id="verdireccion" readonly>
                                        </div>
                                        <div class="col-sm-12 col-lg-12 mb-5">
                                            <label for="telefono" class="col-form-label">TELEFONO:</label>
                                            <input type="number" class="form-control" id="vertelefono" readonly>
                                        </div>
                                        <div class="col-sm-12 col-lg-12 mb-5">
                                            <label for="email" class="col-form-label">EMAIL:</label>
                                            <input type="email" class="form-control" id="veremail" readonly>
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
        document.getElementById("input-search").addEventListener("input", onInputChange)
        const mimodal = document.getElementById('mimodal')
        mimodal.addEventListener('show.bs.modal', event => {

            const button = event.relatedTarget
            const id = button.getAttribute('data-id')
            var urlregistro = "{{ url('admin/cliente/show') }}";
            $.get(urlregistro + '/' + id, function(data) {
                console.log(data);


                const modalTitle = mimodal.querySelector('.modal-title')
                modalTitle.textContent = `Ver Registro ${id}`

                document.getElementById("vernombre").value = data.nombre;
                document.getElementById("verruc").value = data.ruc;
                document.getElementById("verdireccion").value = data.direccion;
                document.getElementById("vertelefono").value = data.telefono;
                document.getElementById("veremail").value = data.email;

            });

        })
        window.addEventListener('close-modal', event => {
            $('#deleteModal').modal('hide');
        });

        function onInputChange() {
            let inputText = document.getElementById("input-search").value.toString().toLowerCase();
            /*console.log(inputText);*/
            let tableBody = document.getElementById("tbody-mantenimientos");
            let tableRows = tableBody.getElementsByTagName("tr");
            for (let i = 0; i < tableRows.length; i++) {
                let textoConsulta = tableRows[i].cells[1].textContent.toString().toLowerCase();
                if (textoConsulta.indexOf(inputText) === -1) {
                    tableRows[i].style.visibility = "collapse";
                } else {
                    tableRows[i].style.visibility = "";
                }

            }
        }
    </script>
@endpush
