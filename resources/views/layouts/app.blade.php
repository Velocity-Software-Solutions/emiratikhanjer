<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Al Khinjar Al Dhahbi') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montaga&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-gray-900 bg-gray-50">
    <header class="sticky top-0 z-50 bg-white shadow">
        <div class="flex items-center justify-between px-4 py-4 mx-auto max-w-7xl">
            <a href="/" class="text-2xl font-bold text-bg-700 bg-\[\#4b3621\]">
                <img src="{{ asset('images/logo.png') }}" alt="{{ __('messages.logo_alt') }}"
                     class="rounded-full w-[70px] h-[70px] mb-4">
            </a>

            <nav class="space-x-6">
                <a href="/" class="nav-item text-gray-700 hover:text-gray-800 p-2">{{ __('messages.nav_home') }}</a>
                <a href="{{ route('home') }}" class="nav-item text-gray-700 hover:text-gray-800 p-2">{{ __('messages.nav_shop') }}</a>
                <a href="{{ route('cart.index') }}" class="nav-item text-gray-700 hover:text-gray-800 p-2" style="vertical-align: sub;">
                    <span class="text-2xl material-icons">shopping_cart</span>
                </a>

                @auth
                    <a href="{{ route('dashboard') }}" class="nav-item text-gray-700 hover:text-gray-800 p-2">{{ __('messages.nav_dashboard') }}</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="nav-item text-gray-700 hover:text-gray-800 p-2">{{ __('messages.nav_logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-item text-gray-700 hover:text-gray-800 p-2">{{ __('messages.nav_login') }}</a>
                    <a href="{{ route('register') }}" class="nav-item text-gray-700 hover:text-gray-800 p-2">{{ __('messages.nav_register') }}</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="py-6 mt-10 bg-white border-t">
        <div class="mx-auto text-center text-gray-500 max-w-7xl">
            &copy; {{ date('Y') }} {{ config('app.name', 'Al Khinjar Al Dhahbi') }}. {{ __('messages.footer_rights') }}
        </div>
    </footer>
</body>

</html>
