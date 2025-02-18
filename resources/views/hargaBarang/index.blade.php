<!DOCTYPE html>
<html lang="en">

<head>
    <title>Daftar Harga</title>
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

                    <h1 class="h3 mb-4 text-gray-800">Daftar Harga Barang</h1>

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
                        {{-- <!-- TOMBOL TAMBAH DATA -->
                        <div class="pb-3" style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <a href="{{ 'kategori/create' }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus fa-xs"></i>
                        Tambah Harga
                        </a>
                    </div>
                </div> --}}

                @include('template.search')

                <div class="table-responsive">
                <table id="myTable" class="table table-striped nowrap">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Supplier</th>
                            <th class="text-center">Harga Beli</th>
                            <th class="text-center">Harga Jual</th>
                            <th class="text-center">Tanggal Mulai</th>
                            <th class="text-center">Tanggal Selesai</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hargaBarang as $item)
                        <tr class="text-center">
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $item->nama_barang }}</td>
                            <td class="text-center">{{ $item->nama_supplier }}</td>
                            <td class="text-center">Rp. {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                            <td class="text-center">Rp. {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                            <td class="text-center">{{ $item->formatted_tanggal_mulai }}</td>
                            <td class="text-center">{{ $item->formatted_tanggal_selesai }}</td>
                            <td class="text-center">
                                @if(!$item->isComplete)
                                <div class="text-center">
                                    <a href='{{ url('harga/'.$item->id.'/edit') }}'
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </a>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @include('template.paging')
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

    <div class="modal fade" id="modalDelete">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Yakin akan menghapus data ini?</h4>
                    {{-- <button class="btn-close" data-dismiss="modal" >
                        <i class="fas fa-times"></i>
                    </button> --}}
                </div>
                <div class="modal-footer">
                    <form id="formDelete" action="" method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-default" style="border: 1px solid #000000"
                            data-dismiss="modal">Tidak</button>
                        <button class="btn btn-danger" type="submit">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

@include('sweetalert::alert')

</html>