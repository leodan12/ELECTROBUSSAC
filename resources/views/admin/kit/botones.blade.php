@can('editar-kit')
    <a href="{{ url('admin/kits/' . $kits->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan
<button type="button" class="btn btn-secondary" data-id="{{ $kits->id }}" data-bs-toggle="modal"
    data-bs-target="#mimodal">Ver</button>
@can('eliminar-kit')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $kits->id }}">Eliminar</button>
@endcan
