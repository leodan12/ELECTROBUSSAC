@extends('layouts.admin')
@push('css')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <style rel="stylesheet">
        #scroll {
            overflow: scroll;
            height: 600px;
        }
    </style>
@endpush
@section('content')
    <div>
        <div class="row">
            <div class="col-md-12">

                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>ROLES
                            @can('crear-rol')
                                <a href="{{ url('admin/rol/create') }}" class="btn btn-primary float-end">Añadir Rol</a>
                            @endcan
                        </h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <input type="text" class="form-control" id="input-search"
                                placeholder="Filtrar por nombre del rol...">
                        </div>
                        <div id="scroll" class="table-responsive">
                            <table class="table table-bordered table-striped dt-responsive nowrap" id="mitabla"
                                name="mitabla">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NOMBRE DEL ROL</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mantenimientos">
                                    @foreach ($roles as $rol)
                                        <tr>
                                            <td>{{ $rol->id }}</td>
                                            <td>{{ $rol->name }}</td>

                                            <td>
                                                @can('editar-rol')
                                                    <a href="{{ url('admin/rol/' . $rol->id . '/edit') }}"
                                                        class="btn btn-success">
                                                        Editar
                                                    </a>
                                                @endcan
                                                @can('eliminar-rol')
                                                    <form action="{{ url('admin/rol/' . $rol->id . '/delete') }}"
                                                        class="d-inline formulario-eliminar">
                                                        <button type="submit" class="btn btn-danger formulario-eliminar">
                                                            Eliminar
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        document.getElementById("input-search").addEventListener("input", onInputChange);

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
