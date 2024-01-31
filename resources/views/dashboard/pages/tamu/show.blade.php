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
                        <small>Daftar Tamu</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Daftar Tamu</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-right mt-4">
                    <a id="downloadButton" onclick="downloadFile('excel')" class="btn btn-csv bg-success border-success" target="__blank">
                        <span class="btn btn-success btn-sm"><i class="fas fa-download"></i></span>
                        <span id="downloadSpinner" class="spinner-border spinner-border-sm" style="display: none;" role="status" aria-hidden="true"></span>
                        <small>Download Excel</small>
                    </a>
                    <a id="downloadButton" onclick="downloadFile('pdf')" class="btn btn-csv bg-danger border-danger" target="__blank">
                        <span class="btn btn-danger btn-sm"><i class="fas fa-print"></i></span>
                        <span id="downloadSpinner" class="spinner-border spinner-border-sm" style="display: none;" role="status" aria-hidden="true"></span>
                        <small>Cetak</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card w-100">
                <div class="card-header">
                    <label>Tabel Daftar Tamu</label>
                    <form id="form" action="{{ route('tamu.filter') }}" method="GET">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label class="col-form-label text-xs">Tanggal</label>
                                <select name="tanggal" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">Seluruh Tanggal</option>
                                    @foreach(range(1, 31) as $dateNumber)
                                    @php $rowTgl = Carbon\Carbon::create()->day($dateNumber)->isoFormat('DD'); @endphp
                                    <option value="{{ $rowTgl }}" <?php echo $tanggal == $rowTgl ? 'selected' : '' ?>>
                                        {{ $rowTgl }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="col-form-label text-xs">Bulan</label>
                                <select name="bulan" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">Seluruh Bulan</option>
                                    @foreach(range(1, 12) as $monthNumber)
                                    @php $rowBulan = Carbon\Carbon::create()->month($monthNumber); @endphp
                                    <option value="{{ $rowBulan->isoFormat('MM') }}" <?php echo $bulan == $rowBulan->isoFormat('M') ? 'selected' : '' ?>>
                                        {{ $rowBulan->isoFormat('MMMM') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="col-form-label text-xs">Tahun</label>
                                <select name="tahun" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">Seluruh Tahun</option>
                                    @foreach(range(2024, 2030) as $yearNumber)
                                    @php $rowTahun = Carbon\Carbon::create()->year($yearNumber); @endphp
                                    <option value="{{ $rowTahun->isoFormat('Y') }}" <?php echo $tahun == $rowTahun->isoFormat('Y') ? 'selected' : '' ?>>
                                        {{ $rowTahun->isoFormat('Y') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label class="col-form-label text-xs">Gedung</label>
                                <select name="gedung" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">Seluruh Gedung</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="col-form-label text-xs">Sub Bagian</label>
                                <select name="area" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="">Seluruh Sub Bagian</option>
                                    @foreach ($dataArea as $row)
                                    <option value="{{ $row->id_area }}" <?php echo $row->id_area == $area ? 'selected' : '' ?>>
                                        {{ $gedung == 2 ? $row->nama_lantai.' - '.$row->nama_sub_bagian : $row->nama_lantai.' ('.$row->nama_ruang.') - '. $row->nama_sub_bagian }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-header">
                    <div class="">
                        <table id="table" class="table table-bordered text-center">
                            <thead class="text-sm">
                                <tr>
                                    <th style="width: 0%;">No</th>
                                    <th style="width: 15%;">Waktu</th>
                                    <th style="width: 23%;">Tamu</th>
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
                                        @if(Auth::user()->role_id != 3)<br>
                                        <a href="{{ route('tamu.edit', $row->id_tamu) }}" class="">
                                            <i class="fas fa-pencil"></i>
                                        </a>

                                        @php $url = route('tamu.delete', ['id' => $row->id_tamu]); @endphp
                                        <a href="" onclick="confirmRemove(event, '{{ $url }}')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        @endif
                                    </td>
                                    <td class="text-left">
                                        @php $lokasi = $row->lokasi_datang == 'lobi-a' ? 'Lobi A, Adhyatma' : ($row->lokasi_data == 'lobi-c' ? 'Lobi C, Adhyatma' : 'Lobi Sujudi'); @endphp
                                        Lokasi : {{ $lokasi }} <br>
                                        Masuk : {{ $row->jam_masuk }} <br>
                                        Keluar <span style="margin-left: 2.8px;">:</span> {{ $row->jam_keluar }}
                                    </td>
                                    <td class="text-left">
                                        <div class="row">
                                            <div class="col-md-4">Nama</div>
                                            <div class="col-md-7">: {{ $row->nama_tamu }}</div>
                                            <div class="col-md-4">NIK_NIP</div>
                                            <div class="col-md-7">: {{ $row->nik_nip }}</div>
                                            <div class="col-md-4">No. Telp</div>
                                            <div class="col-md-7">: {{ $row->no_telpon }}</div>
                                            <div class="col-md-4">Alamat</div>
                                            <div class="col-md-7">: {{ $row->alamat_tamu }}</div>
                                            <div class="col-md-4">Asal Instansi</div>
                                            <div class="col-md-7">: {{ $row->nama_instansi }}</div>
                                        </div>
                                    </td>
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
    $('[name="area"]').select2()

    function confirmRemove(event, url) {
        event.preventDefault();

        Swal.fire({
            title: 'Hapus ?',
            text: 'Hapus data tamu ini',
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

    $(function() {
        var dataSelected = '{{  $gedung }}';
        var select = $('[name="gedung"]');

        $.ajax({
            url: "{{ route('gedung.select') }}",
            type: "GET",
            dataType: 'json',
            success: function(response) {
                select.empty();
                $.each(response, function(key, val) {
                    var selected = dataSelected == val.id ? 'selected' : '';
                    select.append('<option value="' + val.id + '" ' + selected + '>' + val.text + '</option>');
                });

                if (dataSelected) {
                    select.val(dataSelected);
                }
            },
            error: function(error) {
                console.error("Error fetching data:", error);
            }
        });
    });

    function downloadFile(downloadFile) {
        var form = document.getElementById('form');
        var downloadButton = document.getElementById('downloadButton');
        var downloadSpinner = document.getElementById('downloadSpinner');

        downloadSpinner.style.display = 'inline-block';

        var existingDownloadFile = form.querySelector('[name="downloadFile"]');
        if (existingDownloadFile) {
            existingDownloadFile.remove();
        }

        var downloadFileInput = document.createElement('input');
        downloadFileInput.type = 'hidden';
        downloadFileInput.name = 'downloadFile';
        downloadFileInput.value = downloadFile;
        form.appendChild(downloadFileInput);

        downloadButton.disabled = true;
        form.target = '_blank';

        form.submit();
        location.reload();
    }
</script>
@endsection
@endsection
