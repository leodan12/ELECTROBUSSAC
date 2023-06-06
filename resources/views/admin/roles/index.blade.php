@extends('layouts.admin')
@push('css')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
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
                        <div class="table-responsive">
                        <table class="table table-bordered table-striped dt-responsive nowrap" id="mitabla" name="mitabla">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NAME</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-mantenimientos">
                                @foreach($roles as $rol)
                                <tr>
                                    <td>{{ $rol->id }}</td>
                                    <td>{{ $rol->name }}</td>
                                    
                                    <td>
                                        @can('editar-rol')
                                        <a href="{{ url('admin/rol/' . $rol->id . '/edit') }}" class="btn btn-success">
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
   
@endpush

 
