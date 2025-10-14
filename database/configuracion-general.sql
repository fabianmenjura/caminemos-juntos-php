-- Tabla para Configuración General del Sitio
CREATE TABLE IF NOT EXISTS configuracion_general (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(50) NOT NULL UNIQUE COMMENT 'Identificador único',
    valor TEXT,
    descripcion VARCHAR(255) COMMENT 'Descripción del campo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para Redes Sociales
CREATE TABLE IF NOT EXISTS redes_sociales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    icono VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    orden INT DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_activo_deleted (activo, deleted),
    INDEX idx_orden (orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar configuración inicial
INSERT INTO configuracion_general (clave, valor, descripcion) VALUES
('site_name', 'Caminemos Juntos', 'Nombre del sitio'),
('site_description', 'Conectamos corazones generosos con adultos mayores que necesitan compañía, apoyo económico y sobre todo, mucho cariño.', 'Descripción del sitio'),
('logo_url', 'assets/images/placeholder-logo.png', 'URL del logo'),

-- Contacto
('email', 'contacto@caminemosjuntos.org', 'Email de contacto'),
('telefono', '+57 321 9951293', 'Teléfono de contacto'),
('telefono_display', '+57 321 9951293', 'Teléfono para mostrar'),
('direccion', 'Chiquinquirá, Boyacá', 'Dirección física'),

-- WhatsApp
('whatsapp_numero', '573219951293', 'Número de WhatsApp (formato internacional sin +)'),
('whatsapp_mensaje', '¡Hola! Me gustaría obtener más información sobre Caminemos Juntos y cómo puedo ayudar a los adultos mayores de Chiquinquirá. 😊', 'Mensaje predeterminado de WhatsApp');

-- Insertar redes sociales iniciales
INSERT INTO redes_sociales (nombre, icono, url, orden, activo) VALUES
('Facebook', 'fa-facebook-f', 'https://www.facebook.com/caminemosjuntos', 1, 1),
('Instagram', 'fa-instagram', 'https://www.instagram.com/caminemosjuntos', 2, 1),
('WhatsApp', 'fa-whatsapp', 'https://wa.me/573219951293', 3, 1);

-- Nota: El mensaje de WhatsApp se codificará en URL cuando se use
-- Ejemplo: https://wa.me/573219951293?text={mensaje_codificado}

