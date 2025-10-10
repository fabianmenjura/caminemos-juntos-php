<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';

class ContactoController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // POST /api/contacto
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // Validar datos
            $validator = new Validator($data);
            $validator
                ->required('nombre')->min('nombre', 2)->max('nombre', 150)
                ->required('email')->email('email')
                ->required('asunto')->min('asunto', 5)->max('asunto', 200)
                ->required('mensaje')->min('mensaje', 10)->max('mensaje', 1000);

            if ($validator->fails()) {
                Response::badRequest('Datos inválidos', $validator->errors());
            }

            // Insertar mensaje
            $stmt = $this->db->query(
                "INSERT INTO mensajes_contacto 
                 (nombre, email, telefono, asunto, mensaje, estado) 
                 VALUES (?, ?, ?, ?, ?, 'nuevo')",
                [
                    $data['nombre'],
                    $data['email'],
                    $data['telefono'] ?? null,
                    $data['asunto'],
                    $data['mensaje']
                ]
            );

            Response::success(
                ['id' => $this->db->lastInsertId()],
                'Mensaje enviado exitosamente. Te responderemos pronto.'
            );
        } catch (Exception $e) {
            error_log("Error en ContactoController::create - " . $e->getMessage());
            Response::serverError();
        }
    }
}
?>
