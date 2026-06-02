/**
 * LRV Web - Frontend Premium
 */
'use strict';

// === SCROLL ANIMATIONS (Intersection Observer) ===
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Stagger delay based on index within viewport batch
                const delay = entry.target.dataset.delay || (index * 100);
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, delay);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
});

// === SMOOTH SCROLL ===
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});

// === COUNTER ANIMATION ===
function animateCounters() {
    document.querySelectorAll('[data-count]').forEach(el => {
        const target = parseInt(el.dataset.count);
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            el.textContent = Math.floor(current).toLocaleString('pt-BR');
        }, 16);
    });
}

// Trigger counter when visible
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounters();
            counterObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

const statsSection = document.querySelector('[data-counter-section]');
if (statsSection) counterObserver.observe(statsSection);

// === TYPED TEXT EFFECT ===
function typeText(element, texts, speed = 80, pause = 2000) {
    let textIndex = 0;
    let charIndex = 0;
    let deleting = false;

    function tick() {
        const current = texts[textIndex];
        if (deleting) {
            element.textContent = current.substring(0, charIndex--);
            if (charIndex < 0) {
                deleting = false;
                textIndex = (textIndex + 1) % texts.length;
                setTimeout(tick, 500);
                return;
            }
        } else {
            element.textContent = current.substring(0, charIndex++);
            if (charIndex > current.length) {
                deleting = true;
                setTimeout(tick, pause);
                return;
            }
        }
        setTimeout(tick, deleting ? speed / 2 : speed);
    }
    tick();
}

const typedEl = document.getElementById('typed-text');
if (typedEl) {
    const texts = JSON.parse(typedEl.dataset.texts || '[]');
    if (texts.length) typeText(typedEl, texts);
}

// === PARALLAX on mouse move ===
document.addEventListener('mousemove', (e) => {
    const parallaxEls = document.querySelectorAll('[data-parallax]');
    const x = (e.clientX / window.innerWidth - 0.5) * 2;
    const y = (e.clientY / window.innerHeight - 0.5) * 2;

    parallaxEls.forEach(el => {
        const speed = el.dataset.parallax || 20;
        el.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
    });
});

// === BACK TO TOP ===
(function() {
    const btn = document.createElement('button');
    btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>';
    btn.className = 'fixed bottom-8 right-8 w-12 h-12 bg-purple-600 hover:bg-purple-500 text-white rounded-xl shadow-lg shadow-purple-500/30 flex items-center justify-center transition-all duration-300 opacity-0 translate-y-4 pointer-events-none z-40';
    btn.id = 'back-to-top';
    btn.setAttribute('aria-label', 'Voltar ao topo');
    document.body.appendChild(btn);

    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    window.addEventListener('scroll', () => {
        if (window.scrollY > 500) {
            btn.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
            btn.classList.add('opacity-100', 'translate-y-0');
        } else {
            btn.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
            btn.classList.remove('opacity-100', 'translate-y-0');
        }
    });
})();
