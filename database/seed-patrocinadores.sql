-- Insertar patrocinadores de ejemplo
USE caminemos_juntos;

-- Limpiar datos existentes
TRUNCATE TABLE patrocinadores;

-- Insertar patrocinadores
INSERT INTO patrocinadores (nombre_empresa, logo_url, sitio_web, descripcion, orden, activo) VALUES
('Fundación Corazón Solidario', 'assets/images/company-logo-1.jpg', 'https://fundacion-ejemplo.com', 'Aliado principal de nuestras actividades', 1, 1),
('Empresa de Alimentos S.A.', 'assets/images/company-logo-2.jpg', 'https://alimentos-ejemplo.com', 'Proveedor de alimentos para los abuelos', 2, 1),
('Grupo Farmacéutico', 'assets/images/company-logo-3.jpg', 'https://farma-ejemplo.com', 'Donación de medicamentos', 3, 1),
('Banco Solidario', 'assets/images/company-logo-4.jpg', null, 'Apoyo financiero para nuestros programas', 4, 1),
('Clínica Salud Total', 'assets/images/company-logo-5.jpg', null, 'Atención médica gratuita', 5, 1),
('Supermercados Unidos', 'assets/images/company-logo-6.jpg', null, 'Donación de víveres mensuales', 6, 1);

SELECT 'Patrocinadores insertados correctamente' as mensaje;

