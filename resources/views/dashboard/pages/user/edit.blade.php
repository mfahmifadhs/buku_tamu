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
                        <small>Edit Pengguna</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        @if(Auth::user()->role_id == 1)
                        <li class="breadcrumb-item"><a href="{{ route('user.show') }}">Daftar Pengguna</a></li>
                        @endif
                        <li class="breadcrumb-item active">Edit Pengguna</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <form id="form" action="{{ route('user.update', $id) }}" method="POST">
                @csrf
                <div class="card w-100">
                    <div class="card-header">
                        <label class="mt-3">Edit Pengguna</label>
                        <hr>
                        <div class="row">
                            <label class="col-md-3 col-form-label">Pegawai</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select name="pegawai_id" class="form-control">
                                        @foreach($pegawai as $row)
                                        <option value="{{ $row->id_pegawai }}" <?php echo $row->id_role == $user->role_id ? 'selected' : ''; ?>>
                                            {{ $row->nama_pegawai }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">Username</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="username" class="form-control" name="username" value="{{ $user->username }}" required>
                                </div>
                            </div>
                            @if(Auth::user()->role_id == 1)
                            <label class="col-md-3 col-form-label">Role</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select name="role_id" class="form-control" required>
                                        @foreach($role as $row)
                                        <option value="{{ $row->id_role }}" <?php echo $row->id_role == $user->role_id ? 'selected' : ''; ?>>
                                            {{ $row->role }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">Status</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select name="status" class="form-control">
                                        <option value="aktif" <?php echo $user->status == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="non-aktif" <?php echo $user->status == 'non-aktif' ? 'selected' : ''; ?>>Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event)">
                            <i class="fas fa-save"></i> Simpan
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
                title: 'Simpan Perubahan ?',
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
