@can('editar-usuario')
<a href="{{ url('admin/users/' . $usuarios->id . '/edit') }}"
    class="btn btn-success">Editar</a>
@endcan
@can('eliminar-usuario')
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $usuarios->id }}"   >Eliminar</button>
@endcan

 