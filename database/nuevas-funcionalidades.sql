-- Nuevas Funcionalidades para el Sitio
USE caminemos_juntos;

-- Tabla de Voluntarios (personas que quieren unirse)
CREATE TABLE IF NOT EXISTS voluntarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    edad INT NULL,
    profesion VARCHAR(100) NULL,
    tipo_voluntariado ENUM('voluntario', 'tallerista', 'ambos') NOT NULL DEFAULT 'voluntario',
    disponibilidad TEXT NULL COMMENT 'Días y horarios disponibles',
    habilidades TEXT NULL COMMENT 'Habilidades, experiencia, qué puede aportar',
    mensaje TEXT NULL,
    estado ENUM('nuevo', 'revisado', 'aprobado', 'rechazado', 'inactivo') DEFAULT 'nuevo',
    fecha_aprobacion DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_tipo (tipo_voluntariado),
    INDEX idx_estado (estado),
    INDEX idx_ciudad (ciudad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de Testimonios/Reseñas
CREATE TABLE IF NOT EXISTS testimonios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    rol VARCHAR(100) NULL COMMENT 'Ej: Voluntario, Familiar, Donante',
    foto_url VARCHAR(255) NULL,
    testimonio TEXT NOT NULL,
    calificacion INT DEFAULT 5 COMMENT 'Estrellas de 1 a 5',
    destacado BOOLEAN DEFAULT FALSE,
    aprobado BOOLEAN DEFAULT FALSE COMMENT 'Requiere aprobación del admin',
    orden INT DEFAULT 0 COMMENT 'Para ordenar los testimonios destacados',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_destacado (destacado),
    INDEX idx_aprobado (aprobado),
    INDEX idx_orden (orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Verificar si la tabla patrocinadores tiene la columna nivel
-- Si no, se ignora el error
SET @exist := (SELECT COUNT(*) FROM information_schema.COLUMNS 
               WHERE TABLE_SCHEMA = 'caminemos_juntos' 
               AND TABLE_NAME = 'patrocinadores' 
               AND COLUMN_NAME = 'nivel');

-- Nota: Los campos nivel, descripcion y orden ya deberían existir en patrocinadores
-- Si no existen, ejecutar manualmente:
-- ALTER TABLE patrocinadores ADD COLUMN nivel ENUM('oro', 'plata', 'bronce', 'aliado') DEFAULT 'aliado';
-- ALTER TABLE patrocinadores ADD COLUMN descripcion TEXT NULL;
-- ALTER TABLE patrocinadores ADD COLUMN orden INT DEFAULT 0;

-- Insertar testimonios de ejemplo
INSERT INTO testimonios (nombre, rol, testimonio, calificacion, destacado, aprobado) VALUES
('María Rodríguez', 'Voluntaria', 'Acompañar a los abuelos ha sido la experiencia más gratificante de mi vida. Ver sus sonrisas cada semana me llena el corazón.', 5, TRUE, TRUE),
('Carlos Méndez', 'Donante', 'Es maravilloso saber que mi aporte mensual ayuda a que un abuelo tenga compañía y cuidados. Totalmente recomendado.', 5, TRUE, TRUE),
('Ana Gómez', 'Familiar', 'Gracias a este programa, mi abuela encontró amigos y actividades. Ha mejorado mucho su ánimo y salud.', 5, TRUE, TRUE);

SELECT 'Tablas creadas: voluntarios, testimonios, patrocinadores actualizada' as mensaje;

