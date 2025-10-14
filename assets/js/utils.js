/**
 * Utilidades para Notificaciones y Loading
 * Usando SweetAlert2
 */

// Configuración global de SweetAlert2
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

// Mostrar mensaje de éxito
function showSuccess(message, title = '¡Éxito!') {
    Swal.fire({
        icon: 'success',
        title: title,
        text: message,
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#667eea'
    });
}

// Mostrar mensaje de error
function showError(message, title = 'Error') {
    Swal.fire({
        icon: 'error',
        title: title,
        text: message,
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#dc3545'
    });
}

// Mostrar mensaje de advertencia
function showWarning(message, title = 'Atención') {
    Swal.fire({
        icon: 'warning',
        title: title,
        text: message,
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#ffc107'
    });
}

// Mostrar mensaje de info
function showInfo(message, title = 'Información') {
    Swal.fire({
        icon: 'info',
        title: title,
        text: message,
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#667eea'
    });
}

// Toast de éxito (pequeño, esquina)
function toastSuccess(message) {
    Toast.fire({
        icon: 'success',
        title: message
    });
}

// Toast de error (pequeño, esquina)
function toastError(message) {
    Toast.fire({
        icon: 'error',
        title: message
    });
}

// Toast de info (pequeño, esquina)
function toastInfo(message) {
    Toast.fire({
        icon: 'info',
        title: message
    });
}

// Confirmación con SweetAlert
async function showConfirm(message, title = '¿Estás seguro?', confirmText = 'Sí', cancelText = 'No') {
    const result = await Swal.fire({
        title: title,
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        confirmButtonColor: '#667eea',
        cancelButtonColor: '#6c757d'
    });
    
    return result.isConfirmed;
}

// Spinner Global
const globalSpinner = {
    show: function(message = 'Cargando...') {
        const spinner = document.getElementById('globalSpinner');
        if (spinner) {
            const textElement = spinner.querySelector('p');
            if (textElement) textElement.textContent = message;
            spinner.classList.add('active');
        }
    },
    
    hide: function() {
        const spinner = document.getElementById('globalSpinner');
        if (spinner) {
            spinner.classList.remove('active');
        }
    }
};

// Loading con SweetAlert (alternativa)
function showLoading(message = 'Cargando...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

function hideLoading() {
    Swal.close();
}

// Compatibilidad con código existente
function showToast(message, type = 'success') {
    if (type === 'success') {
        toastSuccess(message);
    } else if (type === 'error') {
        toastError(message);
    } else {
        toastInfo(message);
    }
}


