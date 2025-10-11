<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Logger.php';

class PatrocinadoresController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtiene patrocinadores activos (público)
     */
    public function index() {
        try {
            $patrocinadores = $this->db->fetchAll(
                "SELECT id, nombre_empresa, logo_url, sitio_web, descripcion, orden 
                 FROM patrocinadores 
                 WHERE activo = 1 AND deleted = 0 
                 ORDER BY orden ASC, id ASC"
            );
            
            return Response::success([
                'patrocinadores' => $patrocinadores,
                'total' => count($patrocinadores)
            ]);
        } catch (Exception $e) {
            Logger::error("Error al obtener patrocinadores", ['error' => $e->getMessage()]);
            return Response::error('Error al obtener patrocinadores', null, 500);
        }
    }
}
?>

