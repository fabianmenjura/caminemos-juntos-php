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
                ->required('ciudad')->min('ciudad', 2)->max('ciudad', 100)
                ->required('monto')->numeric('monto')->minValue('monto', 10000)
                ->required('frecuencia')->inArray('frecuencia', ['unica', 'mensual', 'trimestral', 'anual'])
                ->required('metodo_pago')->inArray('metodo_pago', ['nequi', 'daviplata', 'tarjeta', 'transferencia']);

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
                    $data['monto'],
                    $data['frecuencia'],
                    $data['metodo_pago'],
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
                ->required('email')->email('email')
                ->required('telefono')->min('telefono', 7)->max('telefono', 20)
                ->required('ciudad')->min('ciudad', 2)->max('ciudad', 100)
                ->required('monto')->numeric('monto')->minValue('monto', 100000)
                ->required('frecuencia')->inArray('frecuencia', ['unica', 'mensual', 'trimestral', 'anual'])
                ->required('metodo_pago')->inArray('metodo_pago', ['transferencia', 'tarjeta'])
                ->required('tipo_patrocinio')->inArray('tipo_patrocinio', ['general', 'especifico']);

            if ($validator->fails()) {
                Response::badRequest('Datos inválidos', $validator->errors());
            }

            // Insertar donación empresarial
            $stmt = $this->db->query(
                "INSERT INTO donaciones_empresas 
                 (nombre_empresa, nit, nombre_contacto, email, telefono, ciudad, monto, frecuencia, metodo_pago, tipo_patrocinio, abuelo_id, mensaje, requiere_factura, estado) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')",
                [
                    $data['nombre_empresa'],
                    $data['nit'],
                    $data['nombre_contacto'],
                    $data['email'],
                    $data['telefono'],
                    $data['ciudad'],
                    $data['monto'],
                    $data['frecuencia'],
                    $data['metodo_pago'],
                    $data['tipo_patrocinio'],
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
