@can('editar-categoria')
<a href="{{ url('admin/category/' . $categorias->id . '/edit') }}"
    class="btn btn-success">Editar</a>
@endcan 
@can('eliminar-categoria')
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $categorias->id }}" >Eliminar</button>
@endcan
 