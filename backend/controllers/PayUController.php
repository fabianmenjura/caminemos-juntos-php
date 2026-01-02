<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Validator.php';
require_once __DIR__ . '/../services/PayUService.php';

class PayUController {
    private $db;
    private $payuService;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->payuService = new PayUService();
    }

    // POST /api/payu/create-payment
    public function createPayment() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            // Validar datos
            $validator = new Validator($data);
            $validator
                ->required('amount')->numeric('amount')->minValue('amount', 1000)
                ->required('buyerEmail')->email('buyerEmail')
                ->required('buyerFullName')->min('buyerFullName', 2)->max('buyerFullName', 100);

            if ($validator->fails()) {
                Response::badRequest('Datos inválidos', $validator->errors());
            }

            $paymentForm = $this->payuService->createPaymentForm([
                'amount' => (float)$data['amount'],
                'currency' => 'COP',
                'description' => $data['description'] ?? 'Donación Caminemos Juntos Chiquiquirá',
                'buyerEmail' => $data['buyerEmail'],
                'buyerFullName' => $data['buyerFullName'],
                'referenceCode' => $data['referenceCode'] ?? 'DON-' . time()
            ]);

            // Guardar transacción en base de datos
            $this->db->query(
                "INSERT INTO transacciones_payu 
                 (reference_code, amount, buyer_email, buyer_name, description, status, created_at) 
                 VALUES (?, ?, ?, ?, ?, 'PENDING', NOW())",
                [
                    $paymentForm['referenceCode'],
                    $paymentForm['amount'],
                    $data['buyerEmail'],
                    $data['buyerFullName'],
                    $paymentForm['description']
                ]
            );

            Response::success([
                'paymentUrl' => $this->payuService->getPaymentUrl(),
                'formData' => $paymentForm
            ]);
        } catch (Exception $e) {
            error_log("Error en PayUController::createPayment - " . $e->getMessage());
            Response::serverError('Error al procesar el pago');
        }
    }

    // POST /api/payu/response (Respuesta de PayU)
    public function response() {
        try {
            $responseData = $_POST;
            error_log("Respuesta de PayU: " . json_encode($responseData));

            // Validar firma
            $validation = $this->payuService->validateResponse($responseData);
            
            if (!$validation['isValid']) {
                error_log("Firma inválida de PayU");
                header('Location: ' . APP_URL . '/donacion/error.html?reason=invalid_signature');
                exit;
            }

            $state = $this->payuService->getTransactionState($responseData['transactionState'] ?? '');

            // Actualizar estado de la transacción
            $this->db->query(
                "UPDATE transacciones_payu 
                 SET status = ?, transaction_id = ?, response_data = ?, updated_at = NOW() 
                 WHERE reference_code = ?",
                [
                    $state,
                    $responseData['transactionId'] ?? null,
                    json_encode($responseData),
                    $responseData['referenceCode']
                ]
            );

            // Redirigir según el estado
            if ($state === 'APPROVED') {
                header('Location: ' . APP_URL . '/donacion/exito.html?ref=' . $responseData['referenceCode']);
            } else {
                header('Location: ' . APP_URL . '/donacion/error.html?reason=' . $state);
            }
            exit;
        } catch (Exception $e) {
            error_log("Error en PayUController::response - " . $e->getMessage());
            header('Location: ' . APP_URL . '/donacion/error.html?reason=server_error');
            exit;
        }
    }

    // POST /api/payu/confirmation (Confirmación de PayU - Webhook)
    public function confirmation() {
        try {
            $confirmationData = $_POST;
            error_log("Confirmación de PayU: " . json_encode($confirmationData));

            // Validar firma
            $validation = $this->payuService->validateResponse($confirmationData);
            
            if (!$validation['isValid']) {
                error_log("Firma inválida en confirmación");
                Response::badRequest('Firma inválida');
            }

            $state = $this->payuService->getTransactionState($confirmationData['transactionState'] ?? '');

            // Actualizar estado final de la transacción
            $this->db->query(
                "UPDATE transacciones_payu 
                 SET status = ?, confirmation_data = ?, updated_at = NOW() 
                 WHERE reference_code = ?",
                [
                    $state,
                    json_encode($confirmationData),
                    $confirmationData['referenceCode']
                ]
            );

            // Procesar lógica adicional si el pago fue aprobado
            if ($state === 'APPROVED') {
                error_log("Pago aprobado: " . $confirmationData['referenceCode']);
            }

            Response::success(['message' => 'Confirmación procesada']);
        } catch (Exception $e) {
            error_log("Error en PayUController::confirmation - " . $e->getMessage());
            Response::serverError();
        }
    }

    // GET /api/payu/status/{referenceCode}
    public function getStatus($referenceCode) {
        try {
            $transaction = $this->db->fetchOne(
                "SELECT * FROM transacciones_payu WHERE reference_code = ?",
                [$referenceCode]
            );

            if (!$transaction) {
                Response::notFound('Transacción no encontrada');
            }

            Response::success($transaction);
        } catch (Exception $e) {
            error_log("Error en PayUController::getStatus - " . $e->getMessage());
            Response::serverError();
        }
    }
}
?>
