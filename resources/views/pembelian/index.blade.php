<!DOCTYPE html>
<html lang="en">

<head>
    <title>Data Pembelian Barang</title>
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Daftar Pembelian</h1>
                    </div>

                    <div class="my-3 p-3 bg-body shadow-sm" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); border-radius:15px;">
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
                        @if (Auth::check() && Auth::user()->hasRole('admin'))
                        <div class="pb-3 d-flex justify-content-start">
                            <a href="#modalCreate" data-toggle="modal" onclick="$('#modalCreate #formCreate').attr('action', '{{ url('pembelian/create') }}')" class="btn btn-primary btn-sm mr-2">
                                <i class="fas fa-plus fa-xs"></i>
                                Tambah Pembelian
                            </a>
                            <a href="{{ url('scan') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus fa-xs"></i>
                                Pembelian Barang Baru
                            </a>
                        </div>
                        @endif
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-md-1 text-center">No</th>
                                    <th class="col-md-1 text-center">Tanggal</th>
                                    <th class="col-md-1 text-center">Supplier</th>
                                    <th class="col-md-1 text-center">Total Item</th>
                                    <th class="col-md-1 text-center">Total Harga</th>
                                    <th class="col-md-1 text-center">Pegawai</th>
                                    <th class="col-md-1 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembelian as $item)
                                <tr class="text-center">
                                    <td class="col-md-1 text-center">{{ $loop->iteration }}</td>
                                    <td class="col-md-1 text-center">{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d-m-Y') }}</td>
                                    <td class="col-md-1 text-center">{{ $item->supplier_nama }}</td>
                                    <td class="col-md-1 text-center">{{ $item->total_item }}</td>
                                    <td class="col-md-1 text-center">Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                    <td class="col-md-1 text-center">{{ $item->user_nama }}</td>
                                    <td class="col-md-2 text-center">
                                        <div class="text-center">
                                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalDetail{{ $item->id }}">
                                                <i class="fas fa-info-circle"></i> Detail
                                            </button>
                                            @if (Auth::check() && Auth::user()->hasRole('admin'))
                                            @if ($item->status == 1)
                                            <a href="{{ url('pembelian/'.$item->id.'/edit-barang-baru') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                            @else
                                            <a href="{{ url('pembelian/'.$item->id.'/edit') }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                            @endif

                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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

    @foreach ($pembelian as $item)
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
                                <th class="col-md-2 text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item->barangs as $barang)
                            <tr class="text-center">
                                <td class="col-md-1 text-center">{{ $loop->iteration }}</td>
                                <td class="col-md-3 text-center">{{ $barang->nama }}</td>
                                <td class="col-md-2 text-center">Rp. {{ number_format($barang->pivot->harga, 0, ',', '.') }}</td>
                                <td class="col-md-2 text-center">{{ $barang->pivot->jumlah }}</td>
                                <td class="col-md-2 text-center">Rp. {{ number_format($barang->pivot->harga * $barang->pivot->jumlah, 0, ',', '.') }}</td>
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

    <div class="modal fade" id="modalCreate">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Pilih Supplier</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- FORM PENCARIAN -->
                    <div class="pb-3 d-flex justify-content-end">
                        <form id="searchForm" class="d-flex w-70">
                            <input id="searchInput" class="form-control me-1" type="search" name="katakunci"
                                value="{{ Request::get('katakunci') }}" placeholder="Masukkan nama supplier" aria-label="Search">
                            <button id="searchButton" class="btn btn-secondary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <table id="myTableModal" class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th class="col-md-1 text-center">No</th>
                                <th class="col-md-2 text-center">Nama</th>
                                <th class="col-md-3 text-center">Nomor</th>
                                <th class="col-md-3 text-center">Alamat</th>
                                <th class="col-md-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($supplier as $supp)
                            <tr class="text-center">
                                <td class="col-md-1 text-center">{{ $loop->iteration }}</td>
                                <td class="col-md-2 text-center">{{ $supp->nama }}</td>
                                <td class="col-md-3 text-center">{{ $supp->nomor }}</td>
                                <td class="col-md-3 text-center">{{ $supp->alamat }}</td>
                                <td class="col-md-2 text-center">
                                    <div class="text-center">
                                        <a href="{{ url('pembelian/create') }}?supplier_id={{ $supp->id }}&supplier_nama={{ $supp->nama }}&supplier_nomor={{ $supp->nomor }}&supplier_alamat={{ $supp->alamat }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-check-square"></i>
                                            Pilih
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('template.modal_logout')
    @include('template.script')

    <script>
        $(document).ready(function() {
            $('#searchButton').on('click', function() {
                performSearch();
            });

            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) {
                    performSearch();
                    return false;
                }
            });

            function performSearch() {
                var katakunci = $('#searchInput').val().toLowerCase();
                $('#myTableModal tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(katakunci) > -1)
                });
            }

            $('#modalCreate').on('hidden.bs.modal', function() {
                $('#searchInput').val('');
                $('#myTableModal tbody tr').show();
            });
        });
    </script>
</body>

</html>