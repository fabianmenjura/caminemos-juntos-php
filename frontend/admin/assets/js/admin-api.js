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
            console.log('AdminAPI.request - URL:', url);
            
            // Verificar content-type antes de parsear JSON
            const contentType = response.headers.get('content-type');
            let data;
            
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                // Si no es JSON, leer como texto primero
                const text = await response.text();
                console.error('AdminAPI.request - Respuesta no JSON:', text.substring(0, 500));
                
                // Intentar parsear como JSON si parece ser JSON
                try {
                    data = JSON.parse(text);
                } catch (parseError) {
                    // Si no es JSON válido, es un error del servidor (probablemente HTML/error de PHP)
                    throw new Error('El servidor devolvió una respuesta no válida. Verifica que la tabla exista en la base de datos.');
                }
            }
            
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
            console.log('Upload: Response OK:', response.ok);
            
            // Leer la respuesta como texto primero para poder inspeccionarla
            const responseText = await response.text();
            console.log('Upload: Response text (first 1000 chars):', responseText.substring(0, 1000));
            
            let data;
            if (contentType && contentType.includes('application/json')) {
                try {
                    data = JSON.parse(responseText);
                } catch (parseError) {
                    console.error('Upload: Error parsing JSON:', parseError);
                    console.error('Upload: Full response text:', responseText);
                    throw new Error('El servidor devolvió una respuesta JSON inválida. Ver la consola para más detalles.');
                }
            } else {
                // Si no es JSON, probablemente es un error HTML de PHP
                console.error('Upload: Respuesta no es JSON, es:', contentType);
                console.error('Upload: Respuesta completa (primeros 2000 chars):', responseText.substring(0, 2000));
                
                // Intentar extraer información del error HTML si es posible
                let errorMessage = 'Error al subir imagen. El servidor devolvió HTML en lugar de JSON.';
                
                // Intentar extraer mensajes de error comunes de PHP
                const errorMatch = responseText.match(/<b>(Warning|Fatal error|Parse error|Notice):<\/b>\s*(.+?)<br/i);
                if (errorMatch) {
                    errorMessage = `Error del servidor: ${errorMatch[2]}`;
                } else if (responseText.includes('Fatal error')) {
                    errorMessage = 'Error fatal del servidor. Verifica los logs del servidor.';
                } else if (responseText.includes('Parse error')) {
                    errorMessage = 'Error de sintaxis en el servidor. Verifica los logs del servidor.';
                } else if (responseText.includes('Warning')) {
                    errorMessage = 'Advertencia del servidor. Verifica los logs del servidor.';
                } else if (responseText.includes('No se envió ningún archivo') || responseText.includes('No file')) {
                    errorMessage = 'No se recibió el archivo en el servidor';
                } else if (responseText.includes('401') || responseText.includes('Unauthorized')) {
                    errorMessage = 'No autorizado. Por favor, inicia sesión nuevamente.';
                } else if (responseText.includes('403') || responseText.includes('Forbidden')) {
                    errorMessage = 'Acceso prohibido. No tienes permisos para esta acción.';
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
