<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';

class AcercaDeController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * GET /api/acerca-de
     * Obtener contenido de la sección "Acerca de"
     */
    public function index() {
        try {
            // Obtener título y descripción
            $contenido = [];
            $rows = $this->db->fetchAll("SELECT clave, contenido FROM seccion_acerca_de");
            
            foreach ($rows as $row) {
                $contenido[$row['clave']] = $row['contenido'];
            }
            
            // Obtener características activas
            $caracteristicas = $this->db->fetchAll(
                "SELECT id, titulo, descripcion, icono, orden
                 FROM caracteristicas_acerca_de
                 WHERE activo = 1 AND deleted = 0
                 ORDER BY orden ASC, id ASC"
            );

            return Response::success([
                'titulo' => $contenido['titulo'] ?? '¿Qué es Caminemos Juntos?',
                'descripcion' => $contenido['descripcion'] ?? '',
                'caracteristicas' => $caracteristicas,
                'total_caracteristicas' => count($caracteristicas)
            ]);
        } catch (Exception $e) {
            error_log("Error en AcercaDeController::index - " . $e->getMessage());
            return Response::serverError();
        }
    }
}



