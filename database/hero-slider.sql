-- Tabla para gestionar imágenes del Hero Slider
USE caminemos_juntos;

CREATE TABLE IF NOT EXISTS hero_slider (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL COMMENT 'Título que aparece sobre la imagen',
    subtitulo TEXT NULL COMMENT 'Subtítulo o descripción',
    imagen_url VARCHAR(255) NOT NULL,
    orden INT DEFAULT 0 COMMENT 'Orden de aparición',
    activo TINYINT(1) DEFAULT 1,
    enlace_url VARCHAR(255) NULL COMMENT 'URL a donde redirige al hacer clic',
    deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_activo (activo),
    INDEX idx_orden (orden),
    INDEX idx_deleted (deleted)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar imágenes de ejemplo (usando las de abuelos existentes)
INSERT INTO hero_slider (titulo, subtitulo, imagen_url, orden, activo) VALUES
('Regala Compañía y Esperanza', 'Miles de adultos mayores en Colombia necesitan tu apoyo. Juntos podemos hacer la diferencia.', 'assets/images/elderly-colombian-woman-smiling-warm.jpg', 1, 1),
('Un Gesto de Amor', 'Tu donación transforma vidas. Ayúdanos a llevar alegría a quienes más lo necesitan.', 'assets/images/elderly-colombian-man-smiling-kind.jpg', 2, 1),
('Juntos por los Abuelos', 'Cada sonrisa cuenta. Únete a nuestra causa y cambia una vida hoy.', 'assets/images/elderly-colombian-woman-happy-flowers.jpg', 3, 1);

SELECT 'Tabla hero_slider creada con éxito' as mensaje;

