<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Tamu</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('dist/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/admin/css/adminlte.min.css') }}">
    <style>
        @media print {
            .pagebreak {
                page-break-after: always;
            }

            .table-data {
                border: 1px solid;
                font-size: 20px;
                vertical-align: middle;
            }

            .table-data th,
            .table-data td {
                border: 1px solid;
                vertical-align: middle;
            }

            .table-data thead th,
            .table-data thead td {
                border: 1px solid;
                vertical-align: middle;
            }
        }

        .divTable {
            border-top: 1px solid;
            border-left: 1px solid;
            border-right: 1px solid;
            font-size: 21px;
        }

        .divThead {
            border-bottom: 1px solid;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container" style="font-size: 20px;">
        <p class="text-center">
            <img src="{{ asset('dist/img/logo-kemenkes.png') }}" class="img-fluid w-25">
        </p>
        <div class="float-left">
            <div class="text-uppercase h2 font-weight-bold" style="color: #3f6791;">
                <p>Daftar Tamu</p>
            </div>
        </div>
        <div class="float-right">
            {{ \Carbon\carbon::now()->isoFormat('HH:mm') }} |
            {{ \Carbon\carbon::now()->isoFormat('DD MMMM Y') }}
        </div>
        <div class="table-responsive mt-5">
            <table class="table table-data text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="width: 20%;">Tanggal</th>
                        <th>Tamu</th>
                        <th>Keperluan</th>
                    </tr>
                </thead>
                @foreach($tamu as $row)
                <thead style="font-size: 18px">
                    <tr>
                        <td class="align-top">{{ $loop->iteration }}</td>
                        <td class="text-left align-top">
                            <div class="row">
                                <div class="col-12">Masuk :</div>
                                <div class="col-12">{{ $row->jam_masuk }}</div>
                                <div class="col-12">Keluar :</div>
                                <div class="col-12">{{ $row->jam_keluar }}</div>
                            </div>
                        </td>
                        <td class="text-left align-top">
                            <div class="row">
                                <div class="col-4">Nama</div>
                                <div class="col-7">: {{ $row->nama_tamu }}</div>
                                <div class="col-4">NIK_NIP</div>
                                <div class="col-7">: {{ $row->nik_nip }}</div>
                                <div class="col-4">No. Telp</div>
                                <div class="col-7">: {{ $row->no_telpon }}</div>
                                <div class="col-4">Alamat</div>
                                <div class="col-7">: {{ $row->alamat_tamu }}</div>
                                <div class="col-4">Asal Instansi</div>
                                <div class="col-7">: {{ $row->nama_instansi }}</div>
                                <div class="col-4">No. Visitor</div>
                                <div class="col-7">: {{ $row->nomor_visitor }}</div>
                            </div>
                        </td>

                        <td class="text-left align-top">
                            <div class="row">
                                <div class="col-12">Tujuan Pegawai/Pejabat :</div>
                                <div class="col-12 font-weight-bold">{{ $row->nama_tujuan }}</div>
                                <div class="col-12">Keperluan : </div>
                                <div class="col-12 font-weight-bold">{{ $row->keperluan }}</div>
                                <div class="col-12">Lokasi : </div>
                                <div class="col-12 font-weight-bold">
                                    {{ $row->area->gedung->nama_gedung }} <br>
                                    {{ $row->area->nama_lantai }} - {{ $row->area->nama_sub_bagian }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </thead>
                @endforeach
            </table>
        </div>
    </div>
    <!-- ./wrapper -->
    <!-- Page specific script -->
    <script>
        window.addEventListener("load", window.print());
    </script>
</body>

</html>
