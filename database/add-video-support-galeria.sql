-- =====================================================
-- AGREGAR SOPORTE PARA VIDEOS DE YOUTUBE EN GALERÍA
-- =====================================================

-- Agregar campo para tipo de contenido (imagen o video)
-- Primero hacer imagen_url nullable si no lo es ya
ALTER TABLE galeria MODIFY COLUMN imagen_url VARCHAR(500) DEFAULT NULL COMMENT 'URL de la imagen (NULL si es video)';

-- Agregar campo para tipo de contenido (imagen o video)
ALTER TABLE galeria 
ADD COLUMN tipo ENUM('imagen', 'video') DEFAULT 'imagen' AFTER imagen_url,
ADD COLUMN video_url VARCHAR(500) DEFAULT NULL COMMENT 'URL completa del video de YouTube' AFTER tipo;

-- Agregar índice para mejorar consultas
ALTER TABLE galeria ADD INDEX idx_tipo (tipo);

-- =====================================================
-- COMENTARIOS
-- =====================================================

/*
CAMBIOS REALIZADOS:
- tipo: Indica si el contenido es una 'imagen' o un 'video'
- video_url: URL completa del video de YouTube (ej: https://www.youtube.com/watch?v=VIDEO_ID)

NOTAS:
- Si tipo = 'imagen', se usa imagen_url
- Si tipo = 'video', se usa video_url
- El campo imagen_url puede quedar NULL si es un video
- El campo video_url puede quedar NULL si es una imagen
*/

