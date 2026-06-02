/**
 * LRV Web - JavaScript Principal do Site
 */

'use strict';

// Scroll animations (Intersection Observer)
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('[data-animate]').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Back to top button
(function() {
    const btn = document.createElement('button');
    btn.innerHTML = '↑';
    btn.className = 'fixed bottom-6 right-6 w-10 h-10 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 transition opacity-0 pointer-events-none z-40';
    btn.id = 'back-to-top';
    btn.setAttribute('aria-label', 'Voltar ao topo');
    document.body.appendChild(btn);

    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            btn.classList.remove('opacity-0', 'pointer-events-none');
            btn.classList.add('opacity-100');
        } else {
            btn.classList.add('opacity-0', 'pointer-events-none');
            btn.classList.remove('opacity-100');
        }
    });
})();
