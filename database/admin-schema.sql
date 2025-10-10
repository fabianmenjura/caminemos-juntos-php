-- ============================================
-- Schema para Panel de Administración
-- Proyecto: Adopta un Abuelo Colombia
-- ============================================

USE caminemos_juntos;

-- Tabla de usuarios administradores
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    nombre_completo VARCHAR(100) NOT NULL,
    rol ENUM('super_admin', 'admin', 'editor') DEFAULT 'editor',
    activo BOOLEAN DEFAULT TRUE,
    ultimo_acceso DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_rol (rol)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de sesiones de admin
CREATE TABLE IF NOT EXISTS admin_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(64) NOT NULL UNIQUE,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE,
    INDEX idx_token (session_token),
    INDEX idx_user_id (user_id),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de logs de actividad del admin
CREATE TABLE IF NOT EXISTS admin_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    accion VARCHAR(100) NOT NULL,
    entidad VARCHAR(50) NULL,
    entidad_id INT NULL,
    descripcion TEXT NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_accion (accion),
    INDEX idx_entidad (entidad, entidad_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Agregar campo 'genero' a la tabla abuelos si no existe
-- MySQL no soporta IF NOT EXISTS en ALTER TABLE ADD COLUMN
-- Se ejecutará solo si la columna no existe (ignorar error si ya existe)

-- Crear usuario administrador por defecto
-- Usuario: admin
-- Contraseña: Admin123! (CAMBIAR INMEDIATAMENTE después del primer login)
INSERT INTO admin_users (username, email, password_hash, nombre_completo, rol, activo) 
VALUES (
    'admin',
    'admin@caminemosjuntos.org',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Admin123!
    'Administrador Principal',
    'super_admin',
    TRUE
) ON DUPLICATE KEY UPDATE username = username;

-- Insertar log de creación del sistema
INSERT INTO admin_logs (user_id, accion, descripcion, ip_address)
SELECT 
    id,
    'SISTEMA_INSTALADO',
    'Sistema de administración instalado correctamente',
    '127.0.0.1'
FROM admin_users 
WHERE username = 'admin'
LIMIT 1;

-- Verificación
SELECT 
    'Tabla admin_users creada' as mensaje,
    COUNT(*) as total_usuarios
FROM admin_users;

