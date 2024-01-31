@extends('app')

@section('content')
<div class="max-w-7xl mx-auto p-6 lg:p-8">
    <div class="flex justify-center">
        <img src="{{ asset('dist/img/logo-kemenkes.png') }}" alt="kemenkes" width="1000">
    </div>

    <div class="mt-16 flex items-center justify-center">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 lg:gap-8" style="width: 50%;">

            <a href="{{ route('login') }}" class="scale-100 p-6 dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-blue-500" style="background-color: #2bbecf;">
                <div class="text-center">
                    <h2 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                        <i class="fa-solid fa-right-to-bracket fa-2x"></i>
                        <p>Masuk</p>
                    </h2>
                </div>
            </a>
        </div>
    </div>

    <!-- <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
        <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-left">
            <div class="flex items-center gap-4">
                <a href="https://github.com/sponsors/taylorotwell" class="group inline-flex items-center hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                    Biro Umum
                </a>
            </div>
        </div>

        <div class="ml-4 text-center text-sm text-gray-500 dark:text-gray-400 sm:text-right sm:ml-0">
            Versi 1.0
        </div>
    </div> -->
</div>
@endsection




<!-- <a href="" class="scale-100 p-6 bg-dark dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-blue-500">
    <div class="text-center">
        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">
            <i class="fa-solid fa-book fa-2x"></i>
            <p>Tamu</p>
        </h2>

        <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
            Laracasts offers thousands of video tutorials on Laravel, PHP, and JavaScript development. Check them out, see for yourself, and massively level up your development skills in the process.
        </p>
    </div>
</a> -->
