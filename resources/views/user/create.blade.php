<!DOCTYPE html>
<html lang="en">

<head>
    <title>Tambah User</title>
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
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tambah User</h1>
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
                    <form action='{{ url('user') }}' method='post'>
                        @csrf
                            <a href='{{ url('/register') }}' class="btn btn-secondary btn-sm"> < Kembali</a>
                            <div>
                                
                            </div>
                            <div class="mb-3 row">
                                <label for="nama" class="col-sm-2 col-form-label">Nama Pengguna</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='name' value="{{ old('name') }}" id="name">
                                    @if (count($errors) > 0)
                                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="email" class="col-sm-2 col-form-label">Alamat Email</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='email' value="{{ old('email') }}" id="email">
                                    @if (count($errors) > 0)
                                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" value="{{ old('password') }}" id="password">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                                <i class="fas fa-eye" id="eyeIcon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if (count($errors) > 0)
                                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="roles" class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <select name="roles_id" class="form-control">
                                        <option value="" class="text-center">--- Pilih ---</option>
                                        @foreach ($role as $item)
                                            <option value="{{ $item->id }}" class="text-center" {{ old('roles_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>                    
                                        @endforeach
                                    </select>
                                    @if (count($errors) > 0)
                                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                            {{ $errors->first('roles_id') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
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

    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
    
            // Toggle the type attribute
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>
    

</body>

</html>