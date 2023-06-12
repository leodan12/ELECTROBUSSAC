<nav class="sidebar sidebar-offcanvas" id="sidebar"  >
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/dashboard') }}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">INICIO</span>
            </a>
        </li>
        @if (auth()->user()->can('ver-categoria') ||
                auth()->user()->can('crear-categoria') ||
                auth()->user()->can('editar-categoria') ||
                auth()->user()->can('eliminar-categoria'))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('admin/category') }}">
                    <i class="mdi mdi-format-list-bulleted-type menu-icon"></i>
                    <span class="menu-title">CATEGORIAS</span>
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
        @if (auth()->user()->can('ver-inventario') ||
                auth()->user()->can('crear-inventario') ||
                auth()->user()->can('editar-cliente') ||
                auth()->user()->can('eliminar-inventario'))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('admin/inventario') }}">
                    <i class="mdi mdi-playlist-check menu-icon"></i>
                    <span class="menu-title">INVENTARIO</span>
                </a>
            </li>
        @endif
        @if (auth()->user()->can('ver-cotizacion') ||
                auth()->user()->can('crear-cotizacion') ||
                auth()->user()->can('editar-cotizacion') ||
                auth()->user()->can('eliminar-cotizacion'))
            <li class="nav-item">
                <a class="nav-link" href="{{ url('admin/cotizacion') }}">
                    <i class="mdi mdi-currency-usd menu-icon"></i>
                    <span class="menu-title">COTIZACION</span>
                </a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                aria-controls="ui-basic">
                <i class="mdi mdi-cart menu-icon"></i>
                <span class="menu-title">FACTURACION</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="  flex-column sub-menu" style="list-style: none;">
                    @if (auth()->user()->can('ver-ingreso') ||
                            auth()->user()->can('crear-ingreso') ||
                            auth()->user()->can('editar-ingreso') ||
                            auth()->user()->can('eliminar-ingreso'))
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/ingreso') }}"><i
                                    class="mdi mdi-clipboard-arrow-down menu-icon"></i>INGRESO</a></li>
                    @endif
                    @if (auth()->user()->can('ver-venta') ||
                            auth()->user()->can('crear-venta') ||
                            auth()->user()->can('editar-venta') ||
                            auth()->user()->can('eliminar-venta'))
                        <li class="nav-item"> <a class="nav-link" href="{{ url('admin/venta') }}"><i
                                    class="mdi mdi-clipboard-arrow-up menu-icon"></i>SALIDA</a></li>
                    @endif
                </ul>
            </div>
        </li>
        @if (auth()->user()->can('ver-reporte'))
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic1" aria-expanded="false"
                aria-controls="ui-basic">
                <i class="mdi mdi-chart-bar menu-icon"></i>
                <span class="menu-title">REPORTES</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic1">
                <ul class="flex-column sub-menu" style="list-style: none;">
                    <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte') }}"><i
                                class="mdi mdi-chart-line menu-icon"></i>GRAFICOS</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte/tabladatos') }}"><i
                                class="mdi mdi-file-excel menu-icon"></i>DATOS VENTAS</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('admin/reporte/rotacionstock') }}"><i
                                class="mdi mdi-timetable menu-icon"></i>ROTACION STOCK</a></li>
                </ul>
            </div>
        </li>
        @endif
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
</nav>
