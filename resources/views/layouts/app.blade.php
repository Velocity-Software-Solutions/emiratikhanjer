<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Al Khinjar Al Dhahbi') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-gray-900 bg-gray-50">
    <header class="sticky top-0 z-50 bg-white shadow">
        <div class="flex items-center justify-between px-4 py-4 mx-auto max-w-7xl">
            <a href="/" class="text-2xl font-bold text-bg-700 bg-\[\#4b3621\]"><img src="{{ asset('storage/images/logo.png') }}" alt="Antiques Shop" class=" rounded-full w-[70px] h-[70px] mb-4"></a>

            <nav class="space-x-6">
                <a href="/" class=" nav-item text-gray-700 hover:text-blue-600">Home</a>
                <a href="{{ route('cart.index') }}" class=" nav-item text-gray-700 hover:text-blue-600" style="vertical-align: sub;">
                 <span
                            class="nav-item text-2xl text-gray-400 transition duration-75 material-icons dark:text-gray-600 group-hover:text-gray-900 dark:group-hover:text-white">
                            shopping_cart</span>
                       </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="nav-item text-gray-700 hover:text-blue-600">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="nav-item text-gray-700 hover:text-blue-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-item text-gray-700 hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="nav-item text-gray-700 hover:text-blue-600">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="py-6 mt-10 bg-white border-t">
        <div class="mx-auto text-center text-gray-500 max-w-7xl">
            &copy; {{ date('Y') }} Al Khinjar Al Dhahbi. All rights reserved.
        </div>
    </footer>
</body>

</html>
