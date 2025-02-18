<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Kategori</title>
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
                    <h1 class="h3 mb-4 text-gray-800">Edit Kategori</h1>
                    <div class="my-3 p-3 bg-body shadow-sm" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); border-radius:15px;">
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        <form action='{{ url('kategori/'.$kategori->id) }}' method='post' enctype="multipart/form-data">
                            @csrf
                            <a href='{{ url('kategori') }}' class="btn btn-secondary btn-sm">
                                < Kembali</a>
                                    <div class="mb-3 row">
                                        <label for="nama" class="col-sm-2 col-form-label required">Nama Kategori</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name='nama_kategori' value="{{ old('nama_kategori', $kategori->nama_kategori) }}" id="nama_kategori">
                                            @if (count($errors) > 0)
                                            <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                                {{ $errors->first('nama_kategori') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="gambar_kategori" class="col-sm-2 col-form-label">Gambar Baru</label>
                                        <div class="col-sm-10">
                                            <input type="file" name="gambar_kategori" id="gambar_kategori">
                                            @error('gambar_kategori')
                                            <div style="color:#dc4c64; margin-top:0.25rem;">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="gambar_kategori" class="col-sm-2 col-form-label">Gambar Lama</label>
                                        <div class="col-sm-10">
                                            <img src="{{ asset('img/' . $kategori->gambar_kategori) }}" alt="Gambar" style="max-width: 200px; max-height: 200px;">
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