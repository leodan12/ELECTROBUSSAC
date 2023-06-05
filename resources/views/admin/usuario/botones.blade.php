<a href="{{ url('admin/users/' . $usuarios->id . '/edit') }}"
    class="btn btn-success">Editar</a>
<button type="button" class="btn btn-secondary" data-id="{{ $usuarios->id }}"
    data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
 
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $usuarios->id }}"   >Borrar</button>

{{-- ----------------------------------------- --}}
 