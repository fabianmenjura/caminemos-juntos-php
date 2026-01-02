// Cliente API para Panel de Administración
class AdminAPI {
    constructor() {
        // Usar la misma URL base que AdminAuth
        const path = window.location.pathname;
        const hostname = window.location.hostname;
        
        if (path.includes('caminemos-juntos-php')) {
            this.baseURL = window.location.origin + '/caminemos-juntos-php/api';
        } else if (path.includes('/admin/')) {
            const basePath = path.substring(0, path.indexOf('/admin/'));
            this.baseURL = window.location.origin + basePath + '/api';
        } else if (hostname.includes('.test') || hostname.includes('localhost')) {
            // Para Laragon con dominio .test
            const pathParts = path.split('/').filter(p => p);
            if (pathParts.length > 0 && pathParts[0] !== 'admin') {
                this.baseURL = window.location.origin + '/' + pathParts[0] + '/api';
            } else {
                this.baseURL = window.location.origin + '/api';
            }
        } else {
            this.baseURL = window.location.origin + '/api';
        }
        
        console.log('AdminAPI - Base URL:', this.baseURL);
        console.log('AdminAPI - Path:', path);
        console.log('AdminAPI - Hostname:', hostname);
    }
    
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}/${endpoint}`;
        
        const authHeaders = adminAuth.getAuthHeaders();
        console.log('AdminAPI.request - URL:', url);
        console.log('AdminAPI.request - Headers:', authHeaders);
        
        const config = {
            method: options.method || 'GET',
            headers: authHeaders,
            ...options
        };
        
        if (options.body && typeof options.body === 'object') {
            config.body = JSON.stringify(options.body);
        }
        
        try {
            const response = await fetch(url, config);
            console.log('AdminAPI.request - Response status:', response.status);
            
            if (!response.ok && response.status !== 401) {
                const errorText = await response.text();
                console.error('AdminAPI.request - Error response:', errorText);
            }
            
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
            // Buscar token en localStorage o sessionStorage
            const token = localStorage.getItem('admin_token') || sessionStorage.getItem('admin_token');
            
            if (!token) {
                console.error('No se encontró token en localStorage ni sessionStorage');
                throw new Error('No hay sesión activa. Por favor, inicia sesión nuevamente.');
            }

            console.log('Upload: Token encontrado:', token.substring(0, 10) + '...');
            console.log('Upload: URL:', `${this.baseURL}/upload/image`);

            const response = await fetch(`${this.baseURL}/upload/image`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'X-Auth-Token': token
                    // NO incluir Content-Type, el navegador lo pone automático para FormData
                },
                body: formData
            });
            
            // Verificar el Content-Type de la respuesta
            const contentType = response.headers.get('content-type');
            console.log('Upload: Content-Type:', contentType);
            console.log('Upload: Status:', response.status);
            
            let data;
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                // Si no es JSON, probablemente es un error HTML de PHP
                const text = await response.text();
                console.error('Upload: Respuesta no JSON:', text.substring(0, 500));
                
                // Intentar extraer información del error HTML si es posible
                let errorMessage = 'Error al subir imagen';
                if (text.includes('Fatal error') || text.includes('Parse error') || text.includes('Warning')) {
                    errorMessage = 'Error del servidor al procesar la imagen. Verifica los logs del servidor.';
                } else if (text.includes('No se envió ningún archivo')) {
                    errorMessage = 'No se recibió el archivo en el servidor';
                }
                
                throw new Error(errorMessage);
            }
            
            if (!response.ok) {
                console.error('Upload error response:', data);
                throw new Error(data.message || data.error || 'Error al subir imagen');
            }
            
            // Asegurar que la respuesta tenga el formato esperado
            if (!data.success && !data.url) {
                throw new Error(data.message || data.error || 'Error al subir imagen');
            }
            
            console.log('Upload exitoso:', data);
            return {
                success: true,
                data: {
                    url: data.data?.url || data.url || data.data,
                    filename: data.data?.filename || data.filename
                }
            };
        } catch (error) {
            console.error('Error al subir imagen:', error);
            throw error;
        }
    }
}

const adminAPI = new AdminAPI();
