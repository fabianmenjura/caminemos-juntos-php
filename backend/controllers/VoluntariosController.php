<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../helpers/Logger.php';

class VoluntariosController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    // Crear solicitud de voluntario (público)
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validaciones básicas
            if (empty($data['nombre_completo']) || empty($data['email']) || empty($data['telefono'])) {
                return Response::error('Nombre, email y teléfono son requeridos', null, 400);
            }
            
            // Sanitizar
            $nombre = htmlspecialchars(strip_tags(trim($data['nombre_completo'])));
            $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return Response::error('Email inválido', null, 400);
            }
            
            // Insertar
            $this->db->execute(
                "INSERT INTO voluntarios (nombre_completo, email, telefono, ciudad, edad, profesion, tipo_voluntariado, disponibilidad, habilidades, mensaje) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                [
                    $nombre,
                    $email,
                    $data['telefono'],
                    $data['ciudad'] ?? null,
                    $data['edad'] ?? null,
                    $data['profesion'] ?? null,
                    $data['tipo_voluntariado'] ?? 'voluntario',
                    $data['disponibilidad'] ?? null,
                    $data['habilidades'] ?? null,
                    $data['mensaje'] ?? null
                ]
            );
            
            Logger::log("Nuevo voluntario registrado", [
                'email' => $email,
                'tipo' => $data['tipo_voluntariado'] ?? 'voluntario'
            ]);
            
            return Response::success(
                ['id' => $this->db->lastInsertId()],
                '¡Gracias por tu interés! Te contactaremos pronto.',
                201
            );
            
        } catch (Exception $e) {
            Logger::error("Error al registrar voluntario", ['error' => $e->getMessage()]);
            return Response::error('Error al enviar solicitud', null, 500);
        }
    }
}
?>

