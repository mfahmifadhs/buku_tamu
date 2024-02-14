<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Buku Tamu</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ asset('dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/main.css') }}" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="antialiased" style="background-color: blue;">
    <div class="relative sm:justify-center sm:items-center min-h-screen selection:bg-red-500 selection:text-white" style="background-color: #111827;">
        @if (Session::has('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ Session::get("success") }}',
            });
        </script>
        @elseif (Session::has('pending'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: '{{ Session::get("pending") }}',
            });
        </script>
        @elseif (Session::has('failed'))
        <script>
            Swal.fire({
                icon: 'error',
                title: '{{ Session::get("failed") }}',
            });
        </script>
        @endif

        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center">
                <img src="{{ asset('dist/img/logo-kemenkes.png') }}" alt="kemenkes" width="500">
            </div>

            <p class="text-white text-center mt-8 mb-4 text-capitalize">
                {{ $gedung->nama_gedung }}, {{ $id }}
            </p>
            <div class="flex items-center justify-center">
                <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8 col-md-6 col-12">

                    <div class="scale-100 p-6 dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none" style="background-color: #2bbecf;">
                        <div class="text-center">
                            <form action="{{ route('survei') }}" method="GET">
                                <input type="hidden" name="lobi" class="form-control text-center form-control-lg" value="{{ $id }}">

                                <h2 class="my-2 text-xl font-semibold text-gray-900 dark:text-white">
                                    Nomor Visitor
                                </h2>
                                <input type="number" name="no_visitor" class="form-control text-center form-control-lg">
                                <button class="btn btn-default hover:bg-secondary border-white text-white mt-3">
                                    <i class="fas fa-paper-plane"></i> Kirim
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>

<script src="{{ asset('dist/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('dist/admin/plugins/select2/js/select2.full.min.js') }}"></script>

@yield('js')
<script>
    function confirmSubmit(event, title, text) {
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
                title: title,
                text: text,
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
                text: 'Username / Password Harus Diisi',
                icon: 'error',
            });
        }
    }
</script>

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

</html>
