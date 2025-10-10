<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';

class AbuelosController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // GET /api/abuelos
    public function index() {
        try {
            $estado = $_GET['estado'] ?? 'disponible';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 50;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

            $abuelos = $this->db->fetchAll(
                "SELECT * FROM abuelos WHERE estado = ? ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset}",
                [$estado]
            );

            $total = $this->db->fetchOne(
                "SELECT COUNT(*) as total FROM abuelos WHERE estado = ?",
                [$estado]
            );

            Response::success([
                'abuelos' => $abuelos,
                'total' => $total['total'],
                'limit' => $limit,
                'offset' => $offset
            ]);
        } catch (Exception $e) {
            error_log("Error en AbuelosController::index - " . $e->getMessage());
            Response::serverError();
        }
    }

    // GET /api/abuelos/{id}
    public function show($id) {
        try {
            $abuelo = $this->db->fetchOne(
                "SELECT * FROM abuelos WHERE id = ? AND estado = 'disponible'",
                [$id]
            );

            if (!$abuelo) {
                Response::notFound('Abuelo no encontrado');
            }

            Response::success($abuelo);
        } catch (Exception $e) {
            error_log("Error en AbuelosController::show - " . $e->getMessage());
            Response::serverError();
        }
    }

    // GET /api/abuelos/cumpleanos/mes-actual
    public function cumpleanosMesActual() {
        try {
            $cumpleanos = $this->db->fetchAll(
                "SELECT a.*, c.fecha_cumpleanos 
                 FROM abuelos a 
                 INNER JOIN cumpleanos c ON a.id = c.abuelo_id 
                 WHERE a.estado = 'disponible' 
                 AND MONTH(c.fecha_cumpleanos) = MONTH(CURDATE()) 
                 AND YEAR(c.fecha_cumpleanos) = YEAR(CURDATE())
                 ORDER BY DAY(c.fecha_cumpleanos) ASC"
            );

            Response::success($cumpleanos);
        } catch (Exception $e) {
            error_log("Error en AbuelosController::cumpleanosMesActual - " . $e->getMessage());
            Response::serverError();
        }
    }

    // GET /api/abuelos/buscar/{termino}
    public function buscar($termino) {
        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            $search = "%{$termino}%";

            $abuelos = $this->db->fetchAll(
                "SELECT * FROM abuelos 
                 WHERE estado = 'disponible' 
                 AND (nombre LIKE ? OR ciudad LIKE ? OR descripcion LIKE ?)
                 ORDER BY nombre ASC 
                 LIMIT {$limit} OFFSET {$offset}",
                [$search, $search, $search]
            );

            Response::success([
                'abuelos' => $abuelos,
                'termino' => $termino
            ]);
        } catch (Exception $e) {
            error_log("Error en AbuelosController::buscar - " . $e->getMessage());
            Response::serverError();
        }
    }
}
?>
