<?php
/**
 * Controlador de Autenticación
 * Endpoints para login, logout y verificación de sesión
 */

require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';

class AuthController {
    private $authService;
    
    public function __construct() {
        $this->authService = new AuthService();
    }
    
    /**
     * POST /api/auth/login
     */
    public function login() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validar datos
            $errors = [];
            
            if (empty($data['username'])) {
                $errors[] = 'El usuario es requerido';
            }
            
            if (empty($data['password'])) {
                $errors[] = 'La contraseña es requerida';
            }
            
            if (!empty($errors)) {
                return Response::error('Datos inválidos', $errors, 400);
            }
            
            // Intentar login
            $result = $this->authService->login(
                $data['username'],
                $data['password']
            );
            
            if ($result['success']) {
                return Response::success($result['data'], $result['message']);
            } else {
                return Response::error($result['message'], null, 401);
            }
            
        } catch (Exception $e) {
            error_log("Error en AuthController::login - " . $e->getMessage());
            return Response::error('Error al procesar login', null, 500);
        }
    }
    
    /**
     * POST /api/auth/logout
     */
    public function logout() {
        try {
            $token = $this->getAuthToken();
            
            if (!$token) {
                return Response::error('Token no proporcionado', null, 401);
            }
            
            $result = $this->authService->logout($token);
            
            if ($result['success']) {
                return Response::success(null, $result['message']);
            } else {
                return Response::error($result['message'], null, 400);
            }
            
        } catch (Exception $e) {
            error_log("Error en AuthController::logout - " . $e->getMessage());
            return Response::error('Error al cerrar sesión', null, 500);
        }
    }
    
    /**
     * GET /api/auth/verify
     */
    public function verify() {
        try {
            $token = $this->getAuthToken();
            
            if (!$token) {
                return Response::error('Token no proporcionado', null, 401);
            }
            
            $result = $this->authService->verifySession($token);
            
            if ($result['success']) {
                return Response::success($result['data'], 'Sesión válida');
            } else {
                return Response::error($result['message'], null, 401);
            }
            
        } catch (Exception $e) {
            error_log("Error en AuthController::verify - " . $e->getMessage());
            return Response::error('Error al verificar sesión', null, 500);
        }
    }
    
    /**
     * POST /api/auth/change-password
     */
    public function changePassword() {
        try {
            $token = $this->getAuthToken();
            
            if (!$token) {
                return Response::error('No autorizado', null, 401);
            }
            
            $user = $this->authService->getUserByToken($token);
            
            if (!$user) {
                return Response::error('Sesión inválida', null, 401);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validar
            if (empty($data['current_password']) || empty($data['new_password'])) {
                return Response::error('Contraseña actual y nueva son requeridas', null, 400);
            }
            
            if (strlen($data['new_password']) < 8) {
                return Response::error('La contraseña debe tener al menos 8 caracteres', null, 400);
            }
            
            // Cambiar contraseña
            $result = $this->authService->changePassword(
                $user['id'],
                $data['current_password'],
                $data['new_password']
            );
            
            if ($result['success']) {
                return Response::success(null, $result['message']);
            } else {
                return Response::error($result['message'], null, 400);
            }
            
        } catch (Exception $e) {
            error_log("Error en AuthController::changePassword - " . $e->getMessage());
            return Response::error('Error al cambiar contraseña', null, 500);
        }
    }
    
    /**
     * GET /api/auth/me
     */
    public function me() {
        try {
            $token = $this->getAuthToken();
            
            if (!$token) {
                return Response::error('No autorizado', null, 401);
            }
            
            $user = $this->authService->getUserByToken($token);
            
            if (!$user) {
                return Response::error('Sesión inválida', null, 401);
            }
            
            return Response::success($user);
            
        } catch (Exception $e) {
            error_log("Error en AuthController::me - " . $e->getMessage());
            return Response::error('Error al obtener datos del usuario', null, 500);
        }
    }
    
    /**
     * Obtener token de autenticación del header
     */
    private function getAuthToken() {
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? '';
        
        if (preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return $matches[1];
        }
        
        return null;
    }
    
    /**
     * Middleware: Verificar autenticación
     */
    public static function requireAuth() {
        $authService = new AuthService();
        $controller = new AuthController();
        $token = $controller->getAuthToken();
        
        if (!$token) {
            Response::error('No autorizado', null, 401);
            exit;
        }
        
        $user = $authService->getUserByToken($token);
        
        if (!$user) {
            Response::error('Sesión inválida o expirada', null, 401);
            exit;
        }
        
        return $user;
    }
    
    /**
     * Middleware: Verificar rol mínimo
     */
    public static function requireRole($requiredRole = 'editor') {
        $user = self::requireAuth();
        
        $authService = new AuthService();
        $controller = new AuthController();
        $token = $controller->getAuthToken();
        
        if (!$authService->checkPermission($token, $requiredRole)) {
            Response::error('Permisos insuficientes', null, 403);
            exit;
        }
        
        return $user;
    }
}

