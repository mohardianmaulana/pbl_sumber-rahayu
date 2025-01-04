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
            transform: scaleX(-1);
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
    <h1 class="mb-4">Scan QR Code Pada Barang</h1>
    <video id="video" width="440" height="440" style="transform: scale(-1, 1);" autoplay></video>
    <button id="startScan" class="btn btn-primary mb-3 btn-lg">Start Scan</button>
    <button id="tambahTanpaQR">Tambah Barang Tanpa QR</button> <!-- Tambah Tombol Baru -->
    <div id="result"></div>
    <canvas id="canvas" style="display:none;"></canvas>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const resultDiv = document.getElementById('result');

        // Mengakses kamera depan
        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment",
                    width: {
                        ideal: 1280
                    }, // Mengatur lebar ideal
                    height: {
                        ideal: 720
                    }, // Mengatur tinggi ideal
                    // Menambahkan pengaturan autofocus
                    advanced: [{
                        torch: false,
                        focusMode: 'continuous'
                    }] // Jika ada, untuk mode fokus terus menerus
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

        // Event listener untuk tombol Tambah Tanpa QR
        document.getElementById('tambahTanpaQR').addEventListener('click', () => {
            // Redirect ke halaman tambah barang tanpa QR
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

                // Mengirim hasil scan ke server menggunakan AJAX untuk pengecekan
                fetch('/cek-qr', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Laravel CSRF Token
                        },
                        body: JSON.stringify({
                            id_qr: code.data // Kirimkan hasil scan QR ke server
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            resultDiv.innerText = 'QR Code sudah ada di database, Nama Barang: ' + data.nama;
                        } else {
                            resultDiv.innerText = 'QR Code baru, melanjutkan ke halaman tambah barang...';
                            // Redirect ke halaman tambah barang
                            window.location.href = '{{ route('
                            create_barang ') }}?id_qr=' + encodeURIComponent(code
                                .data);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        resultDiv.innerText = 'Terjadi kesalahan saat memproses data.';
                    });
            } else {
                requestAnimationFrame(scanQRCode); // Jika belum ada kode, terus scan
            }
        }
    </script>
</body>

</html>