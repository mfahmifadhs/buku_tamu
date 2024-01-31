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
    <div class="content-header"></div>

    <div class="content">
        <div class="container">
            <center>
                <div class="card w-50">
                    <div class="card-header">
                        <label class="mt-3 h4">PROFIL</label>
                        <hr>
                        <div class="row text-left">
                            <label class="col-md-3 col-form-label">Role</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $user->role->role }}" readonly>
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">Nama</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $user->pegawai?->nama_pegawai }}" readonly>
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">Username</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $user->username }}" readonly>
                                </div>
                            </div>
                            <label class="col-md-3 col-form-label">Status</label>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" value="{{ $user->password_teks }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">
                            <i class="fas fa-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </center>
        </div>
    </div><br>
</div>
@endsection
