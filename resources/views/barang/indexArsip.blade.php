<!DOCTYPE html>
<html lang="en">

<head>
    <title>Daftar Barang</title>
    @include('template.header')

    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .table td,
        .table th {
            vertical-align: middle;
            text-align: center;
        }

        .table img {
            max-width: 100px;
            height: auto; /* Proporsional */
        }

        @media (max-width: 767px) {
            .table td,
            .table th {
                font-size: 12px;
                padding: 8px;
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        .btn-sm {
            font-size: 12px;
            padding: 6px 12px;
        }
    </style>
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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Daftar Barang</h1>
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

                        @include('template.search')

                        <div class="table-responsive">
                        <table class="table table-striped nowrap" id="myTable">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-md-1 text-center">No</th>
                                    <th class="col-md-1 text-center">Nama</th>
                                    <th class="col-md-1 text-center">Kategori</th>
                                    <th class="col-md-1 text-center">Harga Beli</th>
                                    <th class="col-md-1 text-center">Harga Jual</th>
                                    <th class="col-md-1 text-center">Jumlah</th>
                                    <th class="col-md-1 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barang as $item)
                                <tr class="text-center">
                                    <td class="col-md-1 text-center">{{ $loop->iteration }}</td>
                                    <td class="col-md-1 text-center">{{ $item->nama }}</td>
                                    <td class="col-md-1 text-center">{{ $item->kategori_nama }}</td>
                                    <td class="col-md-1 text-center">@if(isset($rataRataHargaBeli[$item->id]))
                                        Rp. {{ number_format($rataRataHargaBeli[$item->id], 0, ',', '.') }}
                                        @else
                                        -
                                        @endif</td>
                                    <td class="col-md-1 text-center">Rp. {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                    <td class="col-md-1 text-center">{{ $item->jumlah }}</td>
                                    <td>
                                        <div class="text-center d-flex align-items-end">
                                                @if (Auth::check() && Auth::user()->hasRole('admin'))
                                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalPulihkan" data-id="{{ $item->id }}">
                                                        <i class="fas fa-sync-alt"></i> Pulihkan
                                                    </button>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>

                        @include('template.paging')
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

    <!-- Modal Pulihkan -->
    <div class="modal fade" id="modalPulihkan" tabindex="-1" role="dialog" aria-labelledby="modalPulihkanLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPulihkanLabel">Yakin akan memulihkan kategori ini?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <form id="formPulihkan" action="" method="POST">
                        @csrf
                        @method('POST')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-danger">Pulihkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirm -->
    <div class="modal fade" id="confirmModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ingin mengajukan persetujuan?</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <a href="#" id="confirmYes" class="btn btn-primary">Ya</a>
                    <button class="btn btn-default" data-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Wait -->
    <div class="modal fade" id="waitModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tunggu Dulu</h4>
                </div>
                <div class="modal-body">
                    <p>Masih ada persetujuan yang belum selesai.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Input Code -->
    <div class="modal fade" id="inputCodeModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Masukkan Kode Persetujuan</h4>
                </div>
                <div class="modal-body">
                    <form id="inputCodeForm" action="{{ url('persetujuan/verify') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="kode">Kode Persetujuan</label>
                            <input type="text" class="form-control" id="kode" name="kode" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showWaitModal() {
            $('#waitModal').modal('show');
        }

        function showConfirmModal(url) {
            $('#confirmModal #confirmYes').attr('href', url);
            $('#confirmModal').modal('show');
        }

        function showInputCodeModal() {
            $('#inputCodeModal').modal('show');
        }
    </script>

    <script>
        $('#modalPulihkan').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var action = '{{ url("/barang/pulihkan/") }}/' + id;
            var modal = $(this);
            modal.find('#formPulihkan').attr('action', action);
        });
    </script>

</body>

@include('sweetalert::alert')

</html>