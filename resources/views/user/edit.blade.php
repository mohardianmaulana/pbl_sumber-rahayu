<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit User</title>
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
                        <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
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
                    <form action='{{ url('user/'.$user->id) }}' method='post'>
                        @csrf
                            <a href='{{ url('user') }}' class="btn btn-secondary btn-sm"> < Kembali</a>
                            <div class="mb-3 row">
                                <label for="name" class="col-sm-2 col-form-label">Nama User</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='name' value="{{ old('name', $user->name) }}" id="name">
                                    @if (count($errors) > 0)
                                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('nama') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='email' value="{{ old('email', $user->email) }}" id="nomor">
                                    @if (count($errors) > 0)
                                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('nomor') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name='password' value="{{ old('password', $user->password) }}" id="alamat">
                                    @if (count($errors) > 0)
                                        <div style="width:auto; color:#dc4c64; margin-top:0.25rem;">
                                        {{ $errors->first('alamat') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="simpan" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10"><button type="submit" class="btn btn-primary" name="submit">SIMPAN</button></div>
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