<?php
class Response {
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function success($data, $message = null) {
        self::json([
            'success' => true,
            'data' => $data,
            'message' => $message
        ]);
    }

    public static function error($message, $statusCode = 400, $details = null) {
        self::json([
            'success' => false,
            'error' => $message,
            'details' => $details
        ], $statusCode);
    }

    public static function notFound($message = 'Recurso no encontrado') {
        self::error($message, 404);
    }

    public static function serverError($message = 'Error interno del servidor') {
        self::error($message, 500);
    }

    public static function unauthorized($message = 'No autorizado') {
        self::error($message, 401);
    }

    public static function badRequest($message = 'Solicitud inválida', $details = null) {
        self::error($message, 400, $details);
    }
}
?>
