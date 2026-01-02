-- =====================================================
-- TABLA PARA GALERÍA DE IMÁGENES
-- =====================================================

CREATE TABLE IF NOT EXISTS galeria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) DEFAULT NULL COMMENT 'Título opcional de la imagen',
    descripcion TEXT DEFAULT NULL COMMENT 'Descripción opcional',
    imagen_url VARCHAR(500) NOT NULL COMMENT 'URL de la imagen',
    orden INT DEFAULT 0 COMMENT 'Orden de visualización',
    activo TINYINT(1) DEFAULT 1 COMMENT 'Visible en la galería pública',
    categoria VARCHAR(100) DEFAULT NULL COMMENT 'Categoría opcional para filtrar',
    deleted TINYINT(1) DEFAULT 0 COMMENT 'Soft delete',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_activo_deleted (activo, deleted),
    INDEX idx_orden (orden),
    INDEX idx_categoria (categoria)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- COMENTARIOS Y DOCUMENTACIÓN
-- =====================================================

/*
PROPÓSITO:
- Almacenar imágenes de la galería pública
- Permitir al administrador gestionar múltiples imágenes
- Soporte para categorías, ordenamiento y activación/desactivación

ESTRUCTURA:
- titulo: Título opcional de la imagen
- descripcion: Descripción opcional de la imagen
- imagen_url: Ruta de la imagen (relativa desde assets/images/)
- orden: Orden de visualización (menor número = aparece primero)
- activo: Si está activo, se muestra en la galería pública
- categoria: Categoría opcional para agrupar/filtrar imágenes

NOTAS:
- Las imágenes deben subirse primero usando el endpoint de upload
- Se usa soft delete (deleted = 1) en lugar de eliminar físicamente
- El orden permite reorganizar las imágenes fácilmente
*/

