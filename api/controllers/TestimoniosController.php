<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Logger.php';

class TestimoniosController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Listar testimonios aprobados y destacados (público)
    public function index() {
        try {
            $limite = isset($_GET['limite']) ? min(20, max(1, intval($_GET['limite']))) : 6;
            
            $testimonios = $this->db->fetchAll(
                "SELECT id, nombre, rol, foto_url, testimonio, calificacion, created_at
                 FROM testimonios 
                 WHERE aprobado = TRUE AND destacado = TRUE
                 ORDER BY orden ASC, created_at DESC 
                 LIMIT ?",
                [$limite]
            );
            
            return Response::success([
                'testimonios' => $testimonios,
                'total' => count($testimonios)
            ]);
            
        } catch (Exception $e) {
            Logger::error("Error al obtener testimonios", ['error' => $e->getMessage()]);
            return Response::error('Error al obtener testimonios', null, 500);
        }
    }
    
    // Enviar nuevo testimonio (público, requiere aprobación)
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['nombre']) || empty($data['testimonio'])) {
                return Response::error('Nombre y testimonio son requeridos', null, 400);
            }
            
            if (strlen($data['testimonio']) < 20) {
                return Response::error('El testimonio debe tener al menos 20 caracteres', null, 400);
            }
            
            $this->db->execute(
                "INSERT INTO testimonios (nombre, rol, testimonio, calificacion, aprobado, destacado) 
                 VALUES (?, ?, ?, ?, FALSE, FALSE)",
                [
                    htmlspecialchars($data['nombre']),
                    htmlspecialchars($data['rol'] ?? 'Colaborador'),
                    htmlspecialchars($data['testimonio']),
                    min(5, max(1, intval($data['calificacion'] ?? 5)))
                ]
            );
            
            Logger::log("Nuevo testimonio enviado (pendiente aprobación)", [
                'nombre' => $data['nombre']
            ]);
            
            return Response::success(
                null,
                'Gracias por tu testimonio. Será publicado después de revisión.',
                201
            );
            
        } catch (Exception $e) {
            Logger::error("Error al crear testimonio", ['error' => $e->getMessage()]);
            return Response::error('Error al enviar testimonio', null, 500);
        }
    }
}
?>

