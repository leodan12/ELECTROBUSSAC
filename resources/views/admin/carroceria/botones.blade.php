@can('editar-carroceria')
    <a href="{{ url('admin/carroceria/' . $carrocerias->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan
<button type="button" class="btn btn-secondary" data-id="{{ $carrocerias->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
@can('eliminar-carroceria')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $carrocerias->id }}">Eliminar</button>
@endcan
