<div x-data="{ open: false }" class="card">
    <button
        @click="open = !open"
        class="w-full flex items-center justify-between py-2 px-0 hover:text-blue-600 transition-colors text-left"
        :aria-expanded="open"
    >
        <h3 class="text-lg font-semibold text-ink">{{ $question }}</h3>
        <svg
            class="w-6 h-6 text-muted transition-transform duration-300"
            :class="{ 'rotate-180': open }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
    </button>
    <div
        x-show="open"
        x-transition
        class="mt-4 pt-4 border-t border-gray-200"
    >
        <p class="text-muted leading-relaxed">{{ $answer }}</p>
    </div>
</div>
