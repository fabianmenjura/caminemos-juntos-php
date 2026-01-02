-- =====================================================
-- SCRIPT MAESTRO - INSTALACIÓN COMPLETA DE BASE DE DATOS
-- =====================================================
-- Este archivo ejecuta TODOS los scripts SQL necesarios
-- para tener el sistema funcionando al 100%
-- 
-- INSTRUCCIONES:
-- 1. Abrir phpMyAdmin: http://localhost/phpmyadmin
-- 2. Crear base de datos:
--    CREATE DATABASE caminemos_juntos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- 3. Seleccionar la base de datos (click en el nombre)
-- 4. Ir a pestaña "SQL"
-- 5. Copiar y pegar TODO este archivo
-- 6. Click en "Continuar"
-- 
-- IMPORTANTE: Este proceso puede tardar 1-2 minutos
-- =====================================================

USE caminemos_juntos;

-- =====================================================
-- PASO 1: ESTRUCTURA BASE
-- =====================================================

SOURCE database/schema.sql;
SELECT '✅ Schema principal creado' as PASO_1;

-- =====================================================
-- PASO 2: DATOS DE EJEMPLO
-- =====================================================

SOURCE database/seed.sql;
SELECT '✅ Datos de ejemplo insertados' as PASO_2;

-- =====================================================
-- PASO 3: SISTEMA DE ADMINISTRACIÓN
-- =====================================================

SOURCE database/admin-schema.sql;
SELECT '✅ Sistema de admin creado (Usuario: admin / Admin123!)' as PASO_3;

-- =====================================================
-- PASO 4: FUNCIONALIDADES ADICIONALES
-- =====================================================

SOURCE database/nuevas-funcionalidades.sql;
SELECT '✅ Voluntarios, testimonios y patrocinadores creados' as PASO_4;

-- =====================================================
-- PASO 5: SOFT DELETE
-- =====================================================

SOURCE database/add-soft-delete.sql;
SELECT '✅ Soft delete agregado a todas las tablas' as PASO_5;

-- =====================================================
-- PASO 6: HERO SLIDER
-- =====================================================

SOURCE database/hero-slider.sql;
SELECT '✅ Hero slider creado' as PASO_6;

-- =====================================================
-- PASO 7: OPTIMIZACIÓN DE EDAD
-- =====================================================

SOURCE database/optimizar-edad-cumpleanos.sql;
SELECT '✅ Sistema de edad optimizado' as PASO_7;

-- =====================================================
-- PASO 8: CATEGORÍAS DE VOLUNTARIADO
-- =====================================================

SOURCE database/categorias-voluntariado.sql;
SELECT '✅ Categorías de voluntariado creadas' as PASO_8;

-- =====================================================
-- PASO 9: SECCIÓN "ACERCA DE"
-- =====================================================

SOURCE database/seccion-acerca-de.sql;
SELECT '✅ Sección Acerca de creada' as PASO_9;

-- =====================================================
-- PASO 10: CONFIGURACIÓN GENERAL
-- =====================================================

SOURCE database/configuracion-general.sql;
SELECT '✅ Configuración general creada' as PASO_10;

-- =====================================================
-- PASO 11: QR DE DONACIONES
-- =====================================================

SOURCE database/qr-donaciones.sql;
SELECT '✅ QR de donaciones creado' as PASO_11;

-- =====================================================
-- PASO 12: SISTEMA DE NOTIFICACIONES
-- =====================================================

SOURCE database/sistema-notificaciones.sql;
SELECT '✅ Sistema de notificaciones creado' as PASO_12;

-- =====================================================
-- PASO 13: TRIGGERS DE NOTIFICACIONES
-- =====================================================

SOURCE database/crear-triggers-notificaciones.sql;
SELECT '✅ Triggers de notificaciones creados (6 triggers)' as PASO_13;

-- =====================================================
-- VERIFICACIÓN FINAL
-- =====================================================

SELECT '🎉 ¡INSTALACIÓN COMPLETADA EXITOSAMENTE!' as RESULTADO;

SELECT 
    'Tablas creadas:' as INFO,
    COUNT(*) as TOTAL
FROM information_schema.tables 
WHERE table_schema = 'caminemos_juntos';

SELECT 
    'Triggers creados:' as INFO,
    COUNT(*) as TOTAL
FROM information_schema.triggers
WHERE trigger_schema = 'caminemos_juntos';

-- =====================================================
-- SIGUIENTE PASO
-- =====================================================
-- Ahora puedes acceder a:
-- Frontend: http://localhost/caminemos-juntos-php/index.html
-- Admin: http://localhost/caminemos-juntos-php/admin/login.html
--        Usuario: admin
--        Contraseña: Admin123!
-- =====================================================

