<section class="relative z-1 py-20 md:py-24">
    <div class="container">
        <span class="section-badge">CHOOSE YOUR PATH</span>
        <h2 class="section-title">Discover the right program for your child</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Academic Coaching -->
            <article class="card border border-line p-6 relative overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-green"></div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-emerald-400 to-green text-white text-2xl font-black flex items-center justify-center mx-auto mb-4 shadow-md">▤</div>
                <h3 class="text-xl font-bold text-center text-ink mb-3">Academic Coaching</h3>
                <p class="text-sm text-muted text-center mb-5">Build strong study skills, improve performance and achieve academic goals.</p>
                <button class="btn-link text-green font-bold text-sm mx-auto block hover:opacity-80 program-btn" data-program="Academic Coaching">Learn more →</button>
            </article>

            <!-- Remedial Academic Coaching -->
            <article class="card border border-line p-6 relative overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-red"></div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-400 to-red text-white text-2xl font-black flex items-center justify-center mx-auto mb-4 shadow-md">⌂</div>
                <h3 class="text-xl font-bold text-center text-ink mb-3">Remedial Academic Coaching</h3>
                <p class="text-sm text-muted text-center mb-5">Targeted support for learners who need help catching up and staying on track.</p>
                <button class="btn-link text-red font-bold text-sm mx-auto block hover:opacity-80 program-btn" data-program="Remedial Academic Coaching">Learn more →</button>
            </article>

            <!-- Self Empowerment -->
            <article class="card border border-line p-6 relative overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-600"></div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-white text-2xl font-black flex items-center justify-center mx-auto mb-4 shadow-md">★</div>
                <h3 class="text-xl font-bold text-center text-ink mb-3">Self Empowerment</h3>
                <p class="text-sm text-muted text-center mb-5">Develop confidence, better habits and the mindset to win.</p>
                <button class="btn-link text-blue-600 font-bold text-sm mx-auto block hover:opacity-80 program-btn" data-program="Self Empowerment">Learn more →</button>
            </article>

            <!-- Leadership Coaching -->
            <article class="card border border-line p-6 relative overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                <div class="absolute bottom-0 left-0 right-0 h-1 bg-yellow"></div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-400 to-yellow text-white text-2xl font-black flex items-center justify-center mx-auto mb-4 shadow-md">●</div>
                <h3 class="text-xl font-bold text-center text-ink mb-3">Leadership Coaching</h3>
                <p class="text-sm text-muted text-center mb-5">Grow as a leader, build character and prepare to make an impact.</p>
                <button class="btn-link text-yellow font-bold text-sm mx-auto block hover:opacity-80 program-btn" data-program="Leadership Coaching">Learn more →</button>
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
