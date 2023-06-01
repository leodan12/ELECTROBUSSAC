<a href="{{ url('admin/ingreso/' . $ingresos->id . '/edit') }}"
    class="btn btn-success">Editar</a>
<button type="button" class="btn btn-secondary" data-id="{{ $ingresos->id }}"
    data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
 
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $ingresos->id }}" >Eliminar</button>

{{-- ----------------------------------------- --}}
 