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
                                            <th>Total Item</th>
                                            <th>Total Harga</th>
                                            <th>Nama Pegawai</th>
                                            <th>Tanggal Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pembelian as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="d-flex justify-content-between align-items-center">
                                                <span>{{ $item->barang_nama }}</span>
                                                @if (count(explode(',', $item->barang_nama)) > 1)  <!-- Memeriksa apakah ada lebih dari satu barang -->
                                                <!-- explode() digunakan untuk membagi string menjadi array berdasarkan pemisah yang diberikan(,) -->
                                                    <button class="btn btn-warning btn-sm ml-auto" data-toggle="modal" data-target="#modalDetail{{ $item->id }}">
                                                        <i class="fas fa-info-circle"></i> Detail
                                                    </button>
                                                @endif
                                            </td>
                                            <td>{{ $item->nama_supplier }}</td>
                                            <td>{{ $item->total_item }}</td>
                                            <td>Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                            <td>{{ $item->nama_user }}</td>
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

    @foreach ($pembelianDetail as $item)
    <div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel{{ $item->id }}">Detail Pembelian</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
                </div>
                <div class="modal-body">
                    <h5>Detail Barang</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th class="col-md-1 text-center">No</th>
                                <th class="col-md-3 text-center">Nama Barang</th>
                                <th class="col-md-2 text-center">Harga</th>
                                <th class="col-md-2 text-center">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item->barangs as $barang)
                            <tr class="text-center">
                                <td class="col-md-1 text-center">{{ $loop->iteration }}</td>
                                <td class="col-md-3 text-center">{{ $barang->nama }}</td>
                                <td class="col-md-2 text-center">Rp. {{ number_format($barang->pivot->harga, 0, ',', '.') }}</td>
                                <td class="col-md-2 text-center">{{ $barang->pivot->jumlah }}</td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Logout Modal-->
    @include('template.modal_logout')

    @include('template.script')

</body>

@include('sweetalert::alert')

</html>