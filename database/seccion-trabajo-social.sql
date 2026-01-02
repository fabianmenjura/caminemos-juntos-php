-- =====================================================
-- TABLA PARA SECCIÓN TRABAJO SOCIAL
-- =====================================================

CREATE TABLE IF NOT EXISTS seccion_trabajo_social (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(50) NOT NULL UNIQUE COMMENT 'titulo, descripcion, imagen',
    contenido TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_clave (clave)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar valores iniciales
INSERT INTO seccion_trabajo_social (clave, contenido) VALUES
('titulo', 'Nuestro Trabajo Social'),
('descripcion', 'En Caminemos Juntos, el trabajo social es el corazón de nuestra misión. Acompañamos a los adultos mayores de Chiquinquirá con programas integrales que mejoran su calidad de vida y les brindan el apoyo que necesitan.'),
('imagen', 'assets/images/trabajo-social-placeholder.jpg')
ON DUPLICATE KEY UPDATE contenido = VALUES(contenido);

-- =====================================================
-- COMENTARIOS Y DOCUMENTACIÓN
-- =====================================================

/*
PROPÓSITO:
- Almacenar el contenido de la sección "Trabajo Social"
- Permitir al administrador actualizar título, descripción e imagen
- Mostrar información sobre el trabajo social realizado

ESTRUCTURA:
- titulo: Título de la sección
- descripcion: Descripción del trabajo social
- imagen: URL de la imagen principal de la sección

NOTAS:
- La imagen debe ser administrable desde el panel admin
- El contenido puede incluir HTML básico si es necesario
*/

