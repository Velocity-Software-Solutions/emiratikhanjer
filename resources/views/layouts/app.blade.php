<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Dagger Antiques') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-gray-900 bg-gray-50">
    <header class="sticky top-0 z-50 bg-white shadow">
        <div class="flex items-center justify-between px-4 py-4 mx-auto max-w-7xl">
            <a href="/" class="text-2xl font-bold text-blue-700">Dagger Antiques</a>

            <nav class="space-x-6">
                <a href="/" class="text-gray-700 hover:text-blue-600">Home</a>
                <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600">Shop</a>
                <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600">Cart</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-blue-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-600">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="py-10">
        @yield('content')
    </main>

    <footer class="py-6 mt-10 bg-white border-t">
        <div class="mx-auto text-center text-gray-500 max-w-7xl">
            &copy; {{ date('Y') }} Dagger Antiques. All rights reserved.
        </div>
    </footer>
</body>

</html>
