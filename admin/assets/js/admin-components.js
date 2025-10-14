/**
 * Componentes Reutilizables del Admin Panel
 * Sidebar y otros elementos comunes
 */

/**
 * Sidebar Component
 * @param {string} currentPage - Página actual para marcar como activa
 */
function loadAdminSidebar(currentPage = '') {
    const sidebar = `
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-heart fa-2x mb-2" style="color: #eaa33a;"></i>
            <h3>Caminemos Juntos</h3>
            <p>Panel de Administración</p>
        </div>
        
        <div class="sidebar-menu">
            <a href="dashboard.html" class="menu-item ${currentPage === 'dashboard' ? 'active' : ''}">
                <i class="fas fa-chart-line"></i><span>Dashboard</span>
            </a>
            <a href="abuelos.html" class="menu-item ${currentPage === 'abuelos' ? 'active' : ''}">
                <i class="fas fa-users"></i><span>Abuelos</span>
            </a>
            <a href="donaciones.html" class="menu-item ${currentPage === 'donaciones' ? 'active' : ''}">
                <i class="fas fa-hand-holding-heart"></i><span>Donaciones</span>
            </a>
            <a href="qr-donaciones.html" class="menu-item ${currentPage === 'qr-donaciones' ? 'active' : ''}">
                <i class="fas fa-qrcode"></i><span>QR Donaciones</span>
            </a>
            <a href="mensajes.html" class="menu-item ${currentPage === 'mensajes' ? 'active' : ''}">
                <i class="fas fa-envelope"></i><span>Mensajes</span>
            </a>
            <a href="hero-slider.html" class="menu-item ${currentPage === 'hero-slider' ? 'active' : ''}">
                <i class="fas fa-images"></i><span>Hero Slider</span>
            </a>
            <a href="acerca-de.html" class="menu-item ${currentPage === 'acerca-de' ? 'active' : ''}">
                <i class="fas fa-info-circle"></i><span>Acerca de</span>
            </a>
            <a href="voluntarios.html" class="menu-item ${currentPage === 'voluntarios' ? 'active' : ''}">
                <i class="fas fa-user-friends"></i><span>Voluntarios</span>
            </a>
            <a href="categorias-voluntariado.html" class="menu-item ${currentPage === 'categorias-voluntariado' ? 'active' : ''}">
                <i class="fas fa-list-alt"></i><span>Categorías Voluntariado</span>
            </a>
            <a href="testimonios.html" class="menu-item ${currentPage === 'testimonios' ? 'active' : ''}">
                <i class="fas fa-comment-dots"></i><span>Testimonios</span>
            </a>
            <a href="patrocinadores.html" class="menu-item ${currentPage === 'patrocinadores' ? 'active' : ''}">
                <i class="fas fa-handshake"></i><span>Patrocinadores</span>
            </a>
            <a href="mensajes-cumpleanos.html" class="menu-item ${currentPage === 'mensajes-cumpleanos' ? 'active' : ''}">
                <i class="fas fa-birthday-cake"></i><span>Felicitaciones</span>
            </a>
            <a href="configuracion.html" class="menu-item ${currentPage === 'configuracion' ? 'active' : ''}">
                <i class="fas fa-cog"></i><span>Configuración</span>
            </a>
        </div>
        
        <div class="sidebar-footer">
            <p class="text-white mb-2"><i class="fas fa-user me-2"></i><span id="userName">Admin</span></p>
            <button class="btn-logout" onclick="logout()">
                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
            </button>
        </div>
    </div>
    `;
    
    const sidebarContainer = document.getElementById('sidebar-container');
    if (sidebarContainer) {
        sidebarContainer.innerHTML = sidebar;
    }
}

/**
 * Inicializar componentes del admin
 * @param {string} currentPage - Página actual
 */
async function initAdminComponents(currentPage = '') {
    // Verificar autenticación primero
    await requireAuth();
    
    // Cargar sidebar
    loadAdminSidebar(currentPage);
    
    // Cargar nombre de usuario si está disponible
    if (typeof adminAuth !== 'undefined' && adminAuth.user) {
        const usernameEl = document.getElementById('username');
        if (usernameEl) {
            usernameEl.textContent = adminAuth.user.username || 'Admin';
        }
    }
}

