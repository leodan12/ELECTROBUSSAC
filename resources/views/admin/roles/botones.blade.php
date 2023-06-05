<a href="{{ url('admin/rol/' . $roles->id . '/edit') }}"
    class="btn btn-success">Editar</a>
<button type="button" class="btn btn-secondary" data-id="{{ $roles->id }}"
    data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
 
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $roles->id }}"   >Borrar</button>


 