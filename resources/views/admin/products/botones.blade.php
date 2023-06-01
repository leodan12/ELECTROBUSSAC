<a href="{{ url('admin/products/' . $productos->id . '/edit') }}"
    class="btn btn-success">Editar</a>
<button type="button" class="btn btn-secondary" data-id="{{ $productos->id }}"
    data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
 
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $productos->id }}" >Eliminar</button>

{{-- ----------------------------------------- --}}
 