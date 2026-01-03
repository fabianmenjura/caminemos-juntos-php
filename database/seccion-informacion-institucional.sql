-- =====================================================
-- TABLA PARA SECCIÓN INFORMACIÓN INSTITUCIONAL
-- =====================================================

CREATE TABLE IF NOT EXISTS seccion_informacion_institucional (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(50) NOT NULL UNIQUE COMMENT 'titulo, subtitulo, descripcion, imagen',
    contenido TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_clave (clave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar valores iniciales
INSERT INTO seccion_informacion_institucional (clave, contenido) VALUES
('titulo', 'Información Institucional'),
('subtitulo', 'Conoce más sobre nuestra organización'),
('descripcion', 'Caminemos Juntos es una organización sin ánimo de lucro comprometida con el bienestar y acompañamiento de los adultos mayores en Chiquinquirá, Boyacá. Trabajamos incansablemente para conectar corazones generosos con quienes más lo necesitan, brindando acompañamiento integral, apoyo emocional y económico para mejorar su calidad de vida.'),
('imagen', 'assets/images/informacion-institucional-placeholder.jpg')
ON DUPLICATE KEY UPDATE contenido = VALUES(contenido);

