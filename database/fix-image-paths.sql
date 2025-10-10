-- Script para corregir las rutas de las imágenes en la base de datos
-- Las rutas deben ser relativas, no absolutas

-- Actualizar las rutas de los abuelos
UPDATE abuelos SET foto_url = 'assets/images/elderly-colombian-woman-smiling-warm.jpg' WHERE foto_url LIKE '%elderly-colombian-woman-smiling-warm.jpg%';
UPDATE abuelos SET foto_url = 'assets/images/elderly-colombian-man-smiling-kind.jpg' WHERE foto_url LIKE '%elderly-colombian-man-smiling-kind.jpg%';
UPDATE abuelos SET foto_url = 'assets/images/elderly-colombian-woman-happy-flowers.jpg' WHERE foto_url LIKE '%elderly-colombian-woman-happy-flowers.jpg%';
UPDATE abuelos SET foto_url = 'assets/images/elderly-colombian-man-friendly-wise.jpg' WHERE foto_url LIKE '%elderly-colombian-man-friendly-wise.jpg%';
UPDATE abuelos SET foto_url = 'assets/images/elderly-colombian-woman-cooking-happy.jpg' WHERE foto_url LIKE '%elderly-colombian-woman-cooking-happy.jpg%';
UPDATE abuelos SET foto_url = 'assets/images/elderly-colombian-man-dancing-joyful.jpg' WHERE foto_url LIKE '%elderly-colombian-man-dancing-joyful.jpg%';

-- Verificar los cambios
SELECT id, nombre, foto_url FROM abuelos;

