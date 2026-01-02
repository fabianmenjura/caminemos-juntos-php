<?php
require_once __DIR__ . '/../config/database.php';

class AuthService {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function login($username, $password) {
        try {
            $user = $this->db->fetchOne(
                "SELECT * FROM admin_users WHERE (username = ? OR email = ?) AND activo = TRUE",
                [$username, $username]
            );
            
            if (!$user || !password_verify($password, $user['password_hash'])) {
                return ['success' => false, 'message' => 'Usuario o contraseña incorrectos'];
            }
            
            $sessionToken = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));
            
            $this->db->execute(
                "INSERT INTO admin_sessions (user_id, session_token, ip_address, user_agent, expires_at) 
                 VALUES (?, ?, ?, ?, ?)",
                [$user['id'], $sessionToken, $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null, $expiresAt]
            );
            
            $this->db->execute("UPDATE admin_users SET ultimo_acceso = NOW() WHERE id = ?", [$user['id']]);
            
            unset($user['password_hash']);
            
            return [
                'success' => true,
                'message' => 'Login exitoso',
                'data' => ['user' => $user, 'token' => $sessionToken, 'expires_at' => $expiresAt]
            ];
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error al iniciar sesión'];
        }
    }
    
    public function verifySession($token) {
        try {
            $session = $this->db->fetchOne(
                "SELECT s.*, u.* FROM admin_sessions s
                 JOIN admin_users u ON s.user_id = u.id
                 WHERE s.session_token = ? AND s.expires_at > NOW() AND u.activo = TRUE",
                [$token]
            );
            
            if (!$session) {
                return ['success' => false, 'message' => 'Sesión inválida'];
            }
            
            unset($session['password_hash']);
            return ['success' => true, 'data' => $session];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al verificar sesión'];
        }
    }
    
    public function logout($token) {
        try {
            $this->db->execute("DELETE FROM admin_sessions WHERE session_token = ?", [$token]);
            return ['success' => true, 'message' => 'Sesión cerrada'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error al cerrar sesión'];
        }
    }
    
    public function getUserByToken($token) {
        $result = $this->verifySession($token);
        return $result['success'] ? $result['data'] : null;
    }
    
    public function logActivity($userId, $accion, $entidad = null, $entidadId = null, $descripcion = null) {
        try {
            $this->db->execute(
                "INSERT INTO admin_logs (user_id, accion, entidad, entidad_id, descripcion, ip_address) 
                 VALUES (?, ?, ?, ?, ?, ?)",
                [$userId, $accion, $entidad, $entidadId, $descripcion, $_SERVER['REMOTE_ADDR'] ?? null]
            );
        } catch (Exception $e) {
            error_log("Error al registrar actividad: " . $e->getMessage());
        }
    }
}
