@php
    $number = config('services.whatsapp.number');
    $message = $message ?? config('services.whatsapp.default_message');

    if (!$number) {
        return;
    }

    $href = 'https://wa.me/' . preg_replace('/\D/', '', $number) . '?text=' . urlencode($message);
    $variant = $variant ?? 'default';
    $location = $location ?? '';

    $classes = match($variant) {
        'primary' => 'btn btn-primary',
        'light' => 'btn btn-light',
        'whatsapp' => 'btn btn-whatsapp',
        default => 'btn btn-light',
    };
@endphp

<a href="{{ $href }}"
   target="_blank"
   rel="noopener noreferrer"
   class="{{ $classes }} whatsapp-link"
   @if($location) data-location="{{ $location }}" @endif
   data-cta="whatsapp"
   aria-label="Chat with us on WhatsApp">
    <span class="wa-dot">◔</span>
    {{ $slot->isEmpty() ? 'Chat on WhatsApp' : $slot }}
</a>
