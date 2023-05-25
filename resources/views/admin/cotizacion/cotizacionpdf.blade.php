<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cotización</title>
    <style type="text/css">
        @font-face {
            font-family: 'Arial';
            src: url('/fonts/calibri/calibri.ttf');
        }

        .container {
            /* border: 1px solid black; */
            height: 98%;
            margin-left: 30px;
            margin-top: 15px;
            margin-right: 30px;

        }

        .tdlogo {
            width: 320px;
            height: 120px;
        }

        .logo {
            width: 320px;
            height: 120px;
            /* border: 1px solid red; */
        }

        .logovacio {
            width: 320px;
            height: 120px;
        }

        .empresa {
            width: 310px;
            font-size: 14px;
            font-weight: bold;
            font-family: 'Arial';
            /* border: 1px solid red; */
            text-align: right;
        }

        .cliente {
            width: 690px;
            font-size: 14px;
            /* border: 1px solid red; */
            text-align: left;
            margin-bottom: 80px;
        }

        .cabecera {
            margin-bottom: 20px;
        }

        .linea {
            margin-bottom: 10px;
        }

        .linea2 {
            margin-bottom: 5px;
        }

        .lineacliente {
            margin-bottom: 30px;
        }

        .fecha {
            margin-top: 10px;
        }

        .listacotizacion {
            width: 630px;
            font-size: 14px;
            text-align: right;
            border-collapse: collapse;
            border: none;
            margin-left: 5px;

        }

        .cantidad {
            width: 70px;
        }

        .detalleproducto {
            width: 360px;
        }

        .precio {
            width: 95px;
        }

        .importe {
            width: 95px;
        }

        .borde {
            border: 1px solid black;
        }

        .aleft {
            text-align: left;
        }

        .acenter {
            text-align: center;
        }

        .aright {
            text-align: right;
        }

        .margensuperior {
            margin-top: 30px;
        }

        .letraazul {
            color: #0e3988;
            font-size: 14px;
        }

        .letranegra {
            color: black;
            font-size: 14px;
        }

        .direccionfooter {
            width: 690px;
            margin-bottom: 0;
            font-size: 13px;
            text-align: center;
            /* border: 1px solid orangered; */
            bottom: 10px;
            position: fixed;
        }

        .listacondiciones {
            width: 690px;
            font-size: 14px;
            text-align: left;
            border-collapse: collapse;
            border: none;
            margin-left: 5px;

        }

        .headcondicion {
            text-align: left;
        }

        .fondofinal {
            background-color: #FFFC99;
        }
    </style>

</head>

<body>
    <div class="container">
        @php $simbolo=""; @endphp
        @if ($cotizacion[0]->moneda == 'soles')
            @php $simbolo="S/."; @endphp
        @elseif($cotizacion[0]->moneda == 'dolares')
            @php $simbolo="$"; @endphp
        @endif
        <table class="cabecera">
            <tr>
                <td class="tdlogo">
                    @if ($empresa->logo != null)
                        <img class="logo" src="{{ public_path('logos/' . $empresa->logo) }}"> <br>
                    @else
                        <div class="logovacio"></div>
                    @endif
                    <div class="fecha"> Trujilo, &nbsp;{{ $fechaletra }}</div>
                </td>
                <td class="empresa">
                    <div class="linea"> DE:&nbsp; {{ $empresa->nombre }} <br></div>
                    <div class="linea"> RUC:&nbsp; {{ $empresa->ruc }} <br> </div>
                    <div class="linea"> COTIZACION N° {{ $cotizacion[0]->numero }} </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="cliente">
                    <div class="linea">
                        <b> Señores: </b> <br>
                    </div>
                    @if ($cotizacion[0]->persona != null)
                    <div class="linea">
                        {{ $cliente->nombre }}
                    </div>
                    @else
                    <div class="lineacliente">
                        {{ $cliente->nombre }}
                    </div>
                    @endif
                    
                    @if ($cotizacion[0]->persona != null)
                        <div class="lineacliente">
                            Att. {{ $cotizacion[0]->persona }}
                        </div>
                    @endif
                </td>

            </tr>
            <tr>
                <td class="cliente">
                    <div class="linea">
                        <b>Estimado cliente:</b>
                    </div>
                    <div class="lineacliente">Nos es grato saludarle y a la vez alcanzar la cotizacion solicitada.
                    </div>
                </td>
                <td>

                </td>
            </tr>
        </table>
        <table class="listacotizacion">
            <thead>
                <tr>
                    <th class="borde cantidad acenter">
                        <b>CANT.</b>
                    </th>
                    <th class="borde detalleproducto acenter">
                        <b>DETALLE DEL PRODUCTO</b>
                    </th>
                    <th class="borde precio acenter">
                        <b>PRECIO&nbsp;{{ $simbolo }} </b>
                    </th>
                    <th class="borde importe acenter">
                        <b>IMPORTE&nbsp;{{ $simbolo }}</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cotizacion as $item)
                    <tr>
                        <td class="borde acenter">&nbsp;
                            {{ $item->cantidad }}
                        </td>
                        <td class="borde aleft">&nbsp;
                            @if ($item->tipo == 'kit')
                                @php $c=0;   @endphp
                                <b> {{ $item->producto }}:</b><br>
                                &nbsp;
                                @foreach ($detallekit as $kit)
                                    @if ($item->idproducto == $kit->idkit)
                                        @if ($c == 1)
                                            ,
                                        @endif
                                        @if ($c == 0)
                                            @php $c=1;   @endphp
                                        @endif
                                        &nbsp;{{ $kit->cantidad }}&nbsp;{{ $kit->producto }}
                                    @endif
                                @endforeach
                                .
                            @else
                                {{ $item->producto }}
                            @endif
                        </td>
                        <td class="borde aright">
                            {{ number_format((float) ($item->preciounitariomo + $item->servicio), 2, '.', ',') }}&nbsp;
                        </td>
                        <td class="borde aright">
                            {{ number_format((float) $item->preciofinal, 2, '.', ',') }}&nbsp;
                        </td>
                    </tr>
                @endforeach
                @if ($cotizacion[0]->costoventaconigv != null)
                    <tr>
                        <td class="cantidad"> </td>
                        <td class="detalleproducto"> </td>
                        <td class="precio borde"><b> V. VENTA </b>&nbsp;</td>
                        <td class="importe borde">
                            <b>{{ number_format((float) $cotizacion[0]->costoventasinigv, 2, '.', ',') }}</b>&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td class="cantidad"> </td>
                        <td class="detalleproducto"> </td>
                        <td class="precio borde"><b>IGV 18%</b>&nbsp;</td>
                        <td class="importe borde">
                            <b>{{ number_format((float) round($cotizacion[0]->costoventasinigv * 0.18, 2), 2, '.', ',') }}</b>
                            &nbsp;
                        </td>
                    </tr>
                @endif
                <tr>
                    <td class="cantidad"> </td>
                    <td class="detalleproducto"> </td>
                    <td class="precio borde fondofinal"><b>TOTAL&nbsp;{{ $simbolo }}</b>&nbsp;</td>
                    <td class="importe borde fondofinal">
                        @if ($cotizacion[0]->costoventaconigv != null)
                            <b>{{ number_format((float) $cotizacion[0]->costoventaconigv, 2, '.', ',') }}</b>&nbsp;
                        @else
                            <b>{{ number_format((float) $cotizacion[0]->costoventasinigv, 2, '.', ',') }}</b>&nbsp;
                        @endif
                    </td>
                </tr>
            </tbody>

        </table>
        <table class="listacondiciones margensuperior">
            <thead>
                <tr>
                    <th class="headcondicion">
                        <b> CONDICIONES DE VENTA:</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($condiciones as $condicion)
                    <tr>
                        <td>-{{ $condicion->condicion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="margensuperior">
            <tr>
                <td class="letraazul ">
                    <div class="linea2"> <b> {{ $empresa->nombre }} </b> <br> </div>
                    @if ($cotizacion[0]->moneda == 'soles')
                        <div class="linea2"> <b>{{ $empresa->tipocuentasoles }}</b> <br> </div>
                        <div class="linea2"> Numero de Cuenta</div>
                        <div class="linea2 letranegra"> {{ $empresa->numerocuentasoles }} </div>
                        <div class="linea2"> Codigo de Cuenta Interbancaria</div>
                        <div class="linea2 letranegra"> {{ $empresa->ccisoles }} </div>
                    @elseif($cotizacion[0]->moneda == 'dolares')
                        <div class="linea2"> <b>{{ $empresa->tipocuentadolares }}</b> <br> </div>
                        <div class="linea2"> Numero de Cuenta</div>
                        <div class="linea2 letranegra"> {{ $empresa->numerocuentadolares }} </div>
                        <div class="linea2"> Codigo de Cuenta Interbancaria</div>
                        <div class="linea2 letranegra"> {{ $empresa->ccidolares }} </div>
                    @endif
                </td>


            </tr>
        </table>


        <div class="direccionfooter"><b>{{ $empresa->direccion }}</b></div>

    </div>
</body>

</html>
