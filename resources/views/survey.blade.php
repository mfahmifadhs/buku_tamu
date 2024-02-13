@extends('app')

@section('content')
<div class="max-w-7xl mx-auto p-6 lg:p-8">
    <div class="flex justify-center">
        <img src="{{ asset('dist/img/logo-kemenkes.png') }}" alt="kemenkes" width="250">
    </div>
    <p class="text-white text-center mt-8 mb-4 text-capitalize fa-2x">
        Terima kasih {{ $tamu->nama_tamu }} sudah berkunjung
    </p>
    <div class="flex items-center justify-center">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8" style="width: 120vh;">

            <div class="scale-100 p-6 dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none" style="background-color: #2bbecf;">
                <div class="text-center">
                    <div class="row mt-5">
                        <div class="col-md-5">
                            <center>
                                <a href="#" data-url="{{ route('checkout.store', ['survei' => 'puas', 'id' => $tamu->id_tamu]) }}" onclick="confirm(event)">
                                    <img src="{{ asset('dist/img/puas.png') }}" width="240">
                                    <h1 class="my-5 fa-2x"><b>PUAS</b></h1>
                                </a>
                            </center>
                        </div>
                        <div class="col-md-7">
                            <center>
                                <a href="#" data-url="{{ route('checkout.store', ['survei' => 'tidak', 'id' => $tamu->id_tamu]) }}" onclick="confirm(event)">
                                    <img src="{{ asset('dist/img/tidak-puas.png') }}" width="350">
                                    <h1 class="my-5 fa-2x" style="margin-left: 15vh;"><b>TIDAK PUAS</b></h1>
                                </a>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('js')

<script>
    function confirm(event) {
        event.preventDefault();
        const url = event.currentTarget.dataset.url;
        console.log(url)
        Swal.fire({
            title: 'Terima Kasih',
            icon: 'Sampai jumpa lain waktu 👋🏻',
            imageUrl: "https://upload.wikimedia.org/wikipedia/commons/thumb/b/b2/383-waving-hand-1.svg/1200px-383-waving-hand-1.svg.png",
            imageWidth: 300,
            imageHeight: 300,
        });

        // Menunda eksekusi aksi selanjutnya selama 3 detik
        setTimeout(() => {
            window.location.href = url;
        }, 500);
    }
</script>

@endsection
@endsection
