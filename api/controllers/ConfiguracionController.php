<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';

class ConfiguracionController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * GET /api/configuracion
     * Obtener configuración general del sitio
     */
    public function index() {
        try {
            // Obtener toda la configuración con formato simple
            $config = [];
            $rows = $this->db->fetchAll("SELECT clave, valor FROM configuracion_general");
            
            foreach ($rows as $row) {
                $config[$row['clave']] = ['valor' => $row['valor']];
            }
            
            // Obtener redes sociales activas
            $redes = $this->db->fetchAll(
                "SELECT id, nombre, icono, url, orden
                 FROM redes_sociales
                 WHERE activo = 1 AND deleted = 0
                 ORDER BY orden ASC, id ASC"
            );

            return Response::success([
                'config' => $config,
                'redes_sociales' => $redes
            ]);
        } catch (Exception $e) {
            error_log("Error en ConfiguracionController::index - " . $e->getMessage());
            return Response::serverError();
        }
    }
}

