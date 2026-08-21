<section id="programs" class="relative z-1 pt-12 pb-20 md:pt-14 md:pb-24">
    <div class="container">
        <span class="section-badge">CHOOSE YOUR PATH</span>
        <h2 class="section-title">Discover the right development pathway</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Academic Coaching -->
            <article class="card border border-line p-6 relative overflow-hidden shadow-md hover:shadow-lg transition-shadow flex flex-col">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-green"></div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-green text-white text-2xl font-black flex items-center justify-center mx-auto mb-4 shadow-md">▤</div>
                <h3 class="text-xl font-bold text-center text-ink mb-3">Academic Coaching</h3>
                <p class="text-sm text-muted text-center mb-5 flex-1">Build strong study skills, improve performance and achieve academic goals.</p>
                <button class="inline-flex items-center justify-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-green font-bold text-sm mx-auto shadow-sm transition-all hover:bg-emerald-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-300 program-btn" data-program="Academic Coaching">Learn more <span aria-hidden="true">→</span></button>
            </article>

            <!-- Remedial Academic Coaching -->
            <article class="card border border-line p-6 relative overflow-hidden shadow-md hover:shadow-lg transition-shadow flex flex-col">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-red"></div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-400 to-red text-white text-2xl font-black flex items-center justify-center mx-auto mb-4 shadow-md">⌂</div>
                <h3 class="text-xl font-bold text-center text-ink mb-3">Remedial Academic Coaching</h3>
                <p class="text-sm text-muted text-center mb-5 flex-1">Targeted support for learners who need help catching up and staying on track.</p>
                <button class="inline-flex items-center justify-center gap-2 rounded-full border border-red-200 bg-red-50 px-4 py-2 text-red font-bold text-sm mx-auto shadow-sm transition-all hover:bg-red-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-300 program-btn" data-program="Remedial Academic Coaching">Learn more <span aria-hidden="true">→</span></button>
            </article>

            <!-- Self Empowerment -->
            <article class="card border border-line p-6 relative overflow-hidden shadow-md hover:shadow-lg transition-shadow flex flex-col">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-600"></div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-white text-2xl font-black flex items-center justify-center mx-auto mb-4 shadow-md">★</div>
                <h3 class="text-xl font-bold text-center text-ink mb-3">Self Empowerment</h3>
                <p class="text-sm text-muted text-center mb-5 flex-1">Develop confidence, better habits and the mindset to win.</p>
                <button class="inline-flex items-center justify-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-blue-600 font-bold text-sm mx-auto shadow-sm transition-all hover:bg-blue-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-300 program-btn" data-program="Self Empowerment">Learn more <span aria-hidden="true">→</span></button>
            </article>

            <!-- Leadership Coaching -->
            <article class="card border border-line p-6 relative overflow-hidden shadow-md hover:shadow-lg transition-shadow flex flex-col">
                <div class="absolute bottom-0 left-0 right-0 h-1.5 bg-amber-400"></div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-yellow text-white text-2xl font-black flex items-center justify-center mx-auto mb-4 shadow-md">●</div>
                <h3 class="text-xl font-bold text-center text-ink mb-3">Leadership Coaching</h3>
                <p class="text-sm text-muted text-center mb-5 flex-1">Grow as a leader, build character and prepare to make an impact.</p>
                <button class="inline-flex items-center justify-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-amber-700 font-bold text-sm mx-auto shadow-sm transition-all hover:bg-amber-100 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-amber-300 program-btn" data-program="Leadership Coaching">Learn more <span aria-hidden="true">→</span></button>
            </article>
        </div>
    </div>
</section>

<script>
    document.querySelectorAll('.program-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const programName = btn.dataset.program;
            const programSelect = document.getElementById('program');
            if (programSelect) {
                programSelect.value = programName;
                const formSection = document.getElementById('lead-form');
                if (formSection) {
                    formSection.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });
</script>
