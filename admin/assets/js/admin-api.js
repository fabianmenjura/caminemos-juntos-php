// Cliente API para Panel de Administración
class AdminAPI {
    constructor() {
        // Usar la misma URL base que AdminAuth
        const path = window.location.pathname;
        
        if (path.includes('caminemos-juntos-php')) {
            this.baseURL = window.location.origin + '/caminemos-juntos-php/api';
        } else if (path.includes('/admin/')) {
            const basePath = path.substring(0, path.indexOf('/admin/'));
            this.baseURL = window.location.origin + basePath + '/api';
        } else {
            this.baseURL = window.location.origin + '/api';
        }
        
        console.log('Admin API Base URL:', this.baseURL);
    }
    
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
    
    // Métodos HTTP genéricos
    async get(endpoint, params = {}) {
        const query = Object.keys(params).length > 0 
            ? '?' + new URLSearchParams(params).toString() 
            : '';
        return this.request(`${endpoint}${query}`);
    }
    
    async post(endpoint, data) {
        return this.request(endpoint, {
            method: 'POST',
            body: data
        });
    }
    
    async put(endpoint, data) {
        return this.request(endpoint, {
            method: 'PUT',
            body: data
        });
    }
    
    async delete(endpoint) {
        return this.request(endpoint, {
            method: 'DELETE'
        });
    }
    
    // Dashboard
    async getDashboard() {
        return this.request('admin/dashboard');
    }
    
    // Abuelos
    async getAbuelos(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request(`admin/abuelos?${query}`);
    }
    
    async createAbuelo(data) {
        return this.request('admin/abuelos', { method: 'POST', body: data });
    }
    
    async updateAbuelo(id, data) {
        return this.request(`admin/abuelos/${id}`, { method: 'PUT', body: data });
    }
    
    async deleteAbuelo(id) {
        return this.request(`admin/abuelos/${id}`, { method: 'DELETE' });
    }
    
    // Donaciones
    async getDonaciones(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request(`admin/donaciones?${query}`);
    }
    
    async updateDonacionEstado(tipo, id, estado) {
        return this.request(`admin/donaciones/${tipo}/${id}`, {
            method: 'PUT',
            body: { estado }
        });
    }
    
    // Mensajes
    async getMensajes(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request(`admin/mensajes?${query}`);
    }
    
    async marcarMensajeLeido(id) {
        return this.request(`admin/mensajes/${id}/marcar-leido`, { method: 'PUT' });
    }
    
    async deleteMensaje(id) {
        return this.request(`admin/mensajes/${id}`, { method: 'DELETE' });
    }
    
    // Upload de imágenes
    async uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);
        
        try {
            const token = localStorage.getItem('admin_token');
            if (!token) {
                throw new Error('No hay sesión activa');
            }

            const response = await fetch(`${this.baseURL}/upload/image`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-Auth-Token': token
                    // NO incluir Content-Type, el navegador lo pone automático para FormData
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                console.error('Upload error response:', data);
                throw new Error(data.error || data.message || 'Error al subir imagen');
            }
            
            return data;
        } catch (error) {
            console.error('Error al subir imagen:', error);
            throw error;
        }
    }
}

const adminAPI = new AdminAPI();
