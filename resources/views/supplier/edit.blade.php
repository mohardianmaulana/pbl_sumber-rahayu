<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Supplier</title>
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Edit Supplier</h1>
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
                        <form action='{{ url('supplier/'.$supplier->id) }}' method='post'>
                            @csrf
                            <a href='{{ url('supplier') }}' class="btn btn-secondary btn-sm">
                                < Kembali</a>
                                    <div class="mb-3 row">
                                        <label for="nama" class="col-sm-2 col-form-label required">Nama Supplier</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name='nama' value="{{ old('nama', $supplier->nama) }}" id="nama">
                                            @if (count($errors) > 0)
                                            <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                                {{ $errors->first('nama') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="jumlah" class="col-sm-2 col-form-label required">Nomor</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name='nomor' value="{{ old('nomor', $supplier->nomor) }}" id="nomor">
                                            @if (count($errors) > 0)
                                            <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                                {{ $errors->first('nomor') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="harga" class="col-sm-2 col-form-label required">Alamat</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name='alamat' value="{{ old('alamat', $supplier->alamat) }}" id="alamat">
                                            @if (count($errors) > 0)
                                            <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                                {{ $errors->first('alamat') }}
                                            </div>
                                            @endif
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