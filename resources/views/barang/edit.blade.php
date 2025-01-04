<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Barang</title>
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

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Edit Barang</h1>
                        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
                    </div>
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

                    <form action='{{ url('barang/'.$barang->id) }}' method='post' enctype="multipart/form-data">
                        @csrf
                        <div class="my-3 p-3 bg-body rounded shadow-sm">
                            <div class="pb-3"> <a href='{{ url('barang') }}' class="btn btn-secondary btn-sm">
                                    < Kembali</a>
                            </div>
                            {{-- <div class="mb-3 row">
                                <label for="kode" class="col-sm-2 col-form-label">Kode</label>
                                <div class="col-sm-10">
                                    {{ $data->kode }}
                        </div>
                </div> --}}
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label required">Nama Barang</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='nama' value="{{ old('nama', $barang->nama) }}" id="nama">
                        @if (count($errors) > 0)
                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                            {{ $errors->first('nama') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="kode" class="col-sm-2 col-form-label required">Harga Beli</label>
                    <div class="col-sm-10">
                        {{ $barang->harga_beli }}
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="kode" class="col-sm-2 col-form-label required">Harga Jual</label>
                    <div class="col-sm-10">
                        {{ $barang->harga_jual }}
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="kode" class="col-sm-2 col-form-label required">Jumlah</label>
                    <div class="col-sm-10">
                        {{ $barang->jumlah }}
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="supplier" class="col-sm-2 col-form-label required">Min Limit</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name='minLimit' value="{{ old('minLimit', $barang->minLimit) }}" id="minLimit">
                        @if (count($errors) > 0)
                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                            {{ $errors->first('minLimit') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="supplier" class="col-sm-2 col-form-label required">Max Limit</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name='maxLimit' value="{{ old('maxLimit', $barang->maxLimit) }}" id="maxlimit">
                        @if (count($errors) > 0)
                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                            {{ $errors->first('maxLimit') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="kategori" class="col-sm-2 col-form-label required">Kategori</label>
                    <div class="col-sm-10">
                        <select name="kategori_id" class="form-control">
                            <option value="" class="text-center">--- Pilih ---</option>
                            @foreach ($kategori as $item)
                            <option value="{{ $item->id }}" class="text-center" {{ old('kategori_id', $barang->kategori_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kategori }}
                            </option>
                            @endforeach
                        </select>
                        @if (count($errors) > 0)
                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                            {{ $errors->first('kategori_id') }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="gambar" class="col-sm-2 col-form-label">Gambar Baru</label>
                    <div class="col-sm-10">
                        <input type="file" name="gambar" id="gambar">
                        @error('gambar')
                        <div style="color:#dc4c64; margin-top:0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="gambar" class="col-sm-2 col-form-label">Gambar Lama</label>
                    <div class="col-sm-10">
                        @if($barang->gambar)
                        <img src="{{ asset('img/' . $barang->gambar) }}" alt="Gambar" style="max-width: 200px; max-height: 200px;">
                        @else
                        <span>Tidak ada gambar</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="jurusan" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10"><button type="submit" class="btn btn-primary mt-3" name="submit">SIMPAN</button></div>
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