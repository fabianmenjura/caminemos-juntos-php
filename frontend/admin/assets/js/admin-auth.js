// Sistema de Autenticación del Admin
class AdminAuth {
    constructor() {
        // Detectar automáticamente la base URL de la API
        const path = window.location.pathname;
        
        // Si estamos en un subdirectorio (ej: /caminemos-juntos-php/admin/)
        if (path.includes('caminemos-juntos-php')) {
            this.apiBase = window.location.origin + '/caminemos-juntos-php/api';
        } else if (path.includes('/admin/')) {
            // Extraer la ruta base antes de /admin/
            const basePath = path.substring(0, path.indexOf('/admin/'));
            this.apiBase = window.location.origin + basePath + '/api';
        } else {
            // Raíz del dominio
            this.apiBase = window.location.origin + '/api';
        }
        
        console.log('API Base URL:', this.apiBase);
        
        this.token = this.getToken();
        this.user = this.getUser();
    }
    
    getToken() {
        return localStorage.getItem('admin_token') || sessionStorage.getItem('admin_token');
    }
    
    getUser() {
        const userStr = localStorage.getItem('admin_user') || sessionStorage.getItem('admin_user');
        return userStr ? JSON.parse(userStr) : null;
    }
    
    saveSession(token, user, remember = false) {
        const storage = remember ? localStorage : sessionStorage;
        storage.setItem('admin_token', token);
        storage.setItem('admin_user', JSON.stringify(user));
        this.token = token;
        this.user = user;
    }
    
    clearSession() {
        localStorage.removeItem('admin_token');
        localStorage.removeItem('admin_user');
        sessionStorage.removeItem('admin_token');
        sessionStorage.removeItem('admin_user');
        this.token = null;
        this.user = null;
    }
    
    isAuthenticated() {
        return this.token !== null;
    }
    
    async verifySession() {
        if (!this.token) {
            console.log('verifySession: No hay token');
            return false;
        }
        
        try {
            console.log('verifySession: Verificando con token:', this.token.substring(0, 10) + '...');
            
            const response = await fetch(`${this.apiBase}/auth/verify`, {
                headers: { 'Authorization': `Bearer ${this.token}` }
            });
            
            console.log('verifySession: Response status:', response.status);
            
            const data = await response.json();
            console.log('verifySession: Response data:', data);
            
            if (!data.success) {
                console.log('verifySession: Sesión inválida según servidor');
                this.clearSession();
                return false;
            }
            
            console.log('verifySession: Sesión válida');
            
            const storage = localStorage.getItem('admin_token') ? localStorage : sessionStorage;
            storage.setItem('admin_user', JSON.stringify(data.data));
            this.user = data.data;
            return true;
        } catch (error) {
            console.error('Error al verificar sesión:', error);
            return false;
        }
    }
    
    async logout() {
        if (this.token) {
            try {
                await fetch(`${this.apiBase}/auth/logout`, {
                    method: 'POST',
                    headers: { 'Authorization': `Bearer ${this.token}` }
                });
            } catch (error) {
                console.error('Error al cerrar sesión:', error);
            }
        }
        this.clearSession();
        window.location.href = 'login.html';
    }
    
    async requireAuth() {
        if (!this.isAuthenticated()) {
            window.location.href = 'login.html';
            return false;
        }
        
        const isValid = await this.verifySession();
        if (!isValid) {
            window.location.href = 'login.html';
            return false;
        }
        return true;
    }
    
    getAuthHeaders() {
        return {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.token}`
        };
    }
}

const adminAuth = new AdminAuth();

// Funciones globales para compatibilidad
function verifySession() {
    return adminAuth.verifySession();
}

function logout() {
    return adminAuth.logout();
}

function requireAuth() {
    return adminAuth.requireAuth();
}

function isAuthenticated() {
    return adminAuth.isAuthenticated();
}
