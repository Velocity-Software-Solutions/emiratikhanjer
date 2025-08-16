<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'NexSecure')</title>
    <link rel="icon" type="image/x-icon" href="/images/logo2.png">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Shared JS or per-page head content --}}
    @stack('head')
</head>

<body class="bg-[#ebebeb] dark:bg-gray-900 w-full h-[100vh]">
    {{-- Shared Header --}}
    <div class="flex h-[100%] w-full">
    @include('partials.admin-sidebar')
    <div class="h-full flex flex-col w-[60%] lg:w-[80%]">
        @if (session('success'))
    <div class="flex justify-center items-center px-4 py-3 rounded bg-green-100 text-green-800 border border-green-200">
        {{ session('success') }}
    </div>
@endif
@if (request('success'))
    <div class="flex justify-center items-center px-4 py-3 rounded bg-green-100 text-green-800 border border-green-200">
        {{ request('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="flex justify-center items-center px-4 py-3 rounded bg-red-100 border border-red-300 ">
        <p class="font-semibold mb-2 text-red-800 dark:text-red-800">An Error Occured</p>
    </div>
@endif
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-50 border border-red-300 text-red-700 rounded">
        <p class="font-semibold mb-2">Please fix the following errors:</p>
        <ul class="list-disc list-inside text-sm">
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
