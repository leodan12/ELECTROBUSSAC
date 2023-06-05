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
                    <h4>EDITAR ROL
                        <a href="{{ url('admin/rol') }}" class="btn btn-primary text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/rol/' . $role->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="name" class="form-control borde" required
                                    value="{{ $role->name }}" />
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <div class="col-md-12 mb-3">
                                <div class="row">
                                    <div class="col">
                                        <label class="form-label is-required">Permisos del rol</label>
                                    </div>
                                </div>
                                <div class="row">
                                    @foreach ($permisos as $permiso)
                                        <div class="col-3">
                                            @php  $contp =0; @endphp
                                            @foreach ($rolePermission as $rolep)
                                                @if ($rolep->permission_id == $permiso->id)
                                                    <input type="checkbox" class="form-check-input" name="permission[]"
                                                        value="{{ $permiso->id }}" checked>
                                                    @php $contp++; @endphp
                                                @endif
                                            @endforeach
                                            @if ($contp == 0)
                                                <input type="checkbox" class="form-check-input" name="permission[]"
                                                    value="{{ $permiso->id }}" >
                                            @endif
                                            <label class="form-check-label" for="flexCheckDefault">
                                                {{ $permiso->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>



                            </div>
                            <div class="col-md-12 mb-3">
                                <button type="submit" class="btn btn-primary text-white float-end">Guardar</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
