<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tambah Barang</title>
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

                    <form action='{{ url('barang') }}' method='post'>
                        @csrf
                        <div class="my-3 p-3 bg-body rounded shadow-sm">
                            <div class="pb-3"> <a href='{{ url('barang') }}' class="btn btn-secondary btn-sm">
                                    < Kembali</a>
                            </div>
                            {{-- <div class="mb-3 row">
                                <label for="kode" class="col-sm-2 col-form-label">Kode</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='kode' value="{{ old('kode', 'A') }}" id="kode" oninput="addAPrefix(this)">
                            @if (count($errors) > 0)
                            <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                {{ $errors->first('kode') }}
                            </div>
                            @endif
                        </div>
                        <script>
                            function addAPrefix(input) {
                                let value = input.value;
                                if (!value.startsWith('A')) {
                                    input.value = 'A' + value.replace(/^A*/, '');
                                }
                            }
                        </script>
                </div> --}}
                <div class="mb-3 row">
                    <label for="nama" class="col-sm-2 col-form-label">Nama Barang</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name='nama' value="{{ old('nama') }}" id="nama">
                        @if (count($errors) > 0)
                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                            {{ $errors->first('nama') }}
                        </div>
                        @endif
                    </div>
                </div>
                {{-- <div class="mb-3 row">
                                <label for="harga_beli" class="col-sm-2 col-form-label">Harga Beli</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name='harga_beli' value="{{ old('harga_beli') }}" id="harga_beli">
                @if (count($errors) > 0)
                <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                    {{ $errors->first('harga_beli') }}
                </div>
                @endif
            </div>
        </div> --}}
        <div class="mb-3 row">
            <label for="harga_jual" class="col-sm-2 col-form-label">Harga Jual</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name='harga_jual' value="{{ old('harga_jual') }}" id="harga_jual">
                @if (count($errors) > 0)
                <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                    {{ $errors->first('harga_jual') }}
                </div>
                @endif
            </div>
        </div>
        <div class="mb-3 row">
            <label for="jumlah" class="col-sm-2 col-form-label">Jumlah</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name='jumlah' value="{{ old('jumlah') }}" id="jumlah">
                @if (count($errors) > 0)
                <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                    {{ $errors->first('jumlah') }}
                </div>
                @endif
            </div>
        </div>
        <div class="mb-3 row">
            <label for="supplier" class="col-sm-2 col-form-label">Min Limit</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name='minLimit' value="{{ old('minLimit') }}" id="minLimit">
                @if (count($errors) > 0)
                <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                    {{ $errors->first('minLimit') }}
                </div>
                @endif
            </div>
        </div>
        <div class="mb-3 row">
            <label for="supplier" class="col-sm-2 col-form-label">Max Limit</label>
            <div class="col-sm-10">
                <input type="number" class="form-control" name='maxLimit' value="{{ old('maxLimit') }}" id="maxLimit">
                @if (count($errors) > 0)
                <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                    {{ $errors->first('maxLimit') }}
                </div>
                @endif
            </div>
        </div>
        <div class="mb-3 row">
            <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
            <div class="col-sm-10">
                <select name="kategori_id" class="form-control">
                    <option value="" class="text-center">--- Pilih ---</option>
                    @foreach ($kategori as $item)
                    <option value="{{ $item->id }}" class="text-center" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
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
            <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
            <div class="col-sm-10">
                <input type="file" name="gambar" id="gambar">
                @error('gambar')
                <div style="color:#dc4c64; margin-top:0.25rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-3 row">
            <label for="jurusan" class="col-sm-2 col-form-label"></label>
            <div class="col-sm-10 mb-2">
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