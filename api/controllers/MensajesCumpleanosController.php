<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Logger.php';

class MensajesCumpleanosController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Enviar mensaje de cumpleaños a un abuelo (público)
     */
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validaciones
            if (empty($data['abuelo_id']) || empty($data['nombre_remitente']) || empty($data['mensaje'])) {
                return Response::error('Datos incompletos', null, 400);
            }
            
            if (strlen($data['mensaje']) < 10) {
                return Response::error('El mensaje debe tener al menos 10 caracteres', null, 400);
            }
            
            // Verificar que el abuelo existe y cumple años pronto
            $abuelo = $this->db->fetchOne(
                "SELECT id, nombre,
                        (DATE_FORMAT(fecha_nacimiento, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d')) as cumple_hoy,
                        (DAYOFYEAR(fecha_nacimiento) BETWEEN DAYOFYEAR(CURDATE()) AND DAYOFYEAR(CURDATE() + INTERVAL 7 DAY)) as cumple_esta_semana
                 FROM abuelos 
                 WHERE id = ? AND deleted = 0",
                [$data['abuelo_id']]
            );
            
            if (!$abuelo) {
                return Response::error('Abuelo no encontrado', null, 404);
            }
            
            // Insertar mensaje (requiere aprobación)
            $this->db->execute(
                "INSERT INTO mensajes_cumpleanos (abuelo_id, nombre_remitente, email_remitente, mensaje, fecha_envio, aprobado) 
                 VALUES (?, ?, ?, ?, CURDATE(), 0)",
                [
                    $data['abuelo_id'],
                    htmlspecialchars($data['nombre_remitente']),
                    $data['email_remitente'] ?? null,
                    htmlspecialchars($data['mensaje'])
                ]
            );
            
            Logger::log("Mensaje de cumpleaños enviado", [
                'abuelo' => $abuelo['nombre'],
                'remitente' => $data['nombre_remitente']
            ]);
            
            return Response::success(
                ['id' => $this->db->lastInsertId()],
                '¡Mensaje enviado! Será entregado después de revisión.',
                201
            );
            
        } catch (Exception $e) {
            Logger::error("Error al enviar mensaje de cumpleaños", ['error' => $e->getMessage()]);
            return Response::error('Error al enviar mensaje', null, 500);
        }
    }
    
    /**
     * Obtener abuelos que cumplen años pronto (público)
     */
    public function proximosCumpleanos() {
        try {
            $dias = isset($_GET['dias']) ? min(30, max(1, (int)$_GET['dias'])) : 7;
            
            $abuelos = $this->db->fetchAll(
                "SELECT id, nombre, ciudad, foto_url, fecha_nacimiento,
                        TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) as edad,
                        (DATE_FORMAT(fecha_nacimiento, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d')) as cumple_hoy,
                        (DAYOFYEAR(fecha_nacimiento) BETWEEN DAYOFYEAR(CURDATE()) AND DAYOFYEAR(CURDATE() + INTERVAL 7 DAY)) as cumple_esta_semana,
                        CASE 
                            WHEN DAYOFYEAR(fecha_nacimiento) >= DAYOFYEAR(CURDATE()) 
                            THEN DAYOFYEAR(fecha_nacimiento) - DAYOFYEAR(CURDATE())
                            ELSE (365 - DAYOFYEAR(CURDATE())) + DAYOFYEAR(fecha_nacimiento)
                        END as dias_hasta_cumpleanos
                 FROM abuelos 
                 WHERE estado = 'disponible' AND deleted = 0
                 HAVING dias_hasta_cumpleanos <= ?
                 ORDER BY dias_hasta_cumpleanos ASC",
                [$dias]
            );
            
            return Response::success([
                'abuelos' => $abuelos,
                'total' => count($abuelos),
                'dias' => $dias
            ]);
            
        } catch (Exception $e) {
            Logger::error("Error al obtener próximos cumpleaños", ['error' => $e->getMessage()]);
            return Response::error('Error al obtener cumpleaños', null, 500);
        }
    }
}
?>

