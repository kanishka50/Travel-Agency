@extends('layouts.public')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[50vh] bg-slate-900 overflow-hidden flex items-center">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=1920&q=80" alt="Contact"
             class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/90 to-slate-900/70"></div>
    </div>

    <!-- Decorative Elements -->
    <div class="absolute top-20 right-20 w-72 h-72 bg-amber-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-10 left-10 w-56 h-56 bg-orange-500/10 rounded-full blur-3xl"></div>

    <div class="relative w-full px-6 lg:px-[8%] py-20">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-4 py-2 bg-amber-500/10 border border-amber-500/20 rounded-full text-amber-400 text-sm font-medium mb-6">
                <span class="w-2 h-2 bg-amber-400 rounded-full mr-2 animate-pulse"></span>
                We're here to help
            </span>
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                Let's start a <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">conversation</span>
            </h1>
            <p class="text-slate-300 text-lg md:text-xl leading-relaxed max-w-2xl">
                Have a question about your next adventure? We're just a message away. Our team is ready to help you plan the perfect trip.
            </p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="bg-slate-50 py-20">
    <div class="w-full px-6 lg:px-[8%]">
        <div class="grid lg:grid-cols-5 gap-12 lg:gap-16">
            <!-- Contact Form - Takes more space -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl p-8 lg:p-10 shadow-sm">
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Send us a message</h2>
                        <p class="text-slate-500">Fill out the form below and we'll get back to you within 24 hours.</p>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all @error('name') border-red-400 bg-red-50 @enderror"
                                       placeholder="John Doe">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                       class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all @error('email') border-red-400 bg-red-50 @enderror"
                                       placeholder="john@example.com">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-semibold text-slate-700 mb-2">Subject</label>
                            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                                   class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all @error('subject') border-red-400 bg-red-50 @enderror"
                                   placeholder="How can we help you?">
                            @error('subject')
                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-semibold text-slate-700 mb-2">Your Message</label>
                            <textarea name="message" id="message" rows="5" required
                                      class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all resize-none @error('message') border-red-400 bg-red-50 @enderror"
                                      placeholder="Tell us about your travel plans or questions...">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span>Send Message</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Info Sidebar -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Quick Contact Cards -->
                <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 text-white">
                    <h3 class="text-lg font-bold mb-6">Quick Contact</h3>

                    <div class="space-y-5">
                        <a href="mailto:info@travelagency.com" class="flex items-center gap-4 group">
                            <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center group-hover:bg-amber-500 transition-colors">
                                <svg class="w-5 h-5 text-amber-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-slate-400 text-sm">Email us at</div>
                                <div class="font-semibold group-hover:text-amber-400 transition-colors">info@travelagency.com</div>
                            </div>
                        </a>

                        <a href="tel:+94112345678" class="flex items-center gap-4 group">
                            <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center group-hover:bg-amber-500 transition-colors">
                                <svg class="w-5 h-5 text-amber-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-slate-400 text-sm">Call us at</div>
                                <div class="font-semibold group-hover:text-amber-400 transition-colors">+94 11 234 5678</div>
                            </div>
                        </a>

                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-slate-400 text-sm">Visit us at</div>
                                <div class="font-semibold">123 Tourism Street, Colombo 03</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="bg-white rounded-3xl p-8 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Business Hours
                    </h3>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-600">Monday - Friday</span>
                            <span class="font-semibold text-slate-900">9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-slate-600">Saturday</span>
                            <span class="font-semibold text-slate-900">9:00 AM - 1:00 PM</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-slate-600">Sunday</span>
                            <span class="font-semibold text-red-500">Closed</span>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-3xl p-8 text-white">
                    <h3 class="text-lg font-bold mb-4">Follow Our Adventures</h3>
                    <p class="text-white/80 text-sm mb-6">Stay connected and get inspired for your next trip.</p>

                    <div class="flex gap-3">
                        <a href="#" class="w-11 h-11 bg-white/20 hover:bg-white hover:text-amber-500 rounded-xl flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-11 h-11 bg-white/20 hover:bg-white hover:text-amber-500 rounded-xl flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-11 h-11 bg-white/20 hover:bg-white hover:text-amber-500 rounded-xl flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-11 h-11 bg-white/20 hover:bg-white hover:text-amber-500 rounded-xl flex items-center justify-center transition-all">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="bg-white py-20">
    <div class="w-full px-6 lg:px-[8%]">
        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16">
            <!-- Left Side - Header -->
            <div class="lg:col-span-4">
                <div>
                    <span class="text-amber-500 text-sm font-semibold tracking-widest uppercase">FAQ</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-3 mb-4">Questions? We've got answers.</h2>
                    <p class="text-slate-500 mb-8">Can't find what you're looking for? Feel free to reach out to our support team.</p>

                    <div class="flex items-center gap-4 p-4 bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl border border-amber-100">
                        <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Still have questions?</div>
                            <a href="mailto:support@travelagency.com" class="text-amber-600 hover:text-amber-700 text-sm font-medium">Contact Support →</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Accordion -->
            <div class="lg:col-span-8">
                <div class="space-y-4" x-data="{ openFaq: 1 }">
                    <!-- FAQ Item 1 -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all" :class="openFaq === 1 ? 'bg-slate-50 border-amber-200' : 'bg-white hover:border-slate-300'">
                        <button @click="openFaq = openFaq === 1 ? null : 1" class="w-full flex items-center justify-between p-6 text-left">
                            <span class="font-semibold text-slate-900 pr-4">How do I book a tour package?</span>
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors" :class="openFaq === 1 ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500'">
                                <svg class="w-4 h-4 transition-transform duration-300" :class="openFaq === 1 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div x-show="openFaq === 1" x-collapse>
                            <div class="px-6 pb-6">
                                <p class="text-slate-600 leading-relaxed">Simply browse our tour packages, select one you like, and click "Book Now". You'll be guided through our easy booking process. You can also submit a custom tour request or contact us directly for personalized assistance from our travel experts.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all" :class="openFaq === 2 ? 'bg-slate-50 border-amber-200' : 'bg-white hover:border-slate-300'">
                        <button @click="openFaq = openFaq === 2 ? null : 2" class="w-full flex items-center justify-between p-6 text-left">
                            <span class="font-semibold text-slate-900 pr-4">Can I customize my tour itinerary?</span>
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors" :class="openFaq === 2 ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500'">
                                <svg class="w-4 h-4 transition-transform duration-300" :class="openFaq === 2 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div x-show="openFaq === 2" x-collapse>
                            <div class="px-6 pb-6">
                                <p class="text-slate-600 leading-relaxed">Absolutely! We specialize in personalized travel experiences. You can submit a tour request with your specific preferences, budget, and dates. Our verified local guides will then send you customized proposals tailored to your needs.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all" :class="openFaq === 3 ? 'bg-slate-50 border-amber-200' : 'bg-white hover:border-slate-300'">
                        <button @click="openFaq = openFaq === 3 ? null : 3" class="w-full flex items-center justify-between p-6 text-left">
                            <span class="font-semibold text-slate-900 pr-4">What payment methods do you accept?</span>
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors" :class="openFaq === 3 ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500'">
                                <svg class="w-4 h-4 transition-transform duration-300" :class="openFaq === 3 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div x-show="openFaq === 3" x-collapse>
                            <div class="px-6 pb-6">
                                <p class="text-slate-600 leading-relaxed">We accept all major credit cards (Visa, MasterCard, American Express), bank transfers, and various digital payment options. All transactions are secured with industry-standard encryption to ensure your financial data stays protected.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all" :class="openFaq === 4 ? 'bg-slate-50 border-amber-200' : 'bg-white hover:border-slate-300'">
                        <button @click="openFaq = openFaq === 4 ? null : 4" class="w-full flex items-center justify-between p-6 text-left">
                            <span class="font-semibold text-slate-900 pr-4">What is your cancellation policy?</span>
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors" :class="openFaq === 4 ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500'">
                                <svg class="w-4 h-4 transition-transform duration-300" :class="openFaq === 4 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div x-show="openFaq === 4" x-collapse>
                            <div class="px-6 pb-6">
                                <p class="text-slate-600 leading-relaxed">Cancellation policies vary by tour package and guide. Generally, full refunds are available for cancellations made 7+ days before the tour date. Cancellations within 3-7 days may receive partial refunds. Please check specific tour details or contact us for more information.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all" :class="openFaq === 5 ? 'bg-slate-50 border-amber-200' : 'bg-white hover:border-slate-300'">
                        <button @click="openFaq = openFaq === 5 ? null : 5" class="w-full flex items-center justify-between p-6 text-left">
                            <span class="font-semibold text-slate-900 pr-4">How are your guides verified?</span>
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors" :class="openFaq === 5 ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500'">
                                <svg class="w-4 h-4 transition-transform duration-300" :class="openFaq === 5 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div x-show="openFaq === 5" x-collapse>
                            <div class="px-6 pb-6">
                                <p class="text-slate-600 leading-relaxed">All guides on our platform undergo a rigorous verification process. We check professional licenses, verify identification, review experience and qualifications, and collect references. Only guides who meet our quality standards are approved to offer tours on our platform.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden transition-all" :class="openFaq === 6 ? 'bg-slate-50 border-amber-200' : 'bg-white hover:border-slate-300'">
                        <button @click="openFaq = openFaq === 6 ? null : 6" class="w-full flex items-center justify-between p-6 text-left">
                            <span class="font-semibold text-slate-900 pr-4">Do you offer group discounts?</span>
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors" :class="openFaq === 6 ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-500'">
                                <svg class="w-4 h-4 transition-transform duration-300" :class="openFaq === 6 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>
                        <div x-show="openFaq === 6" x-collapse>
                            <div class="px-6 pb-6">
                                <p class="text-slate-600 leading-relaxed">Yes! Many of our guides offer special rates for larger groups. When booking, simply indicate your group size and you'll see applicable discounts. For corporate or large group bookings, contact us directly for custom pricing packages.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
