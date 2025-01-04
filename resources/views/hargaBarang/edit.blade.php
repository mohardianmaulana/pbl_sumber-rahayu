<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Harga Barang</title>
    @include('template.header')
</head>

<style>
    .required::after {
        content: " *";
        color: red;
    }
</style>

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
                    <h1 class="h3 mb-4 text-gray-800">Edit Harga Barang</h1>
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
                        <form action='{{ url('harga/'.$hargaBarang->id) }}' method='post'>
                            @csrf
                            <a href='{{ url('harga') }}' class="btn btn-secondary btn-sm">
                                < Kembali</a>
                                    <div class="mb-3 row">
                                        <label for="kode" class="col-sm-2 col-form-label">Nama Barang</label>
                                        <div class="col-sm-10">
                                            {{ $hargaBarang->nama_barang }}
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="kode" class="col-sm-2 col-form-label">Harga Beli</label>
                                        <div class="col-sm-10">
                                            {{ $hargaBarang->harga_beli }}
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="nama" class="col-sm-2 col-form-label required">Harga Jual</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name='harga_jual' value="{{ old('harga_jual', $hargaBarang->harga_jual) }}" id="harga_jual">
                                            @if (count($errors) > 0)
                                            <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                                {{ $errors->first('harga_jual') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="kode" class="col-sm-2 col-form-label">Tanggal Mulai</label>
                                        <div class="col-sm-10">
                                            {{ $hargaBarang->formatted_tanggal_mulai }}
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="kode" class="col-sm-2 col-form-label">Tanggal Selesai</label>
                                        <div class="col-sm-10">
                                            {{ $hargaBarang->tanggal_selesai ? \Carbon\Carbon::parse($hargaBarang->tanggal_selesai)->format('d-m-Y') : '' }}
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="simpan" class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10"><button type="submit" class="btn btn-primary" name="submit">SIMPAN</button></div>
                                    </div>
                    </div>
                    </form>
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

</html>