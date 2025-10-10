// Cliente API
class API {
    constructor(baseURL = '/api') {
        this.baseURL = baseURL;
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
