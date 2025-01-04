<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan Pembelian</title>
    @include('template.header')
</head>

<body id="page-top">

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

                    <h1 class="h3 mb-4 text-gray-800">Laporan Pembelian</h1>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Pembelian</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Supplier</th>
                                            <th>Harga</th>
                                            <th>Total Item</th>
                                            <th>Total Harga</th>
                                            <th>Tanggal Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pembelian as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->barang_nama }}</td>
                                            <td>{{ $item->nama_supplier }}</td>
                                            <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td>{{ $item->total_item }}</td>
                                            <td>Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d-m-Y') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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

    <!-- Logout Modal-->
    @include('template.modal_logout')

    @include('template.script')

</body>

@include('sweetalert::alert')

</html>