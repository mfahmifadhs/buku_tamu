@extends('app')

@section('content')
<div class="max-w-7xl mx-auto p-6 lg:p-8">
    <div class="flex justify-center">
        <img src="{{ asset('dist/img/logo-kemenkes.png') }}" alt="kemenkes" width="750">
    </div>

    <p class="text-white text-center mt-8 mb-4 text-capitalize">
        {{ $gedung->nama_gedung }}, {{ $id }}
    </p>
    <div class="flex items-center justify-center">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8" style="width: 75%;">

            <div class="scale-100 p-6 dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none" style="background-color: #2bbecf;">
                <div class="text-center">
                    <form action="{{ route('survei') }}" method="GET">
                        <input type="hidden" name="lobi" class="form-control text-center form-control-lg" value="{{ $id }}">

                        <h2 class="my-2 text-xl font-semibold text-gray-900 dark:text-white">
                            Nomor Visitor
                        </h2>
                        <input type="text" name="no_visitor" class="form-control text-center form-control-lg">
                        <button class="btn btn-default hover:bg-secondary border-white text-white mt-3">
                            <i class="fas fa-paper-plane"></i> Kirim
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@section('js')

<script>
    function confirmSubmit(event) {
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
