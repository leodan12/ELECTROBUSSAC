<a href="{{ url('admin/venta/' . $ventas->id . '/edit') }}"
    class="btn btn-success">Editar</a>
<button type="button" class="btn btn-secondary" data-id="{{ $ventas->id }}"
    data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
 
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $ventas->id }}" >Eliminar</button>

{{-- ----------------------------------------- --}}
 