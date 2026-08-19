<nav class="sticky top-0 z-50 bg-white shadow-sm" x-data="{ open: false }">
    <div class="container py-4">
        <div class="flex items-center justify-between">
            <div class="flex-shrink-0">
                <h1 class="text-2xl font-bold text-navy">BBIP</h1>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="#features" class="text-ink hover:text-blue-600 transition-colors">Features</a>
                <a href="#stats" class="text-ink hover:text-blue-600 transition-colors">Stats</a>
                <a href="#how-it-works" class="text-ink hover:text-blue-600 transition-colors">How It Works</a>
                <a href="#testimonials" class="text-ink hover:text-blue-600 transition-colors">Testimonials</a>
                <a href="#faq" class="text-ink hover:text-blue-600 transition-colors">FAQ</a>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                <button class="btn btn-outline text-sm">Sign In</button>
                <button class="btn btn-primary text-sm">Get Started</button>
            </div>

            <button @click="open = !open" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div x-show="open" @click.outside="open = false" class="md:hidden mt-4 space-y-2 pb-4">
            <a href="#features" @click="open = false" class="block px-4 py-2 rounded-lg hover:bg-soft text-ink">Features</a>
            <a href="#stats" @click="open = false" class="block px-4 py-2 rounded-lg hover:bg-soft text-ink">Stats</a>
            <a href="#how-it-works" @click="open = false" class="block px-4 py-2 rounded-lg hover:bg-soft text-ink">How It Works</a>
            <a href="#testimonials" @click="open = false" class="block px-4 py-2 rounded-lg hover:bg-soft text-ink">Testimonials</a>
            <a href="#faq" @click="open = false" class="block px-4 py-2 rounded-lg hover:bg-soft text-ink">FAQ</a>
            <div class="flex flex-col space-y-2 pt-4">
                <button class="btn btn-outline w-full text-sm">Sign In</button>
                <button class="btn btn-primary w-full text-sm">Get Started</button>
            </div>
        </div>
    </div>
</nav>
