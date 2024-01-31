@extends('tamu.layout.app')
@section('content')

@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ Session::get("success") }}',
    });
</script>
@endif


<section class="content" style="margin-top: 50%">
    <div class="image text-center">
        <img src="{{ asset('dist/img/logo-kemenkes.png') }}" alt="kemenkes" class="w-50 mt-3">
    </div>
    <div class="container-fluid d-flex justify-content-center align-items-center mb-3 mt-3">
        <div class="card w-100 col-12">
            <div class="card-header">
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-4 col-2">Kode</label>
                        <label class="col-md-8 col-8">: {{ $tamu->id_tamu }}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-2">Tanggal</label>
                        <label class="col-md-8 col-8">: {{ Carbon\Carbon::parse($tamu->tanggal_datang)->isoFormat('DD MMMM Y') }}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-2">Jam</label>
                        <label class="col-md-8 col-8">: {{ Carbon\Carbon::parse($tamu->jam_masuk)->isoFormat('HH:mm:ss') }}</label>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 col-2">Nama</label>
                        <label class="col-md-8 col-8">: {{ $tamu->nama_tamu }}</label>
                    </div>
                </div>
            </div>
            <form id="form" action="{{ route('tamu.no_visitor', ['gedung' => $gedung, 'lobi' => $lobi, 'id' => $id ]) }}" method="POST">
                @csrf
                <div class="card-header">
                    <input type="text" class="form-control number text-center" name="no_visitor" placeholder="Nomor Visitor" required>
                </div>
                <div class="card-header text-center">
                    <button type="submit" class="btn btn-success text-dark" onclick="confirmSubmit(event)">
                        KIRIM
                    </button>
                </div>
            </form>
        </div>
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
