-- Script para ejecutar todas las migraciones necesarias
-- Ejecutar desde phpMyAdmin o consola MySQL

USE caminemos_juntos;

-- 1. Categorías de Voluntariado
SOURCE database/categorias-voluntariado.sql;

-- 2. Sección "Acerca de"
SOURCE database/seccion-acerca-de.sql;

-- 3. Configuración General
SOURCE database/configuracion-general.sql;

-- Verificar que todo se creó correctamente
SELECT 'Tablas creadas:' as mensaje;
SHOW TABLES LIKE 'categorias_voluntariado';
SHOW TABLES LIKE 'seccion_acerca_de';
SHOW TABLES LIKE 'caracteristicas_acerca_de';
SHOW TABLES LIKE 'configuracion_general';
SHOW TABLES LIKE 'redes_sociales';

SELECT 'Datos insertados:' as mensaje;
SELECT COUNT(*) as total_categorias_voluntariado FROM categorias_voluntariado;
SELECT COUNT(*) as total_acerca_de FROM seccion_acerca_de;
SELECT COUNT(*) as total_caracteristicas FROM caracteristicas_acerca_de;
SELECT COUNT(*) as total_configuracion FROM configuracion_general;
SELECT COUNT(*) as total_redes FROM redes_sociales;

