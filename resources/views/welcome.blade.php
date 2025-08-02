<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans bg-[#f2f4f8]">
    <div class="mt-7 rounded-xl mx-32 2xl:mx-96 shadow-2xl text-black/50 dark:bg-black dark:text-white/50 bg-cover bg-no-repeat bg-center"
        style="background-image: url('{{ asset('landing-page-assets/bg.png') }}')">
                <main>
                    <div class="flex justify-between flex-col lg:flex-row h-[85vh]">
                        <div class="flex mt-24 md:mt-2 lg:mt-0 items-center justify-center lg:max-w-7xl">
                            <img class="w-auto" src="{{ asset('landing-page-assets/2.png') }}" alt="illustration">
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 min-w-[45%] min-h-[40%]  lg:rounded-tr-xl  2xl:min-w-[35%] shadow-l-sm">
                            @if (Route::has('login'))
                                <livewire:welcome.navigation />
                                <h1 class="text-2xl mt-5 text-gray-600"><strong>Wilujeng Sumping !</strong></h1>
                                <h1>Di sistem informasi konveksi Ramdani.</h1>
                            @endif
                        </div>
                    </div>
                    <footer class="py-5 text-center text-[0.5rem] 2xl:text-sm h-[7vh] rounded-b-xl bg-white text-gray-400 dark:text-white/70">
                        © {{ date('Y') }} Dashboard Konveksi - Ramdani Konveksi
                    </footer>
                </main>
    </div>
</body>

</html>