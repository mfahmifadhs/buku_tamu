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
                        <small>Edit Pegawai</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pegawai.show') }}">Daftar Pegawai</a></li>
                        <li class="breadcrumb-item active">Edit Pegawai</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-right mt-4">
                    <a href="{{ route('pegawai.show') }}" class="btn btn-default border-dark">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <form id="form" action="{{ route('pegawai.update', $id) }}" method="POST">
                @csrf
                <div class="card w-100">
                    <div class="card-header">
                        <label class="mt-3">Edit Pegawai</label>
                        <hr>
                        <div class="row">
                            <label class="col-md-3 col-form-label">Unit Kerja</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <select class="form-control" name="unit_kerja">
                                        <option value="465930">BIRO UMUM</option>
                                    </select>
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">NIP*</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="number" class="form-control" name="nip" value="{{ $pegawai->nip }}">
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">Nama Pegawai*</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="nama_pegawai" value="{{ $pegawai->nama_pegawai }}">
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">Jabatan*</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="nama_jabatan" value="{{ $pegawai->nama_jabatan }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary" onclick="confirmSubmit(event)">
                            <i class="fas fa-circle-plus"></i> Simpan
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
