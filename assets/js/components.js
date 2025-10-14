/**
 * Componentes Reutilizables - Caminemos Juntos
 * Header, Footer y WhatsApp Button
 */

// Configuración
const CONFIG = {
    siteName: 'Caminemos Juntos',
    logo: 'assets/images/placeholder-logo.png',
    phone: '+57 321 9951293',
    phoneDisplay: '+57 321 9951293',
    email: 'contacto@caminemosjuntos.org',
    whatsappNumber: '573219951293'
};

/**
 * Header Component
 */
function loadHeader(currentPage = '') {
    // Header especial para index con botón Donar
    const isIndex = currentPage === 'index';
    
    const header = isIndex ? `
    <header class="main-header fixed-top">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container">
                <a class="navbar-brand" href="index.html">
                    <img src="${CONFIG.logo}" 
                         alt="Logo Caminemos Juntos Chiquinquirá - Acompañamiento adultos mayores Boyacá" 
                         class="logo"
                         width="50"
                         height="50">
                    <span class="brand-text">${CONFIG.siteName}</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item"><a class="nav-link" href="#acerca">Acerca de</a></li>
                        <li class="nav-item"><a class="nav-link" href="abuelos.html">Abuelos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#donar">Donar</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                        <li class="nav-item">
                            <a href="donar.html" class="btn btn-primary btn-donate">
                                <i class="fas fa-heart me-2"></i>Donar Ahora
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    ` : `
    <header class="main-header fixed-top">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.html">
                    <img src="${CONFIG.logo}" 
                         alt="Logo Caminemos Juntos Chiquinquirá" 
                         class="logo"
                         width="40"
                         height="40">
                    <span class="brand-text">${CONFIG.siteName}</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link ${currentPage === 'index' ? 'active' : ''}" href="index.html">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ${currentPage === 'abuelos' ? 'active' : ''}" href="abuelos.html">Abuelos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ${currentPage === 'donar' ? 'active' : ''}" href="donar.html">Donar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.html#voluntarios">Voluntariado</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.html#contacto">Contacto</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    `;
    
    const headerContainer = document.getElementById('header-container');
    if (headerContainer) {
        headerContainer.innerHTML = header;
    }
}

/**
 * Footer Component
 */
function loadFooter() {
    const footer = `
    <footer class="main-footer">
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <div class="footer-brand mb-4">
                        <img src="${CONFIG.logo}" alt="Logo" class="footer-logo mb-3" width="50" height="50">
                        <h5 class="text-white mb-3">${CONFIG.siteName} Chiquinquirá</h5>
                    </div>
                    <p class="footer-description">
                        Conectamos corazones generosos con adultos mayores que necesitan compañía, apoyo económico y sobre todo, mucho cariño.
                    </p>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="footer-title">Enlaces Rápidos</h6>
                    <ul class="footer-links">
                        <li><a href="index.html">Inicio</a></li>
                        <li><a href="index.html#acerca">Acerca de</a></li>
                        <li><a href="abuelos.html">Ver Todos los Abuelos</a></li>
                        <li><a href="donar.html">Donar</a></li>
                        <li><a href="index.html#voluntarios">Voluntariado</a></li>
                        <li><a href="admin/login.html"><i class="fas fa-lock me-1"></i>Panel Admin</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h6 class="footer-title">Contacto</h6>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>${CONFIG.email}</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>${CONFIG.phoneDisplay}</span>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Chiquinquirá, Boyacá</span>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h6 class="footer-title">Síguenos</h6>
                    <div class="footer-social">
                        <a href="https://www.facebook.com/caminemosjuntos" target="_blank" rel="noopener" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/caminemosjuntos" target="_blank" rel="noopener" class="social-link">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://wa.me/${CONFIG.whatsappNumber}" target="_blank" rel="noopener" class="social-link">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <p class="mb-0">© 2025 <strong>Hogar Santo Domingo</strong>. Todos los derechos reservados.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0">
                            Desarrollado por 
                            <a href="https://www.linkedin.com/in/fabián-esneider-m-05a3501a9" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="developer-link">
                                Fabián Menjura
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    `;
    
    const footerContainer = document.getElementById('footer-container');
    if (footerContainer) {
        footerContainer.innerHTML = footer;
    }
}

/**
 * WhatsApp Button Component
 */
function loadWhatsAppButton() {
    const whatsappBtn = `
    <a href="https://wa.me/${CONFIG.whatsappNumber}" 
       class="whatsapp-button" 
       target="_blank" 
       rel="noopener"
       title="Contáctanos por WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    `;
    
    const whatsappContainer = document.getElementById('whatsapp-container');
    if (whatsappContainer) {
        whatsappContainer.innerHTML = whatsappBtn;
    }
}

/**
 * Global Spinner Component
 */
function loadGlobalSpinner() {
    const spinner = `
    <div id="globalSpinner" class="global-spinner">
        <div class="spinner-content">
            <div class="spinner-border text-light" style="width: 4rem; height: 4rem;"></div>
            <p class="text-white mt-3 fs-5">Cargando...</p>
        </div>
    </div>
    `;
    
    const spinnerContainer = document.getElementById('spinner-container');
    if (spinnerContainer) {
        spinnerContainer.innerHTML = spinner;
    }
}

/**
 * Inicializar todos los componentes
 * Llamar desde cada página con: initComponents('index') o initComponents('abuelos')
 */
function initComponents(currentPage = '') {
    // Cargar componentes
    loadHeader(currentPage);
    loadFooter();
    loadWhatsAppButton();
    loadGlobalSpinner();
    
    // Inicializar AOS si está disponible
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 1000, once: true });
    }
}

// Global Spinner Helper (si existe)
if (typeof window !== 'undefined') {
    window.globalSpinner = {
        show: function(message = 'Cargando...') {
            const spinner = document.getElementById('globalSpinner');
            const text = spinner?.querySelector('p');
            if (spinner) {
                if (text) text.textContent = message;
                spinner.classList.add('active');
            }
        },
        hide: function() {
            const spinner = document.getElementById('globalSpinner');
            if (spinner) {
                spinner.classList.remove('active');
            }
        }
    };
}

