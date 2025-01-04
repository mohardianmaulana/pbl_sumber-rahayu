<!DOCTYPE html>
<html lang="en">

<head>
    <title>Pembelian Barang Baru</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Tambah Pembelian Barang Baru</h1>
                    </div>
                    <form action="{{ url('/pembelianBarang') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="my-3 p-3 bg-body rounded shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <a href="{{ url('pembelian') }}" class="btn btn-secondary btn-sm">
                                    < Kembali</a>
                                        <div>Tanggal Transaksi : <span id="tanggalTransaksi"></span></div>
                            </div>
                            <div class="mb-3 row">
                                <label for="id_qr" class="col-sm-2 col-form-label">Id Qr</label>
                                <div class="col-sm-10">
                                    <!-- Menambahkan atribut readonly untuk membuat input tidak bisa diedit -->
                                    <input type="text" class="form-control" name="id_qr" value="{{ old('id_qr', $id_qr) }}" id="id_qr" readonly>
                                    @if (count($errors) > 0)
                                    <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('id_qr') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="nama" class="col-sm-2 col-form-label required">Nama Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nama" value="{{ old('nama') }}" id="nama">
                                    @if (count($errors) > 0)
                                    <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('nama') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="supplier" class="col-sm-2 col-form-label required">Supplier</label>
                                <div class="col-sm-10">
                                    <select name="supplier_id" class="form-control">
                                        <option value="" class="text-center">--- Pilih ---</option>
                                        @foreach ($supplier as $item)
                                        <option value="{{ $item->id }}" class="text-center" {{ old('supplier_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @if (count($errors) > 0)
                                    <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('supplier_id') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="harga_beli" class="col-sm-2 col-form-label required">Harga Beli</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="harga_beli" value="{{ old('harga_beli') }}" id="harga_beli">
                                    @if (count($errors) > 0)
                                    <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('harga_beli') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="harga_jual" class="col-sm-2 col-form-label required">Harga Jual</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="harga_jual" value="{{ old('harga_jual') }}" id="harga_jual">
                                    @if (count($errors) > 0)
                                    <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('harga_jual') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="jumlah" class="col-sm-2 col-form-label required">Jumlah</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="jumlah" value="{{ old('jumlah') }}" id="jumlah">
                                    @if (count($errors) > 0)
                                    <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('jumlah') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="totalHarga" class="col-sm-2 col-form-label required">Total Harga:</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="totalHarga" id="totalHarga" value="{{ old('totalHarga') ?? 'Rp 0' }}" readonly>
                                    @if ($errors->has('totalHarga'))
                                    <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('totalHarga') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="minLimit" class="col-sm-2 col-form-label required">Min Limit</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="minLimit" value="{{ old('minLimit') }}" id="minLimit">
                                    @if (count($errors) > 0)
                                    <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('minLimit') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="maxLimit" class="col-sm-2 col-form-label required">Max Limit</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="maxLimit" value="{{ old('maxLimit') }}" id="maxLimit">
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

    <script>
        var spanTanggal = document.getElementById('tanggalTransaksi');
        var tanggalSekarang = new Date();
        var options = {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        };
        var tanggalFormatted = tanggalSekarang.toLocaleDateString('id-ID', options);
        spanTanggal.textContent = tanggalFormatted;

        $(document).ready(function() {
            // Tangkap perubahan pada input jumlah barang
            $('#jumlah, #harga_beli').on('input', function() {
                hitungTotal();
            });

            // Fungsi untuk menghitung total keseluruhan harga barang yang dipilih
            function hitungTotal() {
                var harga = parseFloat($('#harga_beli').val());
                var jumlah = parseFloat($('#jumlah').val());

                // Periksa jika jumlah valid (bukan NaN atau kosong)
                if (!isNaN(jumlah) && jumlah !== 0 && !isNaN(harga) && harga !== 0) {
                    var total = harga * jumlah;
                    // Tampilkan total di input totalHarga
                    $('#totalHarga').val(total.toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }));
                } else {
                    $('#totalHarga').val('Rp 0');
                }
            }
        });
    </script>

</body>

</html>