-- ========================================
-- Agregar imagen a sección "Acerca de"
-- ========================================

-- NOTA: Este campo NO se usa, la imagen se guarda como un registro más
-- en la tabla seccion_acerca_de con clave='imagen_principal'

-- Insertar valor predeterminado para la imagen si no existe
INSERT INTO seccion_acerca_de (clave, contenido)
VALUES ('imagen_principal', 'assets/images/about-section.jpg')
ON DUPLICATE KEY UPDATE contenido = contenido;

-- Verificar que se insertó correctamente
SELECT clave, contenido FROM seccion_acerca_de WHERE clave = 'imagen_principal';

