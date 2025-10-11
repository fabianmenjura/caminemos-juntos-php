// Inicializar AOS
AOS.init({
    duration: 1000,
    easing: 'ease-in-out',
    once: true
});

// Smooth scroll
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

// Función para corregir rutas de imágenes
function fixImagePath(url) {
    if (!url) return 'assets/images/placeholder.jpg';
    
    if (url.startsWith('/')) {
        const filename = url.substring(1);
        return `assets/images/${filename}`;
    }
    
    if (url.includes('assets/images/')) {
        return url;
    }
    
    return `assets/images/${url}`;
}

// Cargar Hero Slider
async function loadHeroSlider() {
    try {
        const response = await api.get('hero-slider');
        
        if (response.success && response.data.slides.length > 0) {
            const slides = response.data.slides;
            const carouselInner = document.querySelector('#heroCarousel .carousel-inner');
            const indicators = document.getElementById('heroIndicators');
            
            if (!carouselInner || !indicators) return;
            
            // Limpiar contenido existente
            carouselInner.innerHTML = '';
            indicators.innerHTML = '';
            
            // Crear slides
            slides.forEach((slide, index) => {
                const isActive = index === 0 ? 'active' : '';
                
                const slideHTML = `
                    <div class="carousel-item ${isActive}">
                        <div class="hero-slide">
                            <div class="hero-image-container">
                                <img src="${slide.imagen_url}" alt="${slide.titulo}" class="hero-bg-image">
                                <div class="hero-overlay"></div>
                            </div>
                            <div class="container">
                                <div class="row align-items-center min-vh-100">
                                    <div class="col-lg-6" data-aos="fade-right">
                                        <h1 class="hero-title">${slide.titulo}</h1>
                                        <p class="hero-subtitle">${slide.subtitulo || ''}</p>
                                        <div class="hero-buttons">
                                            <a href="${slide.enlace_url || '#donar'}" class="btn btn-primary btn-lg me-3">
                                                <i class="fas fa-heart me-2"></i> Haz tu Donación
                                            </a>
                                            <a href="#abuelos" class="btn btn-outline-light btn-lg">
                                                <i class="fas fa-users me-2"></i> Conoce los Abuelos
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                carouselInner.innerHTML += slideHTML;
                
                // Crear indicador
                const indicatorHTML = `
                    <button type="button" data-bs-target="#heroCarousel" 
                            data-bs-slide-to="${index}" ${isActive ? 'class="active"' : ''} 
                            aria-label="Slide ${index + 1}">
                    </button>
                `;
                indicators.innerHTML += indicatorHTML;
            });
        }
    } catch (error) {
        console.error('Error al cargar hero slider:', error);
    }
}

// Cargar abuelos
async function loadAbuelos() {
    try {
        const response = await api.getAbuelos();
        console.log('Abuelos cargados:', response);
        const abuelosContainer = document.getElementById('abuelos-container');
        const loadingEl = document.getElementById('abuelos-loading');

        if (response.success && response.data.abuelos) {
            const abuelos = response.data.abuelos.slice(0, 6);
            
            abuelosContainer.innerHTML = abuelos.map(abuelo => {
                const imageUrl = fixImagePath(abuelo.foto_url);
                return `
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                        <div class="card grandparent-card h-100">
                            <img src="${imageUrl}" 
                                 class="card-img-top" 
                                 alt="${abuelo.nombre}"
                                 onerror="this.src='assets/images/placeholder.jpg'">
                            <div class="card-body">
                                <h5 class="card-title">${abuelo.nombre}</h5>
                                <p class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    ${abuelo.ciudad} • ${abuelo.edad} años
                                </p>
                                <p class="card-text">${abuelo.descripcion.substring(0, 120)}...</p>
                                <a href="donar.html" class="btn btn-outline-primary btn-sm">
                                    Apoyar a ${abuelo.nombre.split(' ')[0]}
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            loadingEl.style.display = 'none';
            abuelosContainer.style.display = 'flex';
        }
    } catch (error) {
        console.error('Error cargando abuelos:', error);
        document.getElementById('abuelos-loading').innerHTML = `
            <div class="alert alert-danger">
                Error al cargar los abuelos. Por favor, intenta de nuevo.
            </div>
        `;
    }
}

// Formulario de contacto
const contactForm = document.getElementById('contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(contactForm);
        const data = Object.fromEntries(formData);

        const button = contactForm.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';

        try {
            const response = await api.sendContactMessage(data);
            
            if (response.success) {
                showToast('Mensaje enviado exitosamente. Te responderemos pronto.', 'success');
                contactForm.reset();
            }
        } catch (error) {
            showToast('Error al enviar el mensaje. Inténtalo de nuevo.', 'error');
        } finally {
            button.disabled = false;
            button.innerHTML = originalText;
        }
    });
}

// Toast notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : 'success'} toast-notification`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} me-2"></i>
        ${message}
    `;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideIn 0.3s ease-out;
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Cargar testimonios
async function loadTestimonios() {
    try {
        const response = await api.get('testimonios');
        
        if (response.success && response.data.testimonios.length > 0) {
            const container = document.getElementById('testimonios-container');
            
            container.innerHTML = response.data.testimonios.map(t => `
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3 text-warning">
                                ${'<i class="fas fa-star"></i>'.repeat(t.calificacion)}
                            </div>
                            <p class="mb-3">"${t.testimonio}"</p>
                            <div class="d-flex align-items-center mt-auto">
                                <div class="bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px; flex-shrink: 0;">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <strong>${t.nombre}</strong><br>
                                    <small class="text-muted">${t.rol}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error('Error al cargar testimonios:', error);
    }
}

// Cargar patrocinadores
async function loadPatrocinadores() {
    try {
        const sponsors = [
            { nombre: 'Fundación', logo: 'assets/images/company-logo-1.jpg' },
            { nombre: 'Empresa', logo: 'assets/images/company-logo-2.jpg' },
            { nombre: 'Grupo', logo: 'assets/images/company-logo-3.jpg' },
        ];
        
        const container = document.getElementById('patrocinadores-container');
        container.innerHTML = sponsors.map(s => `
            <div class="col-lg-3 col-md-4 col-6 mb-4" data-aos="zoom-in">
                <div class="p-3 text-center">
                    <img src="${s.logo}" 
                         class="img-fluid" 
                         alt="${s.nombre}"
                         style="max-height: 80px; object-fit: contain; filter: grayscale(100%); transition: filter 0.3s;"
                         onmouseover="this.style.filter='grayscale(0%)'"
                         onmouseout="this.style.filter='grayscale(100%)'">
                </div>
            </div>
        `).join('');
    } catch (error) {
        console.error('Error al cargar patrocinadores:', error);
    }
}

// Sistema de calificación con estrellas
document.addEventListener('DOMContentLoaded', () => {
    const stars = document.querySelectorAll('.star');
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-rating');
            document.getElementById('calificacion-input').value = rating;
            
            stars.forEach((s, index) => {
                if (index < rating) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });
    });
});

// Formulario de voluntarios
const voluntarioForm = document.getElementById('voluntario-form');
if (voluntarioForm) {
    voluntarioForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(voluntarioForm);
        const data = Object.fromEntries(formData);
        
        const button = voluntarioForm.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
        
        try {
            const response = await api.post('voluntarios', data);
            
            if (response.success) {
                showToast('¡Gracias! Tu solicitud ha sido recibida. Te contactaremos pronto.', 'success');
                voluntarioForm.reset();
            } else {
                showToast(response.message || 'Error al enviar', 'error');
            }
        } catch (error) {
            showToast('Error al enviar. Por favor intenta de nuevo.', 'error');
        } finally {
            button.disabled = false;
            button.innerHTML = originalText;
        }
    });
}

// Formulario de testimonios
const testimonioForm = document.getElementById('testimonio-form');
if (testimonioForm) {
    testimonioForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(testimonioForm);
        const data = Object.fromEntries(formData);
        
        const button = testimonioForm.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
        
        try {
            const response = await api.post('testimonios', data);
            
            if (response.success) {
                showToast('¡Gracias por compartir tu experiencia! Será publicado tras revisión.', 'success');
                testimonioForm.reset();
                
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('testimonioModal'));
                if (modal) modal.hide();
                
                // Resetear estrellas a 5
                document.querySelectorAll('.star').forEach(s => s.classList.add('active'));
                document.getElementById('calificacion-input').value = 5;
            } else {
                showToast(response.message || 'Error al enviar', 'error');
            }
        } catch (error) {
            showToast('Error al enviar. Por favor intenta de nuevo.', 'error');
        } finally {
            button.disabled = false;
            button.innerHTML = originalText;
        }
    });
}

// Cargar datos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    loadHeroSlider();
    loadAbuelos();
    loadTestimonios();
    loadPatrocinadores();
});
