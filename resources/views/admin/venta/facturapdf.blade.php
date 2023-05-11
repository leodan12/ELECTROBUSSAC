<!DOCTYPE html>
<html>
<head>
    <title>VENTA</title>
    <style type="text/css">
    body{
        font-size: 16px;
        font-family: "Arial";
    }
    table{
        border-collapse: collapse;
    }
    td{
        padding: 6px 5px;
        font-size: 15px;
    }
    .h1{
        font-size: 21px;
        font-weight: bold;
    }
    .h2{
        font-size: 18px;
        font-weight: bold;
    }
    .tabla1{
        margin-bottom: 20px;
    }
    .tabla2 {
        margin-bottom: 20px;
    }
    .tabla3{
        margin-top: 15px;
    }
    .tabla3 td{
        border: 1px solid #000;
    }
    .tabla3 .cancelado{
        border-left: 0;
        border-right: 0;
        border-bottom: 0;
        border-top: 1px dotted #000;
        width: 200px;
    }
    .emisor{
        color: red;
    }
    .linea{
        border-bottom: 1px dotted #000;
    }
    .border{
        border: 1px solid #000;
        
    }
    .fondo{
        background-color: #dfdfdf;
    }
    
    
</style>
</head>
<body>
    <div class="">
        <table width="100%" class="tabla1">
            <tr>
                <td width="73%" align="center"><img id="logo"  src="imgs/geepperu.jpg"   alt="" width="255" height="57"></td>
                <td width="27%" rowspan="3" align="center" style="padding-right:0">
                    <table width="100%">
                        <tr>
                            <td height="10" align="center" class="border"><span class="h2">RUC: 1012345678 </span></td>
                        </tr>
                        <tr>
                            <td height="10" align="center" class="border fondo"><span class="h2">FACTURA  </span></td>
                        </tr>
                        <tr>
                            <td height="10" align="center" class="border">001- Nº 000{{$venta[0]->idventa}}  <span class="text"> </span></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center"  >Jr. Santander Nro. 340 Jesus María - Lima <br> <br> Telf.: (01) 364-2547 Cel.: 985-748514</td>
               
            </tr>
            
        </table>
        <table width="100%" class="tabla2">
            <tr>
                <td width="11%">NOMBRE:</td>
                <td width="37%" class="linea"><span class="text">{{$venta[0]->cliente}}</span></td>
                <td width="5%">&nbsp;</td>
                <td width="13%">&nbsp;</td>
                <td width="4%">&nbsp;</td>
                <td width="7%" align="center" class="border fondo"><strong>DÍA</strong></td>
                <td width="8%" align="center" class="border fondo"><strong>MES</strong></td>
                <td width="7%" align="center" class="border fondo"><strong>AÑO</strong></td>
            </tr>
            <tr>
                
                <td>DOCUMENTO:</td>
                <td class="linea"><span class="text">{{$venta[0]->ruccliente}}</span></td>
                <td> </td>
                <td  ><span  > </span></td>
                <td>&nbsp;</td>
                <td align="center" class="border"><span class="text">{{substr(  $venta[0]->fecha  , 8, 2) }} </span></td>
                <td align="center" class="border"><span class="text">{{substr(  $venta[0]->fecha  , 5, 2) }}</span></td>
                <td align="center" class="border"><span class="text">{{substr(  $venta[0]->fecha  , 0, 4) }}</span></td>
            </tr>
        </table>
        <table width="100%" class="tabla3">
            <tr>
                <td align="center" class="fondo"><strong>Nº</strong></td>
                <td align="center" class="fondo"><strong>DESCRIPCION</strong></td>
                <td align="center" class="fondo"><strong>CANT.</strong></td>
                <td align="center" class="fondo"><strong>P. UNITARIO</strong></td> 
                <td align="center" class="fondo"><strong>IMPORTE</strong></td>
            </tr>
            @php
            $cont=0;
            @endphp
            @if($venta != null)
            @foreach ($venta as $item)
            @php $cont=$cont+1; @endphp
            <tr>
                <td width="7%" align="center"><span class="text">{{ $cont }}</span></td>
                <td width="54%"align="left"><span class="text">{{$item->nombreproducto}}</span></td>
                <td width="10%"align="right"><span class="text">{{$item->cantidad}}</span></td>
                <td width="25%" align="right"><span class="text">{{$item->preciounitariomo}}</span></td> 
                <td width="18%" align="right"><span class="text">{{$item->costoventa}}</span></td>
            </tr>
            @endforeach
            @else
            <tr>
                <td width="7%">&nbsp;</td>
                <td width="59%">&nbsp;</td>
                <td width="16%">&nbsp;</td>
                <td width="18%" align="left">&nbsp;</td>
            </tr>
            @endif
            <tr  >
                <td style="border:0;">&nbsp;</td> 
                <td style="border:0;">&nbsp;</td>
                <td style="border:0;">&nbsp;</td>
                <td align="right" style="border: 1px solid black;"><strong>SUB TOTAL.</strong></td>
                <td align="right" style="border: 1px solid black;" ><span class="text">{{$venta[0]->costoventa}}</span></td>
            </tr>
            <tr  >
                <td style="border:0;">&nbsp;</td> 
                <td style="border:0;">&nbsp;</td>
                <td style="border:0;">&nbsp;</td>
                <td align="right" style="border: 1px solid black;"><strong>IGV.</strong></td>
                <td align="right" style="border: 1px solid black;" ><span class="text">{{round(($venta[0]->costoventa)*0.18,2)}}</span></td>
            </tr>
            <tr  >
                <td style="border:0;">&nbsp;</td> 
                <td style="border:0;">&nbsp;</td>
                <td style="border:0;">&nbsp;</td>
                <td align="right" style="border: 1px solid black;"><strong>VENTA   S/.</strong></td>
                <td align="right" style="border: 1px solid black;" ><span class="text">{{round(($venta[0]->costoventa)+($venta[0]->costoventa)*0.18,2)}}</span></td>
            </tr>
             <tr>
                <td style="border:0;">&nbsp;</td>
                <td align="center" style="border:0;" class="emisor">
                venta  
                </td>
                <td style="border:0;">&nbsp;</td>
                <td style="border:0;">&nbsp;</td>
                <td style="border:0;">&nbsp;</td> 
            </tr>
             
        </table>
    </div>
 
</body>
</html>