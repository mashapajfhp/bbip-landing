@php
    $number = config('services.whatsapp.number');
    $message = config('services.whatsapp.default_message');

    if (!$number) {
        return;
    }

    $href = 'https://wa.me/' . preg_replace('/\D/', '', $number) . '?text=' . urlencode($message);
@endphp

<a href="{{ $href }}"
   target="_blank"
   rel="noopener noreferrer"
   class="fixed bottom-6 right-6 w-14 h-14 md:w-16 md:h-16 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full shadow-lg flex items-center justify-center transition-colors duration-200 z-40"
   data-cta="whatsapp"
   data-location="floating"
   aria-label="Chat with us on WhatsApp"
   title="Chat with us on WhatsApp">
    <svg class="w-7 h-7 md:w-8 md:h-8" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
        <path d="M13.601 2.326A7.854 7.854 0 0 0 9.934.073a7.94 7.94 0 0 0-6.773 12.07L.05 16l3.953-1.167a7.93 7.93 0 0 0 3.93 1.007h.004a7.94 7.94 0 0 0 6.704-12.515zM9.935 14.521a6.57 6.57 0 0 1-3.356-.92l-.24-.144-2.35.693.627-2.293-.157-.247a6.57 6.57 0 1 1 5.476 2.91zm3.61-4.923c-.197-.099-1.17-.578-1.352-.643-.18-.066-.312-.099-.445.099-.132.197-.51.643-.625.775-.115.132-.23.148-.428.05-.197-.1-.832-.307-1.584-.98-.586-.525-.98-1.172-1.096-1.369-.115-.198-.012-.305.087-.404.089-.088.197-.23.296-.345.099-.115.132-.198.197-.33.066-.132.033-.247-.016-.345-.05-.099-.445-1.073-.61-1.47-.16-.389-.323-.336-.445-.342-.115-.006-.247-.007-.379-.007a.729.729 0 0 0-.526.247c-.181.198-.691.677-.691 1.654s.708 1.916.806 2.049c.099.132 1.392 2.125 3.374 2.981.471.203.839.324 1.125.414.472.15.901.129 1.24.078.378-.057 1.17-.478 1.335-.941.164-.462.164-.858.115-.941-.05-.082-.181-.132-.379-.23z"/>
    </svg>
</a>
