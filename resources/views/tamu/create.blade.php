@extends('tamu.layout.app')
@section('content')

@if (Session::has('success'))
<script>
    Swal.fire({
        title: "Selamat Datang!",
        text: "Silahkan masuk dan konfirmasi ke resepsionis",
        imageUrl: "{{ asset('dist/img/logo-kemenkes.png') }}",
        imageWidth: 300,
        imageHeight: 80,
        imageAlt: "Custom image"
    });
</script>
@endif


<section class="content">
    <div class="image text-center">
        <img src="{{ asset('dist/img/logo-kemenkes.png') }}" alt="kemenkes" class="w-50 mt-3">
    </div>
    <div class="container-fluid d-flex justify-content-center align-items-center mb-3">
        <form id="form" action="{{ route('tamu.store', $id) }}" method="POST">
            @csrf
            <input type="hidden" name="lokasi_datang" value="{{ $lobi }}">
            <div class="card mt-4">
                <div class="card-header">
                    <label class="font-weight-bold h5 pt-2">Form Tamu</label>
                </div>
                <div class="card-body">
                    <p class="text-sm" style="text-align: justify;">
                        Mohon untuk memastika area atau lokasi Pegawai/Pejabat yang akan dikunjungi dengan menanyakan kepada Resepsionis
                    </p>
                    <hr class="mt-2">
                    <div class="row" style="font-size: 14px;font-weight: bold;">
                        <div class="form-group">
                            <label class="col-md-3 col-form-label">Nama lengkap</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama" required>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="col-md-3 col-form-label">NIK/NIP</div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nik_nip" placeholder="NIK / NIP" required>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="col-md-3 col-form-label">Alamat</div>
                            <div class="col-md-9">
                                <textarea class="form-control" name="alamat" placeholder="Alamat lengkap sesuai KTP" required></textarea>
                            </div>
                        </div>

                        <div class="form-group mt-2">
                            <div class="col-md-3 col-form-label">No. Telepon</div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="no_telp" placeholder="No. Telp Aktif" required>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="col-md-3 col-form-label">Nama Instansi</div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="instansi" placeholder="Nama Instansi" required>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="col-md-3 col-form-label">Nama Pegawai/Pejabat yang Ingin Ditemui</div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="nama_tujuan" placeholder="Nama Instansi" required>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="col-md-3 col-form-label">Keperluan</div>
                            <div class="col-md-9">
                                <textarea class="form-control" name="keperluan" placeholder="Keperluan kunjungan" required></textarea>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="col-md-3 col-form-label">Area Tujuan</div>
                            <div class="col-md-9">
                                <select name="area_id" class="form-control" required>
                                    <option value="">-- PILIH AREA TUJUAN --</option>
                                    @foreach ($area as $row)
                                    @if ($gedung == 1)
                                    <option value="{{ $row->id_area }}">
                                        {{ $row->nama_lantai.'  ('.$row->nama_ruang.') - '. $row->nama_sub_bagian }}
                                    </option>
                                    @else
                                    <option value="{{ $row->id_area }}">
                                        {{ $row->nama_lantai.' - '.$row->nama_sub_bagian }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary text-dark" onclick="confirmSubmit(event)">KIRIM</button>
                </div>
            </div>

        </form>
    </div>
</section>

@section('js')
<script>
    $(function() {
        $('select[name="area_id"]').select2()
    })

    function confirmSubmit(event) {
        event.preventDefault(); // Prevent the default link behavior

        // Mengecek setiap input pada form
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
                title: 'Selesai ?',
                text: 'Pastikan data sudah terisi dengan benar',
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
                title: 'Isian belum lengkap',
                text: 'Silakan lengkapi semua isian yang diperlukan',
                icon: 'error',
            });
        }
    }
</script>
@endsection


@endsection
