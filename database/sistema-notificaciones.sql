-- =====================================================
-- SISTEMA DE NOTIFICACIONES PARA EL ADMIN
-- =====================================================

CREATE TABLE IF NOT EXISTS notificaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50) NOT NULL COMMENT 'cumpleanos, mensaje, felicitacion, donacion, testimonio, voluntario',
    titulo VARCHAR(255) NOT NULL,
    mensaje TEXT NOT NULL,
    icono VARCHAR(50) DEFAULT 'fa-bell',
    color VARCHAR(20) DEFAULT 'primary' COMMENT 'primary, success, warning, danger, info',
    enlace VARCHAR(255) COMMENT 'URL para ver más detalles',
    leida TINYINT(1) DEFAULT 0,
    referencia_id INT COMMENT 'ID del registro relacionado',
    referencia_tabla VARCHAR(50) COMMENT 'Tabla del registro relacionado',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_leida (leida),
    INDEX idx_tipo (tipo),
    INDEX idx_created (created_at),
    INDEX idx_referencia (referencia_tabla, referencia_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TRIGGERS PARA GENERAR NOTIFICACIONES AUTOMÁTICAS
-- =====================================================

-- Trigger: Nueva donación de persona
DELIMITER $$
CREATE TRIGGER notif_nueva_donacion_persona
AFTER INSERT ON donaciones_personas
FOR EACH ROW
BEGIN
    INSERT INTO notificaciones (tipo, titulo, mensaje, icono, color, enlace, referencia_id, referencia_tabla)
    VALUES (
        'donacion',
        'Nueva Donación Recibida',
        CONCAT('💝 ', NEW.nombre_completo, ' ha realizado una donación', 
               IF(NEW.monto > 0, CONCAT(' de $', FORMAT(NEW.monto, 0)), ''), 
               ' desde ', NEW.ciudad),
        'fa-hand-holding-heart',
        'success',
        'donaciones.html',
        NEW.id,
        'donaciones_personas'
    );
END$$
DELIMITER ;

-- Trigger: Nueva donación de empresa
DELIMITER $$
CREATE TRIGGER notif_nueva_donacion_empresa
AFTER INSERT ON donaciones_empresas
FOR EACH ROW
BEGIN
    INSERT INTO notificaciones (tipo, titulo, mensaje, icono, color, enlace, referencia_id, referencia_tabla)
    VALUES (
        'donacion',
        'Nueva Donación Empresarial',
        CONCAT('🏢 ', NEW.nombre_empresa, ' ha realizado una donación', 
               IF(NEW.monto > 0, CONCAT(' de $', FORMAT(NEW.monto, 0)), '')),
        'fa-building',
        'success',
        'donaciones.html',
        NEW.id,
        'donaciones_empresas'
    );
END$$
DELIMITER ;

-- Trigger: Nuevo mensaje de contacto
DELIMITER $$
CREATE TRIGGER notif_nuevo_mensaje
AFTER INSERT ON mensajes_contacto
FOR EACH ROW
BEGIN
    INSERT INTO notificaciones (tipo, titulo, mensaje, icono, color, enlace, referencia_id, referencia_tabla)
    VALUES (
        'mensaje',
        'Nuevo Mensaje de Contacto',
        CONCAT('✉️ ', NEW.nombre, ' te ha enviado un mensaje desde ', NEW.ciudad),
        'fa-envelope',
        'info',
        'mensajes.html',
        NEW.id,
        'mensajes_contacto'
    );
END$$
DELIMITER ;

-- Trigger: Nueva felicitación de cumpleaños
DELIMITER $$
CREATE TRIGGER notif_nueva_felicitacion
AFTER INSERT ON mensajes_cumpleanos
FOR EACH ROW
BEGIN
    DECLARE nombre_abuelo VARCHAR(255);
    
    SELECT nombre_completo INTO nombre_abuelo 
    FROM abuelos 
    WHERE id = NEW.abuelo_id;
    
    INSERT INTO notificaciones (tipo, titulo, mensaje, icono, color, enlace, referencia_id, referencia_tabla)
    VALUES (
        'felicitacion',
        'Nueva Felicitación de Cumpleaños',
        CONCAT('🎂 ', NEW.nombre_remitente, ' envió una felicitación para ', nombre_abuelo),
        'fa-birthday-cake',
        'warning',
        'mensajes-cumpleanos.html',
        NEW.id,
        'mensajes_cumpleanos'
    );
END$$
DELIMITER ;

-- Trigger: Nuevo voluntario
DELIMITER $$
CREATE TRIGGER notif_nuevo_voluntario
AFTER INSERT ON voluntarios
FOR EACH ROW
BEGIN
    INSERT INTO notificaciones (tipo, titulo, mensaje, icono, color, enlace, referencia_id, referencia_tabla)
    VALUES (
        'voluntario',
        'Nueva Solicitud de Voluntariado',
        CONCAT('👋 ', NEW.nombre_completo, ' quiere ser voluntario en ', NEW.tipo_voluntariado),
        'fa-user-friends',
        'primary',
        'voluntarios.html',
        NEW.id,
        'voluntarios'
    );
END$$
DELIMITER ;

-- Trigger: Nuevo testimonio
DELIMITER $$
CREATE TRIGGER notif_nuevo_testimonio
AFTER INSERT ON testimonios
FOR EACH ROW
BEGIN
    INSERT INTO notificaciones (tipo, titulo, mensaje, icono, color, enlace, referencia_id, referencia_tabla)
    VALUES (
        'testimonio',
        'Nuevo Testimonio Recibido',
        CONCAT('⭐ ', NEW.nombre, ' compartió su experiencia'),
        'fa-comment-dots',
        'info',
        'testimonios.html',
        NEW.id,
        'testimonios'
    );
END$$
DELIMITER ;

-- Trigger: Cumpleaños próximos (ejecutar diariamente via CRON o manual)
-- Este se puede llamar desde un script PHP diario
DELIMITER $$
CREATE PROCEDURE generar_notificaciones_cumpleanos()
BEGIN
    -- Buscar cumpleaños de hoy
    INSERT INTO notificaciones (tipo, titulo, mensaje, icono, color, enlace, referencia_id, referencia_tabla)
    SELECT 
        'cumpleanos',
        'Cumpleaños de Hoy',
        CONCAT('🎉 Hoy es el cumpleaños de ', nombre_completo, '!'),
        'fa-birthday-cake',
        'warning',
        'abuelos.html',
        id,
        'abuelos'
    FROM abuelos
    WHERE DATE_FORMAT(fecha_nacimiento, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d')
      AND estado = 'disponible'
      AND deleted = 0
      AND NOT EXISTS (
          SELECT 1 FROM notificaciones 
          WHERE tipo = 'cumpleanos' 
            AND referencia_id = abuelos.id 
            AND DATE(created_at) = CURDATE()
      );
    
    -- Buscar cumpleaños próximos (en 7 días)
    INSERT INTO notificaciones (tipo, titulo, mensaje, icono, color, enlace, referencia_id, referencia_tabla)
    SELECT 
        'cumpleanos',
        'Cumpleaños Próximo',
        CONCAT('🎂 ', nombre_completo, ' cumple años en 7 días'),
        'fa-calendar',
        'info',
        'abuelos.html',
        id,
        'abuelos'
    FROM abuelos
    WHERE DATE_FORMAT(fecha_nacimiento, '%m-%d') = DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 7 DAY), '%m-%d')
      AND estado = 'disponible'
      AND deleted = 0
      AND NOT EXISTS (
          SELECT 1 FROM notificaciones 
          WHERE tipo = 'cumpleanos' 
            AND referencia_id = abuelos.id 
            AND DATE(created_at) = CURDATE()
      );
END$$
DELIMITER ;

-- =====================================================
-- ÍNDICES Y OPTIMIZACIONES
-- =====================================================

-- Índice compuesto para búsquedas rápidas
CREATE INDEX idx_leida_created ON notificaciones(leida, created_at DESC);

-- =====================================================
-- COMENTARIOS
-- =====================================================

/*
PROPÓSITO:
- Sistema centralizado de notificaciones para el admin
- Notificaciones automáticas mediante triggers
- Badges y alertas en tiempo real

TIPOS DE NOTIFICACIONES:
- cumpleanos: Cumpleaños de abuelos (hoy o próximos)
- mensaje: Nuevos mensajes de contacto
- felicitacion: Felicitaciones de cumpleaños pendientes de aprobar
- donacion: Nuevas donaciones recibidas
- testimonio: Nuevos testimonios pendientes
- voluntario: Nuevas solicitudes de voluntariado

CAMPOS IMPORTANTES:
- tipo: Categoría de la notificación
- titulo: Título corto
- mensaje: Descripción completa
- icono: FontAwesome icon (sin 'fas')
- color: Bootstrap color (primary, success, warning, etc.)
- enlace: Página a la que debe redirigir
- leida: 0 = No leída, 1 = Leída
- referencia_id: ID del registro relacionado
- referencia_tabla: Tabla del registro relacionado

USO:
- Admin: Ver notificaciones no leídas
- Badge: Contador de notificaciones no leídas
- Click: Marcar como leída y redirigir al enlace

MANTENIMIENTO:
- Ejecutar generar_notificaciones_cumpleanos() diariamente (CRON)
- Limpiar notificaciones antiguas periódicamente (>30 días leídas)
*/



