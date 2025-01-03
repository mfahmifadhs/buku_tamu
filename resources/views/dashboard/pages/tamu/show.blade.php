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
                @if (Auth::user()->role_id != 2)
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
                    <a href="#" class="btn btn-csv bg-primary border-primary" data-toggle="modal" data-target="#filterModal">
                        <span class="btn btn-primary btn-sm"><i class="fas fa-filter"></i></span>
                        <small>Filter</small>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="card border border-dark">
                <div class="card-header">
                    <label class="card-title mt-1">Daftar Tamu</label>
                </div>
                <div class="card-header">
                    <div class="table-responsive">
                        <table id="table-data" class="table table-bordered text-xs text-center">
                            <thead class="text-uppercase">
                                <tr>
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th style="width: 12%;">Lokasi</th>
                                    <th style="width: 10%;">Masuk</th>
                                    <th style="width: 10%;">Keluar</th>
                                    <th style="width: 8%;">No. Visit</th>
                                    <th>Nama</th>
                                    <th>Asal</th>
                                    <th>Tujuan</th>
                                    <th>Keperluan</th>
                                    <th>Foto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- @if ($tamu->count() == 0)
                                <tr class="text-center">
                                    <td colspan="11">Tidak ada data</td>
                                </tr>
                                @endif -->
                                <tr>
                                    <td colspan="11">Sedang mengambil data ...</td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- <table id="table" class="table table-bordered text-center">
                            <thead class="text-sm">
                                <tr>
                                    <th style="width: 0%;">No</th>
                                    <th style="width: 15%;">Waktu</th>
                                    <th style="width: 23%;">Tamu</th>
                                    <th style="width: 15%;">Pegawai/Pejabat Tujuan</th>
                                    <th style="width: 12%;">Keperluan</th>
                                    <th style="width: 18%;">Lokasi Tujuan</th>
                                    <th style="width: 10%;">No. Visitor</th>
                                    <th style="width: 10%;">Foto</th>
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
                                        @php $lokasi = $row->lokasi_datang == 'lobi-a' ? 'Lobi A, Adhyatma' : ($row->lokasi_datang == 'lobi-c' ? 'Lobi C, Adhyatma' : ($row->lokasi_datang == '2c' ? '2C, Adhyatma' : 'Lobi Sujudi')); @endphp
                                        <i class="fas fa-building"></i> &ensp;: {{ $lokasi }} <br>
                                        <i class="fas fa-person-walking-arrow-right"></i> : {{ $row->jam_masuk }} <br>
                                        <i class="fas fa-person-walking-arrow-loop-left"></i> : {{ $row->jam_keluar }} <br>
                                        <i class="fas fa-square-poll-vertical"></i> :

                                        @if ($row->survei && $row->survei == 'puas')
                                        <span class="badge badge-success">Puas</span>
                                        @elseif ($row->survei && $row->survei == 'tidak')
                                        <span class="badge badge-danger">Tidak Puas</span>
                                        @endif
                                    </td>
                                    <td class="text-left">
                                        @if (Auth::user()->role_id == 2)
                                        <div class="row">
                                            <div class="col-md-1"><i class="fas fa-user"></i></div>
                                            <div class="col-md-11">: {{ $row->nama_tamu }}</div>
                                            <div class="col-md-1"><i class="fas fa-id-card"></i></div>
                                            <div class="col-md-11">: {{ substr($row->nik_nip, 0, 5) . '*********' . substr($row->nik_nip, -2) }}</div>
                                            <div class="col-md-1"><i class="fas fa-phone"></i></div>
                                            <div class="col-md-11">: {{ substr($row->no_telpon, 0, 5) . '*****' . substr($row->no_telpon, -2) }}</div>
                                            <div class="col-md-1"><i class="fas fa-address-card"></i></div>
                                            <div class="col-md-11">: {{ substr($row->alamat_tamu, 0, 3) . '***' }}</div>
                                            <div class="col-md-1"><i class="fas fa-building-user"></i></div>
                                            <div class="col-md-11">:
                                                {{ $row->instansi?->instansi }} <br>
                                                &ensp;{{ $row->nama_instansi }}
                                            </div>
                                        </div>
                                        @else
                                        <div class="row">
                                            <div class="col-md-1"><i class="fas fa-user"></i></div>
                                            <div class="col-md-11">: {{ $row->nama_tamu }}</div>
                                            <div class="col-md-1"><i class="fas fa-id-card"></i></div>
                                            <div class="col-md-11">: {{ $row->nik_nip }}</div>
                                            <div class="col-md-1"><i class="fas fa-phone"></i></div>
                                            <div class="col-md-11">: {{ $row->no_telpon }}</div>
                                            <div class="col-md-1"><i class="fas fa-address-card"></i></div>
                                            <div class="col-md-11">: {{ $row->alamat_tamu }}</div>
                                            <div class="col-md-1"><i class="fas fa-building-user"></i></div>
                                            <div class="col-md-11">:
                                                {{ $row->instansi?->instansi }} <br>
                                                &ensp;{{ $row->nama_instansi }}
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                    <td>{{ $row->nama_tujuan }}</td>
                                    <td class="text-left">{{ $row->keperluan }}</td>
                                    <td class="text-left">
                                        {{ $row->area->gedung->nama_gedung }} <br>
                                        {{ $row->area->nama_lantai }} - {{ $row->area->nama_ruang }} <br>
                                        {{ $row->area->nama_sub_bagian }}
                                    </td>
                                    <td>No. {{ $row->nomor_visitor }}</td>
                                    <td>
                                        <a data-toggle="modal" data-target="#foto{{ $row->id_tamu }}">
                                            <img src="{{ asset('storage/foto_tamu/' . $row->foto_tamu) }}"
                                                class="img-fluid mt-3" alt="">
                                        </a>

                                        <div class="modal fade" id="foto{{ $row->id_tamu }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                <div class="modal-body">
                                                    <img src="{{ asset('storage/foto_tamu/' . $row->foto_tamu) }}"
                                                    class="img-fluid mt-3" alt="">
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table> -->
                    </div>
                </div>
            </div>
        </div>
    </div><br>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Tamu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <img id="modal-foto" src="" alt="Foto Tamu" class="img-fluid">
                    </div>
                    <div class="col-md-8">
                        <div class="row small">
                            <label class="col-md-12 text-secondary">Informasi Tamu</label>
                            <div class="col-md-3">ID</div>
                            <div class="col-md-9">:
                                <span id="modal-id"></span>
                            </div>

                            <div class="col-md-3">No. Visitor</div>
                            <div class="col-md-9">:
                                <span id="modal-novisit"></span>
                            </div>

                            <div class="col-md-3">Nama</div>
                            <div class="col-md-9">:
                                <span id="modal-nama"></span>
                            </div>

                            <div class="col-md-3">NIP/NIK</div>
                            <div class="col-md-9">:
                                <span id="modal-nipnik"></span>
                            </div>

                            <div class="col-md-3">Asal</div>
                            <div class="col-md-9">:
                                <span id="modal-asal"></span>
                            </div>

                            <div class="col-md-3">No. HP</div>
                            <div class="col-md-9">:
                                <span id="modal-nohp"></span>
                            </div>

                            <div class="col-md-3">Alamat</div>
                            <div class="col-md-9">:
                                <span id="modal-alamat"></span>
                            </div>

                            <label class="col-md-12 text-secondary mt-2">Tujuan</label>

                            <div class="col-md-3">Tujuan</div>
                            <div class="col-md-9">:
                                <span id="modal-tujuan"></span>
                            </div>

                            <div class="col-md-3">Keperluan</div>
                            <div class="col-md-9">:
                                <span id="modal-keperluan"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filter -->
<div class="modal fade" id="filterModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pencarian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('tamu.show') }}" method="GET">
                @csrf
                <div class="modal-body text-xs">
                    <div class="form-group">
                        <b>Pilih Tanggal</b>
                        <select id="tanggal" name="tanggal" class="form-control form-control-sm border-dark rounded text-center">
                            <option value="">Semua Tanggal</option>
                            @foreach(range(1, 31) as $dateNumber)
                            @php $rowTgl = Carbon\Carbon::create()->day($dateNumber)->isoFormat('DD'); @endphp
                            <option value="{{ $rowTgl }}" <?php echo $tanggal == $rowTgl ? 'selected' : '' ?>>
                                {{ $rowTgl }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <b>Pilih Bulan</b>
                        <select id="bulan" name="bulan" class="form-control form-control-sm border-dark rounded text-center">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $monthNumber)
                            @php $rowBulan = Carbon\Carbon::create()->month($monthNumber); @endphp
                            <option value="{{ $rowBulan->isoFormat('MM') }}" <?php echo $bulan == $rowBulan->isoFormat('M') ? 'selected' : '' ?>>
                                {{ $rowBulan->isoFormat('MMMM') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <b>Pilih Tahun</b>
                        <select id="tahun" class="form-control form-control-sm text-center" name="tahun">
                            <option value="2025" <?php echo $tahun == '2025' ? 'selected' : ''; ?>>2025</option>
                            <option value="2024" <?php echo $tahun == '2024' ? 'selected' : ''; ?>>2024</option>
                            <option value="2023" <?php echo $tahun == '2023' ? 'selected' : ''; ?>>2023</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <b>Pilih Gedung</b>
                        <select name="gedung" class="form-control form-control-sm">
                            <option value="">Seluruh Gedung</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <b>Pilih Sub Bagian</b>
                        <select id="area" name="area" class="form-control form-control-sm" style="width: 100%;">
                            <option value="">Seluruh Sub Bagian</option>
                            @foreach ($dataArea as $row)
                            <option value="{{ $row->id_area }}" <?php echo $row->id_area == $area ? 'selected' : '' ?>>
                                {{ $gedung == 2 ? $row->nama_lantai.' - '.$row->nama_sub_bagian : $row->nama_lantai.' ('.$row->nama_ruang.') - '. $row->nama_sub_bagian }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <b>Pilih Asal Instansi</b>
                        <select id="instansi" class="form-control form-control-sm text-center" name="status">
                            <option value="">Semua</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
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
                // console.error("Error fetching data:", error);
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

<script>
    $(document).ready(function() {

        let tanggal = $('#tanggal').val();
        let bulan = $('#bulan').val();
        let tahun = $('#tahun').val();
        let area = $('#area').val();
        let instansi = $('#instansi').val();

        loadTable(tanggal, bulan, tahun, area, instansi);

        function loadTable() {
            $.ajax({
                url: `{{ route('tamu.select') }}`,
                method: 'GET',
                data: {
                    tanggal: tanggal,
                    bulan: bulan,
                    tahun: tahun,
                    area: area,
                    instansi: instansi
                },
                dataType: 'json',
                success: function(response) {
                    let tbody = $('.table tbody');
                    tbody.empty(); // Mengosongkan tbody sebelum menambahkan data

                    if (response.message) {
                        // Jika ada pesan dalam respons (misalnya "No data available")
                        tbody.append(`
                        <tr>
                            <td colspan="9">${response.message}</td>
                        </tr>
                    `);
                    } else {
                        let edit = "{{ route('tamu.edit', ':id') }}";
                        // Jika ada data
                        $.each(response, function(index, item) {
                            let idTamu  = BigInt(item.tamu.replace(/^id/, ""));
                            let delLink = '';
                            let editUrl = edit.replace(':id', idTamu);

                            if (item.role == 1) {
                                let del = "{{ route('tamu.delete', ':id') }}";
                                let deleteUrl = del.replace(':id', idTamu);
                                delLink = `
                                    <a href="" id="edit-link-template" onclick="confirmRemove(event, ${editUrl})">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                `;
                            }

                            tbody.append(`
                                <tr>
                                    <td>${item.no}</td>
                                    <td>
                                        <a href="javascript:void(0);" onclick="showModal(${idTamu})">
                                            <i class="fas fa-info-circle"></i>
                                        </a>
                                        <a href="${editUrl}" id="edit-link-template">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        ${delLink}
                                    </td>
                                    <td class="text-left">${item.tamu.replace(/^id/, "")}</td>
                                    <td>${item.masuk}</td>
                                    <td>${item.keluar}</td>
                                    <td>${item.novisit}</td>
                                    <td class="text-left">${item.nama}</td>
                                    <td class="text-left">${item.asal}</td>
                                    <td class="text-left">${item.tujuan}</td>
                                    <td>${item.keperluan}</td>
                                    <td style="width: 5%;">${item.foto}</td>
                                </tr>
                            `);
                        });

                        $("#table-data").DataTable({
                            "responsive": false,
                            "lengthChange": true,
                            "autoWidth": false,
                            "info": true,
                            "paging": true,
                            "searching": true,
                            buttons: [{
                                extend: 'pdf',
                                text: ' PDF',
                                pageSize: 'A4',
                                className: 'bg-danger',
                                title: 'show',
                                exportOptions: {
                                    columns: [2, 3, 4, 5, 6, 7]
                                },
                            }, {
                                extend: 'excel',
                                text: ' Excel',
                                className: 'bg-success',
                                title: 'show',
                                exportOptions: {
                                    columns: ':not(:nth-child(2))'
                                },
                            }],
                            "bDestroy": true
                        }).buttons().container().appendTo('#table-data_wrapper .col-md-6:eq(0)');
                    }
                },
                error: function(xhr, status, error) {
                    // console.error('Error fetching data:', error);
                }
            });

            // Fungsi untuk menampilkan modal dengan data tamu
            window.showModal = function(idTamu) {
                $.ajax({
                    url: `{{ url('/tamu/detail/') }}/${idTamu}`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Mengisi modal dengan data tamu
                        $('#modal-id').text(data.id_tamu);
                        $('#modal-novisit').text(data.nomor_visitor);
                        $('#modal-nipnik').text(data.nik_nip);
                        $('#modal-nama').text(data.nama_tamu);
                        $('#modal-asal').text(data.area.nama_lantai + ', ' + data.area.nama_sub_bagian);
                        $('#modal-notelp').text(data.no_telpon);
                        $('#modal-alamat').text(data.alamat_tamu);

                        $('#modal-tujuan').text(data.area.nama_lantai + ', ' + data.area.nama_sub_bagian);
                        $('#modal-keperluan').text(data.keperluan);
                        $('#modal-foto').attr('src', `{{ asset('storage/foto_tamu/') }}/${data.foto_tamu}`);

                        // Menampilkan modal
                        $('#detailModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        // console.error('Error fetching detail:', error);
                    }
                });
            };
        }
    });
</script>
@endsection
@endsection
