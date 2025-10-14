-- Tabla para la Sección "Acerca de" (Administrable)
CREATE TABLE IF NOT EXISTS seccion_acerca_de (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(50) NOT NULL UNIQUE COMMENT 'Identificador único: titulo, descripcion',
    contenido TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla para las Cards/Características de "Acerca de"
CREATE TABLE IF NOT EXISTS caracteristicas_acerca_de (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    icono VARCHAR(50) NOT NULL DEFAULT 'fa-heart',
    orden INT DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_activo_deleted (activo, deleted),
    INDEX idx_orden (orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar contenido inicial de "Acerca de"
INSERT INTO seccion_acerca_de (clave, contenido) VALUES
('titulo', '¿Qué es Caminemos Juntos?'),
('descripcion', 'Somos una iniciativa que conecta a personas generosas con adultos mayores que necesitan compañía, apoyo económico y sobre todo, cariño.');

-- Insertar características iniciales
INSERT INTO caracteristicas_acerca_de (titulo, descripcion, icono, orden, activo) VALUES
('Compañía', 'Proporcionamos acompañamiento emocional y social a adultos mayores que viven solos.', 'fa-heart', 1, 1),
('Alimentación', 'Garantizamos una alimentación nutritiva y balanceada para cada abuelo adoptado.', 'fa-utensils', 2, 1),
('Medicamentos', 'Cubrimos los gastos de medicamentos y atención médica básica.', 'fa-pills', 3, 1);

-- Nota: La tabla seccion_acerca_de es simple (clave-valor) para título y descripción
-- Las características son más flexibles con orden, activo/inactivo, soft delete

