<!DOCTYPE html>
<html lang="en">

<head>
    <title>Laporan Penjualan Bulan Ini</title>
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

                    <h1 class="h3 mb-4 text-gray-800">Laporan Penjualan Bulan Ini</h1>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Penjualan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Barang</th>
                                            <th>Customer</th>
                                            <th>Total Item</th>
                                            <th>Harga</th>
                                            <th>Bayar</th>
                                            <th>Kembali</th>
                                            <th>Pegawai</th>
                                            <th>Tanggal Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($penjualan as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->barang_nama }}</td>
                                            <td>{{ $item->nama_customer }}</td>
                                            <td>{{ $item->total_item }}</td>
                                            <td>{{ $item->total_harga }}</td>
                                            <td>{{ $item->bayar }}</td>
                                            <td>{{ $item->kembali }}</td>
                                            <td>{{ $item->nama_user }}</td>
                                            <td>{{ $item->tanggal_transaksi }}</td>
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