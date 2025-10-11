// Cliente API
class API {
    constructor(baseURL = null) {
        // Detectar automáticamente la base URL
        if (baseURL === null) {
            // Obtener la ruta base del sitio
            const path = window.location.pathname;
            const basePath = path.substring(0, path.lastIndexOf('/') + 1);
            
            // Si estamos en un subdirectorio (ej: /caminemos-juntos-php/)
            if (basePath.includes('caminemos-juntos-php')) {
                this.baseURL = '/caminemos-juntos-php/api';
            } else if (basePath !== '/' && !basePath.includes('index.html') && !basePath.includes('donar.html')) {
                // Otro subdirectorio
                const parts = basePath.split('/').filter(p => p);
                if (parts.length > 0) {
                    this.baseURL = `/${parts[0]}/api`;
                } else {
                    this.baseURL = '/api';
                }
            } else {
                // Raíz del dominio
                this.baseURL = '/api';
            }
        } else {
            this.baseURL = baseURL;
        }
        
        console.log('API Base URL:', this.baseURL);
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseURL}/${endpoint}`;
        
        const config = {
            method: options.method || 'GET',
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            }
        };

        if (options.body) {
            config.body = JSON.stringify(options.body);
        }

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Error en la solicitud');
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

    // Abuelos
    async getAbuelos(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request(`abuelos?${query}`);
    }

    async getAbuelo(id) {
        return this.request(`abuelos/${id}`);
    }

    async getCumpleanosMesActual() {
        return this.request('abuelos/cumpleanos/mes-actual');
    }

    async buscarAbuelos(termino) {
        return this.request(`abuelos/buscar/${termino}`);
    }

    // Donaciones
    async createDonacionPersonal(data) {
        return this.request('donaciones/personas', {
            method: 'POST',
            body: data
        });
    }

    async createDonacionEmpresarial(data) {
        return this.request('donaciones/empresas', {
            method: 'POST',
            body: data
        });
    }

    // Contacto
    async sendContactMessage(data) {
        return this.request('contacto', {
            method: 'POST',
            body: data
        });
    }

    // PayU
    async createPayment(data) {
        return this.request('payu/create-payment', {
            method: 'POST',
            body: data
        });
    }

    async getPaymentStatus(referenceCode) {
        return this.request(`payu/status/${referenceCode}`);
    }

    // Health
    async healthCheck() {
        return this.request('health');
    }
}

// Instancia global
const api = new API();
