<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';

class CategoriasVoluntariadoController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * GET /api/categorias-voluntariado
     * Obtener categorías activas para el frontend
     */
    public function index() {
        try {
            $categorias = $this->db->fetchAll(
                "SELECT id, titulo, slug, descripcion, icono, imagen_url, caracteristicas, orden, destacado
                 FROM categorias_voluntariado
                 WHERE activo = 1 AND deleted = 0
                 ORDER BY orden ASC, id ASC"
            );

            // Decodificar JSON de características
            foreach ($categorias as &$categoria) {
                if ($categoria['caracteristicas']) {
                    $categoria['caracteristicas'] = json_decode($categoria['caracteristicas'], true);
                } else {
                    $categoria['caracteristicas'] = [];
                }
                $categoria['destacado'] = (bool)$categoria['destacado'];
            }

            return Response::success([
                'categorias' => $categorias,
                'total' => count($categorias)
            ]);
        } catch (Exception $e) {
            error_log("Error en CategoriasVoluntariadoController::index - " . $e->getMessage());
            return Response::serverError();
        }
    }
}

