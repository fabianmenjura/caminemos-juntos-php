/**
 * Sistema de Autenticación del Admin
 * Maneja login, logout, verificación de sesión
 */

class AdminAuth {
    constructor() {
        this.apiBase = window.location.origin + '/api';
        this.token = this.getToken();
        this.user = this.getUser();
    }
    
    /**
     * Obtener token de sesión
     */
    getToken() {
        return localStorage.getItem('admin_token') || sessionStorage.getItem('admin_token');
    }
    
    /**
     * Obtener datos del usuario
     */
    getUser() {
        const userStr = localStorage.getItem('admin_user') || sessionStorage.getItem('admin_user');
        return userStr ? JSON.parse(userStr) : null;
    }
    
    /**
     * Guardar sesión
     */
    saveSession(token, user, remember = false) {
        const storage = remember ? localStorage : sessionStorage;
        storage.setItem('admin_token', token);
        storage.setItem('admin_user', JSON.stringify(user));
        this.token = token;
        this.user = user;
    }
    
    /**
     * Limpiar sesión
     */
    clearSession() {
        localStorage.removeItem('admin_token');
        localStorage.removeItem('admin_user');
        sessionStorage.removeItem('admin_token');
        sessionStorage.removeItem('admin_user');
        this.token = null;
        this.user = null;
    }
    
    /**
     * Verificar si está autenticado
     */
    isAuthenticated() {
        return this.token !== null;
    }
    
    /**
     * Verificar sesión en el servidor
     */
    async verifySession() {
        if (!this.token) {
            return false;
        }
        
        try {
            const response = await fetch(`${this.apiBase}/auth/verify`, {
                headers: {
                    'Authorization': `Bearer ${this.token}`
                }
            });
            
            const data = await response.json();
            
            if (!data.success) {
                this.clearSession();
                return false;
            }
            
            // Actualizar datos del usuario
            const storage = localStorage.getItem('admin_token') ? localStorage : sessionStorage;
            storage.setItem('admin_user', JSON.stringify(data.data));
            this.user = data.data;
            
            return true;
        } catch (error) {
            console.error('Error al verificar sesión:', error);
            return false;
        }
    }
    
    /**
     * Cerrar sesión
     */
    async logout() {
        if (this.token) {
            try {
                await fetch(`${this.apiBase}/auth/logout`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${this.token}`
                    }
                });
            } catch (error) {
                console.error('Error al cerrar sesión:', error);
            }
        }
        
        this.clearSession();
        window.location.href = 'login.html';
    }
    
    /**
     * Requerir autenticación (middleware)
     */
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
    
    /**
     * Verificar rol
     */
    hasRole(requiredRole) {
        if (!this.user) return false;
        
        const roles = { editor: 1, admin: 2, super_admin: 3 };
        const userLevel = roles[this.user.rol] || 0;
        const requiredLevel = roles[requiredRole] || 0;
        
        return userLevel >= requiredLevel;
    }
    
    /**
     * Obtener headers para requests autenticados
     */
    getAuthHeaders() {
        return {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.token}`
        };
    }
}

// Instancia global
const adminAuth = new AdminAuth();

