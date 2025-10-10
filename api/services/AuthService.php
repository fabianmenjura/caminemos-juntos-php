<?php
/**
 * Servicio de Autenticación
 * Maneja login, logout, sesiones y verificación de permisos
 */

require_once __DIR__ . '/../config/database.php';

class AuthService {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Iniciar sesión
     */
    public function login($username, $password) {
        try {
            // Buscar usuario
            $user = $this->db->fetchOne(
                "SELECT * FROM admin_users WHERE (username = ? OR email = ?) AND activo = TRUE",
                [$username, $username]
            );
            
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Usuario o contraseña incorrectos'
                ];
            }
            
            // Verificar contraseña
            if (!password_verify($password, $user['password_hash'])) {
                $this->logActivity($user['id'], 'LOGIN_FALLIDO', null, null, 'Intento de login con contraseña incorrecta');
                
                return [
                    'success' => false,
                    'message' => 'Usuario o contraseña incorrectos'
                ];
            }
            
            // Generar token de sesión
            $sessionToken = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            // Guardar sesión
            $this->db->execute(
                "INSERT INTO admin_sessions (user_id, session_token, ip_address, user_agent, expires_at) 
                 VALUES (?, ?, ?, ?, ?)",
                [
                    $user['id'],
                    $sessionToken,
                    $_SERVER['REMOTE_ADDR'] ?? null,
                    $_SERVER['HTTP_USER_AGENT'] ?? null,
                    $expiresAt
                ]
            );
            
            // Actualizar último acceso
            $this->db->execute(
                "UPDATE admin_users SET ultimo_acceso = NOW() WHERE id = ?",
                [$user['id']]
            );
            
            // Log de actividad
            $this->logActivity($user['id'], 'LOGIN_EXITOSO');
            
            // Devolver datos del usuario (sin contraseña)
            unset($user['password_hash']);
            
            return [
                'success' => true,
                'message' => 'Login exitoso',
                'data' => [
                    'user' => $user,
                    'token' => $sessionToken,
                    'expires_at' => $expiresAt
                ]
            ];
            
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al iniciar sesión'
            ];
        }
    }
    
    /**
     * Cerrar sesión
     */
    public function logout($token) {
        try {
            // Obtener sesión
            $session = $this->db->fetchOne(
                "SELECT user_id FROM admin_sessions WHERE session_token = ?",
                [$token]
            );
            
            if ($session) {
                $this->logActivity($session['user_id'], 'LOGOUT');
            }
            
            // Eliminar sesión
            $this->db->execute(
                "DELETE FROM admin_sessions WHERE session_token = ?",
                [$token]
            );
            
            return [
                'success' => true,
                'message' => 'Sesión cerrada'
            ];
            
        } catch (Exception $e) {
            error_log("Error en logout: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al cerrar sesión'
            ];
        }
    }
    
    /**
     * Verificar sesión
     */
    public function verifySession($token) {
        try {
            $session = $this->db->fetchOne(
                "SELECT s.*, u.* 
                 FROM admin_sessions s
                 JOIN admin_users u ON s.user_id = u.id
                 WHERE s.session_token = ? 
                   AND s.expires_at > NOW()
                   AND u.activo = TRUE",
                [$token]
            );
            
            if (!$session) {
                return [
                    'success' => false,
                    'message' => 'Sesión inválida o expirada'
                ];
            }
            
            // Extender sesión (renovar por 24 horas más)
            $newExpires = date('Y-m-d H:i:s', strtotime('+24 hours'));
            $this->db->execute(
                "UPDATE admin_sessions SET expires_at = ? WHERE session_token = ?",
                [$newExpires, $token]
            );
            
            // Remover datos sensibles
            unset($session['password_hash']);
            unset($session['session_token']);
            
            return [
                'success' => true,
                'data' => $session
            ];
            
        } catch (Exception $e) {
            error_log("Error al verificar sesión: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al verificar sesión'
            ];
        }
    }
    
    /**
     * Obtener usuario por token
     */
    public function getUserByToken($token) {
        $result = $this->verifySession($token);
        return $result['success'] ? $result['data'] : null;
    }
    
    /**
     * Verificar permisos
     */
    public function checkPermission($token, $requiredRole = 'editor') {
        $user = $this->getUserByToken($token);
        
        if (!$user) {
            return false;
        }
        
        $roles = ['editor' => 1, 'admin' => 2, 'super_admin' => 3];
        $userRoleLevel = $roles[$user['rol']] ?? 0;
        $requiredRoleLevel = $roles[$requiredRole] ?? 0;
        
        return $userRoleLevel >= $requiredRoleLevel;
    }
    
    /**
     * Cambiar contraseña
     */
    public function changePassword($userId, $currentPassword, $newPassword) {
        try {
            // Verificar contraseña actual
            $user = $this->db->fetchOne(
                "SELECT password_hash FROM admin_users WHERE id = ?",
                [$userId]
            );
            
            if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
                return [
                    'success' => false,
                    'message' => 'Contraseña actual incorrecta'
                ];
            }
            
            // Actualizar contraseña
            $newHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $this->db->execute(
                "UPDATE admin_users SET password_hash = ? WHERE id = ?",
                [$newHash, $userId]
            );
            
            $this->logActivity($userId, 'CAMBIO_CONTRASEÑA');
            
            return [
                'success' => true,
                'message' => 'Contraseña actualizada'
            ];
            
        } catch (Exception $e) {
            error_log("Error al cambiar contraseña: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al cambiar contraseña'
            ];
        }
    }
    
    /**
     * Registrar actividad
     */
    public function logActivity($userId, $accion, $entidad = null, $entidadId = null, $descripcion = null) {
        try {
            $this->db->execute(
                "INSERT INTO admin_logs (user_id, accion, entidad, entidad_id, descripcion, ip_address) 
                 VALUES (?, ?, ?, ?, ?, ?)",
                [
                    $userId,
                    $accion,
                    $entidad,
                    $entidadId,
                    $descripcion,
                    $_SERVER['REMOTE_ADDR'] ?? null
                ]
            );
        } catch (Exception $e) {
            error_log("Error al registrar actividad: " . $e->getMessage());
        }
    }
    
    /**
     * Limpiar sesiones expiradas
     */
    public function cleanExpiredSessions() {
        try {
            $this->db->execute(
                "DELETE FROM admin_sessions WHERE expires_at < NOW()"
            );
        } catch (Exception $e) {
            error_log("Error al limpiar sesiones: " . $e->getMessage());
        }
    }
}

