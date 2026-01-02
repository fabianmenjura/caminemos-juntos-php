<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';

class GaleriaController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * GET /api/galeria
     * Obtener todas las imágenes activas de la galería
     */
    public function index() {
        try {
            $imagenes = $this->db->fetchAll(
                "SELECT id, titulo, descripcion, tipo, imagen_url, video_url, categoria
                 FROM galeria
                 WHERE activo = 1 AND deleted = 0
                 ORDER BY orden ASC, id DESC"
            );

            return Response::success([
                'imagenes' => $imagenes,
                'total' => count($imagenes)
            ]);
        } catch (Exception $e) {
            error_log("Error en GaleriaController::index - " . $e->getMessage());
            return Response::serverError();
        }
    }
}

