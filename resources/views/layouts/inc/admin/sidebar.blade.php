<nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{url ('admin/dashboard') }}">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">INICIO</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url ('admin/category') }}">
              <i class="mdi mdi-view-headline menu-icon"></i>
              <span class="menu-title">CATEGORIAS</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url ('admin/company') }}">
              <i class="mdi mdi-chart-pie menu-icon"></i>
              <span class="menu-title">MIS EMPRESAS</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url ('admin/cliente') }}">
              <i class="mdi mdi-chart-pie menu-icon"></i>
              <span class="menu-title">CLIENTES/PROVEEDORES</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url ('admin/products') }}">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title">PRODUCTOS</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url ('admin/inventario') }}">
              <i class="mdi mdi-emoticon menu-icon"></i>
              <span class="menu-title">INVENTARIO</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-circle-outline menu-icon"></i>
              <span class="menu-title">FACTURACION</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{url ('admin/ingreso') }}">INGRESO</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{url ('admin/venta') }}">SALIDA</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{url ('admin/users') }}">
              <i class="mdi mdi-account menu-icon"></i>
              <span class="menu-title">USUARIOS</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="documentation/documentation.html">
              <i class="mdi mdi-file-document-box-outline menu-icon"></i>
              <span class="menu-title">Documentation</span>
            </a>
          </li>
        </ul>
      </nav>