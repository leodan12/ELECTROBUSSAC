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
                    <form action="{{ url('admin/users/' . $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="name" value="{{ $user->name }}" class="form-control "
                                    required />
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">EMAIL</label>
                                <input type="email" name="email" value="{{ $user->email }}" class="form-control "
                                    required />
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label is-required">CONTRASEÑA</label>
                                <input type="password" name="password" class="form-control " />
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">Rol</label>
                                <select name="roles" class="form-select">
                                    <option value="" disabled selected>Seleccione un Rol</option>
                                    @foreach ($roles as $rol)
                                        @if ($userRole)
                                            @if ($rol->id == $userRole->role_id)
                                                <option value="{{ $rol->id }}" selected>{{ $rol->name }}</option>
                                            @else
                                                <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                                            @endif
                                        @else
                                            <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label is-required">Seleccionar Estado</label>
                                <select name="status" class="form-select " required>
                                    <option value="" selected disabled>Seleccione Rol</option>
                                    @if ($user->status == '1')
                                        <option value="activo" selected>Activo</option>
                                        <option value="inactivo">Inactivo</option>
                                    @else
                                        <option value="activo">Activo</option>
                                        <option value="inactivo" selected>Inactivo</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <button type="submit" class="btn btn-primary text-white float-end">Actualizar</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
