<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Logger.php';
require_once __DIR__ . '/AuthController.php';

class QRDonacionesController {
    private $db;
    private $user;
    private $requireAuth;

    public function __construct($requireAuth = false) {
        $this->db = Database::getInstance();
        $this->requireAuth = $requireAuth;
        
        // Si requiere autenticación, verificar
        if ($requireAuth) {
            try {
                Logger::debug("QRDonacionesController: Iniciando autenticación");
                $this->user = AuthController::requireAuth();
                Logger::debug("QRDonacionesController: Auth OK", ['user_id' => $this->user['id'] ?? 'NO_ID']);
            } catch (Exception $e) {
                Logger::error("QRDonacionesController auth failed", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // No enviar respuesta aquí, dejar que el método lo maneje
                throw $e;
            }
        }
    }

    /**
     * GET - Obtener todos los QR activos (público)
     */
    public function index() {
        try {
            $qrs = $this->db->fetchAll(
                "SELECT id, titulo, descripcion, qr_image_url, texto_cuenta, 
                        numero_cuenta, titular, banco, tipo_cuenta, nit, orden
                 FROM qr_donaciones 
                 WHERE activo = 1 AND deleted = 0
                 ORDER BY orden ASC, id ASC"
            );

            // Procesar y estructurar los datos
            $qrsProcessed = array_map(function($qr) {
                return [
                    'id' => $qr['id'],
                    'titulo' => $qr['titulo'],
                    'descripcion' => $qr['descripcion'] ?? '',
                    'qr_image_url' => $qr['qr_image_url'] ?? '',
                    'texto_cuenta' => $qr['texto_cuenta'] ?? '',
                    'cuenta' => [
                        'numero' => $qr['numero_cuenta'] ?? '',
                        'titular' => $qr['titular'] ?? '',
                        'banco' => $qr['banco'] ?? '',
                        'tipo' => $qr['tipo_cuenta'] ?? '',
                        'nit' => $qr['nit'] ?? ''
                    ],
                    'orden' => $qr['orden'] ?? 0
                ];
            }, $qrs);

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => ['qrs' => $qrsProcessed]]);
            exit;
        } catch (Exception $e) {
            Logger::error("Error al obtener QR donaciones", ['error' => $e->getMessage()]);
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * GET - Obtener todos los QR (admin)
     */
    public function getAll() {
        try {
            $qrs = $this->db->fetchAll(
                "SELECT id, titulo, descripcion, qr_image_url, texto_cuenta, 
                        numero_cuenta, titular, banco, tipo_cuenta, nit, activo, orden, created_at
                 FROM qr_donaciones 
                 WHERE deleted = 0
                 ORDER BY orden ASC, id ASC"
            );

            // Procesar y estructurar los datos
            $qrsProcessed = array_map(function($qr) {
                return [
                    'id' => $qr['id'],
                    'titulo' => $qr['titulo'],
                    'descripcion' => $qr['descripcion'] ?? '',
                    'qr_image_url' => $qr['qr_image_url'] ?? '',
                    'texto_cuenta' => $qr['texto_cuenta'] ?? '',
                    'numero_cuenta' => $qr['numero_cuenta'] ?? '',
                    'titular' => $qr['titular'] ?? '',
                    'banco' => $qr['banco'] ?? '',
                    'tipo_cuenta' => $qr['tipo_cuenta'] ?? '',
                    'nit' => $qr['nit'] ?? '',
                    'activo' => $qr['activo'] ?? 0,
                    'orden' => $qr['orden'] ?? 0,
                    'created_at' => $qr['created_at'] ?? ''
                ];
            }, $qrs);

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => ['qrs' => $qrsProcessed]]);
            exit;
        } catch (Exception $e) {
            Logger::error("Error al obtener QR (admin)", ['error' => $e->getMessage()]);
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * POST - Crear nuevo QR
     */
    public function create() {
        // Limpiar cualquier output previo
        if (ob_get_level()) ob_clean();
        
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            Logger::debug("QR create: Data recibida", ['data' => $data]);

            if (empty($data['titulo']) || empty($data['qr_image_url'])) {
                Logger::error("QR create: Validación fallida", [
                    'titulo' => $data['titulo'] ?? 'VACIO',
                    'qr_image_url' => $data['qr_image_url'] ?? 'VACIO'
                ]);
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Campos requeridos: titulo, qr_image_url']);
                exit;
            }

            $sql = "INSERT INTO qr_donaciones (titulo, descripcion, qr_image_url, texto_cuenta, 
                        numero_cuenta, titular, banco, tipo_cuenta, nit, activo, orden) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $params = [
                $data['titulo'],
                $data['descripcion'] ?? '',
                $data['qr_image_url'],
                $data['texto_cuenta'] ?? '',
                $data['numero_cuenta'] ?? '',
                $data['titular'] ?? '',
                $data['banco'] ?? '',
                $data['tipo_cuenta'] ?? '',
                $data['nit'] ?? '',
                $data['activo'] ?? 1,
                $data['orden'] ?? 0
            ];
            
            $this->db->execute($sql, $params);

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'QR creado exitosamente']);
            exit;
        } catch (Exception $e) {
            Logger::error("Error al crear QR", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * PUT - Actualizar QR
     */
    public function update($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data['titulo']) || empty($data['qr_image_url'])) {
                http_response_code(400);
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Campos requeridos: titulo, qr_image_url']);
                exit;
            }

            $sql = "UPDATE qr_donaciones 
                    SET titulo = ?, descripcion = ?, qr_image_url = ?, texto_cuenta = ?, 
                        numero_cuenta = ?, titular = ?, banco = ?, tipo_cuenta = ?, nit = ?, activo = ?, orden = ?
                    WHERE id = ? AND deleted = 0";
            
            $this->db->execute($sql, [
                $data['titulo'],
                $data['descripcion'] ?? '',
                $data['qr_image_url'],
                $data['texto_cuenta'] ?? '',
                $data['numero_cuenta'] ?? '',
                $data['titular'] ?? '',
                $data['banco'] ?? '',
                $data['tipo_cuenta'] ?? '',
                $data['nit'] ?? '',
                $data['activo'] ?? 1,
                $data['orden'] ?? 0,
                $id
            ]);

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'QR actualizado exitosamente']);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * DELETE - Soft delete de QR
     */
    public function delete($id) {
        try {
            $this->db->execute(
                "UPDATE qr_donaciones SET deleted = 1 WHERE id = ?",
                [$id]
            );

            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'QR eliminado exitosamente']);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }
}