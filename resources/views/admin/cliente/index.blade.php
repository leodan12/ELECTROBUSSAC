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
                        <h4>CLIENTES / PROVEEDORES

                            <a href="{{ url('admin/cliente/create') }}" class="btn btn-primary float-end">Añadir
                                Cliente/Proveedor</a>
                            {{-- <a  class="btn btn-warning float-end" style="margin-right: 2em">Ver todos los registros</a>   --}}
                        </h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped display  nowrap" style="width:100%" id="mitabla"
                            name="mitabla">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th>RUC</th>
                                    <th>TELEFONO</th>
                                    <th>EMAIL</th>
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
                                        <td>{{ $cliente->email }}</td>
                                        <td>
                                            <a href="{{ url('admin/cliente/' . $cliente->id . '/edit') }}"
                                                class="btn btn-success">Editar</a>
                                            <button type="button" class="btn btn-secondary" data-id="{{ $cliente->id }}"
                                                data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
                                            <form action="{{ url('admin/cliente/' . $cliente->id . '/delete') }}"
                                                class="d-inline formulario-eliminar">
                                                <button type="submit" class="btn btn-danger formulario-eliminar">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </Tbody>
                        </table>


                    </div>


                    <div class="modal fade" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Empresa</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-sm-12 col-lg-12 mb-3">
                                                <label for="vernombre" class="col-form-label">NOMBRE:</label>
                                                <input type="text" class="form-control" id="vernombre" readonly>
                                            </div>
                                            <div class="col-sm-6 col-lg-6 mb-3">
                                                <label for="verruc" class="col-form-label">RUC:</label>
                                                <input type="number" class="form-control" id="verruc" readonly>
                                            </div>
                                            <div class="col-sm-6 col-lg-6 mb-3" id="divemail">
                                                <label for="veremail" class="col-form-label">EMAIL:</label>
                                                <input type="email" class="form-control" id="veremail" readonly>
                                            </div>
                                            <div class="col-sm-12 col-lg-12 mb-3" id="divdireccion">
                                                <label for="verdireccion" class="col-form-label">DIRECCION:</label>
                                                <input type="text" class="form-control" id="verdireccion" readonly>
                                            </div>
                                            <div class="col-sm-12 col-lg-12 mb-3" id="divtelefono">
                                                <label for="vertelefono" class="col-form-label">TELEFONO:</label>
                                                <input type="number" class="form-control" id="vertelefono" readonly>
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
@endsection
@push('script')
    <script src="{{ asset('admin/midatatable.js') }}"></script>
    <script>
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
                if (data.direccion == null) {
                    document.getElementById('divdireccion').style.display = 'none';
                } else {
                    document.getElementById('divdireccion').style.display = 'inline';
                    document.getElementById("verdireccion").value = data.direccion;
                }
                if (data.telefono == null) {
                    document.getElementById('divtelefono').style.display = 'none';
                } else {
                    document.getElementById('divtelefono').style.display = 'inline';
                    document.getElementById("vertelefono").value = data.telefono;
                }
                if (data.email == null) {
                    document.getElementById('divemail').style.display = 'none';
                } else {
                    document.getElementById('divemail').style.display = 'inline';
                    document.getElementById("veremail").value = data.email;
                }

            });

        })
        window.addEventListener('close-modal', event => {
            $('#deleteModal').modal('hide');
        });
    </script>
    <script>
        $('.formulario-eliminar').submit(function(e) {
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
@endpush
