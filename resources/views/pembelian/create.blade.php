<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tambah Pembelian Barang</title>
    @include('template.header')
    <script src="https://cdn.jsdelivr.net/npm/terbilang/dist/terbilang.min.js"></script>
</head>

<style>
    .required::after {
        content: " *";
        color: red;
    }
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
                        <h1 class="h3 mb-0 text-gray-800">Tambah Pembelian</h1>
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

                        <form method="POST" action="{{ route('pembelian.store') }}" id="pembelianForm">
                            @csrf
                            <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                            <a href="{{ route('pembelian.kembali') }}" class="btn btn-secondary btn-sm" id="kembaliBtn"> < Kembali</a>
                                        <div>Tanggal Transaksi : <span id="tanggalTransaksi"></span></div>
                            </div>
                            <div>
                                <label for="supplier_nama" class="form-label">Supplier :</label>
                                <span>{{ $supplier->nama }}</span>
                            </div>
                            <div>
                                <label for="supplier_nomor" class="form-label">Nomor :</label>
                                <span>{{ $supplier->nomor }}</span>
                            </div>
                            <div>
                                <label for="supplier_alamat" class="form-label">Alamat :</label>
                                <span>{{ $supplier->alamat }}</span>
                            </div>

                            <div class="mb-3 row">
                                <label for="nama" class="col-sm-2 col-form-label">Nama Barang</label>
                                <div class="col-sm-4 d-flex justify-content-end">
                                    <input type="text" class="form-control" id="searchBarang"
                                        placeholder="Pilih Barang" aria-label="Search">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal"
                                        data-target="#qrScanModal">
                                        <i class="fas fa-qrcode"></i>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modalBarang">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                            <table class="table table-striped" id="selectedBarangTable">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Barang</th>
                                        <th class="text-center required">Harga</th>
                                        <th class="text-center required">Jumlah</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataBarang as $index => $item)
                                    <tr class="text-center" data-id="{{ $item['id'] }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item['nama'] }}</td>
                                        <td class="text-center">
                                            <!-- Rp. {{ number_format($item['harga'], 0, ',', '.') }} -->
                                            <input type="number" class="form-control harga-barang" name="harga_beli[]"
                                                value="{{ $item['harga'] }}">
                                            <input type="hidden" name="barang_id[]" value="{{ $item['id'] }}">
                                        </td>
                                        <td class="text-center">
                                            <input type="number" class="form-control jumlah-barang" name="jumlah[]"
                                                value="1" oninput="hitungTotal()">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm deleteBarangBtn"
                                                data-id="{{ $item['id'] }}">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card-header py-4 flex-row align-items-center justify-content-between" style="border-radius: 15px; background-color: cornflowerblue; color: white;">
                                        <h6 class="m-0 font-weight-bold">Total Harga: <span id="totalHarga">Rp 0</span></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">Simpan Pembelian</button>
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
            $(document).ready(function() {
                // Eksekusi hitungTotal saat halaman pertama kali dimuat
                hitungTotal();

                if (sessionStorage.getItem('reloadAndCalculate') === 'true') {
                    // Panggil fungsi hitungTotal untuk menghitung total harga setelah reload
                    hitungTotal();

                    // Hapus flag reloadAndCalculate setelah dipanggil
                    sessionStorage.removeItem('reloadAndCalculate');

                    // Buka modal QR Scan secara otomatis setelah halaman reload
                    $('#qrScanModal').modal('show');
                } else if (sessionStorage.getItem('reload') === 'true') {
                    // Panggil fungsi hitungTotal untuk menghitung total harga setelah reload
                    hitungTotal();

                    // Hapus flag reloadAndCalculate setelah dipanggil
                    sessionStorage.removeItem('reload');

                    // Buka modal QR Scan secara otomatis setelah halaman reload
                    $('#modalBarang').modal('show');
                }

                // Tangkap perubahan pada input jumlah barang dan simpan ke sessionStorage
                $(document).on('input', '.jumlah-barang', function() {
                    let barangId = $(this).closest('tr').data('id');
                    let jumlah = $(this).val();
                    // Simpan jumlah barang ke sessionStorage dengan key yang unik berdasarkan barangId
                    sessionStorage.setItem('jumlah_' + barangId, jumlah);
                });

                // Memulihkan nilai jumlah dari sessionStorage setelah halaman dimuat ulang
                window.addEventListener('load', function() {
                    // Periksa setiap elemen jumlah barang dan perbarui nilai berdasarkan sessionStorage
                    $('.jumlah-barang').each(function() {
                        let barangId = $(this).closest('tr').data('id');
                        // Ambil jumlah barang yang tersimpan di sessionStorage
                        let savedJumlah = sessionStorage.getItem('jumlah_' + barangId);
                        if (savedJumlah) {
                            $(this).val(savedJumlah); // Terapkan nilai jumlah yang disimpan
                        } else {
                            $(this).val(1); // Jika tidak ada nilai yang disimpan, set ke 1 sebagai default
                        }
                    });

                    // Panggil hitungTotal setelah memulihkan nilai jumlah
                    hitungTotal();
                });

                // Tangkap event klik pada tombol kembali
                document.getElementById('kembaliBtn').addEventListener('click', function(e) {
                    // Hapus semua data dari sessionStorage
                    sessionStorage.clear();
                });


                // Tangkap perubahan pada input harga barang
                $(document).on('input', '.harga-barang', function() {
                    hitungTotal(); // Panggil fungsi hitungTotal jika harga diubah
                });

                // Saat memilih barang dari modal
                $(document).on('click', '.pilihBarangBtn', function() {
                    const id = $(this).data('id');
                    const nama = $(this).data('nama');
                    const harga = $(this).data('harga');

                    // Simpan barang ke sesi melalui AJAX
                    $.ajax({
                        url: '/pembelian/tambah-sesi', // Endpoint untuk menambahkan barang ke sesi
                        method: 'POST',
                        data: {
                            id: id,
                            nama: nama,
                            harga: harga,
                            source: 'modal',
                            _token: '{{ csrf_token() }}', // Laravel CSRF Token
                        },
                        success: function(response) {
                            console.log(response.message);
                            // addBarangToTable(id, nama, harga); // Tampilkan barang di tabel
                            sessionStorage.setItem('reload', 'true'); // Set flag reloadPage
                            location.reload();
                        },
                        error: function(xhr) {
                            // Tampilkan pesan error jika barang sudah dipilih
                            if (xhr.status === 400) {
                                alert(xhr.responseJSON.message); // Menampilkan pesan error dari response JSON
                            } else {
                                console.error(xhr.responseText);
                            }
                        },
                    });
                });



                // // Tangkap event submit pada form
                // $('#pembelianForm').on('submit', function(e) {
                //     const total = parseFloat($('#totalHarga').text().replace(/Rp\.|\./g, "").replace(
                //         /[^0-9.-]+/g, ""));
                //     const bayar = parseFloat($('#bayar').val()) || 0; // Ambil nilai bayar

                //     // Periksa jika bayar kurang dari total
                //     if (bayar < total) {
                //         alert("Jumlah bayar kurang dari total!"); // Tampilkan pesan peringatan
                //         e.preventDefault(); // Batalkan pengiriman form
                //     }
                // });

                // Check if there are query parameters for QR code data
                const urlParams = new URLSearchParams(window.location.search);
                const id = urlParams.get('id');
                const nama = urlParams.get('nama');
                const harga = urlParams.get('harga');

                if (id && nama && harga) {
                    // Add the scanned item to the table
                    addBarangToTable(id, nama, harga);
                }

                function addBarangToTable(id, nama, harga) {
                    let exists = false;
                    $('#selectedBarangTable tbody tr').each(function() {
                        if ($(this).data('id') == id) {
                            exists = true;
                            console.log(exists)
                            return false; // Stop loop if found
                        }
                    });

                    function formatRupiah(angka) {
                        var number_string = angka.toString(),
                            sisa = number_string.length % 3,
                            rupiah = number_string.substr(0, sisa),
                            ribuan = number_string.substr(sisa).match(/\d{3}/g);

                        if (ribuan) {
                            separator = sisa ? '.' : '';
                            rupiah += separator + ribuan.join('.');
                        }

                        return 'Rp. ' + rupiah; // Add 'Rp. ' prefix
                    }
                    if (!exists) {
                        var formattedHarga = formatRupiah(harga);
                        var newRow = `<tr class="text-center" data-id="${id}">
                                    <td class="col-md-1 text-center"></td>
                                    <td class="col-md-3 text-center">${nama}</td>
                                    <td class="col-md-2 text-center">
                                        
                                        <input type="number" class="form-control harga-barang" name="harga_beli[]" value="${harga}">
                                        <input type="hidden" name="barang_id[]" value="${id}">
                                    </td>
                                    <td class="col-md-2 text-center">
                                        <input type="number" class="form-control jumlah-barang" name="jumlah[]" value="1" oninput="hitungTotal()">
                                    </td>
                                    <td class="col-md-2 text-center">
                                        <button type="button" class="btn btn-danger btn-sm deleteBarangBtn" data-id="${id}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>`;
                        $('#selectedBarangTable tbody').append(newRow); // Append new row at the end
                        updateNomorUrut(); // Update numbering
                        hitungTotal(); // Update total price
                    } else {
                        alert("Barang sudah dipilih.");
                    }
                }

                // Tangkap perubahan pada input jumlah barang
                $(document).on('input', '.jumlah-barang', function() {
                    hitungTotal();
                });

                $(document).on('click', '.deleteBarangBtn', function() {
                    const barangId = $(this).data('id');

                    // Hapus data jumlah barang dari sessionStorage
                    sessionStorage.removeItem('jumlah_' + barangId);

                    // Hapus baris barang dari tabel
                    $(this).closest('tr').remove();

                    // Panggil fungsi hitungTotal untuk memperbarui total harga setelah barang dihapus
                    hitungTotal();

                    // Hapus barang dari sesi melalui AJAX
                    $.ajax({
                        url: '/pembelian/hapus-sesi',
                        method: 'POST',
                        data: {
                            id: barangId,
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            console.log(response.message);
                            $(`tr[data-id="${id}"]`).remove(); // Hapus baris dari tabel
                            updateNomorUrut();
                            hitungTotal(); // Update total harga
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        },
                    });
                });


                // Update nomor urut pada tabel
                function updateNomorUrut() {
                    $('#selectedBarangTable tbody tr').each(function(index) {
                        $(this).find('td:first').text(index + 1); // Update nomor urut berdasarkan indeks
                    });
                }

                // Hitung total harga
                function hitungTotal() {
                    let total = 0;
                    $('#selectedBarangTable tbody tr').each(function() {
                        const harga = parseFloat($(this).find('.harga-barang').val()) ||
                            0; // Get the harga value
                        const jumlah = parseInt($(this).find('.jumlah-barang').val()) ||
                            0; // Get the jumlah value
                        total += harga * jumlah; // Accumulate total
                    });
                    $('#totalHarga').text('Rp ' + total.toLocaleString('id-ID')); // Format total for display
                    hitungKembali(); // Call to calculate change
                }

                function formatRupiah(angka) {
                    var number_string = angka.toString(),
                        sisa = number_string.length % 3,
                        rupiah = number_string.substr(0, sisa),
                        ribuan = number_string.substr(sisa).match(/\d{3}/g);

                    if (ribuan) {
                        separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    return rupiah; // Add 'Rp. ' prefix
                }

                // Hitung kembalian
                function hitungKembali() {
                    // Ambil total harga dengan menghapus "Rp. " dan karakter non-numeric
                    const total = parseFloat($('#totalHarga').text().replace(/Rp\.|\./g, "").replace(/[^0-9.-]+/g,
                        "")); // Hapus "Rp. " dan format lainnya
                    const bayar = parseFloat($('#bayar').val()) || 0; // Parse nilai input untuk bayar

                    // Periksa jika nilai bayar valid
                    if (!isNaN(bayar)) {
                        if (bayar < total) {
                            $('#kembali').val(''); // Kosongkan jika nilai bayar kurang dari total
                            // alert("Jumlah bayar kurang dari total!"); // Tampilkan pesan peringatan
                        } else {
                            var kembali = bayar - total; // Hitung kembalian
                            if (kembali > 0) {
                                $('#kembali').val(formatRupiah(kembali)); // Format kembalian
                            } else if (kembali === 0) {
                                $('#kembali').val('0'); // Tampilkan 'Uang Pas' jika kembalian 0
                            }
                        }
                    } else {
                        $('#kembali').val(''); // Kosongkan jika nilai bayar tidak valid
                    }
                }



                // Event listener for 'bayar' input field
                $('#bayar').on('input', function() {
                    hitungKembali();
                });

                // Set the current date in the 'tanggalTransaksi' span
                const currentDate = new Date().toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit'
                });
                $('#tanggalTransaksi').text(currentDate);
            });
        </script>

        <!-- QR Scan Modal -->
        <div class="modal fade" id="qrScanModal" tabindex="-1" role="dialog" aria-labelledby="qrScanModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="qrScanModalLabel">Scan QR Code</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body d-flex flex-column justify-content-center align-items-center">
                        <h1 class="mb-4">Scan QR Code Pada Barang</h1>
                        <video id="video" class="border border-dark mb-3" style="transform: scale(-1, 1);"
                            width="440" height="440" autoplay></video>
                        <button id="startScan" class="btn btn-primary mb-3 btn-lg">Start Scan</button>
                        <div id="result" class="font-weight-bold"></div>
                        <canvas id="canvas" style="display:none;"></canvas>
                        <div id="scanNotification" class="mt-3"></div>


                        <script>
                            const video = document.getElementById('video');
                            const canvas = document.getElementById('canvas');
                            const context = canvas.getContext('2d');
                            const resultDiv = document.getElementById('result');

                            // Access the back camera
                            navigator.mediaDevices.getUserMedia({
                                    video: {
                                        facingMode: "environment",
                                        width: {
                                            ideal: 1280
                                        },
                                        height: {
                                            ideal: 720
                                        },
                                        advanced: [{
                                            torch: false,
                                            focusMode: 'continuous'
                                        }]
                                    }
                                })
                                .then(stream => {
                                    video.srcObject = stream;
                                })
                                .catch(err => {
                                    console.error("Error accessing the camera: ", err);
                                });

                            document.getElementById('startScan').addEventListener('click', () => {
                                scanQRCode();
                            });

                            // Saat barang berhasil di-scan QR Code
                            function scanQRCode() {
                                canvas.width = video.videoWidth;
                                canvas.height = video.videoHeight;
                                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                                const code = jsQR(imageData.data, canvas.width, canvas.height);

                                if (code) {
                                    // Kirim data QR ke server untuk mencari barang
                                    fetch('/cek-qr', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            },
                                            body: JSON.stringify({
                                                id_qr: code.data
                                            }),
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.exists) {
                                                console.log(data.exists);
                                                // Ambil jumlah dari sessionStorage
                                                let storedJumlah = sessionStorage.getItem('jumlah_' + data.id);

                                                // Jika jumlah ada di sessionStorage, tambahkan ke sesi di backend
                                                let jumlahBaru = storedJumlah ? parseInt(storedJumlah) + 1 : 1;

                                                // Kirim data ke backend
                                                $.ajax({
                                                    url: '/pembelian/tambah-sesi',
                                                    method: 'POST',
                                                    data: {
                                                        id: data.id,
                                                        nama: data.nama,
                                                        harga: data.harga,
                                                        jumlah: jumlahBaru, // Kirim jumlah yang diperbarui
                                                        source: 'qr',
                                                        _token: '{{ csrf_token() }}',
                                                    },
                                                    success: function(response) {
                                                        console.log(response.message);

                                                        // Perbarui sessionStorage setelah backend berhasil diproses
                                                        sessionStorage.setItem('jumlah_' + data.id, jumlahBaru);

                                                        // Tandai bahwa halaman perlu di-reload dan fungsi hitungTotal akan dipanggil
                                                        sessionStorage.setItem('reloadAndCalculate', 'true');
                                                        location.reload();
                                                    },
                                                    error: function(xhr) {
                                                        // Tampilkan pesan error jika barang sudah dipilih
                                                        if (xhr.status === 400) {
                                                            alert(xhr.responseJSON.message);
                                                        } else {
                                                            console.error(xhr.responseText);
                                                        }
                                                    },
                                                });
                                            } else {
                                                alert('Barang tidak ditemukan!');
                                            }
                                        });
                                } else {
                                    // Jika QR Code tidak terdeteksi, teruskan untuk scan
                                    requestAnimationFrame(scanQRCode);
                                }
                            }

                            // Fungsi untuk menangani logika setelah halaman di-reload
                            $(document).ready(function() {
                                // Periksa apakah ada flag reloadAndCalculate di sessionStorage
                                if (sessionStorage.getItem('reloadAndCalculate') === 'true') {
                                    // Panggil fungsi hitungTotal untuk menghitung total harga setelah reload
                                    hitungTotal();

                                    // Hapus flag reloadAndCalculate setelah dipanggil
                                    sessionStorage.removeItem('reloadAndCalculate');
                                }
                            });
                        </script>
                    </div>
                    {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div> --}}
                </div>
            </div>
        </div>

        <!-- Modal Barang -->
        <div class="modal fade" id="modalBarang" tabindex="-1" role="dialog" aria-labelledby="modalBarangLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalBarangLabel">Pilih Barang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped" id="barangTable">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Harga Beli</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barang as $item)
                                <tr class="text-center">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $item->nama }}</td>
                                    <td class="text-center">Rp. {{ number_format($item->harga_beli, 0, ',', '.') }}
                                        {{-- @if (isset($rataRataHargaBeli[$item->id]))
                                        Rp. {{ number_format($rataRataHargaBeli[$item->id], 0, ',', '.') }}
                                        @else
                                        -
                                        @endif --}}
                                    </td>
                                    <td class="text-center">{{ $item->jumlah }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm pilihBarangBtn"
                                            data-id="{{ $item->id }}" data-nama="{{ $item->nama }}"
                                            data-harga="{{ $item->harga_beli }}" data-jumlah="{{ $item->jumlah }}">
                                            <i class="fas fa-check-square"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


</body>

</html>