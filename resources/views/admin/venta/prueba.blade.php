<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- bootstrap 5-->
    <meta name="csrf-token" content="{{ csrf_token() }}">
 
 
	@yield('page-info')

    <title>GEEPPERU</title>
    <style>
        #whatsapp{
            position:fixed;
            bottom:30px;
            left:10px; 
            display: flex;
        }
        #whatsapp img{
           width: 50px;
           height: 50px;  
        } 
        
        #facebook{
            position:fixed;
            bottom:150px;
            left:10px; 
            display: flex;
        }
        #facebook img{
           width: 50px;
           height: 50px;  
        } 
        .resultados{
           width: 50%;
           align-items: center;
        } 
          
    </style>
 
</head>

<body   >

     
    
 
    <br> <br>
    <div id="facebook">    
        <a href="https://www.facebook.com/geepperuoficial"    > 
        <img src="{{ asset('admin/images/faces/face6.jpg') }}" title="Facebook Geep Perú" alt="Chat en Facebook" />
        </a> 
    </div>
    
    <div id="whatsapp">    
        <a href="https://wa.me/+51936108792?text=hola quiero información acerca de ..."   target="_blank" > 
        <h4>hestrhrthssgg</h4>
        </a> 
    </div>
    <!-- START FOOTER -->
    <div id="servicios">
        <br>
        <div class="container">
            <div class="row justify-content-center" style="text-align: center;">
                <div class="col-xs-12 col-sm-6 col-md-4  col-lg-2">
                    <ul>
                        <i class="fa-solid fa-chart-line  "></i>
                        <br>
                        <h6>Alta Calidad</h6>
                        <p>Contamos con la mejor tecnología nacional e internacional</p>
                    </ul>

                </div>
                <div class="col-xs-12 col-sm-6 col-md-4  col-lg-2">
                    <ul>
                        <i class="fa-solid fa-headset"></i>
                        <br>
                        <h6>Soporte Online</h6>
                        <p>Contamos con personal profesional para ayudarlo</p>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4  col-lg-2">
                    <ul>
                        <i class="fa-solid fa-truck"></i>
                        <br>
                        <h6>Llegamos a tu Casa</h6>
                        <p>Te protegemos!, por eso llegamos hasta la puerta de tu casa</p>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4  col-lg-2">
                    <ul>
                        <i class="fa-solid fa-user-shield"></i>
                        <br>
                        <h6>Garantía</h6>
                        <p>Todos nuestros productos y servicios están garantizados</p>
                    </ul>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-4  col-lg-2">
                    <ul>
                        <i class="fa-solid fa-lock"></i>
                        <br>
                        <h6>Pago Seguro</h6>
                        <p>Contamos con la mayor seguridad para tus compras en linea</p>
                    </ul>

                </div>
            </div>

        </div>
        <hr  />
    </div>
 
    <div id="nosotros">

        <div class="container">
            <div class="row justify-content-center" style="text-align: center;">
                <div class="col-xs-12  col-sm-6  col-md-4 col-lg-3 ">
                    <br>
                    <h6> ¿PORQUE ELEGIRNOS?</h6>
                    <ul>
                        <a href="/nosotros">
                            <li> Nuestra Empresa </li>
                        </a>
                        <a href="/trayectoria">
                            <li> Nuestra Trayectoria </li>
                        </a>
                        <a href="#">
                            <li> Nuestras Distinciones </li>
                        </a>
                        <a href="/principios">
                            <li> Nuestros Principios </li>
                        </a>
                    </ul>
                </div>
                <div class="col-xs-12  col-sm-6  col-md-4 col-lg-3 ">
                    <br>
                    <h6> POLÍTICAS</h6>
                    <ul>
                        <a href="#">
                            <li> Política de Devoluciones </li>
                        </a>
                        <a href="#">
                            <li> Política de Privacidad </li>
                        </a>
                        <a href="#">
                            <li> Política Comerciales </li>
                        </a>
                        <a href="#">
                            <li> Política de Darantias </li>
                        </a>
                        <a href="#">
                            <li> Política de Envios </li>
                        </a>
                    </ul>
                </div>
                <div class="col-xs-12  col-sm-6  col-md-4 col-lg-3 ">
                    <br>
                    <h6> SERVICIOS</h6>
                    <ul>
                        <a href="/contactanos">
                            <li> Soporte </li>
                        </a>
                        <a href="/contactanos">
                            <li> Ventas Tienda</li>
                        </a>
                        <a href="/contactanos">
                            <li> Ventas Online </li>
                        </a>
                        <a href="/contactanos">
                            <li> Reclamaciones </li>
                        </a>
                        <a href="/preguntasfrecuentes">
                            <li> Preguntas Frecuentes </li>
                        </a>
                    </ul>
                </div>
                <div class="col-xs-12  col-sm-6  col-md-4 col-lg-3 ">
                    <br>
                    <h6> CONTÁCTANOS!</h6>
                    <p> Con gusto nuestros ejecutivos especializados atenderán tus dudas, recibirán tus comentarios.</p>

                    <ul style="list-style-type: none;">
                        <a href="https://www.google.com/maps/place/GEEP+PERU+SRL/@-8.114465,-79.0307253,19z/data=!3m1!4b1!4m5!3m4!1s0x91ad3d120d07dfbf:0xf5641a979fb99073!8m2!3d-8.1144663!4d-79.0301781">
                            <li> <i class="fa-solid fa-location-dot"></i>Jirón Francisco Pizarro 203, Trujillo </li>
                        </a>
                        <a href="tel:+51936108792">
                            <li> <i class="fa-solid fa-phone"></i>936108792</li>
                        </a> 
                        <a href="">
                            <li> <i class="fa-solid fa-envelope"></i>ventas@geepperu.com </li>
                        </a>
                    </ul>
                </div>

            </div>
        </div>
        <hr />
        <div style="text-align: center;">

            <a><strong>2022© Todos los derechos reservados</strong></a> &nbsp - &nbsp


            <a><strong>Hecho por TPF</strong></a>

        </div>

    </div>
    <!-- END FOOTER -->
 
    @yield('script')
    <script>
    
    </script>
    
</body>


</html>