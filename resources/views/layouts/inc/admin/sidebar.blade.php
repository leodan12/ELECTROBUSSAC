<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/dashboard') }}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">INICIO</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#products" aria-expanded="false"
                aria-controls="products">
                <i class="mdi mdi-shape-plus menu-icon"></i>
                <span class="menu-title">PRODUCTOS</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="products">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-categoria') ||
                            auth()->user()->can('crear-categoria') ||
                            auth()->user()->can('editar-categoria') ||
                            auth()->user()->can('eliminar-categoria'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/category') }}">
                                <i class="mdi mdi-format-list-bulleted-type menu-icon"></i>
                                <span class="menu-title">CATEGORÍAS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-producto') ||
                            auth()->user()->can('crear-producto') ||
                            auth()->user()->can('editar-producto') ||
                            auth()->user()->can('eliminar-producto'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/products') }}">
                                <i class="mdi mdi-book menu-icon"></i>
                                <span class="menu-title">PRODUCTOS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-kit') ||
                            auth()->user()->can('crear-kit') ||
                            auth()->user()->can('editar-kit') ||
                            auth()->user()->can('eliminar-kit'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/kits') }}">
                                <i class="mdi mdi-google-circles-communities menu-icon"></i>
                                <span class="menu-title">KITS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-inventario') ||
                            auth()->user()->can('crear-inventario') ||
                            auth()->user()->can('editar-inventario') ||
                            auth()->user()->can('eliminar-inventario'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/inventorystock') }}">
                                <i class="mdi mdi-playlist-check menu-icon"></i>
                                <span class="menu-title">INVENTARIO</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-lista-precios') ||
                            auth()->user()->can('crear-lista-precios') ||
                            auth()->user()->can('editar-lista-precios') ||
                            auth()->user()->can('eliminar-lista-precios'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/listaprecios') }}">
                                <i class="mdi mdi-playlist-play menu-icon"></i>
                                <span class="menu-title">LISTA PRECIOS</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#companies" aria-expanded="false"
                aria-controls="companies">
                <i class="mdi mdi-home-modern menu-icon"></i>
                <span class="menu-title">COMPAÑIAS</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="companies">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-empresa') ||
                            auth()->user()->can('crear-empresa') ||
                            auth()->user()->can('editar-empresa') ||
                            auth()->user()->can('eliminar-empresa'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/company') }}">
                                <i class="mdi mdi-store menu-icon"></i>
                                <span class="menu-title">MIS EMPRESAS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-cliente') ||
                            auth()->user()->can('crear-cliente') ||
                            auth()->user()->can('editar-cliente') ||
                            auth()->user()->can('eliminar-cliente'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/cliente') }}">
                                <i class="mdi mdi-hospital-building menu-icon"></i>
                                <span class="menu-title">CLIENTES/PROVEEDORES</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ventas" aria-expanded="false" aria-controls="ventas">
                <i class="mdi mdi-cart menu-icon"></i>
                <span class="menu-title">FACTURACIÓN</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ventas">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-ingreso') ||
                            auth()->user()->can('crear-ingreso') ||
                            auth()->user()->can('editar-ingreso') ||
                            auth()->user()->can('eliminar-ingreso'))
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/ingreso') }}">
                                <i class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 122.43 122.88"
                                        style="enable-background:new 0 0 122.43 122.88">
                                        <style type="text/css">
                                            .st0 {
                                                fill-rule: evenodd;
                                                clip-rule: evenodd;
                                            }
                                        </style>
                                        <path class="st0"
                                            d="M22.63,12.6h93.3c6.1,0,5.77,2.47,5.24,8.77l-3.47,44.23c-0.59,7.05-0.09,5.34-7.56,6.41l-68.62,8.73 l3.63,10.53c29.77,0,44.16,0,73.91,0c1,3.74,2.36,9.83,3.36,14h-12.28l-1.18-4.26c-24.8,0-34.25,0-59.06,0 c-13.55-0.23-12.19,3.44-15.44-8.27L11.18,8.11H0V0h19.61C20.52,3.41,21.78,9.15,22.63,12.6L22.63,12.6z M63.49,23.76h17.76v18.02 h15.98L72.39,65.95L47.51,41.78h15.98V23.76L63.49,23.76z M53.69,103.92c5.23,0,9.48,4.25,9.48,9.48c0,5.24-4.24,9.48-9.48,9.48 c-5.24,0-9.48-4.24-9.48-9.48C44.21,108.17,48.45,103.92,53.69,103.92L53.69,103.92z M92.79,103.92c5.23,0,9.48,4.25,9.48,9.48 c0,5.24-4.25,9.48-9.48,9.48c-5.24,0-9.48-4.24-9.48-9.48C83.31,108.17,87.55,103.92,92.79,103.92L92.79,103.92z" />
                                    </svg> 
                                </i>
                                INGRESO</a></li>
                    @endif
                    @if (auth()->user()->can('ver-venta') ||
                            auth()->user()->can('crear-venta') ||
                            auth()->user()->can('editar-venta') ||
                            auth()->user()->can('eliminar-venta'))
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/venta') }}">
                                <i class="menu-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 122.43 122.88">
                                        <path
                                            d="M22.63 12.6h93.3c6.1 0 5.77 2.47 5.24 8.77l-3.47 44.23c-0.59 7.05-0.09 5.34-7.56 6.41l-68.62 8.73 l3.63 10.53c29.77 0 44.16 0 73.91 0c1 3.74 2.36 9.83 3.36 14h-12.28l-1.18-4.26c-24.8 0-34.25 0-59.06 0 c-13.55-0.23-12.19 3.44-15.44-8.27L11.18 8.11H0V0h19.61C20.52 3.41 21.78 9.15 22.63 12.6z M62.4 62.12h17.76V44.11 h15.98L71.3 19.93L46.43 44.11H62.4V62.12L62.4 62.12z M53.69 103.92c5.23 0 9.48 4.25 9.48 9.48c0 5.24-4.24 9.48-9.48 9.48 c-5.24 0-9.48-4.24-9.48-9.48C44.21 108.16 48.45 103.92 53.69 103.92L53.69 103.92z M92.79 103.92c5.23 0 9.48 4.25 9.48 9.48 c0 5.24-4.25 9.48-9.48 9.48c-5.24 0-9.48-4.24-9.48-9.48C83.31 108.16 87.55 103.92 92.79 103.92L92.79 103.92z" />
                                    </svg>

                                </i>
                                SALIDA</a></li>
                    @endif
                    @if (auth()->user()->can('ver-cotizacion') ||
                            auth()->user()->can('crear-cotizacion') ||
                            auth()->user()->can('editar-cotizacion') ||
                            auth()->user()->can('eliminar-cotizacion'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/cotizacion') }}">
                                <i class="mdi mdi-currency-usd menu-icon"></i>
                                <span class="menu-title">COTIZACIÓN</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-venta') ||
                            auth()->user()->can('eliminar-venta'))
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/ventasantiguas') }}"><i
                                    class="mdi mdi-av-timer menu-icon"></i>VENTAS ANTIGUAS</a></li>
                    @endif
                </ul>
            </div>
        </li>
        @if (auth()->user()->can('ver-reporte'))
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic1" aria-expanded="false"
                    aria-controls="ui-basic1">
                    <i class="mdi mdi-chart-bar menu-icon"></i>
                    <span class="menu-title">REPORTES</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-basic1">
                    <ul class="flex-column sub-menu" style="list-style: none;">
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte') }}"><i
                                    class="mdi mdi-chart-line menu-icon"></i>GRÁFICOS</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte/tabladatos') }}"><i
                                    class="mdi mdi-file-excel menu-icon"></i>DATOS VENTAS</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte/rotacionstock') }}"><i
                                    class="mdi mdi-timetable menu-icon"></i>ROTACIÓN STOCK</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte/pagocompras') }}"><i
                                    class="mdi mdi-wallet menu-icon"></i>PAGO DE COMPRAS</a></li>
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte/cobrovent') }}"><i
                                    class="mdi mdi-wallet menu-icon"></i>COBRO DE VENTAS</a></li>
                        <li class="nav-item"> <a class="nav-link"
                                href="{{ url('admin/reporte/listaprecioscompra') }}"><i
                                    class="mdi mdi-format-list-bulleted menu-icon"></i>PRECIOS DE COMPRAS</a></li>
                    </ul>
                </div>
            </li>
        @endif
        {{-- <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#production" aria-expanded="false"
                aria-controls="production">
                <i class="mdi mdi-factory menu-icon"></i>
                <span class="menu-title">PRODUCCIÓN</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="production">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-usuario') ||
    auth()->user()->can('crear-usuario') ||
    auth()->user()->can('editar-usuario') ||
    auth()->user()->can('eliminar-usuario'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/modelocarro') }}">
                                <i class="mdi mdi-account-multiple menu-icon"></i>
                                <span class="menu-title">MODELOS CARROS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-rol') ||
    auth()->user()->can('crear-rol') ||
    auth()->user()->can('editar-rol') ||
    auth()->user()->can('eliminar-rol'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/carroceria') }}">
                                <i class="mdi mdi-account-settings menu-icon"></i>
                                <span class="menu-title">CARROCERIAS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-historial') ||
    auth()->user()->can('eliminar-historial'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/produccioncarro') }}">
                                <i class="mdi mdi-timetable menu-icon"></i>
                                <span class="menu-title">PRODUCCION DE CARROS</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-toggle="collapse" href="#auth" aria-expanded="false"
                aria-controls="auth">
                <i class="mdi mdi-security menu-icon"></i>
                <span class="menu-title">SEGURIDAD</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-usuario') ||
                            auth()->user()->can('crear-usuario') ||
                            auth()->user()->can('editar-usuario') ||
                            auth()->user()->can('eliminar-usuario'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/users') }}">
                                <i class="mdi mdi-account-multiple menu-icon"></i>
                                <span class="menu-title">USUARIOS</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-rol') ||
                            auth()->user()->can('crear-rol') ||
                            auth()->user()->can('editar-rol') ||
                            auth()->user()->can('eliminar-rol'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/rol') }}">
                                <i class="mdi mdi-account-settings menu-icon"></i>
                                <span class="menu-title">ROLES</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->can('ver-historial') ||
                            auth()->user()->can('eliminar-historial'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('admin/historial') }}">
                                <i class="mdi mdi-timetable menu-icon"></i>
                                <span class="menu-title">HISTORIAL DE CAMBIOS</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </li>




    </ul>
</nav>
