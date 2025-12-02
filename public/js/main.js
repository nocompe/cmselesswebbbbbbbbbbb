// Generar partículas animadas
const particlesContainer = document.getElementById('particles');
const particleCount = 50;

for (let i = 0; i < particleCount; i++) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    particle.style.left = Math.random() * 100 + '%';
    particle.style.animationDelay = Math.random() * 8 + 's';
    particle.style.animationDuration = (Math.random() * 5 + 5) + 's';
    particlesContainer.appendChild(particle);
}

// Efecto parallax suave
document.addEventListener('mousemove', (e) => {
    const waves = document.querySelectorAll('.wave');
    const glow = document.querySelector('.glow-effect');
    const shapes = document.querySelectorAll('.floating-shape');
    
    const mouseX = e.clientX / window.innerWidth;
    const mouseY = e.clientY / window.innerHeight;
    
    waves.forEach((wave, index) => {
        const speed = (index + 1) * 10;
        wave.style.transform = `translate(${mouseX * speed}px, ${mouseY * speed}px)`;
    });

    if (glow) {
        glow.style.left = (50 + mouseX * 10) + '%';
        glow.style.top = (50 + mouseY * 10) + '%';
    }

    shapes.forEach((shape, index) => {
        const speed = (index + 1) * 5;
        shape.style.transform = `translate(${mouseX * speed}px, ${mouseY * speed}px)`;
    });
});

// Control de pausa/play del video
const pauseBtn = document.getElementById('pauseBtn');
const demoVideo = document.getElementById('demoVideo');
let isPaused = false;

if (pauseBtn && demoVideo) {
    pauseBtn.addEventListener('click', function() {
        const icon = this.querySelector('i');
        
        if (isPaused) {
            demoVideo.play();
            icon.classList.remove('fa-play');
            icon.classList.add('fa-pause');
        } else {
            demoVideo.pause();
            icon.classList.remove('fa-pause');
            icon.classList.add('fa-play');
        }
        isPaused = !isPaused;
    });
}

// ========================================
// INTERSECTION OBSERVER PARA ANIMACIONES
// ========================================

// Configuración del Intersection Observer
const observerOptions = {
    root: null, // usa el viewport como root
    threshold: 0.2, // activa cuando el 20% del elemento es visible
    rootMargin: '0px 0px -100px 0px' // activa un poco antes de que llegue
};

// Callback que se ejecuta cuando el elemento es visible
const observerCallback = (entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            // Agregar la clase 'animate' al elemento
            entry.target.classList.add('animate');
            
            // Opcional: dejar de observar después de animar (animar solo una vez)
            // observer.unobserve(entry.target);
        }
    });
};

// Crear el observer
const observer = new IntersectionObserver(observerCallback, observerOptions);

// Observar elementos cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    // Observar la imagen de e-commerce
    const ecommerceImage = document.querySelector('.ecommerce-image');
    if (ecommerceImage) {
        observer.observe(ecommerceImage);
    }

    // Observar las cards de sistemas
    const systemCards = document.querySelectorAll('.system-card');
    systemCards.forEach(card => {
        observer.observe(card);
    });

    // Observar cards de visión y misión
    const vmCards = document.querySelectorAll('.vm-card');
    vmCards.forEach(card => {
        observer.observe(card);
    });

    // Observar valores
    const valueItems = document.querySelectorAll('.value-item');
    valueItems.forEach(item => {
        observer.observe(item);
    });
});

// ========================================
// EFECTO PARALLAX PARA CARDS DE SISTEMAS
// ========================================

const systemsGrid = document.getElementById('systemsGrid');

if (systemsGrid) {
    systemsGrid.addEventListener('mousemove', function(e) {
        const cards = document.querySelectorAll('.system-card');
        const rect = systemsGrid.getBoundingClientRect();
        
        // Calcular posición del mouse relativa al grid
        const mouseX = e.clientX - rect.left;
        const mouseY = e.clientY - rect.top;
        
        // Calcular posición normalizada (0 a 1)
        const xPercent = mouseX / rect.width;
        const yPercent = mouseY / rect.height;
        
        cards.forEach((card, index) => {
            // Diferentes intensidades de movimiento para cada card
            const intensity = (index + 1) * 15;
            
            // Calcular desplazamiento
            const moveX = (xPercent - 0.5) * intensity;
            const moveY = (yPercent - 0.5) * intensity;
            
            // Obtener el offset base de cada card
            let baseY = 0;
            if (index === 1) baseY = 60; // Card central más abajo
            
            // Aplicar transformación manteniendo la posición base
            if (card.classList.contains('animate')) {
                card.style.transform = `translate(${moveX}px, calc(${baseY}px + ${moveY}px))`;
            }
        });
    });

    // Resetear posición cuando el mouse sale del grid
    systemsGrid.addEventListener('mouseleave', function() {
        const cards = document.querySelectorAll('.system-card');
        cards.forEach((card, index) => {
            let baseY = 0;
            if (index === 1) baseY = 60;
            if (card.classList.contains('animate')) {
                card.style.transform = `translateY(${baseY}px)`;
            }
        });
    });
}

// Smooth scroll para los links de navegación
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
// ========================================
// ANIMACIÓN DE CONTADORES
// ========================================

function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200; // Velocidad de animación

    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const increment = target / speed;

        const updateCount = () => {
            const count = parseInt(counter.innerText);

            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(updateCount, 10);
            } else {
                counter.innerText = target;
            }
        };

        updateCount();
    });
}

// Ejecutar animación cuando la página carga
window.addEventListener('load', animateCounters);