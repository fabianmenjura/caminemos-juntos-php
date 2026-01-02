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
                const cumpleHoy = abuelo.cumple_hoy == 1;
                const cumpleEstaSemana = abuelo.cumple_esta_semana == 1;
                
                const totalMensajes = abuelo.total_mensajes || 0;
                const mensajes = abuelo.mensajes_cumpleanos || [];
                
                return `
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                        <div class="card grandparent-card h-100 ${cumpleHoy ? 'border-warning border-3' : ''}">
                            ${cumpleHoy ? '<div class="ribbon-birthday">🎂 ¡Cumpleaños Hoy!</div>' : ''}
                            ${!cumpleHoy && cumpleEstaSemana ? '<div class="ribbon-birthday-soon">🎈 Cumple esta semana</div>' : ''}
                            <img src="${imageUrl}" 
                                 class="card-img-top" 
                                 alt="${abuelo.nombre}, ${abuelo.edad} años, adulto mayor en ${abuelo.ciudad}"
                                 loading="lazy"
                                 onerror="this.src='assets/images/placeholder.jpg'">
                            <div class="card-body">
                                <h5 class="card-title">${abuelo.nombre}</h5>
                                <p class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    ${abuelo.ciudad} • ${abuelo.edad} años
                                </p>
                                <p class="card-text">${abuelo.descripcion.substring(0, 120)}...</p>
                                
                                ${totalMensajes > 0 ? `
                                    <div class="alert alert-warning py-2 px-3 mb-3">
                                        <strong>💌 ${totalMensajes} felicitación${totalMensajes > 1 ? 'es' : ''}</strong>
                                        <button class="btn btn-sm btn-link p-0 ms-2" onclick="verMensajesCumpleanos(${abuelo.id}, '${abuelo.nombre}', ${JSON.stringify(mensajes).replace(/"/g, '&quot;')})">
                                            Ver mensajes
                                        </button>
                                    </div>
                                ` : ''}
                                
                                <div class="d-flex gap-2">
                                    <a href="donar.html" class="btn btn-outline-primary btn-sm flex-grow-1">
                                        Apoyar a ${abuelo.nombre.split(' ')[0]}
                                    </a>
                                    ${(cumpleHoy || cumpleEstaSemana) ? `
                                        <button class="btn btn-warning btn-sm" onclick="mostrarModalCumpleanos(${abuelo.id}, '${abuelo.nombre}')" title="Enviar felicitación">
                                            🎂
                                        </button>
                                    ` : ''}
                                </div>
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
                showSuccess('¡Gracias por contactarnos! Te responderemos pronto.', '¡Mensaje Enviado!');
                contactForm.reset();
            }
        } catch (error) {
            showError('No pudimos enviar tu mensaje. Por favor, intenta de nuevo.', 'Error al Enviar');
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

// Variables globales para el carousel
let currentGallerySlide = 0;
let galleryImages = [];
let galleryItemsPerView = 4;

// Ajustar items por vista según el tamaño de pantalla
function updateGalleryItemsPerView() {
    if (window.innerWidth <= 576) {
        galleryItemsPerView = 2;
    } else if (window.innerWidth <= 768) {
        galleryItemsPerView = 3;
    } else {
        galleryItemsPerView = 4;
    }
}

// Cargar galería
async function loadGaleria() {
    try {
        const response = await api.get('galeria');
        
        if (response.success && response.data.imagenes) {
            const galeriaContainer = document.getElementById('galeria-container');
            const galeriaLoading = document.getElementById('galeria-loading');
            
            if (galeriaLoading) galeriaLoading.style.display = 'none';
            
            galleryImages = response.data.imagenes || [];
            
            
            if (galleryImages.length === 0) {
                if (galeriaContainer) {
                    galeriaContainer.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Pronto compartiremos momentos especiales</p>
                        </div>
                    `;
                    galeriaContainer.style.display = 'block';
                }
                return;
            }
            
            if (galeriaContainer) {
                updateGalleryItemsPerView();
                renderGallery();
                galeriaContainer.style.display = 'block';
                
                // Ajustar items por vista al cambiar el tamaño de ventana (solo una vez)
                if (!window.galleryResizeHandler) {
                    window.galleryResizeHandler = () => {
                        updateGalleryItemsPerView();
                        renderGallery();
                        goToGallerySlide(0);
                    };
                    window.addEventListener('resize', window.galleryResizeHandler);
                }
            }
        } else {
            console.error('Respuesta de galería inválida:', response);
            const galeriaLoading = document.getElementById('galeria-loading');
            if (galeriaLoading) galeriaLoading.style.display = 'none';
        }
    } catch (error) {
        console.error('Error al cargar galería:', error);
        const galeriaLoading = document.getElementById('galeria-loading');
        if (galeriaLoading) galeriaLoading.style.display = 'none';
    }
}

// Renderizar galería
function renderGallery() {
    const galeriaTrack = document.getElementById('galeria-track');
    const galeriaDots = document.getElementById('galeria-dots');
    
    if (!galeriaTrack || !galeriaDots) return;
    
    // Renderizar items
    const itemsHTML = galleryImages.map((img, index) => {
        if (!img.imagen_url) {
            return null; // Saltar items sin imagen
        }
        
        const imageUrl = fixImagePath(img.imagen_url);
        return `
            <div class="gallery-item" data-index="${index}">
                <img src="${imageUrl}" 
                     alt="Imagen de la galería" 
                     loading="lazy"
                     onclick="openLightbox('${imageUrl}', '', ${index})">
            </div>
        `;
    }).filter(item => item !== null && item !== '').join('');
    
    galeriaTrack.innerHTML = itemsHTML;
    
    // Renderizar dots (usar la cantidad real de items renderizados)
    const renderedItems = galeriaTrack.querySelectorAll('.gallery-item').length;
    const totalSlides = Math.ceil(galleryImages.length / galleryItemsPerView);
    galeriaDots.innerHTML = Array.from({ length: totalSlides }, (_, index) => `
        <div class="gallery-carousel-dot ${index === 0 ? 'active' : ''}" 
             onclick="goToGallerySlide(${index})" 
             data-slide="${index}"></div>
    `).join('');
    
    // Actualizar posición inicial
    goToGallerySlide(0);
}

// Ir a un slide específico
function goToGallerySlide(slideIndex) {
    const galeriaTrack = document.getElementById('galeria-track');
    const galeriaDots = document.getElementById('galeria-dots');
    
    if (!galeriaTrack || !galeriaDots) return;
    
    const totalSlides = Math.ceil(galleryImages.length / galleryItemsPerView);
    
    if (slideIndex < 0) slideIndex = totalSlides - 1;
    if (slideIndex >= totalSlides) slideIndex = 0;
    
    currentGallerySlide = slideIndex;
    
    // Calcular transform
    const translateX = -(slideIndex * (100 / galleryItemsPerView));
    galeriaTrack.style.transform = `translateX(${translateX}%)`;
    
    // Actualizar dots
    const dots = galeriaDots.querySelectorAll('.gallery-carousel-dot');
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === slideIndex);
    });
}

// Cambiar slide
function changeGallerySlide(direction) {
    const totalSlides = Math.ceil(galleryImages.length / galleryItemsPerView);
    goToGallerySlide(currentGallerySlide + direction);
}

// Lightbox para galería
let currentLightboxIndex = 0;
let lightboxImages = [];

function openLightbox(imageUrl, title, index) {
    currentLightboxIndex = index;
    const galeriaContainer = document.getElementById('galeria-container');
    if (galeriaContainer) {
        const items = galeriaContainer.querySelectorAll('.gallery-item img');
        lightboxImages = Array.from(items).map(img => ({
            src: img.src,
            alt: img.alt
        }));
    }
    
    const lightbox = document.getElementById('galeria-lightbox');
    if (lightbox) {
        const lightboxImg = lightbox.querySelector('#lightbox-image');
        const lightboxTitle = lightbox.querySelector('#lightbox-title');
        if (lightboxImg) lightboxImg.src = imageUrl;
        if (lightboxTitle) lightboxTitle.textContent = title || '';
        
        const bsModal = new bootstrap.Modal(lightbox);
        bsModal.show();
    }
}

function changeLightboxImage(direction) {
    if (lightboxImages.length === 0) return;
    
    currentLightboxIndex += direction;
    if (currentLightboxIndex < 0) currentLightboxIndex = lightboxImages.length - 1;
    if (currentLightboxIndex >= lightboxImages.length) currentLightboxIndex = 0;
    
    const lightboxImg = document.getElementById('lightbox-image');
    if (lightboxImg) {
        lightboxImg.src = lightboxImages[currentLightboxIndex].src;
        lightboxImg.alt = lightboxImages[currentLightboxIndex].alt;
    }
}

// Cargar patrocinadores
async function loadPatrocinadores() {
    try {
        const response = await api.get('patrocinadores');
        
        if (response.success && response.data.patrocinadores.length > 0) {
            const patrocinadores = response.data.patrocinadores;
            const container = document.getElementById('patrocinadores-container');
            
            container.innerHTML = patrocinadores.map(p => `
                <div class="col-lg-3 col-md-4 col-6 mb-4" data-aos="zoom-in">
                    <div class="p-3 text-center">
                        ${p.sitio_web ? `<a href="${p.sitio_web}" target="_blank" rel="noopener">` : ''}
                            <img src="${p.logo_url}" 
                                 class="img-fluid" 
                                 alt="${p.nombre_empresa}"
                                 title="${p.descripcion || p.nombre_empresa}"
                                 style="max-height: 80px; object-fit: contain; filter: grayscale(100%); transition: filter 0.3s;"
                                 onmouseover="this.style.filter='grayscale(0%)'"
                                 onmouseout="this.style.filter='grayscale(100%)'">
                        ${p.sitio_web ? '</a>' : ''}
                        <p class="small text-muted mt-2 mb-0">${p.nombre_empresa}</p>
                    </div>
                </div>
            `).join('');
        }
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
                showSuccess('¡Tu solicitud ha sido recibida! Te contactaremos pronto para coordinar.', '¡Gracias por Unirte!');
                voluntarioForm.reset();
            } else {
                showError(response.message || 'No pudimos procesar tu solicitud.', 'Error al Enviar');
            }
        } catch (error) {
            showError('Ocurrió un error al enviar. Por favor, intenta de nuevo.', 'Error de Conexión');
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
                showSuccess('Tu testimonio será publicado después de revisión. ¡Gracias por compartir!', '¡Testimonio Recibido!');
                testimonioForm.reset();
                
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('testimonioModal'));
                if (modal) modal.hide();
                
                // Resetear estrellas a 5
                document.querySelectorAll('.star').forEach(s => s.classList.add('active'));
                document.getElementById('calificacion-input').value = 5;
            } else {
                showError(response.message || 'No pudimos guardar tu testimonio.', 'Error');
            }
        } catch (error) {
            showError('Ocurrió un error al enviar. Por favor, intenta de nuevo.', 'Error de Conexión');
        } finally {
            button.disabled = false;
            button.innerHTML = originalText;
        }
    });
}

// Modal de cumpleaños
function mostrarModalCumpleanos(abueloId, nombreAbuelo) {
    document.getElementById('abueloIdCumpleanos').value = abueloId;
    document.getElementById('nombreAbueloCumpleanos').textContent = nombreAbuelo;
    
    const modal = new bootstrap.Modal(document.getElementById('cumpleanosModal'));
    modal.show();
}

// Ver mensajes de cumpleaños
function verMensajesCumpleanos(abueloId, nombreAbuelo, mensajes) {
    const modalBody = document.getElementById('mensajesCumpleanosBody');
    
    if (mensajes.length === 0) {
        modalBody.innerHTML = '<p class="text-muted">Aún no hay mensajes aprobados.</p>';
    } else {
        modalBody.innerHTML = `
            <div class="text-center mb-3">
                <h6>💌 Mensajes para ${nombreAbuelo}</h6>
            </div>
            ${mensajes.map(m => `
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="mb-2">"${m.mensaje}"</p>
                        <div class="text-end">
                            <small class="text-muted">
                                — ${m.nombre_remitente}
                                ${m.fecha_envio ? ` • ${new Date(m.fecha_envio).toLocaleDateString('es-CO')}` : ''}
                            </small>
                        </div>
                    </div>
                </div>
            `).join('')}
        `;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('verMensajesModal'));
    modal.show();
}

// Formulario de mensaje de cumpleaños
const cumpleanosForm = document.getElementById('cumpleanos-form');
if (cumpleanosForm) {
    cumpleanosForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(cumpleanosForm);
        const data = Object.fromEntries(formData);
        data.abuelo_id = document.getElementById('abueloIdCumpleanos').value;
        
        const button = cumpleanosForm.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
        
        try {
            const response = await api.post('cumpleanos', data);
            
            if (response.success) {
                showSuccess('Tu felicitación será entregada al abuelo después de revisión. ¡Gracias por alegrar su día! 🎂', '¡Mensaje Enviado!');
                cumpleanosForm.reset();
                
                const modal = bootstrap.Modal.getInstance(document.getElementById('cumpleanosModal'));
                if (modal) modal.hide();
            } else {
                showError(response.message || 'No pudimos enviar tu mensaje.', 'Error');
            }
        } catch (error) {
            showError('Ocurrió un error al enviar. Por favor, intenta de nuevo.', 'Error de Conexión');
        } finally {
            button.disabled = false;
            button.innerHTML = originalText;
        }
    });
}

// Cargar categorías en el select del formulario
function loadCategoriasEnSelect(categorias) {
    const select = document.getElementById('tipo-voluntariado-select');
    if (!select) return;
    
    if (categorias.length === 0) {
        select.innerHTML = `
            <option value="">No hay opciones disponibles</option>
            <option value="otros">Otros</option>
        `;
        return;
    }
    
    select.innerHTML = `
        <option value="">Selecciona una opción...</option>
        ${categorias.map(cat => `
            <option value="${cat.slug}">${cat.titulo}</option>
        `).join('')}
        <option value="varios">Varias opciones</option>
    `;
}

// Cargar categorías de voluntariado
async function loadCategoriasVoluntariado() {
    const container = document.getElementById('categorias-voluntariado-container');
    if (!container) return;

    try {
        const response = await api.get('categorias-voluntariado');
        
        if (response.success) {
            const categorias = response.data.categorias;
            
            // Cargar también en el select del formulario
            loadCategoriasEnSelect(categorias);
            
            // Si no hay categorías, mostrar mensaje
            if (categorias.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Aún no hay formas de voluntariado disponibles.</p>
                        <p class="text-muted small">El administrador agregará las opciones pronto.</p>
                    </div>
                `;
                return;
            }
            
            // Renderizar categorías
            container.innerHTML = categorias.map((cat, index) => {
                const isReverse = index % 2 !== 0; // Alternar imagen izq/der
                const isFeatured = cat.destacado == 1;
                const imageUrl = cat.imagen_url || '/placeholder.svg?height=400&width=500';
                
                const caracteristicas = Array.isArray(cat.caracteristicas) 
                    ? cat.caracteristicas 
                    : [];
                
                return `
                    <div class="volunteer-category-row" data-aos="fade-up" data-aos-delay="${100 * (index + 1)}">
                        <div class="volunteer-category-card-horizontal ${isReverse ? 'volunteer-category-reverse' : ''} ${isFeatured ? 'volunteer-category-featured-horizontal' : ''}">
                            ${isFeatured ? `
                                <div class="featured-badge-volunteer-horizontal">
                                    <i class="fas fa-heart me-1"></i>Recomendado
                                </div>
                            ` : ''}
                            <div class="volunteer-category-image-horizontal">
                                <img src="${imageUrl}" 
                                     alt="${cat.titulo}" 
                                     class="img-fluid"
                                     onerror="this.src='/placeholder.svg?height=400&width=500'">
                                <div class="volunteer-category-overlay-horizontal">
                                    <div class="volunteer-category-icon-horizontal">
                                        <i class="fas ${cat.icono}"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="volunteer-category-content-horizontal">
                                <h4 class="volunteer-category-title-horizontal">${cat.titulo}</h4>
                                <p class="volunteer-category-description-horizontal">
                                    ${cat.descripcion}
                                </p>
                                ${caracteristicas.length > 0 ? `
                                    <ul class="volunteer-category-features-horizontal">
                                        ${caracteristicas.map(c => `
                                            <li><i class="fas fa-check"></i> ${c}</li>
                                        `).join('')}
                                    </ul>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
    } catch (error) {
        console.error('Error al cargar categorías de voluntariado:', error);
        // Mostrar mensaje de error
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <p class="text-muted">No se pudieron cargar las categorías.</p>
                <button class="btn btn-primary btn-sm" onclick="loadCategoriasVoluntariado()">
                    <i class="fas fa-redo me-2"></i>Reintentar
                </button>
            </div>
        `;
    }
}

// Cargar sección "Trabajo Social"
async function loadTrabajoSocial() {
    try {
        const response = await api.get('trabajo-social');
        
        if (response.success) {
            const loadingEl = document.getElementById('trabajo-social-loading');
            const contentEl = document.getElementById('trabajo-social-content');
            const imageContainerEl = document.getElementById('trabajo-social-image-container');
            const tituloEl = document.getElementById('trabajo-social-titulo');
            const descripcionEl = document.getElementById('trabajo-social-descripcion');
            const imagenEl = document.getElementById('trabajo-social-imagen');
            
            if (loadingEl) loadingEl.style.display = 'none';
            
            if (tituloEl && response.data.titulo) {
                tituloEl.textContent = response.data.titulo;
            }
            
            if (descripcionEl && response.data.descripcion) {
                descripcionEl.textContent = response.data.descripcion;
            }
            
            if (imagenEl && response.data.imagen) {
                const imageUrl = fixImagePath(response.data.imagen);
                imagenEl.src = imageUrl;
                imagenEl.alt = response.data.titulo || 'Trabajo Social';
                imagenEl.onerror = function() {
                    this.src = 'assets/images/placeholder.jpg';
                };
                if (imageContainerEl) imageContainerEl.style.display = 'block';
            }
            
            if (contentEl) contentEl.style.display = 'block';
        }
    } catch (error) {
        console.error('Error al cargar trabajo social:', error);
        // Mantener contenido por defecto si falla
        const loadingEl = document.getElementById('trabajo-social-loading');
        if (loadingEl) loadingEl.style.display = 'none';
        const contentEl = document.getElementById('trabajo-social-content');
        if (contentEl) contentEl.style.display = 'block';
    }
}

// Cargar sección "Acerca de"
async function loadAcercaDe() {
    try {
        const response = await api.get('acerca-de');
        
        if (response.success) {
            // Actualizar título y descripción
            const tituloEl = document.getElementById('acerca-titulo');
            const descripcionEl = document.getElementById('acerca-descripcion');
            const imagenEl = document.getElementById('acerca-imagen');
            
            if (tituloEl && response.data.titulo) {
                tituloEl.textContent = response.data.titulo;
            }
            
            if (descripcionEl && response.data.descripcion) {
                descripcionEl.textContent = response.data.descripcion;
            }
            
            // Actualizar imagen si existe
            if (imagenEl && response.data.imagen) {
                imagenEl.src = response.data.imagen;
                imagenEl.alt = response.data.titulo || 'Caminemos Juntos';
            }
            
            // Renderizar características
            const container = document.getElementById('acerca-caracteristicas-container');
            if (container && response.data.caracteristicas) {
                const caracteristicas = response.data.caracteristicas;
                
                if (caracteristicas.length === 0) {
                    container.innerHTML = `
                        <div class="col-12 text-center py-3">
                            <p class="text-muted">No hay características disponibles.</p>
                        </div>
                    `;
                    return;
                }
                
                container.innerHTML = caracteristicas.map((car, index) => `
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="${100 * (index + 1)}">
                        <div class="feature-card">
                            <div class="feature-icon-wrapper">
                                <div class="feature-icon">
                                    <i class="fas ${car.icono}"></i>
                                </div>
                            </div>
                            <h4 class="feature-title">${car.titulo}</h4>
                            <p class="feature-description">${car.descripcion}</p>
                        </div>
                    </div>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Error al cargar acerca de:', error);
        // Mantener contenido por defecto si falla
    }
}

// Cargar datos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    loadHeroSlider();
    loadAcercaDe();
    loadAbuelos();
    loadTrabajoSocial();
    loadTestimonios();
    loadGaleria();
    loadPatrocinadores();
    loadCategoriasVoluntariado();
});
