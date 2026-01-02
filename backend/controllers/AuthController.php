<?php
require_once __DIR__ . '/../services/AuthService.php';
require_once __DIR__ . '/../helpers/Response.php';

class AuthController {
    private $authService;
    
    public function __construct() {
        $this->authService = new AuthService();
    }
    
    public function login() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['username']) || empty($data['password'])) {
                return Response::error('Usuario y contraseña requeridos', null, 400);
            }
            
            $result = $this->authService->login($data['username'], $data['password']);
            
            if ($result['success']) {
                return Response::success($result['data'], $result['message']);
            } else {
                return Response::error($result['message'], null, 401);
            }
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            return Response::error('Error al procesar login', null, 500);
        }
    }
    
    public function verify() {
        try {
            error_log("=== VERIFY REQUEST ===");
            error_log("Headers: " . print_r(getallheaders(), true));
            
            $token = $this->getAuthToken();
            
            error_log("Token extraído: " . ($token ? substr($token, 0, 10) . '...' : 'NULL'));
            
            if (!$token) {
                error_log("ERROR: Token no proporcionado");
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Token no proporcionado'
                ]);
                exit;
            }
            
            $result = $this->authService->verifySession($token);
            
            if ($result['success']) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                    'data' => $result['data'],
                    'message' => 'Sesión válida'
                ]);
                exit;
            } else {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => $result['message']
                ]);
                exit;
            }
        } catch (Exception $e) {
            error_log("Error en verify: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error al verificar sesión'
            ]);
            exit;
        }
    }
    
    public function logout() {
        try {
            $token = $this->getAuthToken();
            if (!$token) {
                return Response::error('Token no proporcionado', null, 401);
            }
            
            $result = $this->authService->logout($token);
            return Response::success(null, $result['message']);
        } catch (Exception $e) {
            return Response::error('Error al cerrar sesión', null, 500);
        }
    }
    
    private function getAuthToken() {
        // Intentar obtener el token de múltiples fuentes
        
        // 1. Desde getallheaders()
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        
        // 2. Desde $_SERVER['HTTP_AUTHORIZATION']
        if (empty($authHeader) && isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        }
        
        // 3. Desde el Apache environment variable
        if (empty($authHeader) && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $authHeader = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }
        
        // 4. Desde apache_request_headers() si existe
        if (empty($authHeader) && function_exists('apache_request_headers')) {
            $apacheHeaders = apache_request_headers();
            $authHeader = $apacheHeaders['Authorization'] ?? $apacheHeaders['authorization'] ?? '';
        }
        
        error_log("AuthHeader encontrado: " . ($authHeader ?: 'VACÍO'));
        
        if ($authHeader && preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            error_log("Token extraído: " . substr($matches[1], 0, 10) . '...');
            return $matches[1];
        }
        
        error_log("ERROR: No se pudo extraer el token del header");
        return null;
    }
    
    public static function requireAuth() {
        try {
            // Limpiar cualquier output previo
            if (ob_get_level()) {
                ob_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            
            $authService = new AuthService();
            $controller = new AuthController();
            $token = $controller->getAuthToken();
            
            if (!$token) {
                Response::error('No autorizado', 401);
                exit;
            }
            
            $user = $authService->getUserByToken($token);
            if (!$user) {
                Response::error('Sesión inválida', 401);
                exit;
            }
            
            return $user;
        } catch (Exception $e) {
            // Limpiar cualquier output previo
            if (ob_get_level()) {
                ob_clean();
            }
            header('Content-Type: application/json; charset=utf-8');
            error_log("requireAuth exception: " . $e->getMessage());
            Response::error('Error de autenticación: ' . $e->getMessage(), 500);
            exit;
        }
    }
}
