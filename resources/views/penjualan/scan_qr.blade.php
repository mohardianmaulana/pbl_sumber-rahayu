<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <script src="https://unpkg.com/jsqr/dist/jsQR.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }

        video {
            border: 2px solid black;
            margin-bottom: 20px;
            transform: scale(-1, 1);
            /* Membalik video secara horizontal */
        }

        button {
            margin-bottom: 20px;
            padding: 10px 20px;
            font-size: 16px;
        }

        #result {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>Scan QR Code Pada Barang</h1>
    <video id="video" width="300" height="300" autoplay></video>
    <button id="startScan">Start Scan</button>
    <div id="result"></div>
    <canvas id="canvas" style="display:none;"></canvas>

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

        // Event listener for the button to add without QR
        document.getElementById('tambahTanpaQR').addEventListener('click', () => {
            // Redirect to the add item page without QR
            window.location.href = '{{ route('
            create_barang ') }}';
        });

        function scanQRCode() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, canvas.width, canvas.height);

            if (code) {
                resultDiv.innerText = `QR Code detected: ${code.data}`;
                // Proses data QR Code
                fetch('/cek_qr', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id_qr: code.data
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            resultDiv.innerText = `Nama Barang: ${data.nama}`;

                            window.location.href = `/penjualan/create?&id_barang=${data.id}&nama=${encodeURIComponent(data.nama)}&harga=${data.harga}`;
                        } else {
                            resultDiv.innerText = 'QR Code tidak ditemukan!, Tolong tambahkan barang ke Pembelian Barang Baru terlebih dahulu';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        resultDiv.innerText = 'Terjadi kesalahan saat memproses data.';
                    });
            } else {
                // Jika QR tidak terdeteksi, tetap lanjutkan pemindaian
                requestAnimationFrame(scanQRCode);
            }
        }


        // Check if there are query parameters for QR code data
        const urlParams = new URLSearchParams(window.location.search);
        const id_barang = urlParams.get('id_barang');
        const nama = urlParams.get('nama');
        const harga = urlParams.get('harga');

        if (id_barang && nama && harga) {
            // Add the scanned item to the table
            addBarangToTable(id_barang, nama, harga);
        }

        function addBarangToTable(id, nama, harga) {
            let exists = false;
            $('#selectedBarangTable tbody tr').each(function() {
                if ($(this).data('id') == id) {
                    exists = true;
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
                // Append new row to the table
                var newRow = `<tr class="text-center" data-id="${id}">
                                    <td class="col-md-1 text-center"></td>
                                    <td class="col-md-3 text-center">${nama}</td>
                                    <td class="col-md-2 text-center">
                                        ${formattedHarga}
                                        <input type="hidden" class="harga-barang" name="harga_jual[]" value="${harga}">
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
                $('#selectedBarangTable tbody').append(newRow);
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

        // Tangkap klik pada tombol Hapus Barang di tabel
        $(document).on('click', '.deleteBarangBtn', function() {
            var row = $(this).closest('tr');
            row.remove();
            updateNomorUrut(); // Update nomor urut setelah menghapus
            hitungTotal(); // Update total setelah menghapus
        });

        // Update nomor urut pada tabel
        function updateNomorUrut() {
            $('#selectedBarangTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1); // Update the first cell with the index
            });
        }

        // Hitung total harga
        function hitungTotal() {
            let total = 0;
            $('#selectedBarangTable tbody tr').each(function() {
                const harga = parseFloat($(this).find('.harga-barang').val()) || 0; // Get the harga value
                const jumlah = parseInt($(this).find('.jumlah-barang').val()) || 0; // Get the jumlah value
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
            const total = parseFloat($('#totalHarga').text().replace(/Rp\.|\./g, "").replace(/[^0-9.-]+/g, "")); // Hapus "Rp. " dan format lainnya
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
    </script>

</body>

</html>