<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Tamu</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/admin/css/adminlte.css') }}">
    <!-- Data Tables -->
    <link rel="stylesheet" href="{{ asset('dist/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('dist/admin/plugins/select2/css/select2.min.css') }}">
    <!-- Sweet alert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container-fluid">
                <a href="{{ route('dashboard') }}" class="navbar-brand">
                    <img src="{{ asset('dist/admin/img/logo-kemenkes.png') }}" alt="Kemenkes Logo" class="img-fluid w-25" style="opacity: .8">
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ Str::startsWith(request()->path(), 'dashboard') ? 'active' : '' }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tamu.show') }}" class="nav-link {{ Str::startsWith(request()->path(), 'tamu') ? 'active' : '' }}">Tamu</a>
                        </li>
                        @if (Auth::user()->role_id == 1)
                        <li class="nav-item">
                            <a href="{{ route('user.show') }}" class="nav-link {{ Str::startsWith(request()->path(), 'user') ? 'active' : '' }}">Pengguna</a>
                        </li>
                        @elseif (Auth::user()->role_id != 2)
                        <li class="nav-item">
                            <a href="{{ route('pegawai.show') }}" class="nav-link {{ Str::startsWith(request()->path(), 'pegawai') ? 'active' : '' }}">Pegawai</a>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link text-right" style="width: 30vh;" href="{{ route('user.detail', Auth::user()->id) }}" role="button" title="Profil">
                            <i class="fa-solid fa-user-circle"></i> {{ Auth::user()->username }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-left" style="width: 14vh;" href="{{ route('keluar') }}" role="button" title="Keluar">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /.navbar -->

        @yield('content')

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Version 1.0
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2024 <a href="#">Biro Umum</a>.</strong>
        </footer>
    </div>
    <!-- ./wrapper -->

    <script src="{{ asset('dist/admin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('dist/admin/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('dist/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script src="{{ asset('dist/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('dist/admin/plugins/chart.js/Chart.min.js') }}"></script>

    @yield('js')

    <!-- table -->
    <script>
        $(function() {
            $("#table").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "info": true,
                "paging": true,
                "searching": true
            }).buttons().container().appendTo('#table_wrapper .col-md-6:eq(0)')
        })



        // Waktu live
        $(function() {
            setInterval(timestamp, 1000);
        });

        function timestamp() {
            $.ajax({
                url: "{{ route('dashboard.time') }}",
                success: function(response) {
                    $('#timestamp').html(response);
                },
            });
        }
    </script>
</body>

</html>
