<a href="{{ url('admin/inventario/' . $inventarios->id . '/edit') }}"
    class="btn btn-success">Editar</a>
<button type="button" class="btn btn-secondary" data-id="{{ $inventarios->id }}"
    data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
 
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $inventarios->id }}" >Eliminar</button>

{{-- ----------------------------------------- --}}
 