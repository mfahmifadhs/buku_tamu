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
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <small>Daftar Pengguna</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Daftar Pengguna</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-right mt-4">
                    <a href="{{ route('user.create') }}" class="btn btn-default border-dark">
                        <i class="fas fa-circle-plus"></i> Tambah
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card w-100">
                <div class="card-header">
                    <label>Tabel Daftar Pengguna</label>
                </div>
                <div class="card-header">
                    <div class="">
                        <table id="table" class="table table-bordered text-center">
                            <thead class="text-sm">
                                <tr>
                                    <th>No</th>
                                    <th>Role</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($user as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->role->role }}</td>
                                    <td>{{ $row->pegawai?->nama_pegawai }}</td>
                                    <td>{{ $row->username }}</td>
                                    <td>{{ $row->password_teks }}</td>
                                    <td>{{ $row->status }}</td>
                                    <td>
                                        <a href="{{ route('user.edit', $row->id) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i>
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
    </div><br>
</div>

@endsection
