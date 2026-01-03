<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';

class InformacionInstitucionalController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * GET /api/informacion-institucional
     * Obtener contenido de la sección de información institucional
     */
    public function index() {
        try {
            $contenido = [];
            $rows = $this->db->fetchAll("SELECT clave, contenido FROM seccion_informacion_institucional");
            
            foreach ($rows as $row) {
                $contenido[$row['clave']] = $row['contenido'];
            }

            return Response::success([
                'titulo' => $contenido['titulo'] ?? 'Información Institucional',
                'subtitulo' => $contenido['subtitulo'] ?? 'Conoce más sobre nuestra organización',
                'descripcion' => $contenido['descripcion'] ?? '',
                'imagen' => $contenido['imagen'] ?? 'assets/images/informacion-institucional-placeholder.jpg'
            ]);
        } catch (Exception $e) {
            error_log("Error en InformacionInstitucionalController::index - " . $e->getMessage());
            return Response::serverError();
        }
    }
}

