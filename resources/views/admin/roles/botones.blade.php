@can('editar-rol')
    <a href="{{ url('admin/rol/' . $roles->id . '/edit') }}" class="btn btn-success">Editar</a>
@endcan
@can('eliminar-rol')
    <button type="button" class="btn btn-danger btnborrar" data-idregistro="{{ $roles->id }}">Eliminar</button>
@endcan
