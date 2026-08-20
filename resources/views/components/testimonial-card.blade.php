<div class="card">
    <div class="flex items-center mb-4">
        @for ($i = 0; $i < $rating; $i++)
            <span class="text-yellow text-lg">★</span>
        @endfor
    </div>
    <p class="text-ink mb-6 leading-relaxed">{{ $content }}</p>
    <div class="border-t border-gray-200 pt-4">
        <p class="font-semibold text-ink">{{ $name }}</p>
        <p class="text-sm text-muted">{{ $role }}</p>
    </div>
</div>
