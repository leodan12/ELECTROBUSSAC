@extends('layouts.admin')
@push('css')
    <link href="{{ asset('admin/required.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('content')

    <div class="row">
        <div class="col-md-12">
            @php  $nrodetalles = count($kitdetalles) @endphp
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <p>Corrige los siguientes errores:</p>
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h4>EDITAR KIT
                        <a href="{{ url('admin/kits') }}" class="btn btn-danger text-white float-end">VOLVER</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('admin/kits/' . $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">CATEGORIA</label>
                                <select name="category_id" class="form-select select2 borde" required>
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                            {{ $category->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label is-required">NOMBRE</label>
                                <input type="text" name="nombre" value="{{ $product->nombre }}"
                                    class="form-control borde " required />
                                @error('nombre')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label ">CÓDIGO</label>
                                <input type="text" name="codigo" value="{{ $product->codigo }}"
                                    class="form-control borde " />

                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">TASA DE CAMBIO</label>
                                <input type="number" name="tasacambio" id="tasacambio" value="{{ $product->tasacambio }}"
                                    min="0" step="0.01" class="form-control borde" required readonly />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">TIPO DE MONEDA</label>
                                <select name="moneda" class="form-select borde" required>
                                    <option value="">Seleccione Tipo de Moneda</option>
                                    @if ($product->moneda == 'dolares')
                                        <option value="dolares" selected>Dolares Americanos</option>
                                    @elseif($product->moneda == 'soles')
                                        <option value="soles" selected>Soles</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">PRECIO SIN IGV</label>
                                <input type="number" name="NoIGV" id="NoIGV" value="{{ $product->NoIGV }}"
                                    min="0" step="0.01" class="form-control borde" required />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label is-required">PRECIO CON IGV</label>
                                <input type="number" name="SiIGV" id="SiIGV" value="{{ $product->SiIGV }}"
                                    min="0" step="0.01" readonly class="form-control borde" required />
                            </div>
                            <hr>
                            <h4>Agregar Detalle de la Compra</h4>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">PRODUCTO</label>
                                <select class="form-select select2 borde" name="product" id="product">
                                    <option value="" disabled selected>Seleccione una opción</option>
                                    @foreach ($products as $prod)
                                        @php $contp=0;    @endphp
                                        @foreach ($kitdetalles as $item)
                                            @if ($prod->id == $item->kitproduct_id)
                                                @php $contp++;    @endphp
                                            @endif
                                        @endforeach
                                        @if ($contp == 0)
                                            <option id="miproducto{{ $prod->id }}" value="{{ $prod->id }}"
                                                data-name="{{ $prod->nombre }}" data-moneda="{{ $prod->moneda }}"
                                                data-stock="{{ $prod->stockempresa }}" data-price="{{ $prod->NoIGV }}">
                                                {{ $prod->nombre }}</option>
                                        @else
                                            <option disabled id="miproducto{{ $prod->id }}"
                                                value="{{ $prod->id }}" data-name="{{ $prod->nombre }}"
                                                data-moneda="{{ $prod->moneda }}" data-stock="{{ $prod->stockempresa }}"
                                                data-price="{{ $prod->NoIGV }}">{{ $prod->nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" id="labelcantidad">CANTIDAD</label>
                                <input type="number" name="cantidad" id="cantidad" min="1" step="1"
                                    class="form-control borde" />
                                @error('cantidad')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group" id="labelpreciounitarioref">PRECIO UNITARIO
                                        (REFERENCIAL):</label>
                                    <span class="input-group-text" id="spanpreciounitarioref"></span>
                                    <input type="number" name="preciounitario" min="0" step="0.01"
                                        id="preciounitario" readonly class="form-control borde" />
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group" id="labelpreciounitario">PRECIO
                                        UNITARIO:</label>
                                    <span class="input-group-text" id="spanpreciounitario"></span>
                                    <input type="number" name="preciounitariomo" min="0" step="0.01"
                                        id="preciounitariomo" class="form-control borde" />
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="input-group">
                                    <label class="form-label input-group" id="labelpreciototal">PRECIO TOTAL POR
                                        PRODUCTO:</label>
                                    <span class="input-group-text" id="spanpreciototal"></span>
                                    <input type="number" name="preciofinal" min="0" step="0.01"
                                        id="preciofinal" readonly class="form-control borde" />
                                </div>
                            </div>
                            @php $ind=0 ; @endphp
                            @php $indice=count($kitdetalles) ; @endphp
                            <button type="button" class="btn btn-info" id="addDetalleBatch"
                                onclick="agregarFila('{{ $indice }}')"><i class="fa fa-plus"></i> Agregar Producto
                                al Kit</button>


                            <div class="table-responsive">
                                <table class="table table-row-bordered gy-5 gs-5" id="detallesKit">
                                    <thead class="fw-bold text-primary">
                                        <tr>
                                            <th>PRODUCTO</th>
                                            <th>CANTIDAD</th>
                                            <th>PRECIO UNITARIO(REFERENCIAL)</th>
                                            <th>PRECIO UNITARIO</th>
                                            <th>PRECIO FINAL DEL PRODUCTO</th>
                                            <th>ELIMINAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $datobd="db" ;  @endphp
                                        @foreach ($kitdetalles as $item)
                                            @php $ind++;    @endphp
                                            <tr id="fila{{ $ind }}">
                                                <td> {{ $item->producto }}</td>
                                                <td> {{ $item->cantidad }}</td>
                                                <td>
                                                    @if ($item->moneda == 'soles')
                                                        S/.
                                                    @elseif($item->moneda == 'dolares')
                                                        $
                                                    @endif {{ $item->preciounitario }}
                                                </td>
                                                <td>
                                                    @if ($product->moneda == 'soles')
                                                        S/.
                                                    @elseif($product->moneda == 'dolares')
                                                        $
                                                    @endif {{ $item->preciounitariomo }}
                                                </td>
                                                <td><input type="hidden" id="preciof{{ $ind }}"
                                                        value="{{ $item->preciofinal }}" />
                                                    @if ($product->moneda == 'soles')
                                                        S/.
                                                    @elseif($product->moneda == 'dolares')
                                                        $
                                                    @endif {{ $item->preciofinal }}
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="eliminarFila( '{{ $ind }}' ,'{{ $datobd }}', '{{ $item->id }}', '{{ $item->kitproduct_id }}'  )"
                                                        data-id="0"><i class="bi bi-trash-fill"></i>ELIMINAR</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            <hr>
                            <div class="col-md-12 mb-3">
                                <button type="submit" id="btnguardar" name="btnguardar"
                                    class="btn btn-primary text-white float-end">Actualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script type="text/javascript">
        var indice = 0;
        var ventatotal = 0;
        var preciounit = 0;
        var nameproduct = 0;
        var preciototalI = 0;
        var estadoguardar = 0;
        var monedafactura = "";
        var monedaproducto = "";
        var monedaantigua = 0;
        var simbolomonedaproducto = "";
        var simbolomonedafactura = "";
        var indicex = 0;
        estadoguardar = @json($nrodetalles);
        var funcion1 = "inicio";
        botonguardar(funcion1);
        var costoventa = $('[id="NoIGV"]').val();
        ventatotal = costoventa;

        $(document).ready(function() {
            document.getElementById("NoIGV").onchange = function() {
                IGVtotal();
            };
            $('.select2').select2();
        });

        document.getElementById("cantidad").onchange = function() {
            preciofinal();
        };
        document.getElementById("preciounitariomo").onchange = function() {
            preciofinal();
        };

        function preciofinal() {
            var cantidad = $('[name="cantidad"]').val();
            var preciounit = $('[name="preciounitariomo"]').val();
            if (cantidad >= 1 && preciounit >= 0) {
                preciototalI = (parseFloat(parseFloat(cantidad) * parseFloat(preciounit)));
                document.getElementById('preciofinal').value = preciototalI.toFixed(2);
            }
        }


        function IGVtotal() {
            preciototal = 0;
            var cantidad = $('[name="NoIGV"]').val();
            if (cantidad.length != 0) {
                //alert("final");
                preciototal = parseFloat(cantidad) + (parseFloat(cantidad) * 0.18);
                document.getElementById('SiIGV').value = preciototal;
            }
        }


        $("#product").change(function() {

            $("#product option:selected").each(function() {
                $price = $(this).data("price");
                $named = $(this).data("name");
                $moneda = $(this).data("moneda");
                monedaproducto = $moneda;

                monedaproducto = $moneda;
                monedafactura = $('[name="moneda"]').val();
                if (monedafactura == "dolares") {
                    simbolomonedafactura = "$";
                } else if (monedafactura == "soles") {
                    simbolomonedafactura = "S/.";
                }

                var mitasacambio1 = $('[name="tasacambio"]').val();
                //var mimoneda1 = $('[name="moneda"]').val();

                if ($price != null) {
                    preciounit = ($price).toFixed(2);
                    if (monedaproducto == "dolares" && monedafactura == "dolares") {
                        simbolomonedaproducto = "$";
                        preciototalI = ($price).toFixed(2);
                        document.getElementById('preciounitario').value = ($price).toFixed(2);
                        document.getElementById('preciounitariomo').value = ($price).toFixed(2);
                        document.getElementById('preciofinal').value = ($price).toFixed(2);
                    } else if (monedaproducto == "soles" && monedafactura == "soles") {
                        simbolomonedaproducto = "S/.";
                        preciototalI = ($price).toFixed(2);
                        document.getElementById('preciounitario').value = ($price).toFixed(2);
                        document.getElementById('preciounitariomo').value = ($price).toFixed(2);
                        document.getElementById('preciofinal').value = ($price).toFixed(2);
                    } else if (monedaproducto == "dolares" && monedafactura == "soles") {
                        simbolomonedaproducto = "$";
                        preciototalI = ($price * mitasacambio1).toFixed(2);
                        document.getElementById('preciounitario').value = ($price).toFixed(2);
                        document.getElementById('preciounitariomo').value = ($price * mitasacambio1)
                            .toFixed(2);
                        document.getElementById('preciofinal').value = ($price * mitasacambio1).toFixed(2);
                    } else if (monedaproducto == "soles" && monedafactura == "dolares") {
                        simbolomonedaproducto = "S/.";
                        preciototalI = ($price / mitasacambio1).toFixed(2);;
                        document.getElementById('preciounitario').value = ($price).toFixed(2);
                        document.getElementById('preciounitariomo').value = ($price / mitasacambio1)
                            .toFixed(2);
                        document.getElementById('preciofinal').value = ($price / mitasacambio1).toFixed(2);
                    }
                    document.getElementById('labelpreciounitarioref').innerHTML =
                        "PRECIO UNITARIO(REFERENCIAL): " + monedaproducto;
                    document.getElementById('labelpreciounitario').innerHTML = "PRECIO UNITARIO: " +
                        monedafactura;
                    document.getElementById('labelpreciototal').innerHTML = "PRECIO TOTAL POR PRODUCTO: " +
                        monedafactura;
                    document.getElementById('spanpreciounitarioref').innerHTML = simbolomonedaproducto;
                    document.getElementById('spanpreciounitario').innerHTML = simbolomonedafactura;
                    document.getElementById('spanpreciototal').innerHTML = simbolomonedafactura;
                    document.getElementById('cantidad').value = 1;
                    nameproduct = $named;
                } else if ($price == null) {
                    document.getElementById('cantidad').value = "";
                    document.getElementById('preciofinal').value = "";
                    document.getElementById('preciounitario').value = "";
                    document.getElementById('preciounitariomo').value = "";
                }
                //alert(nameprod);
            });
        });

        var indice = 0;
        var pv = 0;

        function agregarFila(indice1) {

            if (pv == 0) {
                indice = indice1;
                pv++;
                indice++;
            } else {
                indice++;
            }
            //datos del detalleSensor
            var product = $('[name="product"]').val();
            var cantidad = $('[name="cantidad"]').val();
            var preciounitario = $('[name="preciounitario"]').val();
            var preciofinal = $('[name="preciofinal"]').val();
            var preciounitariomo = $('[name="preciounitariomo"]').val();
            //alertas para los detallesBatch

            if (!product) {
                alert("Seleccione un Producto");
                return;
            }
            if (!cantidad) {
                alert("Ingrese una cantidad");
                return;
            }
            if (!preciounitariomo) {
                alert("Ingrese un precio unitario");
                return;
            }

            var LVenta = [];
            var tam = LVenta.length;
            LVenta.push(product, nameproduct, cantidad, preciounitario, preciounitariomo, preciofinal);

            filaDetalle = '<tr id="fila' + indice +
                '"><td><input  type="hidden" name="Lproduct[]" value="' + LVenta[0] + '"required>' + LVenta[1] +
                '</td><td><input  type="hidden" name="Lcantidad[]" id="cantidad' + indice + '" value="' + LVenta[2] +
                '"required>' + LVenta[2] +
                '</td><td><input  type="hidden" name="Lpreciounitario[]" id="preciounitario' + indice + '" value="' +
                LVenta[3] + '"required>' + simbolomonedaproducto + LVenta[3] +
                '</td><td><input  type="hidden" name="Lpreciounitariomo[]" id="preciounitariomo' + indice + '" value="' +
                LVenta[4] + '"required>' + simbolomonedafactura + LVenta[4] +
                '</td><td><input  type="hidden" name="Lpreciofinal[]" id="preciof' + indice + '" value="' + LVenta[5] +
                '"required>' + simbolomonedafactura + LVenta[5] +
                '</td><td><button type="button" class="btn btn-danger" onclick="eliminarFila(' + indice + ',' + 0 + ',' +
                0 + ',' + product  + ')" data-id="0">ELIMINAR</button></td></tr>';

            $("#detallesKit>tbody").append(filaDetalle);

            indice++;
            //ventatotal =document.getElementById('NoIGV').value;
            //alert(ventatotal);
            ventatotal = (parseFloat(ventatotal) + parseFloat(preciototalI)).toFixed(2);
            //alert(ventatotal);
            limpiarinputs();
            document.getElementById('NoIGV').value = ventatotal;
            document.getElementById('SiIGV').value = (ventatotal * 1.18).toFixed(2);
            document.getElementById('miproducto' + product).disabled = true;
            var funcion = "agregar";
            botonguardar(funcion);
        }

        function eliminarFila(ind, lugardato, iddetalle, producto) {
            if (lugardato == "db") {
                Swal.fire({
                    title: '¿Esta seguro de Eliminar?',
                    text: "No lo podra revertir!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí,Eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var miurl = "{{ url('admin/deletedetallekit') }}";
                        $.get(miurl + '/' + iddetalle, function(data) {
                            //alert(data[0]);
                            if (data[0] == 1) {
                                Swal.fire({
                                    text: "Registro Eliminado",
                                    icon: "success"
                                });
                                quitarFila(ind);

                                //llenarselectproducto();
                            } else if (data[0] == 0) {
                                alert("no se puede eliminar");
                            } else if (data[0] == 2) {
                                alert("registro no encontrado");
                            }
                        });
                    }
                });
            } else {
                quitarFila(ind);
            }

            document.getElementById('miproducto' + producto).disabled = false;
            return false;
        }

        function quitarFila(ind) {
            var resta = 0;
            resta = $('[id="preciof' + ind + '"]').val();
            ventatotal = ventatotal - resta;
            $('#fila' + ind).remove();
            indice--;
            document.getElementById('NoIGV').value = (ventatotal).toFixed(2);
            document.getElementById('SiIGV').value = (ventatotal * 1.18).toFixed(2);
            var funcion = "eliminar";
            botonguardar(funcion);
            return false;
        }

        function eliminarTabla(ind) {

            $('#fila' + ind).remove();
            indice--;

            // damos el valor
            document.getElementById('NoIGV').value = 0
            document.getElementById('SiIGV').value = 0

            var funcion = "eliminar";
            botonguardar(funcion);

            ventatotal = 0;
            preciounit = 0;
            nameproduct = 0;
            preciototalI = 0;

            return false;
        }

        function limpiarinputs() {
            $('#product').val(null).trigger('change');
            document.getElementById('labelcantidad').innerHTML = "CANTIDAD";
            document.getElementById('labelpreciounitario').innerHTML = "PRECIO UNITARIO: ";
            document.getElementById('labelpreciounitarioref').innerHTML = "PRECIO UNITARIO(REFERENCIAL): ";
            document.getElementById('labelpreciototal').innerHTML = "PRECIO TOTAL POR PRODUCTO:";
            document.getElementById('spanpreciounitarioref').innerHTML = "";
            document.getElementById('spanpreciounitario').innerHTML = "";
            document.getElementById('spanpreciototal').innerHTML = "";
            document.getElementById('cantidad').value = "";
            document.getElementById('preciofinal').value = "";
            document.getElementById('preciounitario').value = "";
            document.getElementById('preciounitariomo').value = "";
            monedaproducto = "";
            simbolomonedaproducto = "";
        }

        function botonguardar(funcion) {

            if (funcion == "eliminar") {
                estadoguardar--;
            } else if (funcion == "agregar") {
                estadoguardar++;
            }
            if (estadoguardar < 1) {
                $("#btnguardar").prop("disabled", true);
            } else if (estadoguardar >= 1) {
                $("#btnguardar").prop("disabled", false);
            }
        }
    </script>
@endpush
