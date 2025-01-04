<!DOCTYPE html>
<html lang="en">

<head>
    <title>Katalog Barang</title>
    @include('template.header')

    <!-- Add Bootstrap CSS (if not included already) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            text-align: center;
        }

        .card-title {
            font-size: 18px;
            font-weight: bold;
        }

        .price {
            font-size: 16px;
            color: #e74c3c;
            font-weight: bold;
        }

        .btn-custom {
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            padding: 8px 15px;
            text-transform: uppercase;
        }

        .btn-custom:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        {{-- <!-- Sidebar -->
        @include('template.sidebar')
        <!-- End of Sidebar --> --}}

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                {{-- <!-- Topbar -->
                @include('template.navbar')
                <!-- End of Topbar --> --}}

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800">Katalog Barang</h1>

                    <a href='{{ url('/') }}' class="btn btn-secondary btn-sm"> < Kembali</a>

                    <!-- Dropdown untuk memilih kategori -->
                    <form id="kategoriForm" action="{{ route('katalog') }}" method="GET">
                        <div class="mb-4">
                            <label for="kategori" class="form-label">Pilih Kategori</label>
                            <select class="form-select" id="kategori" name="kategori_id" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($kategori as $kategori)
                                    <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                        @foreach ($barang as $item)
    <div class="col">
        <div class="card">
            <img src="{{ asset('img/' . ($item->gambar ?: $item->kategori_gambar)) }}" 
                 class="card-img-top" 
                 alt="{{ $item->nama }}">
            <div class="card-body">
                <h5 class="card-title">{{ $item->nama }}</h5>
                <p class="card-text">{{ $item->kategori_nama }}</p>
                <p class="price">Rp. {{ number_format($item->harga_jual, 0, ',', '.') }}</p>
                <p class="card-text">Stok: {{ $item->jumlah }}</p>

                <a href="#modalCreate" data-toggle="modal" onclick="$('#modalCreate #formCreate').attr('action', '{{ url('penjualan/create') }}')" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-shopping-cart"></i> Add To Cart
                </a>
            </div>
        </div>
    </div>
@endforeach
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

    <script>
        $(document).ready(function() {
            
        });
    </script>

    <!-- Add Bootstrap JS (if not included already) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

@include('sweetalert::alert')

</html>
