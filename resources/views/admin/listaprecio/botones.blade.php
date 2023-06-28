@can('editar-usuario')
    <a href="{{ url('admin/listaprecios/' . $listaprecios->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan
<button type="button" class="btn btn-secondary" data-id="{{ $listaprecios->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>

@can('eliminar-usuario')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $listaprecios->id }}">Eliminar</button>
@endcan
