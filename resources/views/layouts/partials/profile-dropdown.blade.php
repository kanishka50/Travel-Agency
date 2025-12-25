<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="flex items-center bg-gray-50 rounded-lg px-3 py-2 hover:bg-gray-100 transition-all duration-200">
        <div class="bg-gradient-to-br from-emerald-600 to-teal-600 rounded-full p-2 mr-2">
            @if(Auth::user()->user_type === 'guide' && Auth::user()->guide && Auth::user()->guide->profile_photo)
                <img src="{{ Storage::url(Auth::user()->guide->profile_photo) }}" alt="Profile" class="w-5 h-5 rounded-full object-cover">
            @else
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            @endif
        </div>
        <div class="text-left hidden sm:block">
            <p class="text-sm font-semibold text-gray-900">
                @if(Auth::user()->user_type === 'guide' && Auth::user()->guide)
                    {{ Auth::user()->guide->full_name }}
                @elseif(Auth::user()->user_type === 'tourist' && Auth::user()->tourist)
                    {{ Auth::user()->tourist->full_name }}
                @else
                    {{ Auth::user()->name ?? Auth::user()->email }}
                @endif
            </p>
            <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->user_type }}</p>
        </div>
        <svg class="w-4 h-4 ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-100 py-2 z-50"
         style="display: none;">

        <!-- User Info -->
        <div class="px-4 py-3 border-b border-gray-100">
            <p class="text-sm font-semibold text-gray-900">
                @if(Auth::user()->user_type === 'guide' && Auth::user()->guide)
                    {{ Auth::user()->guide->full_name }}
                @elseif(Auth::user()->user_type === 'tourist' && Auth::user()->tourist)
                    {{ Auth::user()->tourist->full_name }}
                @else
                    {{ Auth::user()->name ?? 'User' }}
                @endif
            </p>
            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
        </div>

        <!-- Menu Items -->
        <a href="{{ Auth::user()->user_type === 'guide' ? route('guide.dashboard') : route('tourist.dashboard') }}"
           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ Auth::user()->user_type === 'guide' ? route('guide.settings') : route('tourist.settings') }}"
           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Settings
        </a>

        <div class="border-t border-gray-100 mt-2 pt-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
