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
                        <h4>PRODUCTOS
                            <a href="{{ url('admin/products/create') }}" class="btn btn-primary float-end">Añadir Producto</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="mitabla" name="mitabla">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>CATEGORIA</th>
                                    <th>NOMBRE</th>
                                    <th>CODIGO</th>
                                    <th>UNIDAD</th>
                                    <th>TIPO DE MONEDA</th>
                                    <th>PRECIO SIN IGV</th>
                                    <th>PRECIO CON IGV</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <Tbody id="tbody-mantenimientos">

                                @forelse ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>
                                            @if ($product->category)
                                                {{ $product->category->nombre }}
                                            @else
                                                No tiene Categoria
                                            @endif
                                        </td>
                                        <td>{{ $product->nombre }}</td>
                                        <td>{{ $product->codigo }}</td>
                                        <td>{{ $product->unidad }}</td>
                                        <td>{{ $product->moneda }}</td>
                                        <td>{{ $product->NoIGV }}</td>
                                        <td>{{ $product->SiIGV }}</td>

                                        <td>
                                            <a href="{{ url('admin/products/' . $product->id . '/edit') }}"
                                                class="btn btn-success">Editar</a>
                                            <button type="button" class="btn btn-secondary" data-id="{{ $product->id }}"
                                                data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
                                            <form action="{{ url('admin/products/' . $product->id . '/delete') }}"
                                                class="d-inline formulario-eliminar">
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


                    <div class="modal fade" id="mimodal" tabindex="-1" aria-labelledby="mimodal" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="mimodalLabel">Ver Producto</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
                                            <div class="col-sm-4   mb-3">
                                                <label for="vercodigo" class="col-form-label">CÓDIGO:</label>
                                                <input type="text" class="form-control" id="vercodigo" readonly>
                                            </div>
                                            <div class="col-sm-4   mb-3">
                                                <label for="verunidad" class="col-form-label">UNIDAD:</label>
                                                <input type="text" class="form-control" id="verunidad" readonly>
                                            </div>
                                            <div class="col-sm-4   mb-3">
                                                <label for="verund" class="col-form-label">UND:</label>
                                                <input type="text" class="form-control" id="verund" readonly>
                                            </div>
                                            <div class="col-sm-4   mb-3">
                                                <label for="vermoneda" class="col-form-label">TIPO DE MONEDA:</label>
                                                <input type="text" class="form-control" id="vermoneda" readonly>
                                            </div>
                                            <div class="col-sm-4 mb-3">
                                                <label for="vernoigv" class="col-form-label">PRECIO SIN IGV:</label>
                                                <input type="number" class="form-control" id="vernoigv" readonly>
                                            </div>
                                            <div class="col-sm-4 mb-3">
                                                <label for="versiigv" class="col-form-label">PRECIO CON IGV:</label>
                                                <input type="number" class="form-control" id="versiigv" readonly>
                                            </div>
                                            <div class="col-sm-4 mb-3">
                                                <label for="verminimo" class="col-form-label">PRECIO MÍNIMO:</label>
                                                <input type="number" class="form-control" id="verminimo" readonly>
                                            </div>
                                            <div class="col-sm-4 mb-3">
                                                <label for="vermaximo" class="col-form-label">PRECIO MÁXIMO:</label>
                                                <input type="number" class="form-control" id="vermaximo" readonly>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>

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
                var urlregistro = "{{ url('admin/products/show') }}";
                $.get(urlregistro + '/' + id, function(data) {
                    console.log(data);


                    const modalTitle = mimodal.querySelector('.modal-title')
                    modalTitle.textContent = `Ver Producto ${id}`

                    document.getElementById("vercategoria").value = data.nombrecategoria;
                    document.getElementById("vernombre").value = data.nombre;
                    document.getElementById("vercodigo").value = data.codigo;
                    document.getElementById("verunidad").value = data.unidad;
                    document.getElementById("verund").value = data.und;
                    document.getElementById("vermoneda").value = data.moneda;
                    document.getElementById("vernoigv").value = data.NoIGV;
                    document.getElementById("versiigv").value = data.SiIGV;
                    document.getElementById("verminimo").value = data.minimo;
                    document.getElementById("vermaximo").value = data.maximo;

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
@endsection
