<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="{{ asset('template/img/Logo Super.png') }}" style="filter: invert(50%) brightness(1000%);" alt="Logo" width="50" height="50">
        </div>               
        <div class="sidebar-brand-text mx-1">Sumber Rahayu <sup>Store</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Data Master
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-chart-pie"></i>
            <span>Barang</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- <h6 class="collapse-header"></h6> --}}
                <a class="collapse-item" href="/barang">Data Barang</a>
                <a class="collapse-item" href="/barang/arsip">Arsip Barang</a>
                <a class="collapse-item" href="/harga">Data Harga</a>
            </div>
        </div>
    </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#kategori" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-th-large"></i>
                <span>Kategori</span>
            </a>
            <div id="kategori" class="collapse" aria-labelledby="headingPages"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="/kategori">Daftar Kategori</a>
                    <a class="collapse-item" href="/kategori/arsip">Arsip Kategori</a>
                </div>
            </div>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#supplier" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-truck"></i>
                <span>Supplier</span>
            </a>
            <div id="supplier" class="collapse" aria-labelledby="headingPages"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="/supplier">Daftar Supplier</a>
                    <a class="collapse-item" href="/supplier/arsip">Arsip Supplier</a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="#" data-toggle="collapse" data-target="#customer" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-users"></i>
                <span>Customer</span>
            </a>
            <div id="customer" class="collapse" aria-labelledby="headingPages"
                data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="/customer">Daftar Customer</a>
                    <a class="collapse-item" href="/customer/arsip">Arsip Customer</a>
                </div>
            </div>
        </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Transaksi
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#pembelian" aria-expanded="true"
        aria-controls="collapsePages">
        <i class="fas fa-cart-plus"></i>
            <span>Pembelian Barang</span>
        </a>
        <div id="pembelian" class="collapse" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/pembelian">Pembelian Baru</a>
                <a class="collapse-item" href="/pembelian/lama">Pembelian Lama</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#penjualan" aria-expanded="true"
        aria-controls="collapsePages">
        <i class="fas fa-shopping-cart"></i>
            <span>Penjualan Barang</span>
        </a>
        <div id="penjualan" class="collapse" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/penjualan">Transaksi Baru</a>
                <a class="collapse-item" href="/penjualan/lama">Transaksi Lama</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#laporan" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-fw fa-folder"></i>
            <span>Laporan Keuangan</span>
        </a>
        <div id="laporan" class="collapse" aria-labelledby="headingPages"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="/laporan_penjualan">Laporan Penjualan</a>
                <a class="collapse-item" href="/laporan_pembelian">Laporan Pembelian</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Tables -->
    {{-- Auth::check(): Mengecek apakah ada user yang sedang login. --}}
    {{-- Auth::user()->hasRole('owner'): Mengecek apakah user yang login memiliki role owner. --}}
    @if (Auth::check() && Auth::user()->hasRole('owner'))
    <li class="nav-item">
        <a class="nav-link" href="/persetujuan">
            <i class="fas fa-file-contract"></i>
            <span>Persetujuan</span>
        </a>
    </li>
    @endif
    <!-- Nav Item - Dashboard
    <li class="nav-item">
        <a class="nav-link" href="/register">
            <i class="fas fa-users"></i>
            <span>Register</span>
        </a>
    </li> -->

    <li class="nav-item">
        <a class="nav-link" href="/katalog">
            <i class="fas fa-file-contract"></i>
            <span>Katalog</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>