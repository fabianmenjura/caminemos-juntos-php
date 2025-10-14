-- =====================================================
-- TABLA PARA QR DE DONACIONES
-- =====================================================

CREATE TABLE IF NOT EXISTS qr_donaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL DEFAULT 'Donación por QR',
    descripcion TEXT,
    qr_image_url VARCHAR(500) NOT NULL,
    texto_cuenta TEXT COMMENT 'Información de cuenta bancaria o método de pago',
    activo TINYINT(1) DEFAULT 1,
    orden INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted TINYINT(1) DEFAULT 0,
    INDEX idx_activo (activo),
    INDEX idx_deleted (deleted),
    INDEX idx_orden (orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar QR de ejemplo
INSERT INTO qr_donaciones (titulo, descripcion, qr_image_url, texto_cuenta, activo, orden) VALUES
('Nequi - Hogar Santo Domingo', 
 'Escanea este código QR con tu app de Nequi para hacer tu donación',
 'assets/images/qr-nequi-placeholder.png',
 'Cuenta Nequi: 300 123 4567\nTitular: Fundación Hogar Santo Domingo\nNIT: 123456789-0',
 1, 
 1),
('Bancolombia - Cuenta de Ahorros', 
 'Escanea este código QR o realiza tu transferencia directa',
 'assets/images/qr-bancolombia-placeholder.png',
 'Banco: Bancolombia\nTipo: Cuenta de Ahorros\nNúmero: 123-456789-01\nTitular: Fundación Hogar Santo Domingo\nNIT: 123456789-0',
 1, 
 2),
('Daviplata', 
 'Donación rápida con Daviplata',
 'assets/images/qr-daviplata-placeholder.png',
 'Daviplata: 321 654 9876\nTitular: Fundación Hogar Santo Domingo',
 1, 
 3);

-- =====================================================
-- COMENTARIOS Y DOCUMENTACIÓN
-- =====================================================

/*
PROPÓSITO:
- Almacenar códigos QR para diferentes métodos de donación
- Permitir al administrador actualizar QR y datos de cuenta
- Soportar múltiples métodos de pago (Nequi, Bancolombia, Daviplata, etc.)

CAMPOS IMPORTANTES:
- qr_image_url: Ruta a la imagen del código QR
- texto_cuenta: Información de cuenta que se muestra junto al QR
- orden: Para ordenar los QR en la página de donaciones
- activo: Para activar/desactivar un QR sin borrarlo

USO:
- Frontend: Muestra QR activos ordenados por 'orden'
- Admin: CRUD completo para gestionar QR
*/

