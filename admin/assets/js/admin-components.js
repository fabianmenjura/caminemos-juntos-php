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
            <a href="notificaciones.html" class="menu-item ${currentPage === 'notificaciones' ? 'active' : ''}">
                <i class="fas fa-bell"></i><span>Notificaciones</span>
                <span id="notif-badge" class="badge bg-danger ms-2" style="display: none;">0</span>
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
    
    // Cargar buscador global y campana de notificaciones
    loadTopbarComponents();
    
    // Actualizar notificaciones cada 30 segundos
    setInterval(updateNotifications, 30000);
}

/**
 * Cargar componentes del topbar (solo campana de notificaciones)
 */
function loadTopbarComponents() {
    const topbars = document.querySelectorAll('.topbar');
    topbars.forEach(topbar => {
        // Crear topbar-right si no existe
        if (!topbar.querySelector('.topbar-right')) {
            const rightDiv = document.createElement('div');
            rightDiv.className = 'topbar-right';
            topbar.appendChild(rightDiv);
        }
    });
    
    // Cargar campana de notificaciones
    loadNotificationBell();
}

/**
 * Cargar campana de notificaciones (actualizado para usar topbar-right)
 */

/**
 * Cargar campana de notificaciones en el topbar
 */
function loadNotificationBell() {
    const topbarRights = document.querySelectorAll('.topbar-right');
    topbarRights.forEach(topbarRight => {
        // Crear contenedor de la campana si no existe
        if (!topbarRight.querySelector('.notification-bell-container')) {
            const bellContainer = document.createElement('div');
            bellContainer.className = 'notification-bell-container position-relative';
            bellContainer.innerHTML = `
                <div class="notification-bell" onclick="toggleNotificationDropdown()">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge" id="notification-badge-topbar" style="display: none;">0</span>
                </div>
                <div class="notification-dropdown" id="notification-dropdown">
                    <div class="notification-header">
                        <h6><i class="fas fa-bell me-2"></i>Notificaciones</h6>
                        <button class="btn btn-sm btn-link text-decoration-none" onclick="event.stopPropagation(); marcarTodasLeidasDropdown()">
                            <small>Marcar todas leídas</small>
                        </button>
                    </div>
                    <div class="notification-list" id="notification-list-dropdown">
                        <div class="text-center py-4">
                            <div class="spinner-border spinner-border-sm text-primary"></div>
                        </div>
                    </div>
                    <div class="notification-footer">
                        <a href="notificaciones.html">Ver todas las notificaciones</a>
                    </div>
                </div>
            `;
            topbarRight.appendChild(bellContainer);
        }
    });
    
    // Cargar notificaciones iniciales
    updateNotifications();
}

/**
 * Toggle dropdown de notificaciones
 */
function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notification-dropdown');
    const isVisible = dropdown.classList.contains('show');
    
    if (isVisible) {
        dropdown.classList.remove('show');
    } else {
        dropdown.classList.add('show');
        loadNotificationsDropdown();
    }
}

// Cerrar dropdown al hacer click fuera
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notification-dropdown');
    const bell = document.querySelector('.notification-bell');
    
    if (dropdown && bell && !bell.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.classList.remove('show');
    }
});

/**
 * Actualizar notificaciones (contador + dropdown)
 */
async function updateNotifications() {
    try {
        const token = localStorage.getItem('admin_token') || sessionStorage.getItem('admin_token');
        if (!token) return;
        
        const currentPath = window.location.pathname;
        const basePath = currentPath.substring(0, currentPath.indexOf('/admin/'));
        const apiBase = window.location.origin + basePath + '/api';
        
        // Obtener contador
        const response = await fetch(apiBase + '/admin/notificaciones/count', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-Auth-Token': token
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                const count = data.data.count || 0;
                
                // Actualizar badge en sidebar
                const sidebarBadge = document.getElementById('notif-badge');
                if (sidebarBadge) {
                    if (count > 0) {
                        sidebarBadge.textContent = count > 99 ? '99+' : count;
                        sidebarBadge.style.display = 'inline-block';
                    } else {
                        sidebarBadge.style.display = 'none';
                    }
                }
                
                // Actualizar badge en topbar
                const topbarBadge = document.getElementById('notification-badge-topbar');
                if (topbarBadge) {
                    if (count > 0) {
                        topbarBadge.textContent = count > 99 ? '99+' : count;
                        topbarBadge.style.display = 'block';
                    } else {
                        topbarBadge.style.display = 'none';
                    }
                }
            }
        }
    } catch (error) {
        console.error('Error al actualizar notificaciones:', error);
    }
}

/**
 * Cargar notificaciones en el dropdown
 */
async function loadNotificationsDropdown() {
    try {
        const token = localStorage.getItem('admin_token') || sessionStorage.getItem('admin_token');
        if (!token) return;
        
        const currentPath = window.location.pathname;
        const basePath = currentPath.substring(0, currentPath.indexOf('/admin/'));
        const apiBase = window.location.origin + basePath + '/api';
        
        const response = await fetch(apiBase + '/admin/notificaciones', {
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-Auth-Token': token
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success) {
                const notificaciones = data.data.notificaciones.slice(0, 10); // Solo las 10 más recientes
                const listContainer = document.getElementById('notification-list-dropdown');
                
                if (notificaciones.length === 0) {
                    listContainer.innerHTML = `
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-bell-slash fa-2x mb-2"></i>
                            <p class="mb-0">No hay notificaciones</p>
                        </div>
                    `;
                } else {
                    listContainer.innerHTML = notificaciones.map(n => {
                        const colorMap = {
                            'success': 'bg-success',
                            'warning': 'bg-warning',
                            'danger': 'bg-danger',
                            'info': 'bg-info',
                            'primary': 'bg-primary'
                        };
                        
                        return `
                            <div class="notification-item ${n.leida == 0 ? 'unread' : ''}" 
                                 onclick="clickNotificacion(${n.id}, '${n.enlace || ''}')">
                                <div class="d-flex gap-3">
                                    <div class="notification-item-icon ${colorMap[n.color] || 'bg-primary'}">
                                        <i class="fas ${n.icono}"></i>
                                    </div>
                                    <div class="notification-item-content">
                                        <div class="notification-item-title">${n.titulo}</div>
                                        <div class="notification-item-message">${n.mensaje}</div>
                                        <div class="notification-item-time">${getTimeAgo(n.created_at)}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                }
            }
        }
    } catch (error) {
        console.error('Error al cargar notificaciones:', error);
    }
}

/**
 * Click en una notificación
 */
async function clickNotificacion(id, enlace) {
    try {
        const token = localStorage.getItem('admin_token') || sessionStorage.getItem('admin_token');
        const currentPath = window.location.pathname;
        const basePath = currentPath.substring(0, currentPath.indexOf('/admin/'));
        const apiBase = window.location.origin + basePath + '/api';
        
        // Cerrar dropdown primero
        const dropdown = document.getElementById('notification-dropdown');
        if (dropdown) dropdown.classList.remove('show');
        
        // Marcar como leída (sin esperar respuesta)
        fetch(apiBase + `/admin/notificaciones/${id}`, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-Auth-Token': token,
                'Content-Type': 'application/json'
            }
        }).then(() => {
            // Actualizar contador después de marcar
            updateNotifications();
        }).catch(err => console.error('Error al marcar notificación:', err));
        
        // Redirigir inmediatamente si hay enlace
        if (enlace && enlace.trim() !== '') {
            window.location.href = enlace;
        } else {
            // Si no hay enlace, solo recargar notificaciones
            setTimeout(() => {
                const dropdownReload = document.getElementById('notification-dropdown');
                if (dropdownReload && dropdownReload.classList.contains('show')) {
                    loadNotificationsDropdown();
                }
            }, 500);
        }
    } catch (error) {
        console.error('Error en clickNotificacion:', error);
    }
}

/**
 * Marcar todas como leídas desde el dropdown
 */
async function marcarTodasLeidasDropdown() {
    try {
        const token = localStorage.getItem('admin_token') || sessionStorage.getItem('admin_token');
        const currentPath = window.location.pathname;
        const basePath = currentPath.substring(0, currentPath.indexOf('/admin/'));
        const apiBase = window.location.origin + basePath + '/api';
        
        await fetch(apiBase + '/admin/notificaciones/mark-all-read', {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-Auth-Token': token
            }
        });
        
        // Actualizar
        updateNotifications();
        loadNotificationsDropdown();
    } catch (error) {
        console.error('Error:', error);
    }
}

/**
 * Calcular tiempo transcurrido
 */
function getTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);

    if (seconds < 60) return 'Hace un momento';
    if (seconds < 3600) return `Hace ${Math.floor(seconds / 60)} min`;
    if (seconds < 86400) return `Hace ${Math.floor(seconds / 3600)}h`;
    if (seconds < 604800) return `Hace ${Math.floor(seconds / 86400)} días`;
    
    return date.toLocaleDateString('es-CO');
}

// Buscador global eliminado - se usan buscadores individuales en cada página

