<!DOCTYPE html>
<html lang="en">

<head>
    <title>Data Pengguna</title>
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

                    <!-- Page Heading -->
                    
                    <h1 class="h3 mb-0 text-gray-800">Data Pengguna</h1>
                    <div class="my-3 p-3 bg-body shadow-sm"
                         style="box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); border-radius:15px;">
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
                        <!-- TOMBOL TAMBAH DATA -->
                        <div class="pb-3" style="display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                @if (Auth::check() && Auth::user())
                                    <a href="{{ 'user/create' }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus fa-xs"></i>
                                        Tambah Data User
                                    </a>
                                @endif
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                    <tr>
                                                        <td>{{ $user->id }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->roles_name }}</td>
                                                        <td>
                                                            <a href='{{ url('user/'.$user->id.'/edit') }}' class="btn btn-primary btn-sm">
                                                                <i class="fas fa-edit"></i>
                                                                Edit
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <div>
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

    @include('template.modal_logout')
    @include('template.script')

</body>

</html>