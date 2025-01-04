<!DOCTYPE html>
<html lang="en">

<head>
    <title>Daftar Kategori</title>
    @include('template.header')

    <style>
        .table td,
        .table th {
            vertical-align: middle;
            /* Untuk vertikal */
            text-align: center;
            /* Untuk horizontal */
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

                    <h1 class="h3 mb-4 text-gray-800">Daftar Kategori</h1>

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
                        <!-- TOMBOL TAMBAH DATA -->
                        <div class="pb-3" style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                @if (Auth::check() && Auth::user()->hasRole('admin'))
                                <a href="{{ 'kategori/create' }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus fa-xs"></i>
                                    Tambah Kategori
                                </a>
                                @endif
                            </div>
                        </div>

                        <table id="myTable" class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-md-1 text-center">No</th>
                                    <th class="col-md-2 text-center">Nama Kategori</th>
                                    <th class="col-md-2 text-center">Gambar Kategori</th>
                                    @if (Auth::check() && Auth::user()->hasRole('admin'))
                                    <th class="col-md-2 text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategori as $item)
                                <tr class="text-center">
                                    <td class="col-md-1 text-center">{{ $loop->iteration }}</td>
                                    <td class="col-md-2 text-center">{{ $item->nama_kategori }}</td>
                                    <td class="col-md-2 text-center">
                                        <img src="{{ asset('img/' . $item->gambar_kategori) }}" alt="Gambar" style="max-width: 200px; max-height: 200px;">
                                    </td>
                                    @if (Auth::check() && Auth::user()->hasRole('admin'))
                                    <td class="col-md-2 text-center">
                                        <div class="text-center">
                                            @php
                                            $persetujuanForUser = \App\Models\Persetujuan::where('kategori_id', $item->id)
                                            ->where('user_id', Auth::id())
                                            ->where('kerjaAksi', 'update')
                                            ->where('namaTabel', 'Kategori')
                                            ->first();
                                            $persetujuanIsiForm = $persetujuanForUser && $persetujuanForUser->kodePersetujuan !== null;
                                            $persetujuanDisetujui = $persetujuanIsiForm && $persetujuanForUser->lagiProses == 1;
                                            @endphp

                                            @if (!$persetujuanForUser)
                                            <a href="#" onclick="showConfirmModal('{{ url('kategori/' . $item->id . '/checkEdit') }}')" class="btn btn-primary btn-sm mx-2">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                            @elseif ($persetujuanDisetujui)
                                            <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-primary btn-sm mx-2">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                            @elseif ($persetujuanIsiForm)
                                            <a href="#" onclick="showInputCodeModal()" class="btn btn-primary btn-sm mx-2">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                            @else
                                            <a href="#" onclick="showWaitModal()" class="btn btn-primary btn-sm mx-2">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                            @endif

                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalArsipkan" data-id="{{ $item->id }}">
                                                <i class="fas fa-sync-alt"></i> Arsipkan
                                            </button>
                                        </div>
                                    </td>
                                    @endif
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

    <!-- Logout Modal-->
    @include('template.modal_logout')

    @include('template.script')

    <!-- Modal Arsipkan -->
    <div class="modal fade" id="modalArsipkan" tabindex="-1" role="dialog" aria-labelledby="modalArsipkanLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalArsipkanLabel">Yakin akan mengarsipkan kategori ini?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-footer">
                    <form id="formArsipkan" action="" method="POST">
                        @csrf
                        @method('POST')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-danger">Arsipkan</button>
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
        $('#modalArsipkan').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var action = '{{ url("/kategori/arsipkan/") }}/' + id;
            var modal = $(this);
            modal.find('#formArsipkan').attr('action', action);
        });

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

</body>

@include('sweetalert::alert')

</html>