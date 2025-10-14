-- =====================================================
-- TRIGGERS PARA NOTIFICACIONES (FALTANTES)
-- =====================================================
-- Este archivo crea los triggers que faltaron

USE caminemos_juntos;

-- Eliminar triggers existentes si existen (para evitar duplicados)
DROP TRIGGER IF EXISTS notif_nuevo_mensaje;
DROP TRIGGER IF EXISTS notif_nueva_felicitacion;
DROP TRIGGER IF EXISTS notif_nuevo_voluntario;
DROP TRIGGER IF EXISTS notif_nuevo_testimonio;

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
        CONCAT('✉️ ', NEW.nombre, ' te ha enviado un mensaje: ', LEFT(NEW.asunto, 50)),
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
    WHERE id = NEW.abuelo_id
    LIMIT 1;
    
    INSERT INTO notificaciones (tipo, titulo, mensaje, icono, color, enlace, referencia_id, referencia_tabla)
    VALUES (
        'felicitacion',
        'Nueva Felicitación de Cumpleaños',
        CONCAT('🎂 ', NEW.nombre_remitente, ' envió una felicitación para ', IFNULL(nombre_abuelo, 'un abuelo')),
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
        CONCAT('👋 ', NEW.nombre_completo, ' quiere ser voluntario en ', IFNULL(NEW.tipo_voluntariado, 'voluntariado')),
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

-- Verificar que se crearon
SELECT 'Triggers creados correctamente!' as resultado;
SHOW TRIGGERS FROM caminemos_juntos WHERE `Trigger` LIKE 'notif_%';

