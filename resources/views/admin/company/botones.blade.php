@can('editar-producto')
    <a href="{{ url('admin/company/' . $empresas->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan

<button type="button" class="btn btn-secondary" data-id="{{ $empresas->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
    
@can('eliminar-producto')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $empresas->id }}">Eliminar</button>
@endcan
