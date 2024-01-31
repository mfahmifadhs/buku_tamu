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
                        <small>Tambah Tamu</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Tambah Tamu</li>
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
            <form id="form" action="{{ route('tamu.admin.store') }}" method="POST">
                @csrf
                <input type="hidden" name="lobi" value="{{ $lobi }}">
                <div class="card w-100">
                    <div class="card-header">
                        <label>Tambah Tamu</label>
                        <div class="row text-sm">
                            <div class="form-group col-md-5">
                                <label>Nama Tamu</label>
                                <input type="text" class="form-control" name="nama_tamu" placeholder="Nama lengkap" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>NIK/NIP</label>
                                <input type="text" class="form-control" name="nik_nip" placeholder="NIK/NIP" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>No. Telepon</label>
                                <input type="text" class="form-control" name="no_telepon" placeholder="No Telepon" required>
                            </div>
                            <div class="form-group col-md-5">
                                <label>Alamat</label>
                                <input type="text" class="form-control" name="alamat" placeholder="Alamat lengkap" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Asal Instansi</label>
                                <input type="text" class="form-control" name="instansi" placeholder="Nama Instansi" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Nomor Visitor</label>
                                <input type="text" class="form-control" name="nomor_visitor" placeholder="No. Visitor" required>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-sm">
                            <div class="form-group col-md-6">
                                <label>Jam Masuk</label>
                                <input type="datetime-local" class="form-control" name="jam_masuk" value="{{ Carbon\Carbon::now() }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Jam Keluar</label>
                                <input type="datetime-local" class="form-control" name="jam_keluar">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Pegawai/Pejabat yang dituju</label>
                                <input type="text" class="form-control" name="nama_tujuan" placeholder="Nama pegawai/pejabat" required>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Gedung</label>
                                <select name="gedung_id" id="gedung_id" class="form-control" required>
                                    @foreach($gedung as $row)
                                    <option value="{{ $row->id_gedung }}">{{ $row->nama_gedung }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Lantai</label>
                                <select name="area_id" id="area_id" class="form-control" required>
                                    @foreach($area as $row)
                                    <option value="{{ $row->id_area }}">{{ $row->nama_lantai.' - '.$row->nama_sub_bagian }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Keperluan</label>
                                <textarea type="text" class="form-control" name="keperluan" required></textarea>
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
                title: 'Tambah Tamu ?',
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
