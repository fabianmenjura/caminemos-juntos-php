-- Script para insertar datos de ejemplo
-- Ejecutar después de crear las tablas

USE caminemos_juntos;

-- Insertar abuelos de ejemplo
INSERT INTO abuelos (nombre, edad, ciudad, descripcion, foto_url, fecha_nacimiento, estado) VALUES
('María González', 78, 'Bogotá', 'Me encanta cocinar y compartir historias de mi juventud. Busco alguien con quien conversar y compartir recetas tradicionales.', 'assets/images/elderly-colombian-woman-smiling-warm.jpg', '1947-03-15', 'disponible'),
('Carlos Ramírez', 82, 'Medellín', 'Fui maestro durante 40 años. Me gusta leer, escuchar música clásica y hablar sobre historia colombiana.', 'assets/images/elderly-colombian-man-smiling-kind.jpg', '1943-07-22', 'disponible'),
('Rosa Martínez', 75, 'Cali', 'Amo las flores y la jardinería. Me gustaría tener compañía para pasear por el parque y compartir momentos alegres.', 'assets/images/elderly-colombian-woman-happy-flowers.jpg', '1950-11-08', 'disponible'),
('José Hernández', 80, 'Cartagena', 'Soy un hombre alegre que disfruta de la música vallenata y contar anécdotas de la costa. Busco amistad sincera.', 'assets/images/elderly-colombian-man-friendly-wise.jpg', '1945-05-30', 'disponible'),
('Ana López', 76, 'Barranquilla', 'Me encanta cocinar platos típicos costeños. Busco alguien que aprecie la buena comida y la conversación amena.', 'assets/images/elderly-colombian-woman-cooking-happy.jpg', '1949-09-12', 'disponible'),
('Pedro Sánchez', 79, 'Bucaramanga', 'Bailar ha sido mi pasión toda la vida. Aunque ya no bailo como antes, me encanta la música y la alegría.', 'assets/images/elderly-colombian-man-dancing-joyful.jpg', '1946-01-25', 'disponible');

-- Insertar cumpleaños del mes actual
INSERT INTO cumpleanos (abuelo_id, fecha_cumpleanos, destacado) VALUES
(1, '2025-01-15', TRUE),
(3, '2025-01-08', TRUE),
(5, '2025-01-12', FALSE);

-- Insertar patrocinadores de ejemplo
INSERT INTO patrocinadores (nombre_empresa, logo_url, sitio_web, activo, orden) VALUES
('Empresa Solidaria S.A.', 'assets/images/company-logo-1.jpg', 'https://ejemplo.com', TRUE, 1),
('Fundación Corazón', 'assets/images/company-logo-2.jpg', 'https://ejemplo.com', TRUE, 2),
('Grupo Empresarial Unidos', 'assets/images/company-logo-3.jpg', 'https://ejemplo.com', TRUE, 3),
('Corporación Ayuda', 'assets/images/company-logo-4.jpg', 'https://ejemplo.com', TRUE, 4),
('Alianza por los Abuelos', 'assets/images/company-logo-5.jpg', 'https://ejemplo.com', TRUE, 5),
('Red de Apoyo Social', 'assets/images/company-logo-6.jpg', 'https://ejemplo.com', TRUE, 6);
