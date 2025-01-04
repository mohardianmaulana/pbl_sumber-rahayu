<!DOCTYPE html>
<html lang="en">

<head>
    <title>Daftar Persetujuan</title>
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

                    <h1 class="h3 mb-4 text-gray-800">Daftar Persetujuan</h1>

                    <div class="my-3 p-3 bg-body shadow-sm"
                        style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); border-radius:15px;">
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
                                    <th class="col-md-1 text-center">Pegawai</th>
                                    <th class="col-md-1 text-center">Perbuatan</th>
                                    <th class="col-md-1 text-center">Nama Barang</th>
                                    <th class="col-md-1 text-center">Nama Kategori</th>
                                    <th class="col-md-1 text-center">Nama Supplier</th>
                                    <th class="col-md-1 text-center">Nama Customer</th>
                                    <th class="col-md-1 text-center">Nama Tabel</th>
                                    <th class="col-md-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($persetujuan as $item)
                                <tr class="text-center">
                                    <td class="col-md-1 text-center">{{ $loop->iteration }}</td>
                                    <td class="col-md-1 text-center">{{ $item->user_nama }}</td>
                                    <td class="col-md-1 text-center">{{ $item->kerjaAksi }}</td>
                                    <td class="col-md-1 text-center">{{ $item->barang_nama ?? '' }}</td>
                                    <td class="col-md-1 text-center">{{ $item->kategori_nama ?? '' }}</td>
                                    <td class="col-md-1 text-center">{{ $item->supplier_nama ?? '' }}</td>
                                    <td class="col-md-1 text-center">{{ $item->customer_nama ?? '' }}</td>
                                    <td class="col-md-1 text-center">{{ $item->namaTabel }}</td>
                                    <td class="col-md-2 text-center">
                                        <div class="text-center">
                                            @if (is_null($item->kodePersetujuan))
                                            <a href="#" onclick="generateCode('{{ $item->id }}')" class="btn btn-primary btn-sm" data-id="{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                                Ya
                                            </a>
                                            @else
                                            <a href="#" data-toggle="modal" data-target="#modalPersetujuan" onclick="showPersetujuan('{{ $item->kodePersetujuan }}')" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endif

                                            <a href="#" data-toggle="modal"
                                                data-target="#modalDelete"
                                                onclick="$('#modalDelete #formDelete').attr('action', '{{ url('persetujuan/' . $item->id) }}')"
                                                class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                                Tidak
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- {{ $kategori->withQueryString()->links() }} --}}
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

    <!-- Modal Persetujuan -->
    <div class="modal fade" id="modalPersetujuan">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Kode Persetujuan</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p id="kodePersetujuan"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDelete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Yakin akan menghapus data ini?</h4>
                </div>
                <div class="modal-footer">
                    <form id="formDelete" action="" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-default" style="border: 1px solid #000000" data-dismiss="modal">Tidak</button>
                        <button class="btn btn-danger" type="submit">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function generateCode(id) {
            fetch('/persetujuan/' + id + '/generateCode')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show the modal with the generated code
                        showPersetujuan(data.kodePersetujuan);
                        $('#modalPersetujuan').modal('show');

                        // Update the button to show the eye icon
                        const button = document.querySelector(`a[data-id="${id}"]`);
                        if (button) {
                            button.outerHTML = `
                            <a href="#" data-toggle="modal" data-target="#modalPersetujuan" onclick="showPersetujuan('${data.kodePersetujuan}')" class="btn btn-primary btn-sm" data-id="${id}">
                                <i class="fas fa-eye"></i>
                            </a>`;
                        }
                    } else {
                        alert('Gagal menggenerate kode persetujuan.');
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function showPersetujuan(kode) {
            document.getElementById('kodePersetujuan').textContent = kode;
        }
    </script>
</body>

@include('sweetalert::alert')

</html>