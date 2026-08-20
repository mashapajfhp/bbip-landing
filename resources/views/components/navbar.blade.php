<header class="sticky top-0 z-50 py-3 bg-white/90 backdrop-blur-md border-b border-blue-100 shadow-sm">
    <div class="container">
        <nav class="flex items-center justify-between gap-5 bg-white/97 border border-line rounded-3xl p-2.5 shadow-md" x-data="{ open: false }">
            <a href="/" class="flex-shrink-0" aria-label="BBIP Home">
                <img class="h-12 w-auto" src="{{ asset('logo.png') }}" alt="BBIP logo">
            </a>

            <div class="hidden md:flex items-center gap-7 text-sm font-bold text-ink">
                <a href="#how" class="relative py-2 transition-colors hover:text-blue-600 group">
                    How It Works
                    <span class="absolute left-0 right-0 bottom-0 h-0.5 rounded bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform"></span>
                </a>
                <a href="#lead-form" class="relative py-2 transition-colors hover:text-blue-600 group">
                    For Parents
                    <span class="absolute left-0 right-0 bottom-0 h-0.5 rounded bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform"></span>
                </a>
                <a href="#about" class="relative py-2 transition-colors hover:text-blue-600 group">
                    About BBIP
                    <span class="absolute left-0 right-0 bottom-0 h-0.5 rounded bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform"></span>
                </a>
                <a href="#lead-form" class="relative py-2 transition-colors hover:text-blue-600 group">
                    Contact
                    <span class="absolute left-0 right-0 bottom-0 h-0.5 rounded bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform"></span>
                </a>
            </div>

            <div class="hidden md:flex items-center gap-2">
                <a href="#" class="btn btn-whatsapp text-xs hidden md:inline-flex whatsapp-link">
                    <span class="wa-dot">◔</span> Chat on WhatsApp
                </a>
                <a href="#lead-form" class="btn btn-primary text-xs">Get Started</a>
            </div>

            <button @click="open = !open" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors ml-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </nav>

        <!-- Mobile Menu -->
        <div x-show="open" @click.outside="open = false" class="md:hidden mt-4 space-y-3 pb-4 px-4">
            <a href="#how" @click="open = false" class="block px-4 py-2.5 rounded-lg hover:bg-blue-50 text-gray-700 font-medium transition-colors">How It Works</a>
            <a href="#lead-form" @click="open = false" class="block px-4 py-2.5 rounded-lg hover:bg-blue-50 text-gray-700 font-medium transition-colors">For Parents</a>
            <a href="#about" @click="open = false" class="block px-4 py-2.5 rounded-lg hover:bg-blue-50 text-gray-700 font-medium transition-colors">About BBIP</a>
            <a href="#lead-form" @click="open = false" class="block px-4 py-2.5 rounded-lg hover:bg-blue-50 text-gray-700 font-medium transition-colors">Contact</a>
            <div class="flex flex-col gap-3 pt-3 border-t border-gray-200">
                <a href="#" class="btn btn-whatsapp text-xs w-full justify-center whatsapp-link">
                    <span class="wa-dot">◔</span> Chat on WhatsApp
                </a>
                <a href="#lead-form" class="btn btn-primary text-xs w-full justify-center">Get Started</a>
            </div>
        </div>
    </div>
</header>
