<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';

class DonacionesController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // POST /api/donaciones/personas
    public function createPersonal() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // Validar datos
            $validator = new Validator($data);
            $validator
                ->required('nombre_completo')->min('nombre_completo', 2)->max('nombre_completo', 150)
                ->required('email')->email('email')
                ->required('telefono')->min('telefono', 7)->max('telefono', 20)
                ->required('ciudad')->min('ciudad', 2)->max('ciudad', 100);
            
            // Monto y frecuencia opcionales para QR
            if (isset($data['monto']) && $data['monto'] > 0) {
                $validator->numeric('monto')->minValue('monto', 1000);
            }
            
            if (isset($data['frecuencia']) && !empty($data['frecuencia'])) {
                $validator->inArray('frecuencia', ['unica', 'mensual', 'trimestral', 'anual']);
            }
            
            if (isset($data['metodo_pago']) && !empty($data['metodo_pago'])) {
                $validator->inArray('metodo_pago', ['nequi', 'daviplata', 'tarjeta', 'transferencia']);
            }

            if ($validator->fails()) {
                Response::badRequest('Datos inválidos', $validator->errors());
            }

            // Verificar abuelo si se especifica
            if (!empty($data['abuelo_id'])) {
                $abuelo = $this->db->fetchOne(
                    "SELECT id FROM abuelos WHERE id = ? AND estado = 'disponible'",
                    [$data['abuelo_id']]
                );
                if (!$abuelo) {
                    Response::badRequest('Abuelo no encontrado o no disponible');
                }
            }

            // Insertar donación
            $stmt = $this->db->query(
                "INSERT INTO donaciones_personas 
                 (nombre_completo, email, telefono, ciudad, monto, frecuencia, metodo_pago, abuelo_id, mensaje, estado) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')",
                [
                    $data['nombre_completo'],
                    $data['email'],
                    $data['telefono'],
                    $data['ciudad'],
                    $data['monto'] ?? 0,
                    $data['frecuencia'] ?? 'unica',
                    $data['metodo_pago'] ?? 'transferencia',
                    $data['abuelo_id'] ?? null,
                    $data['mensaje'] ?? null
                ]
            );

            Response::success(
                ['id' => $this->db->lastInsertId()],
                'Donación registrada exitosamente'
            );
        } catch (Exception $e) {
            error_log("Error en DonacionesController::createPersonal - " . $e->getMessage());
            Response::serverError();
        }
    }

    // POST /api/donaciones/empresas
    public function createEmpresarial() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // Validar datos
            $validator = new Validator($data);
            $validator
                ->required('nombre_empresa')->min('nombre_empresa', 2)->max('nombre_empresa', 200)
                ->required('nit')->min('nit', 8)->max('nit', 50)
                ->required('nombre_contacto')->min('nombre_contacto', 2)->max('nombre_contacto', 150)
                ->required('email_contacto')->email('email_contacto')
                ->required('telefono')->min('telefono', 7)->max('telefono', 20)
                ->required('ciudad')->min('ciudad', 2)->max('ciudad', 100);
            
            // Campos opcionales para QR
            if (isset($data['monto']) && $data['monto'] > 0) {
                $validator->numeric('monto')->minValue('monto', 1000);
            }
            
            if (isset($data['frecuencia']) && !empty($data['frecuencia'])) {
                $validator->inArray('frecuencia', ['unica', 'mensual', 'trimestral', 'anual']);
            }
            
            if (isset($data['metodo_pago']) && !empty($data['metodo_pago'])) {
                $validator->inArray('metodo_pago', ['transferencia', 'tarjeta']);
            }

            if ($validator->fails()) {
                Response::badRequest('Datos inválidos', $validator->errors());
            }

            // Insertar donación empresarial
            $stmt = $this->db->query(
                "INSERT INTO donaciones_empresas 
                 (nombre_empresa, nit, nombre_contacto, email_contacto, telefono, ciudad, monto, frecuencia, metodo_pago, tipo_patrocinio, abuelo_id, mensaje, requiere_factura, estado) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')",
                [
                    $data['nombre_empresa'],
                    $data['nit'],
                    $data['nombre_contacto'],
                    $data['email_contacto'],
                    $data['telefono'],
                    $data['ciudad'],
                    $data['monto'] ?? 0,
                    $data['frecuencia'] ?? 'unica',
                    $data['metodo_pago'] ?? 'transferencia',
                    $data['tipo_patrocinio'] ?? 'general',
                    $data['abuelo_id'] ?? null,
                    $data['mensaje'] ?? null,
                    isset($data['requiere_factura']) ? 1 : 0
                ]
            );

            Response::success(
                ['id' => $this->db->lastInsertId()],
                'Solicitud de donación empresarial registrada exitosamente'
            );
        } catch (Exception $e) {
            error_log("Error en DonacionesController::createEmpresarial - " . $e->getMessage());
            Response::serverError();
        }
    }
}
?>
