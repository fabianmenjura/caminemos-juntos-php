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

// Cargar abuelos
async function loadAbuelos() {
    try {
        const response = await api.getAbuelos();
        const abuelosContainer = document.getElementById('abuelos-container');
        const loadingEl = document.getElementById('abuelos-loading');

        if (response.success && response.data.abuelos) {
            const abuelos = response.data.abuelos.slice(0, 6);
            
            abuelosContainer.innerHTML = abuelos.map(abuelo => `
                <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up">
                    <div class="card grandparent-card h-100">
                        <img src="${abuelo.foto_url || 'assets/images/placeholder.jpg'}" class="card-img-top" alt="${abuelo.nombre}">
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
            `).join('');

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

// Cargar datos cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    loadAbuelos();
});
