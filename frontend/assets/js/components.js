/**
 * Componentes Reutilizables - Caminemos Juntos
 * Header, Footer, WhatsApp Button, Head Tags y Scripts
 */

// Configuración (se carga dinámicamente desde la API)
let CONFIG = {
    siteName: 'Caminemos Juntos',
    logo: 'assets/images/placeholder-logo.png',
    phone: '+57 321 9951293',
    phoneDisplay: '+57 321 9951293',
    email: 'contacto@caminemosjuntos.org',
    whatsappNumber: '573219951293',
    whatsappMensaje: '¡Hola! Me gustaría obtener más información sobre Caminemos Juntos.',
    redesSociales: []
};

/**
 * Genera el head común con meta tags, favicon y CDN links
 * @param {Object} options - Opciones de configuración
 * @param {string} options.title - Título de la página
 * @param {string} options.description - Meta description
 * @param {string} options.keywords - Meta keywords
 * @param {string} options.canonical - URL canónica
 * @param {string} options.ogImage - Imagen Open Graph
 * @param {boolean} options.includeAOS - Incluir AOS (default: true)
 * @param {boolean} options.includeSweetAlert - Incluir SweetAlert2 (default: true)
 * @param {string} options.customCSS - CSS personalizado adicional
 * @returns {string} HTML del head
 */
function generateHead(options = {}) {
    const {
        title = 'Caminemos Juntos Chiquinquirá',
        description = 'Fundación en Chiquinquirá dedicada al acompañamiento de adultos mayores',
        keywords = 'adultos mayores chiquinquirá, fundación boyacá',
        canonical = '',
        ogImage = 'https://caminemosjuntos.org/assets/images/elderly-colombian-woman-smiling-warm.jpg',
        includeAOS = true,
        includeSweetAlert = true,
        customCSS = ''
    } = options;

    const logoPath = CONFIG.logo || 'assets/images/placeholder-logo.png';
    const canonicalUrl = canonical || window.location.href;
    
    return `
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="${logoPath}">
    <link rel="shortcut icon" type="image/png" href="${logoPath}">
    <link rel="apple-touch-icon" href="${logoPath}">
    
    <!-- SEO -->
    <title>${title}</title>
    <meta name="description" content="${description}">
    <meta name="keywords" content="${keywords}">
    <link rel="canonical" href="${canonicalUrl}">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="${title}">
    <meta property="og:description" content="${description}">
    <meta property="og:url" content="${canonicalUrl}">
    <meta property="og:image" content="${ogImage}">
    
    <!-- Preconnect -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Merriweather:wght@300;400;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    ${includeAOS ? '<!-- AOS Animation -->\n    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">' : ''}
    ${includeSweetAlert ? '<!-- SweetAlert2 -->\n    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">' : ''}
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    ${customCSS}
    `;
}

/**
 * Genera los scripts comunes al final del body
 * @param {Object} options - Opciones de configuración
 * @param {boolean} options.includeBootstrap - Incluir Bootstrap JS (default: true)
 * @param {boolean} options.includeAOS - Incluir AOS (default: true)
 * @param {boolean} options.includeSweetAlert - Incluir SweetAlert2 (default: true)
 * @param {boolean} options.includeComponents - Incluir components.js (default: true)
 * @param {boolean} options.includeAPI - Incluir api.js (default: false)
 * @param {boolean} options.includeApp - Incluir app.js (default: false)
 * @param {string} options.customScripts - Scripts personalizados adicionales
 * @returns {string} HTML de los scripts
 */
function generateScripts(options = {}) {
    const {
        includeBootstrap = true,
        includeAOS = true,
        includeSweetAlert = true,
        includeComponents = true,
        includeAPI = false,
        includeApp = false,
        customScripts = ''
    } = options;

    return `
    ${includeBootstrap ? '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>' : ''}
    ${includeAOS ? '<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>' : ''}
    ${includeSweetAlert ? '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>' : ''}
    ${includeComponents ? '<script src="assets/js/components.js"></script>' : ''}
    ${includeAPI ? '<script src="assets/js/api.js"></script>' : ''}
    ${includeApp ? '<script src="assets/js/app.js"></script>' : ''}
    ${customScripts}
    `;
}

// Cargar configuración desde la API
async function loadConfig() {
    try {
        const response = await fetch(getApiBase() + '/configuracion');
        const data = await response.json();
        
        if (data.success && data.data.config) {
            const config = data.data.config;
            
            // Actualizar CONFIG con valores de la BD
            CONFIG.siteName = config.site_name?.valor || CONFIG.siteName;
            CONFIG.logo = config.logo_url?.valor || CONFIG.logo;
            CONFIG.phone = config.telefono?.valor || CONFIG.phone;
            CONFIG.phoneDisplay = config.telefono_display?.valor || CONFIG.phoneDisplay;
            CONFIG.email = config.email?.valor || CONFIG.email;
            CONFIG.whatsappNumber = config.whatsapp_numero?.valor || CONFIG.whatsappNumber;
            CONFIG.whatsappMensaje = config.whatsapp_mensaje?.valor || CONFIG.whatsappMensaje;
            CONFIG.direccion = config.direccion?.valor || 'Chiquinquirá, Boyacá';
            CONFIG.redesSociales = data.data.redes_sociales || [];
            
            console.log('Configuración cargada:', CONFIG);
        }
    } catch (error) {
        console.error('Error al cargar configuración:', error);
        // Mantener valores por defecto
    }
}

// Helper para obtener la URL base de la API
function getApiBase() {
    const path = window.location.pathname;
    const basePath = path.substring(0, path.lastIndexOf('/') + 1);
    
    if (basePath.includes('caminemos-juntos-php')) {
        return '/caminemos-juntos-php/api';
    } else if (basePath.includes('admin')) {
        return basePath.replace('admin/', '') + 'api';
    } else {
        return '/api';
    }
}

/**
 * Header Component
 * Navbar unificado para todas las páginas - mismo diseño y estructura
 */
function loadHeader(currentPage = '') {
    // Determinar si estamos en la página index
    const isIndex = currentPage === 'index' || 
                    window.location.pathname.includes('index.html') || 
                    window.location.pathname === '/' || 
                    window.location.pathname.endsWith('/');
    
    // Navbar unificado - mismo estilo y estructura en todas las páginas
    // Solo cambia el fondo si NO es index (para que se vea sobre el hero)
    const header = `
    <header class="main-header fixed-top">
        <nav class="navbar navbar-expand-lg navbar-light ${isIndex ? '' : 'bg-white shadow-sm'}">
            <div class="container">
                <a class="navbar-brand" href="index.html">
                    <img src="${CONFIG.logo}" 
                         alt="Logo Caminemos Juntos Chiquinquirá - Acompañamiento adultos mayores Boyacá" 
                         class="logo"
                         width="45"
                         height="45">
                    <span class="brand-text">${CONFIG.siteName}</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link ${currentPage === 'index' || isIndex ? 'active' : ''}" 
                               href="${isIndex ? '#acerca' : 'index.html#acerca'}">
                                Acerca de
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ${currentPage === 'abuelos' ? 'active' : ''}" 
                               href="${isIndex ? '#abuelos' : 'abuelos.html'}">
                                Adultos Mayores
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link ${currentPage === 'donar' ? 'active' : ''}" 
                               href="donar.html">
                                Donar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.html#voluntarios">
                                Voluntariado
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="${isIndex ? '#contacto' : 'index.html#contacto'}">
                                Contacto
                            </a>
                        </li>
                        <li class="nav-item ms-2">
                            <a href="donar.html" class="btn btn-primary btn-donate">
                                <i class="fas fa-heart me-2"></i>Donar Ahora
                            </a>
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
        
        // Inicializar scroll spy solo en index.html
        if (isIndex) {
            initScrollSpy();
        }
    }
}

/**
 * Scroll Spy - Actualiza el enlace activo según la sección visible
 */
function initScrollSpy() {
    // Esperar a que el DOM esté completamente cargado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setupScrollSpy();
        });
    } else {
        setupScrollSpy();
    }
}

function setupScrollSpy() {
    const sections = [
        { id: 'acerca', selector: '#acerca' },
        { id: 'abuelos', selector: '#abuelos' },
        { id: 'donar', selector: '#donar' },
        { id: 'voluntarios', selector: '#voluntarios' },
        { id: 'contacto', selector: '#contacto' }
    ];
    
    // Función para actualizar el enlace activo
    function updateActiveLink() {
        const navLinks = document.querySelectorAll('#navbarNav .nav-link');
        if (!navLinks.length) return;
        
        const scrollPosition = window.scrollY + 200; // Offset para activar antes
        let activeSection = null;
        let minDistance = Infinity;
        
        // Buscar qué sección está más cerca del viewport
        sections.forEach(section => {
            const element = document.querySelector(section.selector);
            if (element) {
                const rect = element.getBoundingClientRect();
                const elementTop = window.scrollY + rect.top;
                const elementBottom = elementTop + rect.height;
                
                // Si la sección está visible en el viewport
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    const distance = Math.abs(rect.top);
                    if (distance < minDistance) {
                        minDistance = distance;
                        activeSection = section.id;
                    }
                }
                
                // Si estamos antes de la sección pero cerca
                if (scrollPosition >= elementTop - 100 && scrollPosition < elementBottom) {
                    activeSection = section.id;
                }
            }
        });
        
        // Actualizar clases activas
        navLinks.forEach(link => {
            link.classList.remove('active');
            
            const href = link.getAttribute('href');
            if (!href) return;
            
            // Verificar si el enlace corresponde a la sección activa
            if (activeSection) {
                // Verificar si el href contiene el ID de la sección activa
                const isActive = href.includes(activeSection) || 
                                href === `#${activeSection}` || 
                                (href === 'abuelos.html' && activeSection === 'abuelos') ||
                                (href.includes('abuelos') && activeSection === 'abuelos');
                
                if (isActive) {
                    link.classList.add('active');
                }
            } else {
                // Si no hay sección activa y estamos en el top, activar "Acerca de"
                if (window.scrollY < 300 && (href === '#acerca' || href.includes('#acerca'))) {
                    link.classList.add('active');
                }
            }
        });
    }
    
    // Ejecutar al cargar
    setTimeout(updateActiveLink, 100);
    
    // Ejecutar al hacer scroll (con throttling para mejor rendimiento)
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                updateActiveLink();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
    
    // También actualizar cuando se hace click en un enlace (para smooth scroll)
    document.querySelectorAll('#navbarNav .nav-link[href^="#"]').forEach(link => {
        link.addEventListener('click', () => {
            setTimeout(updateActiveLink, 500); // Esperar a que termine el smooth scroll
        });
    });
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
                        <h5 class="text-white mb-3">${CONFIG.siteName}</h5>
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
                        <li><a href="abuelos.html">Ver Todos los Adultos Mayores</a></li>
                        <li><a href="donar.html">Donar</a></li>
                        <li><a href="index.html#voluntarios">Voluntariado</a></li>
                        <li><a href="admin/login.html" target="_blank" rel="noopener noreferrer"><i class="fas fa-lock me-1"></i>Panel Admin</a></li>
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
                            <span>${CONFIG.direccion || 'Chiquinquirá, Boyacá'}</span>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h6 class="footer-title">Síguenos</h6>
                    <div class="footer-social">
                        ${CONFIG.redesSociales && CONFIG.redesSociales.length > 0 
                            ? CONFIG.redesSociales.map(red => `
                                <a href="${red.url}" target="_blank" rel="noopener" class="social-link" title="${red.nombre}">
                                    <i class="fab ${red.icono}"></i>
                                </a>
                            `).join('')
                            : `
                                <a href="https://www.facebook.com/caminemosjuntos" target="_blank" rel="noopener" class="social-link">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://www.instagram.com/caminemosjuntos" target="_blank" rel="noopener" class="social-link">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="https://wa.me/${CONFIG.whatsappNumber}" target="_blank" rel="noopener" class="social-link">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            `
                        }
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
    // Codificar mensaje para URL
    const mensajeCodificado = encodeURIComponent(CONFIG.whatsappMensaje || '¡Hola! Me gustaría obtener más información sobre Caminemos Juntos.');
    const whatsappUrl = `https://wa.me/${CONFIG.whatsappNumber}?text=${mensajeCodificado}`;
    
    const whatsappBtn = `
    <a href="${whatsappUrl}" 
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
 * Hero Section Component
 * @param {Object} options - Opciones de configuración
 * @param {string} options.title - Título principal
 * @param {string} options.subtitle - Subtítulo
 * @param {string} options.image - URL de la imagen de fondo
 * @param {string} options.imageAlt - Texto alternativo de la imagen
 * @param {Array} options.buttons - Array de botones [{text, href, class, icon}]
 * @param {string} options.containerId - ID del contenedor (default: 'hero-container')
 * @param {number} options.minHeight - Altura mínima en px (default: 500)
 */
function loadHeroSection(options = {}) {
    const {
        title = 'Bienvenido',
        subtitle = '',
        image = 'assets/images/elderly-colombian-woman-smiling-warm.jpg',
        imageAlt = 'Hero image',
        buttons = [],
        containerId = 'hero-container',
        minHeight = 500
    } = options;

    const buttonsHTML = buttons.map(btn => {
        const icon = btn.icon ? `<i class="${btn.icon} me-2"></i>` : '';
        return `<a href="${btn.href || '#'}" class="btn ${btn.class || 'btn-primary'} btn-lg">${icon}${btn.text}</a>`;
    }).join('\n                        ');

    const heroHTML = `
    <section class="hero-section">
        <div class="hero-image-container">
            <img src="${image}" 
                 alt="${imageAlt}" 
                 class="hero-bg-image">
        </div>
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="row align-items-center" style="min-height: ${minHeight}px;">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="hero-title" data-aos="fade-up">${title}</h1>
                    ${subtitle ? `<p class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">${subtitle}</p>` : ''}
                    ${buttons.length > 0 ? `
                    <div class="d-flex gap-3 justify-content-center flex-wrap mt-4" data-aos="fade-up" data-aos-delay="200">
                        ${buttonsHTML}
                    </div>
                    ` : ''}
                </div>
            </div>
        </div>
    </section>
    `;

    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = heroHTML;
    } else {
        console.warn(`Hero container with ID "${containerId}" not found`);
    }
}

/**
 * Inyecta el head común en el documento
 * Nota: Esto debe llamarse ANTES de que el DOM esté completamente cargado
 * @param {Object} options - Opciones para generateHead
 */
function injectHead(options = {}) {
    if (document.head) {
        const headHTML = generateHead(options);
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = headHTML;
        
        // Mover todos los nodos al head
        while (tempDiv.firstChild) {
            document.head.appendChild(tempDiv.firstChild);
        }
    }
}

/**
 * Inyecta los scripts comunes al final del body
 * @param {Object} options - Opciones para generateScripts
 */
function injectScripts(options = {}) {
    if (document.body) {
        const scriptsHTML = generateScripts(options);
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = scriptsHTML;
        
        // Mover todos los nodos al body
        while (tempDiv.firstChild) {
            document.body.appendChild(tempDiv.firstChild);
        }
    }
}

/**
 * Inicializar todos los componentes
 * Llamar desde cada página con: initComponents('index') o initComponents('abuelos')
 */
async function initComponents(currentPage = '') {
    // Cargar configuración primero
    await loadConfig();
    
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

