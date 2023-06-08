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
                        <h4>USUARIOS
                            @can('crear-usuario')
                                <a href="{{ url('admin/users/create') }}" class="btn btn-primary float-end">Añadir Usuario</a>
                            @endcan
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="scroll" class="table-responsive">
                            <table class="table table-bordered table-striped dt-responsive nowrap scroll" id="mitabla"
                                name="mitabla">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>ROL</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-mantenimientos">
                                    @foreach ($usuarios as $usuario)
                                        <tr>
                                            <td>{{ $usuario->id }}</td>
                                            <td>{{ $usuario->name }}</td>
                                            <td>{{ $usuario->email }}</td>
                                            <td>
                                                @if (!empty($usuario->getRoleNames()))
                                                    @foreach ($usuario->getRoleNames() as $rolName)
                                                        <h5><span>{{ $rolName }}</span></h5>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @can('editar-usuario')
                                                    <a href="{{ url('admin/users/' . $usuario->id . '/edit') }}"
                                                        class="btn btn-success">
                                                        Editar
                                                    </a>
                                                @endcan
                                                @can('eliminar-usuario')
                                                    <form action="{{ url('admin/users/' . $usuario->id . '/delete') }}"
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
@endpush
