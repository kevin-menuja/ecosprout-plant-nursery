document.addEventListener('DOMContentLoaded', () => {
    const fallbackImage = 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?auto=format&fit=crop&w=900&q=80';
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('img').forEach((image) => {
        image.addEventListener('error', () => {
            if (image.src !== fallbackImage) {
                image.src = fallbackImage;
            }
        });
    });

    if (!prefersReducedMotion) {
        const hero = document.querySelector('.hero');

        if (hero) {
            for (let index = 0; index < 12; index += 1) {
                const leaf = document.createElement('i');
                leaf.className = 'fa-solid fa-leaf leaf-particle';
                leaf.style.setProperty('--leaf-left', `${Math.random() * 100}%`);
                leaf.style.setProperty('--leaf-drift', `${Math.random() * 120 - 60}px`);
                leaf.style.setProperty('--leaf-rotate', `${Math.random() * 180}deg`);
                leaf.style.animationDelay = `${Math.random() * 9}s`;
                leaf.style.animationDuration = `${10 + Math.random() * 9}s`;
                hero.appendChild(leaf);
            }

            hero.addEventListener('mousemove', (event) => {
                const x = (event.clientX / window.innerWidth - 0.5) * 10;
                const y = (event.clientY / window.innerHeight - 0.5) * 8;
                hero.style.backgroundPosition = `calc(50% + ${x}px) calc(50% + ${y}px)`;
            });

            hero.addEventListener('mouseleave', () => {
                hero.style.backgroundPosition = '';
            });
        }

        const animatedItems = document.querySelectorAll('.section-title, .plant-card, .service-card, .workshop-card, .blog-card, .dashboard-tile, .auth-panel, .table-responsive');
        animatedItems.forEach((item) => item.classList.add('reveal-on-scroll'));

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });

        animatedItems.forEach((item, index) => {
            item.style.transitionDelay = `${Math.min(index % 4, 3) * 80}ms`;
            revealObserver.observe(item);
        });

        document.querySelectorAll('.dashboard-tile i').forEach((icon, index) => {
            icon.classList.add('pulse-icon');
            icon.style.animationDelay = `${index * 120}ms`;
        });
    }

    const forms = document.querySelectorAll('.needs-validation');

    forms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        });
    });

    const searchInput = document.querySelector('[data-plant-search]');
    const categorySelect = document.querySelector('[data-category-filter]');
    const cards = document.querySelectorAll('[data-plant-card]');

    function filterPlants() {
        const term = (searchInput?.value || '').toLowerCase();
        const category = categorySelect?.value || '';

        cards.forEach((card) => {
            const name = card.dataset.name.toLowerCase();
            const cardCategory = card.dataset.category;
            const matchesTerm = !term || name.includes(term);
            const matchesCategory = !category || cardCategory === category;
            card.classList.toggle('d-none', !(matchesTerm && matchesCategory));
        });
    }

    searchInput?.addEventListener('input', filterPlants);
    categorySelect?.addEventListener('change', filterPlants);
});
