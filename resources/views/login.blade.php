<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Selamat Datang</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script async src="https://www.google.com/recaptcha/api.js"></script>
    <style>
        body {
            background-image: url('img/back.jpg')
        }

        .container-centered {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .logo {
            display: block;
            margin: 10px auto;
            max-width: 100px;
            /* Adjust the size as needed */
        }
    </style>
</head>

<body>
    <div class="container container-centered">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h2><b>Selamat Datang</b></h2>
                    <img src="{{ asset('template/img/Logo Super.png') }}" alt="Logo" class="logo">
                    <h3>Toko Sumberahayu</h3>
                </div>
                <div class="panel-body">
                    @if(session('error'))
                    <div class="alert alert-danger">
                        <b>Opps!</b> {{ session('error') }}
                    </div>
                    @endif
                    <form action="/actionlogin" method="post">
                        @csrf
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required="">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required="">
                        </div>
                        <div>

                        </div>
                        <!-- Google Recaptcha Widget-->
                        <div class="g-recaptcha mt-4" data-sitekey={{config('services.recaptcha.key')}}></div>
                        <button type="submit" class="btn btn-primary btn-block">Log In</button>
                    </form>
                    <a href="{{ route('katalog') }}" class="btn btn-success btn-block">Katalog</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>