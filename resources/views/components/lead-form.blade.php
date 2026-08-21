<section id="lead-form" class="relative z-1 py-20 md:py-28 bg-soft border-t border-line">
    <div class="container">
        <div class="grid lg:grid-cols-2 gap-9 items-start">
            <div class="form-intro">
                <span class="badge">
                    <span class="spark">✦</span>
                    START YOUR JOURNEY
                </span>
                <h2 class="text-4xl md:text-5xl font-bold text-ink mt-3.5 mb-3">Tell us what you need.</h2>
                <p class="text-lg text-muted max-w-md mb-8">
                    Complete this short form and the BBIP team will contact you on WhatsApp to guide you through the most suitable program and onboarding.
                </p>
                <x-whatsapp-button variant="whatsapp" location="lead-form">Prefer WhatsApp? Chat now</x-whatsapp-button>
            </div>

            <div class="card border border-line p-7 shadow-lg">
                <form id="bbipForm" method="POST" action="{{ route('leads.store') }}">
                    @csrf
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="text-xs font-black mb-1.5 block">Full name *</label>
                            <input id="name" type="text" name="name" required class="w-full border border-line rounded-2xl px-3.5 py-3 bg-soft outline-none focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-200 transition-colors" value="{{ old('name') }}">
                            @error('name')<span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>@enderror
                        </div>
                        <div>
                            <label for="email" class="text-xs font-black mb-1.5 block">Email address *</label>
                            <input id="email" type="email" name="email" required class="w-full border border-line rounded-2xl px-3.5 py-3 bg-soft outline-none focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-200 transition-colors" value="{{ old('email') }}">
                            @error('email')<span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="whatsapp" class="text-xs font-black mb-1.5 block">WhatsApp number *</label>
                        <input id="whatsapp" type="tel" name="whatsapp" required placeholder="+27..." class="w-full border border-line rounded-2xl px-3.5 py-3 bg-soft outline-none focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-200 transition-colors" value="{{ old('whatsapp') }}">
                        @error('whatsapp')<span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div class="mb-4">
                        <label for="challenge" class="text-xs font-black mb-1.5 block">What would you most like BBIP to help with? *</label>
                        <textarea id="challenge" name="challenge" required class="w-full border border-line rounded-2xl px-3.5 py-3 bg-soft outline-none focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-200 transition-colors min-h-24 resize-vertical">{{ old('challenge') }}</textarea>
                        @error('challenge')<span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <label class="flex gap-2.5 text-xs text-muted font-medium mb-4">
                        <input type="checkbox" name="consent" id="consent" required class="mt-1" {{ old('consent') ? 'checked' : '' }}>
                        <span>I agree that BBIP may contact me using the details above about coaching programs and onboarding.</span>
                    </label>
                    @error('consent')<span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>@enderror

                    <div class="flex gap-2.5 flex-wrap mb-3">
                        <button type="submit" id="submitBtn" class="btn btn-primary text-sm">
                            <span id="submitText">Submit & Continue</span>
                            <span id="submittingText" class="hidden">Submitting...</span>
                        </button>
                        @if(config('services.whatsapp.number'))
                            <button type="button" id="sendWhatsApp" class="btn btn-light text-sm">Send via WhatsApp</button>
                        @endif
                    </div>

                    <div id="successBox" class="hidden p-3 bg-emerald-50 text-emerald-800 border border-emerald-300 rounded-2xl font-bold text-sm">
                        Thank you. Your enquiry has been submitted. The BBIP team will contact you shortly.
                    </div>
                    <div id="errorBox" class="hidden p-3 bg-red-50 text-red-800 border border-red-300 rounded-2xl font-bold text-sm"></div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    @if(config('services.whatsapp.number'))
    const CONFIG = {
        WHATSAPP_NUMBER: @json(config('services.whatsapp.number'))
    };

    function waUrl(message) {
        const n = CONFIG.WHATSAPP_NUMBER.replace(/\D/g, "");
        return `https://wa.me/${n}?text=${encodeURIComponent(message)}`;
    }
    @endif

    const form = document.getElementById("bbipForm");
    const submitBtn = document.getElementById("submitBtn");
    const submitText = document.getElementById("submitText");
    const submittingText = document.getElementById("submittingText");
    const successBox = document.getElementById("successBox");
    const errorBox = document.getElementById("errorBox");
    let isSubmitting = false;

    function getFormValues() {
        return {
            name: document.getElementById("name").value.trim(),
            email: document.getElementById("email").value.trim(),
            whatsapp: document.getElementById("whatsapp").value.trim(),
            challenge: document.getElementById("challenge").value.trim()
        };
    }

    form.addEventListener("submit", async e => {
        e.preventDefault();

        if (isSubmitting) return;

        if (!form.reportValidity()) return;

        isSubmitting = true;
        submitBtn.disabled = true;
        submitText.classList.add("hidden");
        submittingText.classList.remove("hidden");
        errorBox.classList.add("hidden");
        successBox.classList.add("hidden");

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                successBox.classList.remove("hidden");
                form.reset();
                setTimeout(() => {
                    successBox.classList.add("hidden");
                }, 5000);
            } else {
                errorBox.textContent = data.message || "An error occurred. Please try again.";
                errorBox.classList.remove("hidden");
            }
        } catch (error) {
            console.error("Submission error:", error);
            errorBox.textContent = "We couldn't submit your request right now. Please try again.";
            errorBox.classList.remove("hidden");
        } finally {
            isSubmitting = false;
            submitBtn.disabled = false;
            submitText.classList.remove("hidden");
            submittingText.classList.add("hidden");
        }
    });

    @if(config('services.whatsapp.number'))
    document.getElementById("sendWhatsApp").addEventListener("click", () => {
        if (!form.reportValidity()) return;
        const v = getFormValues();
        const msg = `Hello BBIP,

Name: ${v.name}
Email: ${v.email}
WhatsApp: ${v.whatsapp}

What I need help with:
${v.challenge}`;
        window.open(waUrl(msg), "_blank", "noopener");
    });
    @endif
</script>
