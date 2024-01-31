@extends('dashboard.layout.app')

@section('content')

@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ Session::get("success") }}',
    });
</script>
@endif


<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <small>Tambah Pengguna</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.show') }}">Daftar Pengguna</a></li>
                        <li class="breadcrumb-item active">Tambah Pengguna</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-right mt-4">
                    <a href="{{ route('user.show') }}" class="btn btn-default border-dark">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <form id="form" action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="card w-100">
                    <div class="card-header">
                        <label class="mt-3">Tambah Pengguna Baru</label>
                        <hr>
                        <div class="row">
                            <label class="col-md-3 col-form-label">Role</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select name="role_id" class="form-control" required>
                                        @foreach($role as $row)
                                        <option value="{{ $row->id_role }}">{{ $row->role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">Pegawai</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select name="pegawai_id" class="form-control">
                                        @foreach($pegawai as $row)
                                        <option value="{{ $row->id_pegawai }}">{{ $row->nama_pegawai }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">Username</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="username" class="form-control" name="username" placeholder="Masukkan Username" required>
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">Password*</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text border-secondary">
                                                <i class="fas fa-eye-slash" id="eye-icon-pass"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">Konfirmasi Password*</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="conf-password" name="conf_password" placeholder="Konfirmasi Password" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text border-secondary">
                                            <i class="fas fa-eye-slash" id="eye-icon-conf"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event)">
                            <i class="fas fa-circle-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div><br>
</div>

@section('js')
<script>
    function confirmSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('form');
        const inputs = form.querySelectorAll('select[required], input[required], textarea[required]');
        let isFormValid = true;

        inputs.forEach(input => {
            if (input.hasAttribute('required') && input.value.trim() === '') {
                input.style.borderColor = 'red';
                isFormValid = false;
            } else {
                input.style.borderColor = '';
            }
        });

        var password = $("#password").val();
        var conf_password = $("#conf-password").val();
        if (password != conf_password) {
            if (password != conf_password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Konfirmasi password tidak sama!',
                });
                return false;
            }
        }

        if (isFormValid) {
            Swal.fire({
                title: 'Tambah Pengguna ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Gagal',
                text: 'Pastikan seluruh kolom terisi',
                icon: 'error',
            });
        }
    }

    // Password
    $(document).ready(function() {
        $("#eye-icon-pass").click(function() {
            var password = $("#password");
            var icon = $("#eye-icon");
            if (password.attr("type") == "password") {
                password.attr("type", "text");
                icon.removeClass("fas fa-eye-slash").addClass("fas fa-eye");
            } else {
                password.attr("type", "password");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            }
        });

        $("#eye-icon-conf").click(function() {
            var password = $("#conf-password");
            var icon = $("#eye-icon");
            if (password.attr("type") == "password") {
                password.attr("type", "text");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            } else {
                password.attr("type", "password");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            }
        });
    });
</script>
@endsection
@endsection
