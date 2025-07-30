<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Antique Shop')</title>
    <link rel="icon" type="image/x-icon" href="/images/logo.ico">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    {{-- Shared JS or per-page head content --}}
    @stack('head')
</head>

<body class="bg-gray-100 dark:bg-gray-900 w-full h-[100vh]" x-data="{ sidebarOpen: true }">
    {{-- Shared Header --}}
    <div class="flex h-[100%]">
        @include('partials.admin-sidebar')
        <div class="flex flex-col w-full h-full">
            {{-- Toast Error Popup --}}
            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    class="fixed z-50 max-w-sm px-4 py-3 text-red-800 bg-red-100 border border-red-300 rounded shadow top-4 right-4"
                    @click="show = false">
                    <strong class="block mb-1 font-semibold">Something went wrong:</strong>
                    <ul class="text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @include('partials.admin-header')

            @yield('content')
        </div>
    </div>
    {{-- Shared Footer --}}
    @include('partials.footer')
</body>

</html>
