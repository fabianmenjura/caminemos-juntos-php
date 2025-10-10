/**
 * Cliente API para Panel de Administración
 */

class AdminAPI {
    constructor() {
        this.baseURL = window.location.origin + '/api';
    }
    
    /**
     * Request genérico autenticado
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}/${endpoint}`;
        
        const config = {
            method: options.method || 'GET',
            headers: adminAuth.getAuthHeaders(),
            ...options
        };
        
        if (options.body && typeof options.body === 'object') {
            config.body = JSON.stringify(options.body);
        }
        
        try {
            const response = await fetch(url, config);
            const data = await response.json();
            
            // Si la sesión expiró, redirigir a login
            if (response.status === 401) {
                adminAuth.clearSession();
                window.location.href = 'login.html';
                throw new Error('Sesión expirada');
            }
            
            if (!response.ok && !data.success) {
                throw new Error(data.message || 'Error en la solicitud');
            }
            
            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }
    
    // ============================================
    // DASHBOARD
    // ============================================
    
    async getDashboard() {
        return this.request('admin/dashboard');
    }
    
    // ============================================
    // ABUELOS
    // ============================================
    
    async getAbuelos(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request(`admin/abuelos?${query}`);
    }
    
    async createAbuelo(data) {
        return this.request('admin/abuelos', {
            method: 'POST',
            body: data
        });
    }
    
    async updateAbuelo(id, data) {
        return this.request(`admin/abuelos/${id}`, {
            method: 'PUT',
            body: data
        });
    }
    
    async deleteAbuelo(id) {
        return this.request(`admin/abuelos/${id}`, {
            method: 'DELETE'
        });
    }
    
    // ============================================
    // DONACIONES
    // ============================================
    
    async getDonaciones(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request(`admin/donaciones?${query}`);
    }
    
    async updateDonacion(tipo, id, data) {
        return this.request(`admin/donaciones/${tipo}/${id}`, {
            method: 'PUT',
            body: data
        });
    }
    
    // ============================================
    // MENSAJES
    // ============================================
    
    async getMensajes(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request(`admin/mensajes?${query}`);
    }
    
    async marcarMensajeLeido(id) {
        return this.request(`admin/mensajes/${id}/marcar-leido`, {
            method: 'PUT'
        });
    }
    
    async deleteMensaje(id) {
        return this.request(`admin/mensajes/${id}`, {
            method: 'DELETE'
        });
    }
    
    // ============================================
    // LOGS
    // ============================================
    
    async getLogs(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request(`admin/logs?${query}`);
    }
    
    // ============================================
    // UTILIDADES
    // ============================================
    
    async uploadImage(file) {
        // TODO: Implementar upload de imágenes
        // Por ahora, retornar una URL de ejemplo
        return {
            success: true,
            data: {
                url: 'assets/images/' + file.name
            }
        };
    }
}

// Instancia global
const adminAPI = new AdminAPI();

