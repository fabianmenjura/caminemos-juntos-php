<?php
class PayUService {
    private $merchantId;
    private $apiKey;
    private $apiLogin;
    private $accountId;
    private $testMode;
    private $responseUrl;
    private $confirmationUrl;

    public function __construct() {
        $this->merchantId = PAYU_MERCHANT_ID;
        $this->apiKey = PAYU_API_KEY;
        $this->apiLogin = PAYU_API_LOGIN;
        $this->accountId = PAYU_ACCOUNT_ID;
        $this->testMode = PAYU_TEST_MODE === 'true';
        $this->responseUrl = PAYU_RESPONSE_URL;
        $this->confirmationUrl = PAYU_CONFIRMATION_URL;
    }

    // Generar firma MD5 para PayU
    public function generateSignature($referenceCode, $amount) {
        $currency = "COP";
        $signatureString = "{$this->apiKey}~{$this->merchantId}~{$referenceCode}~{$amount}~{$currency}";
        return md5($signatureString);
    }

    // Obtener URL de PayU según el modo
    public function getPaymentUrl() {
        return $this->testMode
            ? "https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/"
            : "https://checkout.payulatam.com/ppp-web-gateway-payu/";
    }

    // Crear formulario de pago
    public function createPaymentForm($data) {
        $referenceCode = $data['referenceCode'] ?? 'DON-' . time();
        $amount = (string)$data['amount'];

        return [
            'merchantId' => $this->merchantId,
            'accountId' => $this->accountId,
            'description' => $data['description'],
            'referenceCode' => $referenceCode,
            'amount' => $amount,
            'tax' => (string)($data['tax'] ?? 0),
            'taxReturnBase' => (string)($data['taxReturnBase'] ?? 0),
            'currency' => $data['currency'],
            'signature' => $this->generateSignature($referenceCode, $amount),
            'test' => $this->testMode ? "1" : "0",
            'buyerEmail' => $data['buyerEmail'],
            'buyerFullName' => $data['buyerFullName'],
            'responseUrl' => $this->responseUrl,
            'confirmationUrl' => $this->confirmationUrl,
        ];
    }

    // Validar respuesta de PayU
    public function validateResponse($responseData) {
        $signature = $responseData['signature'] ?? '';
        $referenceCode = $responseData['referenceCode'] ?? '';
        $amount = $responseData['TX_VALUE'] ?? '';
        
        $expectedSignature = $this->generateSignature($referenceCode, $amount);
        
        return [
            'isValid' => $signature === $expectedSignature,
            'expectedSignature' => $expectedSignature,
            'receivedSignature' => $signature
        ];
    }

    // Obtener estado de transacción
    public function getTransactionState($state) {
        $states = [
            '4' => 'APPROVED',
            '6' => 'DECLINED',
            '104' => 'ERROR',
            '7' => 'PENDING',
            '5' => 'EXPIRED'
        ];
        
        return $states[$state] ?? 'UNKNOWN';
    }
}
?>
