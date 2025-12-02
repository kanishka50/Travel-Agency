<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Public Navigation -->
        <nav class="bg-white shadow-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ route('welcome') }}" class="text-2xl font-bold text-blue-600">
                            TourismPlatform
                        </a>
                    </div>
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('plans.index') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">
                            Browse Tours
                        </a>
                        @guest
                            <a href="{{ route('guide.register') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">
                                Become a Guide
                            </a>
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">
                                Login
                            </a>
                            <a href="{{ route('tourist.register.form') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Sign Up
                            </a>
                        @else
                            @if(auth()->user()->isTourist())
                                <a href="{{ route('tourist.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">
                                    Dashboard
                                </a>
                            @elseif(auth()->user()->isGuide())
                                <a href="{{ route('guide.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition">
                                    Dashboard
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-blue-600 font-medium transition">
                                    Logout
                                </button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
