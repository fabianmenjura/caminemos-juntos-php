-- Optimización: Calcular edad desde fecha_nacimiento
-- Y sistema de mensajes de cumpleaños

USE caminemos_juntos;

-- Eliminar columna edad (redundante)
ALTER TABLE abuelos DROP COLUMN edad;

-- Crear tabla de mensajes de cumpleaños
CREATE TABLE IF NOT EXISTS mensajes_cumpleanos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    abuelo_id INT NOT NULL,
    nombre_remitente VARCHAR(150) NOT NULL,
    email_remitente VARCHAR(150) NULL,
    mensaje TEXT NOT NULL,
    fecha_envio DATE NOT NULL,
    aprobado TINYINT(1) DEFAULT 0 COMMENT 'Requiere aprobación del admin',
    mostrado TINYINT(1) DEFAULT 0 COMMENT 'Si ya se mostró al abuelo',
    deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (abuelo_id) REFERENCES abuelos(id),
    INDEX idx_abuelo (abuelo_id),
    INDEX idx_fecha (fecha_envio),
    INDEX idx_aprobado (aprobado),
    INDEX idx_deleted (deleted)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Nota: La edad ahora se calcula directamente en las consultas usando:
-- TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) as edad

-- Para verificar cumpleaños de hoy:
-- (DATE_FORMAT(fecha_nacimiento, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d'))

-- Para verificar cumpleaños en los próximos 7 días:
-- (DAYOFYEAR(fecha_nacimiento) BETWEEN DAYOFYEAR(CURDATE()) AND DAYOFYEAR(CURDATE() + INTERVAL 7 DAY))

SELECT 'Optimización completada: edad calculada dinámicamente en consultas' as mensaje;

