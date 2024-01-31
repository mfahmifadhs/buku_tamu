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
                        <small>Edit Tamu
                            <small class="text-sm">({{ $tamu->id_tamu }})</small>
                        </small>
                    </h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tamu.show') }}">Daftar Tamu</a></li>
                        <li class="breadcrumb-item active">Edit Tamu</li>
                    </ol>
                </div>
                <div class="col-sm-6 text-right mt-4">
                    <a href="{{ route('tamu.show') }}" class="btn btn-default border-dark">
                       <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container">
            <form id="form" action="{{ route('tamu.update', $id) }}" method="POST">
                @csrf
                <div class="card w-100">
                    <div class="card-header">
                        <label>Edit Informasi Tamu</label>
                        <div class="row text-sm">
                            <div class="form-group col-md-5">
                                <label>Nama Tamu</label>
                                <input type="text" class="form-control" name="nama_tamu" value="{{ $tamu->nama_tamu }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>NIK/NIP</label>
                                <input type="text" class="form-control" name="nik_nip" value="{{ $tamu->nik_nip }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>No. Telepon</label>
                                <input type="text" class="form-control" name="no_telepon" value="{{ $tamu->no_telpon }}" required>
                            </div>
                            <div class="form-group col-md-5">
                                <label>Alamat</label>
                                <input type="text" class="form-control" name="alamat" value="{{ $tamu->alamat_tamu }}" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Asal Instansi</label>
                                <input type="text" class="form-control" name="instansi" value="{{ $tamu->nama_instansi }}" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Nomor Visitor</label>
                                <input type="text" class="form-control" name="nomor_visitor" value="{{ $tamu->nomor_visitor }}" required>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-sm">
                            <div class="form-group col-md-6">
                                <label>Jam Masuk</label>
                                <input type="datetime-local" class="form-control" name="jam_masuk" value="{{ Carbon\Carbon::parse($tamu->jam_masuk)->format('Y-m-d H:i:s') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Jam Keluar</label>
                                <input type="datetime-local" class="form-control" name="jam_keluar" value="{{ Carbon\Carbon::parse($tamu->jam_keluar)->format('Y-m-d H:i:s') }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Pegawai/Pejabat yang dituju</label>
                                <input type="text" class="form-control" name="nama_tujuan" value="{{ $tamu->nama_tujuan }}" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Gedung</label>
                                <select name="gedung_id" id="gedung_id" class="form-control" required>
                                    <option value="">-- Pilih Gedung --</option>
                                    @foreach($gedung as $row)
                                    <option value="{{ $row->id_gedung }}" <?php echo $row->id_gedung == $tamu->area->gedung_id ? 'selected' : ''; ?>>
                                        {{ $row->nama_gedung }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Lantai</label>
                                <select name="area_id" id="area_id" class="form-control" required>
                                    <option value="">-- Pilih Area --</option>
                                    @foreach($tamu->area->gedung->area as $row)
                                    <option value="{{ $row->id_area }}" <?php echo $row->id_area == $tamu->area_id ? 'selected' : ''; ?>>
                                        {{ $row->nama_lantai }} - {{ $row->nama_sub_bagian }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Keperluan</label>
                                <textarea type="text" class="form-control" name="keperluan" required>{{ $tamu->keperluan }}</textarea>
                            </div>
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
    $('[name="area"]').select2()

    // Get the elements
    var gedungSelect = document.getElementById('gedung_id');
    var areaSelect = document.getElementById('area_id');

    gedungSelect.addEventListener('change', function() {
        var selectedGedungId = this.value;
        areaSelect.innerHTML = '<option value="">-- Pilih Area --</option>';

        fetchAreas(selectedGedungId);
    });

    function fetchAreas(gedungId) {
        fetch('/area/select/' + gedungId)
            .then(response => response.json())
            .then(data => {
                data.forEach(area => {
                    var option = document.createElement('option');
                    option.value = area.id;
                    option.textContent = area.text;
                    areaSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching areas:', error);
            });
    }

    function confirmSubmit(event) {
        event.preventDefault();

        const form = document.getElementById('form');
        const inputs = form.querySelectorAll('select[required], input[required], textarea[required]');
        let isFormValid = true;

        // inputs.forEach(input => {
        //     if (input.hasAttribute('required') && input.value.trim() === '') {
        //         input.style.borderColor = 'red';
        //         isFormValid = false;
        //     } else {
        //         input.style.borderColor = '';
        //     }
        // });

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
</script>
@endsection
@endsection
