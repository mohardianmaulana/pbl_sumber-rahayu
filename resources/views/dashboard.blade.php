<!DOCTYPE html>
<html lang="en">

<head>
    <title>Dashboard</title>
    @include('template.header')
</head>

<body id="page-top">

    @php
    // Target jumlah supplier
    $targetSuppliers = 100;

    // Hitung jumlah supplier saat ini
    $currentSuppliers = \App\Models\Supplier::count();

    // Hitung persentase supplier saat ini terhadap target
    $percentage = ($currentSuppliers / $targetSuppliers) * 100;
    $percentage = $percentage > 100 ? 100 : $percentage; // Pastikan tidak lebih dari 100%
    @endphp

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('template.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('template.navbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Barang</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \App\Models\Barang::count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-chart-pie fa-lg text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Penjualan Bulan Ini
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. {{ number_format($totalPenjualanBulanIni, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-shopping-cart fa-lg text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pembelian Bulan Ini</div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">Rp. {{ number_format($totalPembelianBulanIni, 0, ',', '.') }}</div>
                                                </div>
                                                {{-- <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-cart-plus fa-lg text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Keuntungan Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. {{ number_format($keuntungan, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-lg text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
    <!-- Area Chart -->
    <div class="col-xl-12 col-lg-7">
        <div class="card shadow mb-4" style="border-radius:15px;">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="border-radius:15px;">
                <h6 class="m-0 font-weight-bold text-primary">Notifikasi Stok Barang</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Pemasukan</a>
                        <a class="dropdown-item" href="#">Pengeluaran</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                @foreach ($barang as $item)
                    @if ($item->jumlah <= $item->minLimit)
                        <div class="alert alert-danger">
                            Stok {{ $item->nama }} menipis! segera isi stok atau lapor ke owner ya!
                        </div>
                    @endif
                    @if ($item->jumlah >= $item->maxLimit)
                        <div class="alert alert-warning">
                            Stok {{ $item->nama }} terlalu berlebihan akan memakan tempat, lapor ke owner!
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Data Stok Barang -->
    <div class="col-xl-4 col-lg-4">
        <div class="card shadow mb-4" style="border-radius:15px;">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="border-radius:15px;">
                <h6 class="m-0 font-weight-bold text-primary">Data Stok Barang</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie" style="margin-left: 40px;">
                    {!! $donutChart->container() !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Data Penjualan Bulan Ini -->
    <div class="col-xl-8 col-lg-8">
        <div class="card shadow mb-4" style="border-radius:15px;">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="border-radius:15px;">
                <h6 class="m-0 font-weight-bold text-primary">Data Penjualan Bulan Ini</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie" style="margin-left: 20px;">
                    {!! $barChart->container() !!}
                </div>
            </div>
        </div>
    </div>
</div>




    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    @include('template.footer')
    <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    @include('template.modal_logout')

    @include('template.script')
    <script src="{{ $donutChart->cdn() }}"></script>
    <script src="{{ $barChart->cdn() }}"></script>

    {{ $donutChart->script() }}
    {{ $barChart->script() }}

</body>

</html>