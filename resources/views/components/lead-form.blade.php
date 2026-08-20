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
                <a href="#" class="btn btn-whatsapp whatsapp-link">
                    <span class="wa-dot">◔</span> Prefer WhatsApp? Chat now
                </a>
            </div>

            <div class="card border border-line p-7 shadow-lg">
                <form id="bbipForm" method="POST" target="hidden_iframe">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="text-xs font-black mb-1.5 block">Full name *</label>
                            <input id="name" type="text" required class="w-full border border-line rounded-2xl px-3.5 py-3 bg-soft outline-none focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-200 transition-colors">
                        </div>
                        <div>
                            <label for="email" class="text-xs font-black mb-1.5 block">Email address *</label>
                            <input id="email" type="email" required class="w-full border border-line rounded-2xl px-3.5 py-3 bg-soft outline-none focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-200 transition-colors">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="whatsapp" class="text-xs font-black mb-1.5 block">WhatsApp number *</label>
                        <input id="whatsapp" type="tel" required placeholder="+27..." class="w-full border border-line rounded-2xl px-3.5 py-3 bg-soft outline-none focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-200 transition-colors">
                    </div>

                    <div class="mb-4">
                        <label for="challenge" class="text-xs font-black mb-1.5 block">What would you most like BBIP to help with? *</label>
                        <textarea id="challenge" required class="w-full border border-line rounded-2xl px-3.5 py-3 bg-soft outline-none focus:border-blue-600 focus:bg-white focus:ring-1 focus:ring-blue-200 transition-colors min-h-24 resize-vertical"></textarea>
                    </div>

                    <label class="flex gap-2.5 text-xs text-muted font-medium mb-4">
                        <input type="checkbox" id="consent" required class="mt-1">
                        <span>I agree that BBIP may contact me using the details above about coaching programs and onboarding.</span>
                    </label>

                    <div class="flex gap-2.5 flex-wrap mb-3">
                        <button type="submit" class="btn btn-primary text-sm">Submit & Continue</button>
                        <button type="button" id="sendWhatsApp" class="btn btn-light text-sm">Send via WhatsApp</button>
                    </div>

                    <div id="statusBox" class="hidden p-3 bg-emerald-50 text-emerald-800 border border-emerald-300 rounded-2xl font-bold text-sm">
                        Thank you. Your enquiry has been submitted. The BBIP team will contact you shortly.
                    </div>
                </form>
                <iframe name="hidden_iframe" style="display:none;"></iframe>
            </div>
        </div>
    </div>
</section>

<script>
    const CONFIG = {
        WHATSAPP_NUMBER: "REPLACE_WITH_WHATSAPP_NUMBER",
        GOOGLE_FORM_ID: "REPLACE_WITH_GOOGLE_FORM_ID",
        GOOGLE_FORM_FIELDS: {
            name: "entry.REPLACE_NAME",
            email: "entry.REPLACE_EMAIL",
            whatsapp: "entry.REPLACE_WHATSAPP",
            challenge: "entry.REPLACE_CHALLENGE"
        }
    };

    function waUrl(message) {
        const n = CONFIG.WHATSAPP_NUMBER.replace(/\D/g, "");
        return `https://wa.me/${n}?text=${encodeURIComponent(message)}`;
    }

    document.querySelectorAll(".whatsapp-link").forEach(a => {
        a.href = waUrl("Hello BBIP, I would like to know more about your coaching programs and platform.");
    });

    const form = document.getElementById("bbipForm");
    const statusBox = document.getElementById("statusBox");

    function getFormValues() {
        return {
            name: document.getElementById("name").value.trim(),
            email: document.getElementById("email").value.trim(),
            whatsapp: document.getElementById("whatsapp").value.trim(),
            challenge: document.getElementById("challenge").value.trim()
        };
    }

    form.addEventListener("submit", e => {
        const missing = CONFIG.GOOGLE_FORM_ID.includes("REPLACE_") ||
            Object.values(CONFIG.GOOGLE_FORM_FIELDS).some(v => v.includes("REPLACE_"));
        if (missing) {
            e.preventDefault();
            alert("Google Form connection is not configured yet. Replace the GOOGLE_FORM_ID and entry IDs in the CONFIG section.");
            return;
        }

        const v = getFormValues();
        form.action = `https://docs.google.com/forms/d/e/${CONFIG.GOOGLE_FORM_ID}/formResponse`;
        form.querySelectorAll("[data-google-field]").forEach(el => el.remove());

        Object.entries(CONFIG.GOOGLE_FORM_FIELDS).forEach(([key, entryName]) => {
            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = entryName;
            hidden.value = v[key] || "";
            hidden.setAttribute("data-google-field", "true");
            form.appendChild(hidden);
        });

        setTimeout(() => statusBox.classList.remove("hidden"), 650);
    });

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
</script>
