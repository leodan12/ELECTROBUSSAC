<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/dashboard') }}">
                <i class="mdi mdi-home menu-icon"></i>
                <span class="menu-title">INICIO</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/category') }}">
                <i class="mdi mdi-format-list-bulleted-type menu-icon"></i>
                <span class="menu-title">CATEGORIAS</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/products') }}">
                <i class="mdi mdi-book menu-icon"></i>
                <span class="menu-title">PRODUCTOS</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/kits') }}">
                <i class="mdi mdi-google-circles-communities menu-icon"></i>
                <span class="menu-title">KITS</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/company') }}">
                <i class="mdi mdi-store menu-icon"></i>
                <span class="menu-title">MIS EMPRESAS</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/cliente') }}">
                <i class="mdi mdi-hospital-building menu-icon"></i>
                <span class="menu-title">CLIENTES/PROVEEDORES</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/inventario') }}">
                <i class="mdi mdi-playlist-check menu-icon"></i>
                <span class="menu-title">INVENTARIO</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/cotizacion') }}">
                <i class="mdi mdi-currency-usd menu-icon"></i>
                <span class="menu-title">COTIZACION</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false"
                aria-controls="ui-basic">
                <i class="mdi mdi-cart menu-icon"></i>
                <span class="menu-title">FACTURACION</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
                <ul class="  flex-column sub-menu" style="list-style: none;">
                    <li class="nav-item"> <a class="nav-link" href="{{ url('admin/ingreso') }}"><i
                                class="mdi mdi-clipboard-arrow-down menu-icon"></i>INGRESO</a></li>
                    <li class="nav-item"> <a class="nav-link" href="{{ url('admin/venta') }}"><i
                                class="mdi mdi-clipboard-arrow-down menu-icon"></i>SALIDA</a></li>
                     
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('admin/reporte') }}">
                <i class="mdi mdi-currency-usd menu-icon"></i>
                <span class="menu-title">REPORTES</span>
            </a>
        </li>
        @if (Auth::user()->role_as == 1)
            <li class="nav-item">
                <a class="nav-link" href="{{ url('admin/users') }}">
                    <i class="mdi mdi-account-multiple menu-icon"></i>
                    <span class="menu-title">USUARIOS</span>
                </a>
            </li>
        @endif

    </ul>
</nav>
