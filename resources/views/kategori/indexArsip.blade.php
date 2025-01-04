<!DOCTYPE html>
<html lang="en">

<head>
    <title>Daftar Kategori</title>
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

                    <h1 class="h3 mb-4 text-gray-800">Arsip Kategori</h1>

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
                        <table id="myTable" class="table table-striped">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-md-1 text-center">No</th>
                                    <th class="col-md-2 text-center">Nama Kategori</th>
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
                                    @if (Auth::check() && Auth::user()->hasRole('admin'))
                                    <td class="col-md-2 text-center">
                                        <div class="text-center">
                                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalPulihkan" data-id="{{ $item->id }}">
                                                <i class="fas fa-sync-alt"></i> Pulihkan
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

    <script>
        $('#modalPulihkan').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var action = '{{ url("/kategori/pulihkan/") }}/' + id;
            var modal = $(this);
            modal.find('#formPulihkan').attr('action', action);
        });
    </script>

</body>

@include('sweetalert::alert')

</html>