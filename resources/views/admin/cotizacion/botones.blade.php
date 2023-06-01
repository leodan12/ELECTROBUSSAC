<a href="{{ url('admin/cotizacion/' . $cotizaciones->id . '/edit') }}"
    class="btn btn-success">Editar</a>
<button type="button" class="btn btn-secondary" data-id="{{ $cotizaciones->id }}"
    data-bs-toggle="modal" data-bs-target="#mimodal">Ver</button>
 
<button type="button" class="btn btn-danger btnborrar"  data-idregistro="{{ $cotizaciones->id }}" >Eliminar</button>

{{-- ----------------------------------------- --}}
 