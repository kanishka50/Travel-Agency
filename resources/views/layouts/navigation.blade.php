<nav class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">
                        {{ config('app.name') }}
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:flex sm:ml-10">
                    @auth
                        @if(auth()->user()->isTourist())
                            <a href="{{ route('tourist.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-gray-900">
                                Dashboard
                            </a>
                            <a href="{{ route('plans.index') }}" class="inline-flex items-center px-1 pt-1 text-gray-600 hover:text-gray-900">
                                Browse Tours
                            </a>
                            <a href="{{ route('tourist-requests.index') }}" class="inline-flex items-center px-1 pt-1 text-gray-600 hover:text-gray-900">
                                My Requests
                            </a>
                            <a href="{{ route('tourist-requests.create') }}" class="inline-flex items-center px-1 pt-1 bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-700 font-medium">
                                + Request Custom Tour
                            </a>
                        @elseif(auth()->user()->isGuide())
                            <a href="{{ route('guide.dashboard') }}" class="inline-flex items-center px-1 pt-1 text-gray-900">
                                Dashboard
                            </a>
                            <a href="{{ route('guide.plans.index') }}" class="inline-flex items-center px-1 pt-1 text-gray-600 hover:text-gray-900">
                                My Plans
                            </a>
                            <a href="{{ route('guide.requests.index') }}" class="inline-flex items-center px-1 pt-1 text-gray-600 hover:text-gray-900">
                                Browse Requests
                            </a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="/admin" class="inline-flex items-center px-1 pt-1 text-gray-900">
                                Admin Panel
                            </a>
                        @endif
                    @else
                        <a href="{{ route('plans.index') }}" class="inline-flex items-center px-1 pt-1 text-gray-600 hover:text-gray-900">
                            Browse Tours
                        </a>
                        <a href="{{ route('guide.register') }}" class="inline-flex items-center px-1 pt-1 text-gray-600 hover:text-gray-900">
                            Become a Guide
                        </a>
                    @endauth
                </div>
            </div>

            <!-- User Menu -->
            <div class="flex items-center">
                @auth
                    <div class="relative">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-700">{{ auth()->user()->email }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm text-gray-700 hover:text-gray-900">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900 mr-4">Login</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>