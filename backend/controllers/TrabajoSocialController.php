<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';

class TrabajoSocialController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * GET /api/trabajo-social
     * Obtener contenido de la sección "Trabajo Social"
     */
    public function index() {
        try {
            // Obtener título, descripción e imagen
            $contenido = [];
            $rows = $this->db->fetchAll("SELECT clave, contenido FROM seccion_trabajo_social");
            
            foreach ($rows as $row) {
                $contenido[$row['clave']] = $row['contenido'];
            }

            return Response::success([
                'titulo' => $contenido['titulo'] ?? 'Nuestro Trabajo Social',
                'descripcion' => $contenido['descripcion'] ?? '',
                'imagen' => $contenido['imagen'] ?? 'assets/images/trabajo-social-placeholder.jpg'
            ]);
        } catch (Exception $e) {
            error_log("Error en TrabajoSocialController::index - " . $e->getMessage());
            return Response::serverError();
        }
    }
}

