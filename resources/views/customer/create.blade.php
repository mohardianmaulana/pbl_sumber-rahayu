<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tambah Customer</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Tambah Customer</h1>
                    </div>
                    <div class="my-3 p-3 bg-body shadow-sm" style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); border-radius:15px;">
                        <form action='{{ url('customer') }}' method='post'>
                            @csrf
                            <a href='{{ url('customer') }}' class="btn btn-secondary btn-sm">
                                < Kembali</a>
                                    {{-- <div class="mb-3 row">
                                <label for="kode" class="col-sm-2 col-form-label">Kode</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='kode' value="{{ old('kode', 'CUST') }}" id="kode" oninput="addAPrefix(this)">
                                    @if (count($errors) > 0)
                                    <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('kode') }}
                                    </div>
                                    @endif
                    </div>
                    <script>
                        function addAPrefix(input) {
                            let value = input.value;
                            if (!value.startsWith('CUST')) {
                                input.value = 'CUST' + value.replace(/^CUST*/, '');
                            }
                        }
                    </script>
                </div> --}}
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label required">Nama Customer</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='nama' value="{{ old('nama') }}" id="nama">
                        @if (count($errors) > 0)
                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                            {{ $errors->first('nama') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nomor" class="col-sm-2 col-form-label required">Nomor</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='nomor' value="{{ old('nomor') }}" id="nomor">
                        @if (count($errors) > 0)
                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                            {{ $errors->first('nomor') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="alamat" class="col-sm-2 col-form-label required">Alamat</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='alamat' value="{{ old('alamat') }}" id="alamat">
                        @if (count($errors) > 0)
                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                            {{ $errors->first('alamat') }}
                        </div>
                        @endif
                    </div>
                </div>
                {{-- <div class="mb-3 row">
                                <label for="penjelasan" class="col-sm-2 col-form-label">Tentang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='penjelasan' value="{{ old('penjelasan') }}" id="penjelasan">
                @if (count($errors) > 0)
                <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                    {{ $errors->first('penjelasan') }}
                </div>
                @endif
            </div>
        </div> --}}
        <div class="mb-3 row justify-content-end">
            <div class="col-auto">
                <button type="submit" class="btn btn-primary" name="submit">SIMPAN</button>
            </div>
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