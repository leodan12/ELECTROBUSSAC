@can('editar-cliente')
    <a href="{{ url('admin/cliente/' . $clientes->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan
<button type="button" class="btn btn-secondary" data-id="{{ $clientes->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
@can('eliminar-cliente')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $clientes->id }}">Eliminar</button>
@endcan
