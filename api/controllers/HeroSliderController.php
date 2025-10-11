<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Logger.php';

class HeroSliderController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Obtiene todas las imágenes activas del slider (público)
     */
    public function index() {
        try {
            $slides = $this->db->fetchAll(
                "SELECT id, titulo, subtitulo, imagen_url, enlace_url, orden 
                 FROM hero_slider 
                 WHERE activo = 1 AND deleted = 0 
                 ORDER BY orden ASC, id ASC"
            );
            
            return Response::success([
                'slides' => $slides,
                'total' => count($slides)
            ]);
        } catch (Exception $e) {
            Logger::error("Error al obtener slides", ['error' => $e->getMessage()]);
            return Response::error('Error al obtener slides', null, 500);
        }
    }
}
?>

