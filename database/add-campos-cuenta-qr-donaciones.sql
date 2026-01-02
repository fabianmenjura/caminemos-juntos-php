-- =====================================================
-- MIGRACIÓN: Agregar campos estructurados de cuenta
-- a la tabla qr_donaciones
-- =====================================================

ALTER TABLE qr_donaciones
ADD COLUMN numero_cuenta VARCHAR(50) NULL COMMENT 'Número de cuenta bancaria o móvil' AFTER texto_cuenta,
ADD COLUMN titular VARCHAR(255) NULL COMMENT 'Nombre del titular de la cuenta' AFTER numero_cuenta,
ADD COLUMN banco VARCHAR(100) NULL COMMENT 'Nombre del banco o método de pago' AFTER titular,
ADD COLUMN tipo_cuenta VARCHAR(50) NULL COMMENT 'Tipo de cuenta (Ahorros, Corriente, Nequi, Daviplata, etc)' AFTER banco,
ADD COLUMN nit VARCHAR(50) NULL COMMENT 'NIT o documento de identificación' AFTER tipo_cuenta;

-- Actualizar datos existentes desde texto_cuenta (si existe)
-- Esto es opcional, los datos pueden actualizarse manualmente desde el admin

SELECT 'Campos de cuenta agregados correctamente a qr_donaciones' as mensaje;

