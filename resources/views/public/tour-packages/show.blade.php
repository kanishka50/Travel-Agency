@extends('layouts.public')

@section('content')
<!-- Hero Section with Full-Width Gallery -->
<section class="relative">
    <!-- Main Image Gallery -->
    <div class="relative h-[500px] lg:h-[600px] overflow-hidden">
        @if($plan->cover_photo)
            <img src="{{ Storage::url($plan->cover_photo) }}" alt="{{ $plan->title }}"
                 class="w-full h-full object-cover" id="main-gallery-photo">
        @elseif($plan->photos->count() > 0)
            <img src="{{ $plan->photos->first()->url }}" alt="{{ $plan->title }}"
                 class="w-full h-full object-cover" id="main-gallery-photo">
        @else
            <div class="w-full h-full bg-gradient-to-br from-amber-400 via-orange-500 to-amber-600"></div>
        @endif

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/10"></div>

        <!-- Breadcrumb -->
        <div class="absolute top-6 left-0 right-0 w-full px-6 lg:px-[8%] z-10">
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ url('/') }}" class="text-white/70 hover:text-amber-400 transition-colors">Home</a>
                <svg class="w-4 h-4 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('tour-packages.index') }}" class="text-white/70 hover:text-amber-400 transition-colors">Tour Packages</a>
                <svg class="w-4 h-4 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-amber-400">{{ Str::limit($plan->title, 30) }}</span>
            </nav>
        </div>

        <!-- Gallery Buttons -->
        @php
            $totalPhotos = ($plan->cover_photo ? 1 : 0) + $plan->photos->count();
        @endphp
        @if($totalPhotos > 1)
            <button onclick="openGallery(0)"
                    class="absolute bottom-6 right-6 lg:right-[8%] flex items-center gap-2 px-5 py-3 bg-white/20 backdrop-blur-md text-white rounded-xl hover:bg-white/30 transition-all z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="font-semibold">View all {{ $totalPhotos }} photos</span>
            </button>
        @endif

        <!-- Bottom Content -->
        <div class="absolute bottom-0 left-0 right-0 w-full px-6 lg:px-[8%] pb-8 z-10">
            <div class="max-w-4xl">
                <!-- Tags -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-4 py-2 bg-amber-500 text-white text-sm font-bold rounded-full shadow-lg">
                        {{ $plan->num_days }} Days / {{ $plan->num_nights ?? $plan->num_days - 1 }} Nights
                    </span>
                    @if($plan->availability_type === 'always_available')
                        <span class="px-4 py-2 bg-green-500/90 text-white text-sm font-semibold rounded-full">
                            Always Available
                        </span>
                    @else
                        <span class="px-4 py-2 bg-orange-500/90 text-white text-sm font-semibold rounded-full">
                            {{ $plan->available_start_date?->format('M d') }} - {{ $plan->available_end_date?->format('M d, Y') }}
                        </span>
                    @endif
                    @if($plan->trip_focus_tags && count($plan->trip_focus_tags) > 0)
                        @foreach(array_slice($plan->trip_focus_tags, 0, 3) as $tag)
                            <span class="px-4 py-2 bg-white/20 backdrop-blur-md text-white text-sm font-medium rounded-full">
                                {{ $tag }}
                            </span>
                        @endforeach
                    @endif
                </div>

                <!-- Title -->
                <h1 class="font-display text-3xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                    {{ $plan->title }}
                </h1>

                <!-- Quick Stats Row -->
                <div class="flex flex-wrap items-center gap-6 text-white/80">
                    <!-- Rating -->
                    @if($plan->guide && $plan->guide->average_rating > 0)
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1">
                                <svg class="w-5 h-5 text-amber-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="font-semibold text-white">{{ number_format($plan->guide->average_rating, 1) }}</span>
                            </div>
                            <span>({{ $plan->guide->total_reviews ?? 0 }} reviews)</span>
                        </div>
                    @endif
                    <!-- Views & Bookings -->
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>{{ $plan->view_count ?? 0 }} views</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 4v12l-4-2-4 2V4M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>{{ $plan->booking_count ?? 0 }} bookings</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thumbnail Strip -->
    @if($totalPhotos > 1)
        <div class="bg-slate-900 py-4 px-6 lg:px-[8%]">
            <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                @if($plan->cover_photo)
                    <div class="flex-shrink-0 cursor-pointer" onclick="openGallery(0)">
                        <img src="{{ Storage::url($plan->cover_photo) }}" alt="Cover"
                             class="h-20 w-28 object-cover rounded-xl ring-2 ring-amber-500 hover:ring-amber-400 transition-all">
                    </div>
                @endif
                @foreach($plan->photos as $index => $photo)
                    <div class="flex-shrink-0 cursor-pointer" onclick="openGallery({{ $index + ($plan->cover_photo ? 1 : 0) }})">
                        <img src="{{ $photo->url }}" alt="Photo {{ $index + 1 }}"
                             class="h-20 w-28 object-cover rounded-xl ring-2 ring-transparent hover:ring-amber-500 transition-all">
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>

<!-- Main Content -->
<section class="bg-slate-50 py-12">
    <div class="w-full px-6 lg:px-[8%]">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Photo Gallery Section -->
                @if($totalPhotos > 1)
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="font-display text-xl font-bold text-slate-900">Tour Gallery</h2>
                                        <p class="text-sm text-slate-500">{{ $totalPhotos }} photos from this experience</p>
                                    </div>
                                </div>
                                <button onclick="openGallery(0)" class="hidden sm:flex items-center gap-2 px-4 py-2 text-amber-600 hover:text-amber-700 font-semibold text-sm hover:bg-amber-50 rounded-xl transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                    </svg>
                                    View All
                                </button>
                            </div>
                        </div>

                        <!-- Gallery Grid -->
                        <div class="p-4">
                            @php
                                $allPhotos = collect();
                                if($plan->cover_photo) {
                                    $allPhotos->push(['url' => Storage::url($plan->cover_photo), 'type' => 'cover']);
                                }
                                foreach($plan->photos as $photo) {
                                    $allPhotos->push(['url' => $photo->url, 'type' => 'gallery']);
                                }
                            @endphp

                            @if($totalPhotos >= 5)
                                <!-- Large grid for 5+ photos -->
                                <div class="grid grid-cols-4 grid-rows-2 gap-3 h-[400px]">
                                    <!-- Main large photo -->
                                    <div class="col-span-2 row-span-2 relative group cursor-pointer rounded-2xl overflow-hidden" onclick="openGallery(0)">
                                        <img src="{{ $allPhotos[0]['url'] }}" alt="Main photo"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        <div class="absolute bottom-4 left-4 text-white opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="px-3 py-1.5 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">Cover Photo</span>
                                        </div>
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                            <div class="w-14 h-14 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Secondary photos -->
                                    @foreach($allPhotos->slice(1, 3) as $index => $photo)
                                        <div class="relative group cursor-pointer rounded-2xl overflow-hidden" onclick="openGallery({{ $index + 1 }})">
                                            <img src="{{ $photo['url'] }}" alt="Photo {{ $index + 2 }}"
                                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                                <div class="w-10 h-10 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- More photos button -->
                                    @if($totalPhotos > 4)
                                        <div class="relative group cursor-pointer rounded-2xl overflow-hidden" onclick="openGallery(4)">
                                            <img src="{{ $allPhotos[4]['url'] ?? $allPhotos->last()['url'] }}" alt="More photos"
                                                 class="w-full h-full object-cover brightness-50">
                                            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/80 to-orange-600/80 flex items-center justify-center">
                                                <div class="text-center text-white">
                                                    <div class="text-3xl font-bold">+{{ $totalPhotos - 4 }}</div>
                                                    <div class="text-sm font-medium opacity-90">more photos</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @elseif($totalPhotos >= 3)
                                <!-- Medium grid for 3-4 photos -->
                                <div class="grid grid-cols-3 gap-3 h-[300px]">
                                    <div class="col-span-2 relative group cursor-pointer rounded-2xl overflow-hidden" onclick="openGallery(0)">
                                        <img src="{{ $allPhotos[0]['url'] }}" alt="Main photo"
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                                    </div>
                                    <div class="flex flex-col gap-3">
                                        @foreach($allPhotos->slice(1, 2) as $index => $photo)
                                            <div class="flex-1 relative group cursor-pointer rounded-2xl overflow-hidden" onclick="openGallery({{ $index + 1 }})">
                                                <img src="{{ $photo['url'] }}" alt="Photo {{ $index + 2 }}"
                                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                                                @if($index == 1 && $totalPhotos > 3)
                                                    <div class="absolute inset-0 bg-gradient-to-br from-amber-500/80 to-orange-600/80 flex items-center justify-center">
                                                        <div class="text-center text-white">
                                                            <div class="text-2xl font-bold">+{{ $totalPhotos - 3 }}</div>
                                                            <div class="text-xs font-medium opacity-90">more</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <!-- Simple grid for 2 photos -->
                                <div class="grid grid-cols-2 gap-3 h-[280px]">
                                    @foreach($allPhotos->take(2) as $index => $photo)
                                        <div class="relative group cursor-pointer rounded-2xl overflow-hidden" onclick="openGallery({{ $index }})">
                                            <img src="{{ $photo['url'] }}" alt="Photo {{ $index + 1 }}"
                                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                                <div class="w-12 h-12 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Gallery Footer -->
                        <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-amber-50/50 border-t border-slate-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4 text-sm text-slate-500">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $totalPhotos }} photos
                                    </span>
                                    <span class="hidden sm:flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Click any image to view full size
                                    </span>
                                </div>
                                <button onclick="openGallery(0)" class="flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold text-sm rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:-translate-y-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                    </svg>
                                    Open Gallery
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Destinations Bar -->
                @if($plan->destinations && count($plan->destinations) > 0)
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-display text-lg font-bold text-slate-900">Destinations Covered</h3>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($plan->destinations as $destination)
                                <span class="px-4 py-2 bg-gradient-to-r from-amber-50 to-orange-50 text-amber-700 font-medium rounded-xl border border-amber-200">
                                    {{ $destination }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Overview -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="font-display text-2xl font-bold text-slate-900">Overview</h2>
                    </div>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                        {!! nl2br(e($plan->description)) !!}
                    </div>
                </div>

                <!-- Trip Highlights -->
                @if($plan->trip_focus_tags && count($plan->trip_focus_tags) > 0)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                            <h2 class="font-display text-2xl font-bold text-slate-900">Trip Highlights</h2>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($plan->trip_focus_tags as $tag)
                                <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                    <svg class="w-5 h-5 text-purple-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-medium text-purple-700">{{ $tag }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Day-by-Day Itinerary -->
                @if($plan->itineraries && $plan->itineraries->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-8">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h2 class="font-display text-2xl font-bold text-slate-900">Day-by-Day Itinerary</h2>
                        </div>

                        <div class="relative">
                            <!-- Timeline Line -->
                            <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-gradient-to-b from-amber-500 via-amber-400 to-amber-300"></div>

                            <div class="space-y-6">
                                @foreach($plan->itineraries->sortBy('day_number') as $itinerary)
                                    <div class="relative pl-14" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                                        <!-- Day Circle -->
                                        <div class="absolute left-0 w-10 h-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-500 text-white flex items-center justify-center font-bold shadow-lg shadow-amber-500/30">
                                            {{ $itinerary->day_number }}
                                        </div>

                                        <!-- Day Card -->
                                        <div class="bg-gradient-to-r from-slate-50 to-white rounded-2xl border border-slate-100 overflow-hidden">
                                            <!-- Header (always visible) -->
                                            <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left hover:bg-slate-50 transition-colors">
                                                <div>
                                                    <h3 class="text-lg font-bold text-slate-900">
                                                        Day {{ $itinerary->day_number }}: {{ $itinerary->day_title }}
                                                    </h3>
                                                    <div class="flex items-center gap-3 mt-1 text-sm text-slate-500">
                                                        @if($itinerary->hasAccommodation())
                                                            <span class="flex items-center gap-1">
                                                                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                                </svg>
                                                                {{ $itinerary->accommodation_name }}
                                                            </span>
                                                        @endif
                                                        @if($itinerary->hasMealsIncluded())
                                                            <span class="flex items-center gap-1">
                                                                <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                                </svg>
                                                                {{ count($itinerary->included_meals) }} meals
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <svg class="w-5 h-5 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>

                                            <!-- Collapsible Content -->
                                            <div x-show="open" x-collapse class="px-5 pb-5">
                                                <div class="prose prose-sm max-w-none text-slate-600 mb-4">
                                                    {!! nl2br(e($itinerary->description)) !!}
                                                </div>

                                                <!-- Accommodation & Meals Details -->
                                                <div class="flex flex-wrap gap-3 mt-4 pt-4 border-t border-slate-100">
                                                    @if($itinerary->hasAccommodation())
                                                        <div class="flex items-center gap-2 px-4 py-2 bg-amber-50 rounded-xl border border-amber-200">
                                                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                            </svg>
                                                            <div>
                                                                <span class="font-medium text-amber-800">{{ $itinerary->accommodation_name }}</span>
                                                                @if($itinerary->accommodation_type_label || $itinerary->accommodation_tier_label)
                                                                    <span class="text-sm text-amber-600 ml-1">
                                                                        ({{ collect([$itinerary->accommodation_type_label, $itinerary->accommodation_tier_label])->filter()->implode(' • ') }})
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($itinerary->hasMealsIncluded())
                                                        <div class="flex items-center gap-2 px-4 py-2 bg-orange-50 rounded-xl border border-orange-200">
                                                            <svg class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                                            </svg>
                                                            <div class="flex flex-wrap gap-1">
                                                                @foreach($itinerary->included_meals as $meal)
                                                                    <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-xs font-medium rounded-full">{{ $meal }}</span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                @if($itinerary->meal_notes)
                                                    <p class="text-sm text-slate-500 mt-3 italic flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        {{ $itinerary->meal_notes }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Inclusions & Exclusions -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h2 class="font-display text-2xl font-bold text-slate-900">What's Included</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Inclusions -->
                        <div class="bg-green-50 rounded-2xl p-6 border border-green-100">
                            <h3 class="font-semibold text-green-800 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Included
                            </h3>
                            <ul class="space-y-3">
                                @if($plan->inclusions)
                                    @foreach(explode("\n", $plan->inclusions) as $inclusion)
                                        @if(trim($inclusion))
                                            <li class="flex items-start gap-3">
                                                <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-slate-700">{{ trim($inclusion, '- ') }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        <!-- Exclusions -->
                        <div class="bg-red-50 rounded-2xl p-6 border border-red-100">
                            <h3 class="font-semibold text-red-800 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Not Included
                            </h3>
                            <ul class="space-y-3">
                                @if($plan->exclusions)
                                    @foreach(explode("\n", $plan->exclusions) as $exclusion)
                                        @if(trim($exclusion))
                                            <li class="flex items-start gap-3">
                                                <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-slate-700">{{ trim($exclusion, '- ') }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Optional Add-ons -->
                @if($plan->addons && $plan->addons->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="font-display text-2xl font-bold text-slate-900">Optional Add-ons</h2>
                                <p class="text-slate-500 text-sm">Enhance your experience with these optional activities</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($plan->addons as $addon)
                                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-5 border border-purple-100 hover:border-purple-300 hover:shadow-lg transition-all">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-slate-900">{{ $addon->addon_name }}</h3>
                                            @if($addon->day_number > 0)
                                                <span class="inline-block mt-1 px-2.5 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                                    Day {{ $addon->day_number }}
                                                </span>
                                            @else
                                                <span class="inline-block mt-1 px-2.5 py-1 bg-slate-100 text-slate-600 text-xs font-semibold rounded-full">
                                                    Available Any Day
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-2xl font-bold text-purple-600">${{ number_format($addon->price_per_person, 0) }}</p>
                                            <p class="text-xs text-slate-500">per person</p>
                                        </div>
                                    </div>

                                    <p class="text-sm text-slate-600 mb-3">{{ $addon->addon_description }}</p>

                                    <div class="flex items-center gap-3 text-xs text-slate-500">
                                        @if($addon->max_participants)
                                            <span class="flex items-center gap-1 bg-white px-2 py-1 rounded-full border">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                Max {{ $addon->max_participants }}
                                            </span>
                                        @endif
                                        @if($addon->require_all_participants)
                                            <span class="flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-1 rounded-full border border-amber-200">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                All must participate
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Transportation -->
                @if($plan->vehicle_type)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M8 17h8M8 17v-4h8v4M8 17H5a2 2 0 01-2-2V9a2 2 0 012-2h1.586a1 1 0 01.707.293L9 9h6l1.707-1.707A1 1 0 0117.414 7H19a2 2 0 012 2v6a2 2 0 01-2 2h-3M8 17v2a1 1 0 001 1h1m4-3v2a1 1 0 001 1h1"/>
                                </svg>
                            </div>
                            <h2 class="font-display text-2xl font-bold text-slate-900">Transportation</h2>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center p-5 bg-blue-50 rounded-2xl border border-blue-100">
                                <div class="text-sm text-blue-600 mb-1 font-medium">Vehicle Type</div>
                                <div class="font-bold text-slate-900">{{ $plan->vehicle_type }}</div>
                            </div>
                            @if($plan->vehicle_capacity)
                                <div class="text-center p-5 bg-blue-50 rounded-2xl border border-blue-100">
                                    <div class="text-sm text-blue-600 mb-1 font-medium">Capacity</div>
                                    <div class="font-bold text-slate-900">{{ $plan->vehicle_capacity }} seats</div>
                                </div>
                            @endif
                            <div class="text-center p-5 bg-blue-50 rounded-2xl border border-blue-100">
                                <div class="text-sm text-blue-600 mb-1 font-medium">Air Conditioning</div>
                                <div class="font-bold text-slate-900 flex items-center justify-center gap-1">
                                    @if($plan->vehicle_ac)
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Yes
                                    @else
                                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                        No
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($plan->vehicle_description)
                            <p class="text-slate-600 mt-4">{{ $plan->vehicle_description }}</p>
                        @endif
                    </div>
                @endif

                <!-- Dietary Options -->
                @if($plan->dietary_options && count($plan->dietary_options) > 0)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h2 class="font-display text-2xl font-bold text-slate-900">Dietary Options Available</h2>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @foreach($plan->dietary_options as $option)
                                <span class="px-4 py-2 bg-green-50 text-green-700 font-medium rounded-xl border border-green-200">
                                    {{ ucfirst(str_replace('_', ' ', $option)) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Accessibility -->
                @if($plan->accessibility_info)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h2 class="font-display text-2xl font-bold text-slate-900">Accessibility Information</h2>
                        </div>
                        <p class="text-slate-600">{{ $plan->accessibility_info }}</p>
                    </div>
                @endif

                <!-- Your Guide -->
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 p-6 text-white">
                        <h2 class="font-display text-2xl font-bold flex items-center gap-3">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Meet Your Guide
                        </h2>
                    </div>
                    <div class="p-8">
                        <div class="flex items-start gap-6">
                            @if($plan->guide->profile_photo)
                                <img src="{{ Storage::url($plan->guide->profile_photo) }}" alt="{{ $plan->guide->full_name }}"
                                     class="w-24 h-24 rounded-2xl object-cover ring-4 ring-amber-100">
                            @else
                                <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                                    <span class="text-white text-3xl font-bold">{{ strtoupper(substr($plan->guide->full_name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-2xl font-bold text-slate-900 mb-1">{{ $plan->guide->full_name }}</h3>
                                <span class="inline-block px-3 py-1 bg-amber-100 text-amber-700 text-sm font-semibold rounded-full mb-3">{{ $plan->guide->guide_type_label ?? 'Professional Guide' }}</span>

                                <!-- Rating -->
                                @if($plan->guide->average_rating > 0)
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-5 h-5 {{ $i <= $plan->guide->average_rating ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="text-slate-600 font-medium">{{ number_format($plan->guide->average_rating, 1) }}</span>
                                        <span class="text-slate-400">({{ $plan->guide->total_reviews ?? 0 }} reviews)</span>
                                    </div>
                                @endif

                                <!-- Guide Info -->
                                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                                    @if($plan->guide->languages && count($plan->guide->languages) > 0)
                                        <div class="flex items-center gap-2 text-slate-600">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                            </svg>
                                            <span>{{ implode(', ', $plan->guide->languages) }}</span>
                                        </div>
                                    @endif
                                    @if($plan->guide->years_experience)
                                        <div class="flex items-center gap-2 text-slate-600">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ $plan->guide->years_experience }} years experience</span>
                                        </div>
                                    @endif
                                    @if($plan->guide->total_bookings)
                                        <div class="flex items-center gap-2 text-slate-600">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ $plan->guide->total_bookings }} completed tours</span>
                                        </div>
                                    @endif
                                </div>

                                @if($plan->guide->bio)
                                    <p class="text-slate-600 leading-relaxed">{{ $plan->guide->bio }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                @if($plan->reviews && $plan->reviews->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <h2 class="font-display text-2xl font-bold text-slate-900">Guest Reviews</h2>
                        </div>
                        <div class="space-y-6">
                            @foreach($plan->reviews->take(5) as $review)
                                <div class="border-b border-slate-100 pb-6 last:border-0 last:pb-0">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center">
                                                <span class="text-slate-600 font-semibold text-sm">{{ strtoupper(substr($review->booking->tourist->full_name ?? 'G', 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-slate-900">{{ $review->booking->tourist->full_name ?? 'Guest' }}</div>
                                                <div class="text-sm text-slate-500">{{ $review->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }} fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-slate-600 leading-relaxed">{{ $review->review_text }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 space-y-6">
                    <!-- Price Calculator -->
                    @include('public.tour-packages.partials.price-calculator', ['plan' => $plan])

                    <!-- Availability Calendar -->
                    @include('public.tour-packages.partials.availability-calendar', ['plan' => $plan])

                    <!-- Quick Info -->
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="font-display font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Quick Info
                        </h3>
                        <dl class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                <dt class="text-slate-500 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Group Size
                                </dt>
                                <dd class="font-semibold text-slate-900">{{ $plan->min_group_size }}-{{ $plan->max_group_size }} people</dd>
                            </div>
                            @if($plan->pickup_location)
                                <div class="flex justify-between items-center py-2 border-b border-slate-100">
                                    <dt class="text-slate-500 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        Pickup
                                    </dt>
                                    <dd class="font-semibold text-slate-900 text-right text-sm">{{ Str::limit($plan->pickup_location, 25) }}</dd>
                                </div>
                            @endif
                            @if($plan->dropoff_location)
                                <div class="flex justify-between items-center py-2">
                                    <dt class="text-slate-500 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        Dropoff
                                    </dt>
                                    <dd class="font-semibold text-slate-900 text-right text-sm">{{ Str::limit($plan->dropoff_location, 25) }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Share Buttons -->
                    @include('public.tour-packages.partials.share-buttons', ['plan' => $plan])
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
@if($totalPhotos > 0)
    <div id="gallery-lightbox" class="fixed inset-0 z-50 hidden bg-black/95">
        <div class="absolute inset-0 flex items-center justify-center">
            <!-- Close button -->
            <button onclick="closeGallery()" class="absolute top-6 right-6 text-white/70 hover:text-white z-20 p-2 bg-white/10 hover:bg-white/20 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Navigation buttons -->
            <button onclick="prevGalleryPhoto()" class="absolute left-6 text-white/70 hover:text-white z-20 p-3 bg-white/10 hover:bg-white/20 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button onclick="nextGalleryPhoto()" class="absolute right-6 text-white/70 hover:text-white z-20 p-3 bg-white/10 hover:bg-white/20 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </button>

            <!-- Main image -->
            <img id="gallery-lightbox-image" src="" alt="Gallery" class="max-h-[80vh] max-w-[85vw] object-contain rounded-lg">

            <!-- Counter -->
            <div id="gallery-counter" class="absolute bottom-24 left-1/2 transform -translate-x-1/2 text-white bg-black/50 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium"></div>
        </div>

        <!-- Thumbnail strip in lightbox -->
        <div class="absolute bottom-6 left-0 right-0 px-6">
            <div class="flex justify-center gap-2 overflow-x-auto py-2" id="gallery-thumbnails">
                @if($plan->cover_photo)
                    <div class="flex-shrink-0 cursor-pointer gallery-thumb" onclick="goToGalleryPhoto(0)">
                        <img src="{{ Storage::url($plan->cover_photo) }}" alt="Cover"
                             class="h-14 w-20 object-cover rounded-lg opacity-50 hover:opacity-100 transition-opacity ring-2 ring-transparent">
                    </div>
                @endif
                @foreach($plan->photos as $index => $photo)
                    <div class="flex-shrink-0 cursor-pointer gallery-thumb" onclick="goToGalleryPhoto({{ $index + ($plan->cover_photo ? 1 : 0) }})">
                        <img src="{{ $photo->url }}" alt="Photo {{ $index + 1 }}"
                             class="h-14 w-20 object-cover rounded-lg opacity-50 hover:opacity-100 transition-opacity ring-2 ring-transparent">
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        const galleryPhotos = [
            @if($plan->cover_photo)
                "{{ Storage::url($plan->cover_photo) }}",
            @endif
            @foreach($plan->photos as $photo)
                "{{ $photo->url }}",
            @endforeach
        ];
        let currentGalleryIndex = 0;

        function openGallery(index) {
            currentGalleryIndex = index;
            updateGalleryImage();
            document.getElementById('gallery-lightbox').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            document.getElementById('gallery-lightbox').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function nextGalleryPhoto() {
            currentGalleryIndex = (currentGalleryIndex + 1) % galleryPhotos.length;
            updateGalleryImage();
        }

        function prevGalleryPhoto() {
            currentGalleryIndex = (currentGalleryIndex - 1 + galleryPhotos.length) % galleryPhotos.length;
            updateGalleryImage();
        }

        function goToGalleryPhoto(index) {
            currentGalleryIndex = index;
            updateGalleryImage();
        }

        function updateGalleryImage() {
            document.getElementById('gallery-lightbox-image').src = galleryPhotos[currentGalleryIndex];
            document.getElementById('gallery-counter').textContent = `${currentGalleryIndex + 1} / ${galleryPhotos.length}`;

            // Update thumbnail highlighting
            document.querySelectorAll('.gallery-thumb img').forEach((thumb, index) => {
                if (index === currentGalleryIndex) {
                    thumb.classList.remove('opacity-50');
                    thumb.classList.add('opacity-100', 'ring-amber-500');
                } else {
                    thumb.classList.add('opacity-50');
                    thumb.classList.remove('opacity-100', 'ring-amber-500');
                }
            });
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const lightbox = document.getElementById('gallery-lightbox');
            if (lightbox && !lightbox.classList.contains('hidden')) {
                if (e.key === 'Escape') closeGallery();
                if (e.key === 'ArrowRight') nextGalleryPhoto();
                if (e.key === 'ArrowLeft') prevGalleryPhoto();
            }
        });

        // Close on background click
        document.getElementById('gallery-lightbox')?.addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('flex')) {
                closeGallery();
            }
        });
    </script>
@endif
@endsection
