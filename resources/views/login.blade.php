@extends('app')

@section('content')

@if (Session::has('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: '{{ Session::get("success") }}',
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
    <a href="{{ route('home') }}">
        <div class="flex justify-center">
            <img src="{{ asset('dist/img/logo-kemenkes.png') }}" alt="kemenkes" width="500">
        </div>
    </a>

    <div class="mt-16 flex items-center justify-center">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8" style="width: 100%;">

            <a class="p-6 bg-dark dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20">
                <div>
                    <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white text-center">
                        <p>SELAMAT DATANG</p>
                    </h2>

                    <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    <form id="form" action="{{ route('post.login') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="text-white text-left">Username</label>
                            <input type="text" class="form-control rounded" name="username" required>
                        </div>
                        <div class="form-group mt-3">
                            <label class="text-white">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan Password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text border-secondary h-100 rounded-0 bg-white border border-white">
                                        <i class="fas fa-eye-slash" id="eye-icon-pass"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-5 text-right">
                            <button type="submit" class="btn btn-default text-white border-white" onclick="confirmSubmit(event, 'Masuk', 'Pastikan data sudah terisi dengan benar')">
                                <i class="fa-solid fa-right-to-bracket"></i> Masuk
                            </button>
                        </div>
                    </form>
                    </p>
                </div>
            </a>

        </div>
    </div>

    <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
        <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-left">
            <div class="flex items-center gap-4">
                <a href="" class="group inline-flex items-center hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                    Biro Umum
                </a>
            </div>
        </div>

        <div class="ml-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
            Versi 1.0
        </div>
    </div>
</div>
@section('js')
<script>
    // Password
    $(document).ready(function() {
        $("#eye-icon-pass").click(function() {
            var password = $("#password");
            var icon = $("#eye-icon");
            if (password.attr("type") == "password") {
                password.attr("type", "text");
                icon.removeClass("fas fa-eye-slash").addClass("fas fa-eye");
            } else {
                password.attr("type", "password");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            }
        });

        $("#eye-icon-conf").click(function() {
            var password = $("#conf-password");
            var icon = $("#eye-icon");
            if (password.attr("type") == "password") {
                password.attr("type", "text");
                icon.removeClass("fa-eye-slash").addClass("fa-eye");
            } else {
                password.attr("type", "password");
                icon.removeClass("fa-eye").addClass("fa-eye-slash");
            }
        });
    });
</script>
@endsection
@endsection
