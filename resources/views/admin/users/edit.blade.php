@extends('layouts.admin')
@push('css')
 <link href="{{ asset('admin/required.css') }}" rel="stylesheet" type="text/css" />
@endpush
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
                            <label class="form-label is-required">NOMBRE</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control borde" required />
                            @error('name') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">EMAIL</label>
                            <input type="email" name="email" readonly value="{{ $user->email }}"  class="form-control borde" required />
                            @error('email') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">CONTRASEÑA</label>
                            <input type="password" name="password" class="form-control borde" required/>
                            @error('password') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label is-required">Seleccionar Rol</label>
                            <select name="role_as" class="form-select">
                                <option value="" class="silver">Seleccione Rol</option>
                                <option value="0" {{ $user->role_as == '0' ? 'selected':''}}>Usuario</option>
                                <option value="1" {{ $user->role_as == '1' ? 'selected':''}}>Administrador</option>
                            </select> 
                    </div>
                    <div class="col-md-12 mb-3">
                        <button type= "submit" class="btn btn-primary text-white float-end">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection