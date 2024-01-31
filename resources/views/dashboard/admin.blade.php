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
                    <h1 class="m-0"> Hello, <small>{{ $name }}</small></h1>
                </div>
                <div class="col-sm-6 text-right">
                    <h6 id="timestamp" class="mt-2"></h6>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card w-100">
                <div class="card-header mt-2">
                    <div class="float-left">
                        <label>Tabel Tamu</label>
                    </div>
                    <div class="float-right">
                        <a href="{{ route('tamu.admin.create') }}" class="btn btn-default border-dark">
                            <i class="fas fa-circle-plus"></i> Tambah
                        </a>
                    </div>
                </div>

                <div class="card-header">
                    <div class="">
                        <table id="table" class="table table-bordered text-center">
                            <thead class="text-sm">
                                <tr>
                                    <th style="width: 0%;">No</th>
                                    <th style="width: 12%;">Tanggal/Jam</th>
                                    <th style="width: 13%;">Nama Tamu</th>
                                    <th style="width: 10%;">NIK/NIP</th>
                                    <th style="width: 10%;">Asal Instansi</th>
                                    <th style="width: 15%;">Pegawai/Pejabat Tujuan</th>
                                    <th style="width: 12%;">Keperluan</th>
                                    <th style="width: 18%;">Lokasi Tujuan</th>
                                    <th style="width: 10%;">No. Visitor</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($tamu as $row)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                        @php $url = route('tamu.leave', ['id' => $row->id_tamu]); @endphp
                                        <a href="" onclick="confirmLink(event, '{{ $url }}', 'Selesai', 'Apakah tamu atas nama {{ $row->nama_tamu }} dengan no. visitor {{ $row->nomor_visitor }} akan keluar?')">
                                            <i class="fas fa-circle-check text-warning border border-dark rounded-circle p-1"></i>
                                        </a>
                                    </td>
                                    <td>{{ $row->jam_masuk }}</td>
                                    <td class="text-left">{{ $row->nama_tamu }}</td>
                                    <td>{{ $row->nik_nip }}</td>
                                    <td>{{ $row->nama_instansi }}</td>
                                    <td>{{ $row->nama_tujuan }}</td>
                                    <td class="text-left">{{ $row->keperluan }}</td>
                                    <td class="text-left">
                                        {{ $row->area->gedung->nama_gedung }} <br>
                                        {{ $row->area->nama_lantai }} - {{ $row->area->nama_ruang }} <br>
                                        {{ $row->area->nama_sub_bagian }}
                                    </td>
                                    <td>No. {{ $row->nomor_visitor }}</td>
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

@section('js')
<script>
    function confirmLink(event, url, title, text) {
        event.preventDefault();

        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>
@endsection

@endsection
