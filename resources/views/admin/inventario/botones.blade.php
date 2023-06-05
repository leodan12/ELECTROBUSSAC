@can('editar-inventario')
<a href="{{ url('admin/inventario/' . $inventarios->id . '/edit') }}"
    class="btn btn-success">Editar</a>
@endcan
<button type="button" class="btn btn-secondary" data-id="{{ $inventarios->id }}"
    data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
@can('eliminar-inventario')
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $inventarios->id }}" >Eliminar</button>
@endcan
 