@extends('tamu.layout.app')
@section('content')

@if (Session::has('success'))
<script>
    alert('Berhasil Mengisi Form!')
    // Swal.fire({
    //     icon: 'success',
    //     title: '{{ Session::get("success") }}',
    // });
</script>
@endif


<!-- <section class="content">
    <div class="image text-center mx-auto">
        <img src="{{ asset('dist/img/logo-kemenkes.png') }}" alt="kemenkes" class="w-50 mt-3">
    </div>
    <div class="container-fluid d-flex justify-content-center align-items-center mb-3 mt-3">
        <div class="card w-100 col-12">
            <div class="card-header">
                <div class="row">
                    <div class="form-group" style="font-size: 16px;">
                        <label class="col-md-4 col-3">Kode</label>
                        <label class="col-md-7 col-8">: {{ $tamu->id_tamu }}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-3">Tanggal</label>
                        <label class="col-md-7 col-8">: {{ Carbon\Carbon::parse($tamu->tanggal_datang)->isoFormat('DD MMMM Y') }}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-3">Jam</label>
                        <label class="col-md-7 col-8">: {{ Carbon\Carbon::parse($tamu->jam_masuk)->isoFormat('HH:mm:ss') }}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-3">Nama</label>
                        <label class="col-md-7 col-8">: {{ $tamu->nama_tamu }}</label>
                    </div>
                </div>
            </div>
            <form id="form" action="{{ route('tamu.no_visitor', ['gedung' => $gedung, 'lobi' => $lobi, 'id' => $id ]) }}" method="POST">
                @csrf
                <div class="card-header">
                    <input type="text" class="form-control number text-center number" name="no_visitor" placeholder="Nomor Visitor" required>
                </div>
                <div class="card-header text-center">
                    <button type="submit" class="btn btn-info text-dark" onclick="return confirm('Apakah nomor visitor sudah sesuai ?')">
                        KIRIM
                    </button>
                </div>
            </form>
        </div>
    </div>
</section> -->

<section class="content border border-white" style="margin-top: 30vh;">
    <div class="mb-3">
        <img src="{{ asset('dist/img/logo-kemenkes.png') }}" alt="kemenkes" class="w-25 mt-3 mx-auto">
    </div>
    <div class="container-fluid d-flex justify-content-center align-items-center mb-3">
        <div class="row text-white">
            <div class="form-group" style="font-size: 16px;">
                <label class="col-md-2 col-2">Kode</label>
                <label class="col-md-8 col-8">: {{ $tamu->id_tamu }}</label>
            </div>
            <div class="form-group">
                <label class="col-md-2 col-2">Tanggal</label>
                <label class="col-md-8 col-8">: {{ Carbon\Carbon::parse($tamu->tanggal_datang)->isoFormat('DD MMMM Y') }}</label>
            </div>
            <div class="form-group">
                <label class="col-md-2 col-2">Jam</label>
                <label class="col-md-8 col-8">: {{ Carbon\Carbon::parse($tamu->jam_masuk)->isoFormat('HH:mm:ss') }}</label>
            </div>
            <div class="form-group">
                <label class="col-md-2 col-2">Nama</label>
                <label class="col-md-8 col-8">: {{ $tamu->nama_tamu }}</label>
            </div>
        </div>
    </div>
    <div class="col-md-6 mx-auto">
        <form id="form" action="{{ route('tamu.no_visitor', ['gedung' => $gedung, 'lobi' => $lobi, 'id' => $id ]) }}" method="POST">
            @csrf
            <div class="card-header">
                <input type="number" class="form-control number text-center number form-control-lg" name="no_visitor" placeholder="Nomor Visitor" required>
            </div>
            <div class="card-header text-center">
                <button type="submit" class="btn btn-default border-white text-white" onclick="return confirm('Apakah nomor visitor sudah sesuai ?')">
                    <i class="fas fa-paper-plane"></i> <b>KIRIM</b>
                </button>
            </div>
        </form>
    </div>
</section>

@section('js')
<script>
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
                text: 'Pastikan nomor visitor sesuai',
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
