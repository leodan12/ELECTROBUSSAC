@extends('layouts.admin')

@section('content')

<div class="row">
    <div class="col-md-12">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <p>Corrige los siguientes errores:</p>
            <ul>
                                @foreach ($errors->all() as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
    @endif

    
        <div class="card">
            <div class="card-header">
                <h4>EDITAR USUARIO
                    <a href="{{ url('admin/users') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ url('admin/users/'.$user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>NOMBRE</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control" />
                            @error('name') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>EMAIL</label>
                            <input type="email" name="email" readonly value="{{ $user->email }}"  class="form-control" />
                            @error('email') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>CONTRASEÑA</label>
                            <input type="password" name="password" class="form-control" />
                            @error('password') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Seleccionar Rol</label>
                            <select name="role_as" class="form-control">
                                <option value="">Seleccione Rol</option>
                                <option value="0" {{ $user->role_as == '0' ? 'selected':''}}>Usuario</option>
                                <option value="1" {{ $user->role_as == '1' ? 'selected':''}}>Administrador</option>
                            </select>
                        <div class="col-md-12 mb-3">
                            <button type= "submit" class="btn btn-primary text-white float-end">Actualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection