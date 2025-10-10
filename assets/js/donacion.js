// Inicializar AOS
AOS.init();

// Formulario de donación personal
const personalForm = document.getElementById('personal-donation-form');
if (personalForm) {
    personalForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(personalForm);
        const data = Object.fromEntries(formData);
        const metodoPago = data.metodo_pago;

        const button = personalForm.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';

        try {
            if (metodoPago === 'tarjeta') {
                // Procesar con PayU
                await processPayUPayment(data, 'personal');
            } else {
                // Guardar donación directamente
                const response = await api.createDonacionPersonal(data);
                if (response.success) {
                    showToast(response.message, 'success');
                    personalForm.reset();
                }
            }
        } catch (error) {
            showToast(error.message || 'Error al procesar la donación', 'error');
        } finally {
            button.disabled = false;
            button.innerHTML = originalText;
        }
    });
}

// Formulario de donación empresarial
const corporateForm = document.getElementById('corporate-donation-form');
if (corporateForm) {
    corporateForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(corporateForm);
        const data = Object.fromEntries(formData);
        const metodoPago = data.metodo_pago;

        const button = corporateForm.querySelector('button[type="submit"]');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Procesando...';

        try {
            if (metodoPago === 'tarjeta') {
                // Procesar con PayU
                await processPayUPayment(data, 'empresa');
            } else {
                // Guardar solicitud directamente
                const response = await api.createDonacionEmpresarial(data);
                if (response.success) {
                    showToast(response.message, 'success');
                    corporateForm.reset();
                }
            }
        } catch (error) {
            showToast(error.message || 'Error al procesar la solicitud', 'error');
        } finally {
            button.disabled = false;
            button.innerHTML = originalText;
        }
    });
}

// Procesar pago con PayU
async function processPayUPayment(formData, tipo) {
    try {
        const paymentData = {
            amount: parseFloat(formData.monto),
            buyerFullName: tipo === 'personal' ? formData.nombre_completo : formData.nombre_contacto,
            buyerEmail: formData.email,
            description: `Donación ${tipo === 'personal' ? 'Personal' : 'Empresarial'} - Adopta un Abuelo Colombia`
        };

        const response = await api.createPayment(paymentData);

        if (response.success) {
            // Crear formulario y enviarlo a PayU
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = response.data.paymentUrl;

            Object.entries(response.data.formData).forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    } catch (error) {
        throw error;
    }
}

// Toast notifications
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : 'success'} toast-notification`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} me-2"></i>
        ${message}
    `;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        animation: slideIn 0.3s ease-out;
    `;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}
