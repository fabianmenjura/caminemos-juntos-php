-- Agregar columna 'deleted' para soft delete en todas las tablas
USE caminemos_juntos;

-- Nota: Si la columna ya existe, ignorar el error

-- Abuelos
ALTER TABLE abuelos ADD COLUMN deleted TINYINT(1) DEFAULT 0 AFTER estado;

-- Donaciones Personas
ALTER TABLE donaciones_personas ADD COLUMN deleted TINYINT(1) DEFAULT 0 AFTER estado;

-- Donaciones Empresas
ALTER TABLE donaciones_empresas ADD COLUMN deleted TINYINT(1) DEFAULT 0 AFTER estado;

-- Mensajes Contacto
ALTER TABLE mensajes_contacto ADD COLUMN deleted TINYINT(1) DEFAULT 0 AFTER estado;

-- Voluntarios
ALTER TABLE voluntarios ADD COLUMN deleted TINYINT(1) DEFAULT 0 AFTER estado;

-- Testimonios
ALTER TABLE testimonios ADD COLUMN deleted TINYINT(1) DEFAULT 0 AFTER aprobado;

-- Patrocinadores
ALTER TABLE patrocinadores ADD COLUMN deleted TINYINT(1) DEFAULT 0 AFTER activo;

-- Cumpleaños
ALTER TABLE cumpleanos ADD COLUMN deleted TINYINT(1) DEFAULT 0;

-- Crear índices para mejorar performance
CREATE INDEX idx_deleted_abuelos ON abuelos(deleted);
CREATE INDEX idx_deleted_donaciones_personas ON donaciones_personas(deleted);
CREATE INDEX idx_deleted_donaciones_empresas ON donaciones_empresas(deleted);
CREATE INDEX idx_deleted_mensajes ON mensajes_contacto(deleted);
CREATE INDEX idx_deleted_voluntarios ON voluntarios(deleted);
CREATE INDEX idx_deleted_testimonios ON testimonios(deleted);
CREATE INDEX idx_deleted_patrocinadores ON patrocinadores(deleted);

SELECT 'Columna deleted agregada a todas las tablas' as mensaje;

