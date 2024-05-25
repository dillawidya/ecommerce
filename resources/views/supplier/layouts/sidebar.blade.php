<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #1f2b1a">
    <!-- Brand Logo -->
    <a href="#" class="brand-link" style="background-color: #dbb143">
        <img src="{{ asset('admin-assets/img/logo.png') }}" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
        <span class="brand-text font-weight" style="color: #dbb143">.</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-item ">
                    <a href="{{ route('supplier.dashboard') }}" class="nav-link" style="color: #dbb143;">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>																
                </li>
                <li class="nav-item">
                    <a href="{{ route('supplier-products.index') }}" class="nav-link" style="color: #dbb143;">
                        <i class="nav-icon fas fa-cubes"></i>
                        <p>Produk</p>
                    </a>
                </li>							
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
 </aside>