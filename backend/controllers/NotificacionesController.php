<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/AuthController.php';

class NotificacionesController {
    private $db;
    private $user;

    public function __construct() {
        $this->db = Database::getInstance();
        
        // Requiere autenticación
        try {
            $this->user = AuthController::requireAuth();
        } catch (Exception $e) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            exit;
        }
    }

    /**
     * GET - Obtener todas las notificaciones
     */
    public function index() {
        try {
            $notificaciones = $this->db->fetchAll(
                "SELECT id, tipo, titulo, mensaje, icono, color, enlace, leida, referencia_id, referencia_tabla, created_at
                 FROM notificaciones 
                 ORDER BY leida ASC, created_at DESC
                 LIMIT 100"
            );

            $noLeidas = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM notificaciones WHERE leida = 0"
            );

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'data' => [
                    'notificaciones' => $notificaciones,
                    'no_leidas' => $noLeidas['count'] ?? 0
                ]
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * GET - Contador de notificaciones no leídas
     */
    public function getCount() {
        try {
            $result = $this->db->fetchOne(
                "SELECT COUNT(*) as count FROM notificaciones WHERE leida = 0"
            );

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'data' => ['count' => $result['count'] ?? 0]
            ]);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * PUT - Marcar notificación como leída
     */
    public function markAsRead($id) {
        try {
            $this->db->execute(
                "UPDATE notificaciones SET leida = 1 WHERE id = ?",
                [$id]
            );

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Notificación marcada como leída']);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * PUT - Marcar todas como leídas
     */
    public function markAllAsRead() {
        try {
            $this->db->execute("UPDATE notificaciones SET leida = 1 WHERE leida = 0");

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Todas las notificaciones marcadas como leídas']);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * DELETE - Eliminar notificación
     */
    public function delete($id) {
        try {
            $this->db->execute(
                "DELETE FROM notificaciones WHERE id = ?",
                [$id]
            );

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Notificación eliminada']);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * POST - Generar notificaciones de cumpleaños (llamado manualmente o por CRON)
     */
    public function generarCumpleanos() {
        try {
            $this->db->execute("CALL generar_notificaciones_cumpleanos()");

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Notificaciones de cumpleaños generadas']);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
}

