-- Tabla para Categorías de Voluntariado (Administrable)
CREATE TABLE IF NOT EXISTS categorias_voluntariado (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    descripcion TEXT NOT NULL,
    icono VARCHAR(50) NOT NULL DEFAULT 'fa-hands-helping',
    imagen_url VARCHAR(255),
    caracteristicas JSON,
    orden INT DEFAULT 0,
    destacado TINYINT(1) DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    deleted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_activo_deleted (activo, deleted),
    INDEX idx_orden (orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar categorías iniciales
INSERT INTO categorias_voluntariado (titulo, slug, descripcion, icono, imagen_url, caracteristicas, orden, destacado, activo) VALUES
('Voluntario', 'voluntario', 'Dedica tu tiempo acompañando a nuestros abuelos en actividades diarias, visitas domiciliarias y eventos especiales. Tu presencia hace la diferencia.', 'fa-hands-helping', 'assets/images/voluntario-placeholder.jpg', '["Visitas semanales", "Acompañamiento personalizado", "Actividades recreativas", "Eventos especiales"]', 1, 0, 1),
('Tallerista', 'tallerista', 'Comparte tus conocimientos y habilidades impartiendo talleres educativos, artísticos o recreativos que enriquezcan la vida de nuestros abuelos.', 'fa-chalkboard-teacher', 'assets/images/tallerista-placeholder.jpg', '["Talleres mensuales", "Enseñanza de habilidades", "Actividades grupales", "Desarrollo creativo"]', 2, 0, 1),
('Apadrinamiento', 'apadrinamiento', 'Conviértete en el ángel guardián de un abuelo. Brinda apoyo económico mensual y acompañamiento emocional para transformar completamente su vida.', 'fa-heart', 'assets/images/apadrinamiento-placeholder.jpg', '["Apoyo económico mensual", "Seguimiento personalizado", "Vínculo especial", "Impacto directo"]', 3, 1, 1);

-- Nota: Las características se guardan como JSON array
-- Ejemplo: ["Característica 1", "Característica 2", "Característica 3"]

